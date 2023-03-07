<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'restaurant_id',
        'seat_id',
        'is_all_delivered',
        'is_order_finished',
        'total_price',
        'is_paid',
        'paid_at',
    ];

    protected $attributes = [
        'is_all_delivered' => false,
        'is_order_finished' => false,
        'total_price' => 0,
        'is_paid' => false,
        'paid_at' => null,
    ];
}
