<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Owner;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Owner::create([
            'name' => 'Super Owner',
            'email' => 'owner@bayangbrothers.com',
            'password' => Hash::make('owner123'),
            'status' => true,
        ]);

        Owner::create([
            'name' => 'Owner 2',
            'email' => 'owner2@bayangbrothers.com',
            'password' => Hash::make('owner123'),
            'status' => true,
        ]);
    }
}
