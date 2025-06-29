<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What time is check-in and check-out?',
                'answer' => 'Check-in time is 2:00 PM and check-out time is 12:00 PM. Early check-in or late check-out may be available upon request and subject to availability.'
            ],
            [
                'question' => 'Do you provide free Wi-Fi?',
                'answer' => 'Yes, we provide complimentary high-speed Wi-Fi internet access throughout the hotel, including all guest rooms and common areas.'
            ],
            [
                'question' => 'Is parking available?',
                'answer' => 'Yes, we offer free parking for our guests. The parking area is secure and monitored 24/7 for your peace of mind.'
            ],
            [
                'question' => 'What is your cancellation policy?',
                'answer' => 'Reservations can be cancelled up to 24 hours before the check-in date without penalty. Cancellations made within 24 hours of check-in will be charged for the first night.'
            ],
            [
                'question' => 'Do you allow pets?',
                'answer' => 'Unfortunately, we do not allow pets in our hotel, with the exception of certified service animals for guests with disabilities.'
            ],
            [
                'question' => 'What amenities are included in the room?',
                'answer' => 'All rooms include air conditioning, flat-screen TV, private bathroom with hot water, complimentary toiletries, and daily housekeeping service.'
            ]
        ];

        foreach ($faqs as $faq) {
            \App\Models\Faq::create($faq);
        }
    }
}
