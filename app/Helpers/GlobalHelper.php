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
        if (Storage::disk('public')->exists($publicPath)) {
            return Storage::url($publicPath);
        }

        $legacyPath = 'uploads/systems/' . ltrim($value, '/');
        if (file_exists(public_path($legacyPath))) {
            return asset($legacyPath);
        }

        return $default ? asset($default) : asset($value);
    }
}
