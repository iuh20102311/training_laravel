<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $fillable = [
        'order_id', 'phone_number', 'city', 'district', 'ward', 'address',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}