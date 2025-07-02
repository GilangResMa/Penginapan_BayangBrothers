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
            'name' => 'Admin GRM',
            'email' => 'grm@gmail.com',
            'password' => Hash::make('gilangrm'),
            'created_by' => 1, // Assuming 1 is the owner ID
            'status' => true,
        ]);

        Admin::create([
            'name' => 'Admin MDA',
            'email' => 'mda@gmail.com',
            'password' => Hash::make('madedelsa'),
            'created_by' => 1, // Assuming 1 is the owner ID
            'status' => true,
        ]);
    }
}
