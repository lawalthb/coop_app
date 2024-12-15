<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'title' => 'Mr.',
            'surname' => 'Admin',
            'firstname' => 'Super',
            'department_id' => 1,
            'faculty_id' => 1,
            'phone_number' => '08012345678',
            'email' => 'admin@admin.com',
            'state_id' => 1,
            'lga_id' => 1,
            'member_no' => 'ADMIN001',
            'password' => Hash::make('12345678'),
            'is_admin' => 1,
            'is_approved' => 1,
            'approved_at' => now(),
        ]);
    }
}
