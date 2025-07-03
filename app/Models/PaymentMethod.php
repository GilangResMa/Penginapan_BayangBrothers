<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'bank_name',
        'account_number',
        'account_name',
        'qr_image',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the formatted account number with spaces for better readability
     */
    public function getFormattedAccountNumberAttribute()
    {
        if ($this->account_number) {
            return implode(' ', str_split($this->account_number, 4));
        }
        return null;
    }

    /**
     * Get the full QR image URL
     */
    public function getQrImageUrlAttribute()
    {
        if ($this->qr_image) {
            return asset('storage/' . $this->qr_image);
        }
        return null;
    }

    /**
     * Check if this is a bank transfer method
     */
    public function getIsBankAttribute()
    {
        return $this->type === 'bank';
    }

    /**
     * Check if this is a QRIS method
     */
    public function getIsQrisAttribute()
    {
        return $this->type === 'qris';
    }
}
