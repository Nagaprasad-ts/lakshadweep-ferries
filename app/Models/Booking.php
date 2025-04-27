<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'guest_name',
        'slug',
        'package_info',
        'price',
        'is_active',
        'is_paid',
    ];
}
