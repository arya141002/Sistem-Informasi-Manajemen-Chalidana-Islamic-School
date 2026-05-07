<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class system extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Information
            ['key' => 'site_name', 'value' => 'Chalidana Islamic School', 'group' => 'general'],
            
            // SEO Configuration
            ['key' => 'site_title', 'value' => 'Chalidana Islamic School | Smart Islamic Leadership', 'group' => 'seo'],
            ['key' => 'site_description', 'value' => 'Chalidana Islamic School adalah institusi pendidikan Islam modern yang berfokus pada pengembangan karakter, kepemimpinan cerdas (Smart Leadership), dan nilai-nilai Islami untuk jenjang Preschool hingga Primary.', 'group' => 'seo'],
            ['key' => 'site_keywords', 'value' => 'Chalidana Islamic School, CIS, Sekolah Islam Gresik, Sekolah Islam Sidoarjo, Sekolah Islam Malang, Preschool Islam, Primary School Indonesia, Smart Islamic Leadership', 'group' => 'seo'],
            
            // Contact Information
            ['key' => 'contact_email', 'value' => 'admission@chalidanaislamicschool.sch.id', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '+6281231113232', 'group' => 'contact'], // Hotline Pusat (Gresik)
            
            // Appearance
            ['key' => 'site_logo', 'value' => 'logo-cis.png', 'group' => 'appearance'],
            ['key' => 'site_favicon', 'value' => 'favicon-cis.ico', 'group' => 'appearance'],
        ];

        foreach ($settings as $setting) {
            \App\Models\System::updateOrCreate(
                ['key' => $setting['key']], 
                [
                    'value' => $setting['value'],
                    'group' => $setting['group']
                ]
            );
        }
    }
}
