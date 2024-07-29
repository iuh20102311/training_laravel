<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'city',
        'district',
        'ward',
        'address',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
