<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Agency;
use App\Models\Branches;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@cert.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Create manager user
        User::create([
            'name' => 'Manager',
            'email' => 'manager@cert.com',
            'password' => Hash::make('manager123'),
            'role' => 'manager'
        ]);

        // Create sample agencies
        $faculty = Agency::create([
            'agency_name' => 'คณะวิทยาศาสตร์'
        ]);

        $business = Agency::create([
            'agency_name' => 'คณะบริหารธุรกิจ'
        ]);

        // Create sample branches
        Branches::create([
            'branch_name' => 'วิทยาการคอมพิวเตอร์',
            'agency_id' => $faculty->agency_id
        ]);

        Branches::create([
            'branch_name' => 'คณิตศาสตร์',
            'agency_id' => $faculty->agency_id
        ]);

        Branches::create([
            'branch_name' => 'การจัดการ',
            'agency_id' => $business->agency_id
        ]);

        Branches::create([
            'branch_name' => 'การตลาด',
            'agency_id' => $business->agency_id
        ]);
    }
}
