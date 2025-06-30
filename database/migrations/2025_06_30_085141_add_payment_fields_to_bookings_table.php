<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('payment_method', ['bank_transfer', 'cash', 'digital_wallet'])->nullable()->after('status');
            $table->text('payment_note')->nullable()->after('payment_method');
            $table->timestamp('payment_confirmed_at')->nullable()->after('payment_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_note', 'payment_confirmed_at']);
        });
    }
};
