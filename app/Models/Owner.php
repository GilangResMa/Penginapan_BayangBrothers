<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Owner extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'status' => 'boolean',
    ];

    /**
     * Get all admins created by this owner
     */
    public function admins()
    {
        return $this->hasMany(Admin::class, 'created_by');
    }

    /**
     * Get all bookings related to this owner's rooms
     */
    public function bookings()
    {
        return $this->hasManyThrough(
            Booking::class,
            Room::class,
            'owner_id', // Foreign key on rooms table
            'room_id',  // Foreign key on bookings table
            'id',       // Local key on owners table
            'id'        // Local key on rooms table
        );
    }

    /**
     * Get total revenue from confirmed bookings
     */
    public function getTotalRevenueAttribute()
    {
        return $this->bookings()
            ->where('status', 'confirmed')
            ->sum('total_cost');
    }

    /**
     * Get monthly revenue
     */
    public function getMonthlyRevenue($year = null, $month = null)
    {
        $year = $year ?? date('Y');
        $month = $month ?? date('m');

        return $this->bookings()
            ->where('status', 'confirmed')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('total_cost');
    }

    /**
     * Get booking statistics
     */
    public function getBookingStats()
    {
        $totalBookings = $this->bookings()->count();
        $confirmedBookings = $this->bookings()->where('status', 'confirmed')->count();
        $pendingBookings = $this->bookings()->where('status', 'pending')->count();
        $cancelledBookings = $this->bookings()->where('status', 'cancelled')->count();

        return [
            'total' => $totalBookings,
            'confirmed' => $confirmedBookings,
            'pending' => $pendingBookings,
            'cancelled' => $cancelledBookings,
            'success_rate' => $totalBookings > 0 ? round(($confirmedBookings / $totalBookings) * 100, 2) : 0
        ];
    }
}
