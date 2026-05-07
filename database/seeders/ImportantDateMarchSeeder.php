<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\ImportantDateEvent;
use Illuminate\Database\Seeder;

class ImportantDateMarchSeeder extends Seeder
{
    public function run(): void
    {
        $campuses = Campus::query()->orderBy('title')->limit(6)->get(['id', 'title']);
        $dates = ['2026-03-05', '2026-03-07', '2026-03-12', '2026-03-16', '2026-03-20', '2026-03-25'];

        foreach ($campuses as $idx => $campus) {
            $date = $dates[$idx % count($dates)];
            $title = 'Academic Event - ' . $campus->title;
            $description = 'Academic calendar event for ' . $campus->title . '.';

            ImportantDateEvent::firstOrCreate(
                ['campus_id' => $campus->id, 'event_date' => $date, 'title' => $title],
                ['description' => $description]
            );
        }
    }
}
