<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\About_school;
use App\Models\CampusArea;
use App\Models\Tagline;

class About extends Controller
{
    public function index()
    {
        $tagline = Tagline::first();
        $campusAreas = CampusArea::query()
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->get(['title', 'address', 'phone']);
        $aboutIds = [1, 2, 3, 4, 5];
        $aboutSections = About_school::query()
            ->whereIn('id', $aboutIds)
            ->get()
            ->sortBy(fn ($item) => array_search($item->id, $aboutIds, true))
            ->keyBy('id');

        $settings = [
            'site_name' => get_system('site_name', config('app.name')),
            'site_title' => get_system('site_title', config('app.name')),
            'site_description' => get_system('site_description', 'Website resmi ' . config('app.name')),
            'site_keywords' => get_system('site_keywords', config('app.name')),
            'contact_email' => get_system('contact_email', ''),
            'contact_phone' => get_system('contact_phone', ''),
            'site_favicon' => get_system('site_favicon'),
        ];

        return view('landing.about', compact('tagline', 'campusAreas', 'aboutSections', 'settings'));
    }
}
