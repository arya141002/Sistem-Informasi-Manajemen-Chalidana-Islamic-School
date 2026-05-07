<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\ImportantDateBranch;
use App\Models\ImportantDateEvent;
use Illuminate\Http\Request;

class ImportantDateController extends Controller
{
    public function primary()
    {
        $title = 'Important Dates - Primary';
        $program = 'primary';
        $campuses = $this->campusesList();

        return view('important_dates.index', compact('title', 'program', 'campuses'));
    }

    public function preschool()
    {
        $title = 'Important Dates - Preschool';
        $program = 'preschool';
        $campuses = $this->campusesList();

        return view('important_dates.index', compact('title', 'program', 'campuses'));
    }

    public function events(Request $request)
    {
        $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $start = sprintf('%04d-%02d-01', $request->year, $request->month);
        $end = date('Y-m-t', strtotime($start));

        $events = ImportantDateEvent::query()
            ->where('campus_id', $request->campus_id)
            ->whereBetween('event_date', [$start, $end])
            ->orderBy('event_date')
            ->get()
            ->map(function ($e) {
                $e->event_date = $e->event_date ? $e->event_date->format('Y-m-d') : null;
                return $e;
            });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
            'dates' => 'nullable',
        ]);

        $payload = $request->only(['campus_id', 'event_date', 'title', 'description']);
        $dates = $request->input('dates');
        $dateList = [];
        if (is_array($dates)) {
            $dateList = $dates;
        } elseif (is_string($dates)) {
            $dateList = preg_split('/[,\s]+/', $dates, -1, PREG_SPLIT_NO_EMPTY);
        }

        if (empty($payload['event_date']) && empty($dateList)) {
            return response()->json(['message' => 'Tanggal event wajib diisi'], 422);
        }

        $created = [];
        if (!empty($dateList)) {
            foreach ($dateList as $d) {
                if (!strtotime($d)) {
                    continue;
                }
                $created[] = ImportantDateEvent::create([
                    'campus_id' => $payload['campus_id'],
                    'event_date' => $d,
                    'title' => $payload['title'],
                    'description' => $payload['description'],
                ]);
            }
            return response()->json(['message' => 'Events created successfully', 'count' => count($created)]);
        }

        $event = ImportantDateEvent::create($payload);
        return response()->json(['message' => 'Event created successfully', 'event' => $event]);
    }

    private function campusesList()
    {
        return Campus::query()
            ->select('id', 'title')
            ->orderBy('title')
            ->limit(6)
            ->get();
    }
}
