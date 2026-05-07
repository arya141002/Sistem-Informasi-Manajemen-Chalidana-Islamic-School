<?php

namespace Database\Seeders;

use App\Models\System;
use Illuminate\Database\Seeder;

class SystemSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Chalidana Islamic School', 'group' => 'general'],
            ['key' => 'site_title', 'value' => 'Chalidana Islamic School | Smart Islamic Leadership', 'group' => 'seo'],
            ['key' => 'site_description', 'value' => 'Chalidana Islamic School adalah institusi pendidikan Islam modern dengan fokus karakter, kepemimpinan, dan nilai Islami.', 'group' => 'seo'],
            ['key' => 'site_keywords', 'value' => 'Chalidana Islamic School, sekolah islam, CIS, smart leadership', 'group' => 'seo'],
            ['key' => 'contact_email', 'value' => 'admission@chalidanaislamicschool.sch.id', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '+6281231113232', 'group' => 'contact'],
            ['key' => 'site_logo', 'value' => 'logo-cis.png', 'group' => 'appearance'],
            ['key' => 'site_favicon', 'value' => 'favicon-cis.ico', 'group' => 'appearance'],
        ];

        foreach ($settings as $setting) {
            System::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}
