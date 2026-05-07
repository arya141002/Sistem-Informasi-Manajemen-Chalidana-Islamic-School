<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\About_school;
use App\Models\Campus;
use App\Models\CampusArea;
use App\Models\CisInNumber;
use App\Models\CISUpdate;
use App\Models\Event;
use App\Models\Testimonial;
use App\Models\Tagline;

class Index extends Controller
{
    public function index()
    {
        $tagline = Tagline::first();
        $aboutSchool = About_school::find(11);
        $campusAreas = CampusArea::query()
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->get(['title', 'address', 'phone']);
        $campuses = Campus::with('campusArea:id,title')
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->get();
        $cisInNumbers = CisInNumber::query()->latest()->get();
        $cisUpdates = CISUpdate::query()
            ->where('status', true)
            ->latest()
            ->take(5)
            ->get();
        $events = Event::with('study:id,title')->latest()->take(6)->get();
        $testimonials = Testimonial::with(['grade:id,name', 'campus:id,title'])
            ->where('status', true)
            ->latest()
            ->take(9)
            ->get();

        $settings = [
            'site_name' => get_system('site_name', config('app.name')),
            'site_title' => get_system('site_title', config('app.name')),
            'site_description' => get_system('site_description', 'Website resmi ' . config('app.name')),
            'site_keywords' => get_system('site_keywords', config('app.name')),
            'contact_email' => get_system('contact_email', ''),
            'contact_phone' => get_system('contact_phone', ''),
            'site_favicon' => get_system('site_favicon'),
        ];

        return view('landing.index', compact('settings', 'tagline', 'aboutSchool', 'campusAreas', 'campuses', 'cisInNumbers', 'cisUpdates', 'events', 'testimonials'));
    }
}
