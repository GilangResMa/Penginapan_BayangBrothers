<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Room;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create sample user
        $user = User::first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'contact' => '081234567890',
                'password' => bcrypt('password123'),
            ]);
        }

        // Get first room
        $room = Room::first();
        
        if (!$room) {
            $this->command->error('No rooms found. Please create rooms first.');
            return;
        }

        // Create sample bookings if none exist
        if (Booking::count() == 0) {
            $bookings = [];
            
            // Create 5 sample bookings for the last 3 months
            for ($i = 0; $i < 5; $i++) {
                $checkIn = Carbon::now()->subDays(rand(10, 90));
                $checkOut = $checkIn->copy()->addDays(rand(1, 5));
                
                $booking = Booking::create([
                    'user_id' => $user->id,
                    'room_id' => $room->id,
                    'booking_code' => 'BB' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'guests' => rand(1, 4),
                    'extra_bed' => rand(0, 1),
                    'total_cost' => rand(300000, 800000),
                    'status' => ['confirmed', 'completed', 'awaiting_payment'][rand(0, 2)],
                    'created_at' => $checkIn->copy()->subDays(rand(1, 10)),
                    'updated_at' => $checkIn->copy()->subDays(rand(1, 10)),
                ]);
                
                $bookings[] = $booking;
            }
            
            $this->command->info('Created ' . count($bookings) . ' sample bookings.');
            
            // Create sample payments for some bookings
            foreach ($bookings as $index => $booking) {
                if ($index < 3) { // Create payments for first 3 bookings
                    $payment = Payment::create([
                        'booking_id' => $booking->id,
                        'amount' => $booking->total_cost,
                        'payment_method' => ['qris', 'bank_transfer'][rand(0, 1)],
                        'status' => ['verified', 'pending'][rand(0, 1)],
                        'proof_of_payment' => null,
                        'verified_at' => $booking->status == 'completed' ? $booking->created_at->addHours(rand(1, 24)) : null,
                        'verified_by' => null,
                        'verification_notes' => null,
                        'created_at' => $booking->created_at->addHours(rand(1, 6)),
                        'updated_at' => $booking->created_at->addHours(rand(1, 6)),
                    ]);
                    
                    if ($payment->status == 'verified') {
                        $payment->update(['verified_at' => $payment->created_at->addHours(rand(1, 12))]);
                    }
                }
            }
            
            $this->command->info('Created sample payments for 3 bookings.');
        } else {
            $this->command->info('Bookings already exist. Skipping sample data creation.');
        }
    }
}
