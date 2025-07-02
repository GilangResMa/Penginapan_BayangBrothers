<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all existing rooms to belong to the first owner (owner_id = 1)
        // In a real scenario, you would have proper logic to assign rooms to correct owners
        DB::table('rooms')
            ->whereNull('owner_id')
            ->update(['owner_id' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally reset owner_id to null if needed
        DB::table('rooms')->update(['owner_id' => null]);
    }
};
