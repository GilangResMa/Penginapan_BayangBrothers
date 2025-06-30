<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo user for testing
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'grm@gmail.com',
            'password' => Hash::make('password123'),
            'status' => true,
        ]);

        Admin::create([
            'name' => 'Admin 2',
            'email' => 'mda@gmail.com',
            'password' => Hash::make('password123'),
            'status' => true,
        ]);
    }
}
