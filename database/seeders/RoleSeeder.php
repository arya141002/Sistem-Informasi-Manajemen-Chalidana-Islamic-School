<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin'
            ],
            [
                'name' => 'managing_director',
                'display_name' => 'Managing Director'
            ],
            [
                'name' => 'administration_staff',
                'display_name' => 'Administration Staff'
            ],
            [
                'name' => 'teacher',
                'display_name' => 'Teacher'
            ],
            [
                'name' => 'principal',
                'display_name' => 'Principal'
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}