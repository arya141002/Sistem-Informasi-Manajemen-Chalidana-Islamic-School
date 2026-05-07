<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\CampusArea;
use App\Models\ImportantDateEvent;
use App\Models\Tagline;
use Illuminate\Http\Request;

class ImportantDates extends Controller
{
    public function index()
    {
        $tagline = Tagline::first();
        $campusAreas = CampusArea::query()
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->get(['title', 'address', 'phone']);
        $campuses = Campus::query()
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->get(['id', 'title']);

        $settings = [
            'site_name' => get_system('site_name', config('app.name')),
            'site_title' => get_system('site_title', config('app.name')),
            'site_description' => get_system('site_description', 'Website resmi ' . config('app.name')),
            'site_keywords' => get_system('site_keywords', config('app.name')),
            'contact_email' => get_system('contact_email', ''),
            'contact_phone' => get_system('contact_phone', ''),
            'site_favicon' => get_system('site_favicon'),
        ];

        return view('landing.important_dates', compact('tagline', 'campusAreas', 'campuses', 'settings'));
    }

    public function events(Request $request)
    {
        $validated = $request->validate([
            'campus_id' => 'nullable|exists:campuses,id',
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $monthStart = sprintf('%04d-%02d-01', $validated['year'], $validated['month']);
        $monthEnd = date('Y-m-t', strtotime($monthStart));

        $events = ImportantDateEvent::query()
            ->with(['campus:id,title', 'branch:id,name'])
            ->when(!empty($validated['campus_id']), fn ($query) => $query->where('campus_id', $validated['campus_id']))
            ->where(function ($query) use ($monthStart, $monthEnd) {
                $query
                    ->whereBetween('event_date', [$monthStart, $monthEnd])
                    ->orWhere(function ($rangeQuery) use ($monthStart, $monthEnd) {
                        $rangeQuery
                            ->whereNotNull('start_date')
                            ->whereNotNull('end_date')
                            ->whereDate('start_date', '<=', $monthEnd)
                            ->whereDate('end_date', '>=', $monthStart);
                    });
            })
            ->orderByRaw('COALESCE(start_date, event_date) asc')
            ->orderBy('title')
            ->get()
            ->map(function (ImportantDateEvent $event) {
                $startSource = $event->start_date ?? $event->event_date;
                $endSource = $event->end_date ?? $event->event_date;
                $startDate = $startSource ? $startSource->format('Y-m-d') : null;
                $endDate = $endSource ? $endSource->format('Y-m-d') : null;

                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'event_date' => optional($event->event_date)->format('Y-m-d'),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'campus' => $event->campus?->title,
                    'branch' => $event->branch?->name,
                ];
            })
            ->values();

        return response()->json($events);
    }
}
