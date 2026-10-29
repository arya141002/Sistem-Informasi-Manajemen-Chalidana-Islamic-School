<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\CampusArea;
use App\Models\Tagline;

class SchoolLeadership extends Controller
{
    public function index()
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

        $foundationStructures = [
            [
                'name' => 'M. Yunus Chalidana',
                'organization_key' => 'front.leadership_organization_ilyas_safira_foundation',
                'position_key' => 'front.leadership_position_chairman',
                'image' => webp_asset('landing/img/Mr-Yunus.png'),
            ],
            [
                'name' => 'Jamaludin',
                'organization_key' => 'front.leadership_organization_chalidana_islamic_school',
                'position_key' => 'front.leadership_position_managing_director',
                'image' => webp_asset('landing/img/Mr-Jamal.jpeg?v=999'),
            ],
            [
                'name' => 'Yudi Iskandar Atmaja',
                'organization_key' => 'front.leadership_organization_chalidana_islamic_school',
                'position_key' => 'front.leadership_position_head_of_hrd',
                'image' => webp_asset('landing/img/Mr-Yudi.png'),
            ],
            [
                'name' => 'Dodo Hidra Laksmono',
                'organization_key' => 'front.leadership_organization_chalidana_islamic_school',
                'position_key' => 'front.leadership_position_public_relations',
                'image' => webp_asset('landing/img/Mr-Dodo.jpg'),
            ],
            [
                'name' => 'Arya Dhiva Handy',
                'organization_key' => 'front.leadership_organization_chalidana_islamic_school',
                'position_key' => 'front.leadership_position_head_of_web_development',
                'image' => webp_asset('landing/img/Arya-dhiva.jpg'),
            ],
        ];

        return view('landing.school_leadership', compact('tagline', 'campusAreas', 'settings', 'foundationStructures'));
    }
}
