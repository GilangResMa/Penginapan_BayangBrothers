<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Standard Room',
                'description' => 'Comfortable standard room with basic amenities including AC, TV, and private bathroom. Perfect for budget travelers.',
                'price_weekday' => 150000,
                'price_weekend' => 180000,
                'extra_bed_price' => 70000,
                'max_guests' => 2,
                'total_quantity' => 12,      // Total 12 kamar
                'available_quantity' => 12   // Semua tersedia di awal
            ],
        ];

        foreach ($rooms as $room) {
            \App\Models\Room::create($room);
        }
    }
}
