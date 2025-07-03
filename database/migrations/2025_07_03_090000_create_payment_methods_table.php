<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('type');  // 'bank' atau 'qris'
            $table->string('name');  // Nama bank atau 'QRIS'
            $table->string('bank_name')->nullable();  // untuk bank transfer
            $table->string('account_number')->nullable();  // untuk bank transfer
            $table->string('account_name')->nullable();  // untuk bank transfer
            $table->text('qr_image')->nullable();  // untuk QRIS (path ke gambar)
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Tambahkan data default
        \Illuminate\Support\Facades\DB::table('payment_methods')->insert([
            [
                'type' => 'bank',
                'name' => 'Bank Transfer - BCA',
                'bank_name' => 'BCA',
                'account_number' => '4561133632',
                'account_name' => 'Ribka Sebayang',
                'description' => null,
                'qr_image' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => 'qris',
                'name' => 'QRIS Payment',
                'bank_name' => null,
                'account_number' => null,
                'account_name' => null,
                'qr_image' => null,
                'description' => 'Scan QRIS code to pay with any e-wallet or mobile banking app',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
}
