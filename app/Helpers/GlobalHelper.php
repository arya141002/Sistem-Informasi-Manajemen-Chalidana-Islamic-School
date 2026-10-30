<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\System;

if (!function_exists('current_locale')) {
    function current_locale(): string
    {
        $locale = session('locale', config('app.locale', 'en'));

        return in_array($locale, ['en', 'id'], true) ? $locale : 'en';
    }
}

if (!function_exists('localized_value')) {
    function localized_value($source, string $field, $default = null)
    {
        if (!$source) {
            return $default;
        }

        $locale = current_locale();
        $localizedField = $locale === 'id' ? $field . '_id' : $field;

        $value = data_get($source, $localizedField);

        if (filled($value)) {
            return $value;
        }

        $fallback = data_get($source, $field);

        return filled($fallback) ? $fallback : $default;
    }
}

if (!function_exists('get_system')) {
    /**
     * Mengambil pengaturan sistem dari database dengan dukungan Cache.
     */
    function get_system($key, $default = null) {
        try {
            if (!Schema::hasTable('systems')) {
                return $default;
            }

            return Cache::rememberForever('sys_' . $key, function () use ($key, $default) {
                return System::query()->where('key', $key)->value('value') ?? $default;
            });
        } catch (\Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('set_system')) {
    /**
     * Menyimpan pengaturan sistem dan membersihkan cache key terkait.
     */
    function set_system(string $key, ?string $value, string $group = 'general'): void
    {
        System::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        Cache::forget('sys_' . $key);
    }
}

if (!function_exists('get_system_file')) {
    /**
     * Mengambil URL file (Logo/Favicon) dari folder storage/public.
     */
    function get_system_file($key, $default = null) {
        $value = get_system($key);

        if (empty($value)) {
            return $default ? asset($default) : '';
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        $publicPath = 'systems/' . ltrim($value, '/');
        $publicDisk = Storage::disk('public');
        if ($publicDisk->exists($publicPath)) {
            $version = $publicDisk->lastModified($publicPath);
            $url = $publicDisk->url($publicPath);

            return $url . (str_contains($url, '?') ? '&' : '?') . 'v=' . $version;
        }

        $legacyPath = 'uploads/systems/' . ltrim($value, '/');
        if (file_exists(public_path($legacyPath))) {
            $url = asset($legacyPath);
            $version = filemtime(public_path($legacyPath));

            return $url . (str_contains($url, '?') ? '&' : '?') . 'v=' . $version;
        }

        return $default ? asset($default) : asset($value);
    }
}

if (!function_exists('webp_asset')) {
    function webp_asset(?string $path, ?string $fallback = null): string
    {
        $path = $path ?: $fallback;

        if (empty($path)) {
            return '';
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');
        $extension = strtolower(pathinfo($normalizedPath, PATHINFO_EXTENSION));

        if (in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
            $webpPath = preg_replace('/\.[^.]+$/', '.webp', $normalizedPath);

            if ($webpPath && file_exists(public_path($webpPath))) {
                return asset($webpPath);
            }
        }

        return asset($normalizedPath);
    }
}

if (!function_exists('safe_rich_text')) {
    /**
     * Render HTML terbatas dengan whitelist tag sederhana.
     */
    function safe_rich_text($value): string
    {
        $html = (string) ($value ?? '');
        if ($html === '') {
            return '';
        }

        $html = str_replace(['‹', '›'], ['<', '>'], $html);

        // Jika tersimpan sebagai entity (&lt;br&gt;), decode dulu agar tag valid bisa dirender.
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $clean = strip_tags($html, '<p><br><strong><b><em><i><ul><ol><li><a>');

        // Hapus atribut event handler seperti onclick, onerror, dll.
        $clean = preg_replace('/\son\w+\s*=\s*("|\').*?\1/iu', '', $clean) ?? $clean;
        $clean = preg_replace('/\son\w+\s*=\s*[^\s>]+/iu', '', $clean) ?? $clean;

        // Netralisir javascript: pada href.
        $clean = preg_replace('/href\s*=\s*("|\')\s*javascript:[^"\']*\1/iu', 'href="#"', $clean) ?? $clean;
        $clean = preg_replace('/href\s*=\s*javascript:[^\s>]*/iu', 'href="#"', $clean) ?? $clean;
        $clean = preg_replace('/<a\b(?![^>]*\brel=)([^>]*\btarget\s*=\s*("|\')_blank\2[^>]*)>/iu', '<a$1 rel="noopener noreferrer">', $clean) ?? $clean;

        return $clean;
    }
}
