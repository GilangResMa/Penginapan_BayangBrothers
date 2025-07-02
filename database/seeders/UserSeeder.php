<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Regular Users/Customers with complete data for testing
        $customers = [
            [
                'name' => 'Tester',
                'email' => 'test@gmail.com',
                'password' => Hash::make('test0123'),
                'contact' => '012345678900',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($customers as $customer) {
            User::create($customer);
        }
    }
}
