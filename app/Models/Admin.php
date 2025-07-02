<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'created_by',
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
     * Get the owner who created this admin
     */
    public function createdBy()
    {
        return $this->belongsTo(Owner::class, 'created_by');
    }

    /**
     * Get all payments verified by this admin
     */
    public function verifiedPayments()
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }
}