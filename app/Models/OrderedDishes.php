<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderedDishes extends Model
{
    use HasFactory;

    protected $table = 'ordered_dishes';

    protected $primaryKey = 'ordered_dish_id';

    protected $fillable = [
        'order_id',
        'restaurant_id',
        'dish_id',
        'quantity',
        'is_delivered',
        'is_canceled',
    ];

    protected $attributes = [
        'quantity' => 0,
        'is_delivered' => false,
        'is_canceled' => false,
    ];
}
