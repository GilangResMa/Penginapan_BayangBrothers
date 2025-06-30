<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'price_weekday',
        'price_weekend',
        'extra_bed_price',
        'max_guests',
        'total_quantity',
        'available_quantity',
    ];

    protected $casts = [
        'price_weekday' => 'decimal:2',
        'price_weekend' => 'decimal:2',
        'extra_bed_price' => 'decimal:2',
    ];

    // Relasi dengan owner
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    // Relasi dengan booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Cek ketersediaan kamar untuk tanggal tertentu
     */
    public function getAvailableQuantityForDate($checkIn, $checkOut)
    {
        // Hitung berapa kamar yang sudah dibooking untuk periode ini
        $bookedQuantity = $this->bookings()
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    // Booking yang overlap dengan periode yang diminta
                    $q->where('check_in', '<', $checkOut)
                        ->where('check_out', '>', $checkIn);
                })
                    ->whereIn('status', ['pending', 'confirmed']); // Hanya booking aktif
            })
            ->count();

        return $this->total_quantity - $bookedQuantity;
    }

    /**
     * Cek apakah kamar tersedia untuk booking
     */
    public function isAvailableForBooking($checkIn, $checkOut, $requestedQuantity = 1)
    {
        $availableQuantity = $this->getAvailableQuantityForDate($checkIn, $checkOut);
        return $availableQuantity >= $requestedQuantity;
    }

    /**
     * Get booking conflicts for specific date range
     */
    public function getBookingConflicts($checkIn, $checkOut)
    {
        return $this->bookings()
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn);
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->with('user')
            ->get();
    }

    /**
     * Check if room type is fully booked for date range
     */
    public function isFullyBookedForDate($checkIn, $checkOut)
    {
        return $this->getAvailableQuantityForDate($checkIn, $checkOut) <= 0;
    }
}
