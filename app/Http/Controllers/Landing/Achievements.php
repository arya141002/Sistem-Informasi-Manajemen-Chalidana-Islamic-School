<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Campus;
use App\Models\CampusArea;
use App\Models\Tagline;
use Illuminate\Http\Request;

class Achievements extends Controller
{
    public function index(Request $request)
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

        $selectedCampus = $request->integer('campus');

        $achievements = Achievement::query()
            ->with('campus:id,title')
            ->when($selectedCampus, fn ($query) => $query->where('campus_id', $selectedCampus))
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        $settings = [
            'site_name' => get_system('site_name', config('app.name')),
            'site_title' => get_system('site_title', config('app.name')),
            'site_description' => get_system('site_description', 'Website resmi ' . config('app.name')),
            'site_keywords' => get_system('site_keywords', config('app.name')),
            'contact_email' => get_system('contact_email', ''),
            'contact_phone' => get_system('contact_phone', ''),
            'site_favicon' => get_system('site_favicon'),
        ];

        return view('landing.achievement', compact('tagline', 'campusAreas', 'campuses', 'achievements', 'selectedCampus', 'settings'));
    }
}
