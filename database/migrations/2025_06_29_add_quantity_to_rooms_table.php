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
        Schema::table('rooms', function (Blueprint $table) {
            $table->integer('total_quantity')->default(1)->after('max_guests'); // Total kamar tersedia
            $table->integer('available_quantity')->default(1)->after('total_quantity'); // Kamar yang masih tersedia
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['total_quantity', 'available_quantity']);
        });
    }
};
