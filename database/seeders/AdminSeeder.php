<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo user for testing
        Admin::create([
            'email' => 'grm@email.com',
            'password' => 'password123'
        ]);

        Admin::create([
            'email' => 'mda@email.com',
            'password' => 'password123'
        ]);
    }
}
