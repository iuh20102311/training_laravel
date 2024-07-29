<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    protected $fillable = [
        'code',
        'amount',
        'percentage',
        'valid_from',
        'valid_to',
        'usage_limit',
        'used_time',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isValid()
    {
        $currentDate = now();
        return $this->valid_from <= $currentDate && $this->valid_to >= $currentDate && $this->used_time < $this->usage_limit;
    }
}
