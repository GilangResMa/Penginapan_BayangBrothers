<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Enable automatic timestamps untuk created_at/updated_at
    public $timestamps = true;

    protected $fillable = [
        'name',
        'contact',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Automatically hash password when creating user
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * Get all bookings for this user
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
