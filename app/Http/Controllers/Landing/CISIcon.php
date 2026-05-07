<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\CampusArea;
use App\Models\CISIcon as CISIconModel;
use App\Models\Tagline;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CISIcon extends Controller
{
    public function index()
    {
        $tagline = Tagline::first();
        $campusAreas = CampusArea::query()
            ->whereNull('deleted_at')
            ->orderBy('title')
            ->get(['title', 'address', 'phone']);
        $icons = CISIconModel::query()
            ->whereNull('deleted_at')
            ->latest()
            ->paginate(12);

        $settings = [
            'site_name' => get_system('site_name', config('app.name')),
            'site_title' => get_system('site_title', config('app.name')),
            'site_description' => get_system('site_description', 'Website resmi ' . config('app.name')),
            'site_keywords' => get_system('site_keywords', config('app.name')),
            'contact_email' => get_system('contact_email', ''),
            'contact_phone' => get_system('contact_phone', ''),
            'site_favicon' => get_system('site_favicon'),
        ];

        return view('landing.cis_icon', compact('tagline', 'campusAreas', 'icons', 'settings'));
    }

    public function download(int $id)
    {
        $icon = CISIconModel::query()->findOrFail($id);

        abort_if(empty($icon->file), 404);

        $filePath = public_path('images/icon/' . $icon->file);
        abort_unless(File::exists($filePath), 404);

        $extension = pathinfo($icon->file, PATHINFO_EXTENSION);
        $downloadName = Str::slug($icon->title ?: 'cis-icon');
        $downloadName = ($downloadName !== '' ? $downloadName : 'cis-icon') . ($extension ? '.' . $extension : '');

        return response()->download($filePath, $downloadName);
    }
}
