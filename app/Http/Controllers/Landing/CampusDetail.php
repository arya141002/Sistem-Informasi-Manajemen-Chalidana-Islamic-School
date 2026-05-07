<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\CampusArea;
use App\Models\Tagline;
use App\Models\TeacherAndStaff;

class CampusDetail extends Controller
{
    public function show(string $slug)
    {
        $tagline = Tagline::first();
        $campusAreas = CampusArea::query()
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->get(['title', 'address', 'phone']);
        $campus = Campus::with([
                'campusArea:id,title',
                'facilities' => fn ($query) => $query
                    ->whereNull('deleted_at')
                    ->latest(),
            ])
            ->whereNull('deleted_at')
            ->where('slug', $slug)
            ->firstOrFail();

        $teachersAndStaff = TeacherAndStaff::query()
            ->where('campus_id', $campus->id)
            ->whereNull('deleted_at')
            ->latest()
            ->get()
            ->groupBy('role');

        $settings = [
            'site_name' => get_system('site_name', config('app.name')),
            'site_title' => get_system('site_title', config('app.name')),
            'site_description' => get_system('site_description', 'Website resmi ' . config('app.name')),
            'site_keywords' => get_system('site_keywords', config('app.name')),
            'contact_email' => get_system('contact_email', ''),
            'contact_phone' => get_system('contact_phone', ''),
            'site_favicon' => get_system('site_favicon'),
        ];

        return view('landing.school_detail', compact('tagline', 'campusAreas', 'campus', 'teachersAndStaff', 'settings'));
    }
}
