<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'guest_name',
        'slug',
        'location',
        'booking_date',
        'Adults',
        'Children',
        'Kids',
        'Infants',
        'price',
        'is_active',
        'is_paid',
        'razorpay_order_id',
        'razorpay_payment_id',
        'payment_status',
    ];
}
