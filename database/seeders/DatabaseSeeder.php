<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SystemSeeder::class,
            CategoryStudySeeder::class,
            GradeSeeder::class,
        ]);

        // Create roles first
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
        ]);

        $userRole = Role::create([
            'name' => 'user',
            'display_name' => 'User',
        ]);

        // Create user with admin role
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => $adminRole->id,
        ]);

        // Create additional user with user role
        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'role_id' => $userRole->id,
        ]);
    }
}
