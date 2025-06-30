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
                'question' => 'Jam berapa check-in dan check-out?',
                'answer' => 'Waktu check-in adalah pukul 14:00 dan check-out pukul 12:00. Check-in lebih awal atau check-out lebih lambat dapat tersedia atas permintaan dan tergantung ketersediaan.'
            ],
            [
                'question' => 'Apakah menyediakan Wi-Fi gratis?',
                'answer' => 'Ya, kami menyediakan akses internet Wi-Fi berkecepatan tinggi gratis di seluruh area hotel, termasuk semua kamar tamu dan area umum.'
            ],
            [
                'question' => 'Apakah tersedia tempat parkir?',
                'answer' => 'Ya, kami menyediakan parkir gratis untuk tamu kami. Area parkir aman dan dipantau 24/7 untuk ketenangan pikiran Anda.'
            ],
            [
                'question' => 'Bagaimana kebijakan pembatalan?',
                'answer' => 'Reservasi dapat dibatalkan hingga 24 jam sebelum tanggal check-in tanpa penalti. Pembatalan yang dilakukan dalam 24 jam sebelum check-in akan dikenakan biaya untuk malam pertama.'
            ],
            [
                'question' => 'Apakah boleh membawa hewan peliharaan?',
                'answer' => 'Maaf, kami tidak mengizinkan hewan peliharaan di hotel kami, kecuali hewan pemandu yang bersertifikat untuk tamu dengan disabilitas.'
            ],
            [
                'question' => 'Fasilitas apa saja yang tersedia di kamar?',
                'answer' => 'Semua kamar dilengkapi dengan AC, TV layar datar, kamar mandi pribadi dengan air panas, perlengkapan mandi gratis, dan layanan kebersihan harian.'
            ]
        ];

        foreach ($faqs as $faq) {
            \App\Models\Faq::create($faq);
        }
    }
}
