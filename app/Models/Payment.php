<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_id',
        'amount',
        'payment_date',
        'method',
        'reference',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getMethodLabelAttribute(): string
    {
        return match($this->method) {
            'bank_transfer' => 'Bank Transfer',
            'credit_card'   => 'Credit Card',
            default         => ucfirst($this->method),
        };
    }
}