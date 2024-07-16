<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'discount_code_id', 'user_id', 'order_number', 'user_name', 'user_email',
        'phone_number', 'order_date', 'order_status', 'payment_method',
        'shipment_date', 'cancel_date', 'sub_total', 'total', 'tax',
        'ship_charge', 'discount_amount', 'discount_code',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'shipment_date' => 'datetime',
        'cancel_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function shippingAddresses()
    {
        return $this->hasMany(ShippingAddress::class, 'order_id');
    }
}