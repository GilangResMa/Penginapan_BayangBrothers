<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_weekday',
        'price_weekend',
        'extra_bed_price',
        'max_guests',
    ];

    protected $casts = [
        'price_weekday' => 'decimal:2',
        'price_weekend' => 'decimal:2',
        'extra_bed_price' => 'decimal:2',
    ];

    // Relasi dengan booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
