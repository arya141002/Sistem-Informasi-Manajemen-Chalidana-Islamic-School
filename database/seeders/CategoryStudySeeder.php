<?php

namespace Database\Seeders;

use App\Models\CategoryStudy;
use Illuminate\Database\Seeder;

class CategoryStudySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $rows = [
            ['title' => 'Preschool', 'slug' => 'preschool'],
            ['title' => 'Primary', 'slug' => 'primary'],
        ];

        foreach ($rows as $row) {
            CategoryStudy::firstOrCreate(['slug' => $row['slug']], $row);
        }
    }
}
