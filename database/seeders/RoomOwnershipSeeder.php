<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Owner;

class RoomOwnershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first owner or create one if none exists
        $owner = Owner::first();
        
        if (!$owner) {
            $owner = Owner::create([
                'name' => 'Default Owner',
                'email' => 'owner@bayangbrothers.com',
                'password' => bcrypt('password123'),
                'status' => true,
            ]);
        }

        // Assign all rooms without owner to the first owner
        Room::whereNull('owner_id')
            ->orWhere('owner_id', 0)
            ->update(['owner_id' => $owner->id]);

        $this->command->info('All rooms have been assigned to owner: ' . $owner->name);
    }
}
