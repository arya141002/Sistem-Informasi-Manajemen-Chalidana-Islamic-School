<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $title = 'System Settings';

        $settings = [
            'site_name' => get_system('site_name', config('app.name')),
            'site_title' => get_system('site_title', config('app.name')),
            'site_description' => get_system('site_description', 'Website resmi ' . config('app.name')),
            'site_keywords' => get_system('site_keywords', config('app.name')),
            'contact_email' => get_system('contact_email', ''),
            'contact_phone' => get_system('contact_phone', ''),
            'site_favicon' => get_system('site_favicon'),
        ];

        return view('admin.settings', compact('title', 'settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_title' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_keywords' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'site_favicon' => 'nullable|image|mimes:png,jpg,jpeg,webp,ico|max:2048',
        ]);

        set_system('site_name', $validated['site_name'] ?? null, 'general');
        set_system('site_title', $validated['site_title'], 'seo');
        set_system('site_description', $validated['site_description'] ?? null, 'seo');
        set_system('site_keywords', $validated['site_keywords'] ?? null, 'seo');
        set_system('contact_email', $validated['contact_email'] ?? null, 'contact');
        set_system('contact_phone', $validated['contact_phone'] ?? null, 'contact');

        if ($request->hasFile('site_favicon')) {
            $existingFile = get_system('site_favicon');

            if ($existingFile && Storage::disk('public')->exists('systems/' . $existingFile)) {
                Storage::disk('public')->delete('systems/' . $existingFile);
            }

            $file = $request->file('site_favicon');
            $filename = 'favicon-' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('systems', $filename, 'public');

            set_system('site_favicon', $filename, 'appearance');
        }

        return redirect()->route('settings.index')->with('success', 'System settings berhasil diperbarui.');
    }
}
