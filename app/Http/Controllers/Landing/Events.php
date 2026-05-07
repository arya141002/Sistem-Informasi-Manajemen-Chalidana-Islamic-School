<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\CampusArea;
use App\Models\CategoryStudy;
use App\Models\Event;
use App\Models\Tagline;
use Illuminate\Http\Request;

class Events extends Controller
{
    private function sharedData(): array
    {
        $tagline = Tagline::first();
        $campusAreas = CampusArea::query()
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->get(['title', 'address', 'phone']);
        $settings = [
            'site_name' => get_system('site_name', config('app.name')),
            'site_title' => get_system('site_title', config('app.name')),
            'site_description' => get_system('site_description', 'Website resmi ' . config('app.name')),
            'site_keywords' => get_system('site_keywords', config('app.name')),
            'contact_email' => get_system('contact_email', ''),
            'contact_phone' => get_system('contact_phone', ''),
            'site_favicon' => get_system('site_favicon'),
        ];

        return compact('tagline', 'campusAreas', 'settings');
    }

    public function index(Request $request)
    {
        $studies = CategoryStudy::query()
            ->orderBy('title')
            ->get(['id', 'title']);

        $selectedStudy = $request->integer('study');

        $events = Event::query()
            ->with('study:id,title')
            ->when($selectedStudy, fn ($query) => $query->where('study_id', $selectedStudy))
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        return view('landing.events', array_merge(
            $this->sharedData(),
            compact('studies', 'events', 'selectedStudy')
        ));
    }

    public function show(string $slug)
    {
        $event = Event::query()
            ->with('study:id,title')
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedEvents = Event::query()
            ->with('study:id,title')
            ->where('id', '!=', $event->id)
            ->when($event->study_id, fn ($query) => $query->where('study_id', $event->study_id))
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('landing.event_detail', array_merge(
            $this->sharedData(),
            compact('event', 'relatedEvents')
        ));
    }
}
