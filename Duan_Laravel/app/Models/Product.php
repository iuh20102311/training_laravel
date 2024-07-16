<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Product extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $incrementing = false; // Vì product_id không phải là số tự tăng
    protected $keyType = 'string'; // Vì product_id là chuỗi

    protected $fillable = [
        'product_id',
        'name',
        'price',
        'description',
        'status',
        'image',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }
}
