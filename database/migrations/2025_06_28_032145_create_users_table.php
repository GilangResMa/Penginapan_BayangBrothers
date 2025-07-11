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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('User Name');
            $table->string('contact')->comment('User Contact');
            $table->string('email')->unique()->comment('User Email');
            $table->timestamp('email_verified_at')->nullable()->comment('Email Verification Time');
            $table->string('password')->comment('User Password');
            $table->rememberToken()->comment('Remember Token for User');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
