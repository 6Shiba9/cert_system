<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Agency;
use App\Models\Branches;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create manager user
        User::create([
            'name' => 'Manager',
            'email' => 'manager@test.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        // Create sample agency
        $agency = Agency::create([
            'agency_name' => 'คณะวิทยาศาสตร์',
        ]);

        // Create sample branches
        Branches::create([
            'branch_name' => 'วิทยาการคอมพิวเตอร์',
            'agency_id' => $agency->agency_id,
        ]);

        Branches::create([
            'branch_name' => 'คณิตศาสตร์',
            'agency_id' => $agency->agency_id,
        ]);
    }
}
