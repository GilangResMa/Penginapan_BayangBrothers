<?php

namespace Database\Seeders;

use App\Models\Admin;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder untuk data yang diperlukan
        $this->call([
            OwnerSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            RoomSeeder::class,
            FaqSeeder::class,
        ]);
    }
}
