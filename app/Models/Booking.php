<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'booking_code',
        'check_in',
        'check_out',
        'guests',
        'extra_bed',
        'total_cost',
        'status',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'extra_bed' => 'boolean',
        'total_cost' => 'decimal:2',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Room
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Hitung jumlah malam
     */
    public function getNightsAttribute()
    {
        return $this->check_in->diffInDays($this->check_out);
    }

    /**
     * Format check-in date
     */
    public function getFormattedCheckInAttribute()
    {
        return $this->check_in->format('d M Y');
    }

    /**
     * Format check-out date
     */
    public function getFormattedCheckOutAttribute()
    {
        return $this->check_out->format('d M Y');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'confirmed':
                return 'bg-green-100 text-green-800';
            case 'cancelled':
                return 'bg-red-100 text-red-800';
            case 'completed':
                return 'bg-blue-100 text-blue-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'Menunggu Pembayaran';
            case 'confirmed':
                return 'Terkonfirmasi';
            case 'cancelled':
                return 'Dibatalkan';
            case 'completed':
                return 'Selesai';
            default:
                return 'Unknown';
        }
    }

    /**
     * Scope untuk booking aktif
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'pending']);
    }

    /**
     * Scope untuk booking user tertentu
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
