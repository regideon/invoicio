<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'company',
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

    public function invoices()
    {
        return $this->hasMany(Invoice::class)->latest();
    }
}