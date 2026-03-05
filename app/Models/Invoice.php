<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'client_id',
        'invoice_number',
        'status',
        'issue_date',
        'due_date',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date'   => 'date',
    ];


    public function aiInsights()
    {
        return $this->morphMany(AiInsight::class, 'subject')->latest();
    }
    
    public function latestInsight(string $type): ?AiInsight
    {
        return $this->aiInsights()->where('type', $type)->first();
    }

    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Auto-generate invoice number
    public static function generateNumber(): string
    {
        // Lock the table to prevent race conditions
        $last = static::lockForUpdate()->latest()->first();
        $next = $last ? ((int) substr($last->invoice_number, 4)) + 1 : 1;
        return 'INV-' . str_pad($next, 8, '0', STR_PAD_LEFT);
    }

    public function getStatusColorClass(): string
    {
        return match($this->status) {
            'paid'    => 'bg-emerald-100 text-emerald-800',
            'partial' => 'bg-amber-100 text-amber-800',
            'sent'    => 'bg-blue-100 text-blue-800',
            'overdue' => 'bg-rose-100 text-rose-800',
            default   => 'bg-slate-100 text-slate-600',
        };
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalPaidAttribute(): float
    {
        return $this->payments->sum('amount');
    }

    public function getBalanceDueAttribute(): float
    {
        return $this->total - $this->total_paid;
    }
}
