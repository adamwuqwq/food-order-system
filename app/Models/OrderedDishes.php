<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected $hidden = [
        'is_canceled',
        'updated_at',
    ];

    public function orders(): BelongsTo
    {
        return $this->belongsTo(Orders::class, 'order_id', 'order_id');
    }

    public function restaurants(): BelongsTo
    {
        return $this->belongsTo(Restaurants::class, 'restaurant_id', 'restaurant_id');
    }

    public function dishes(): BelongsTo
    {
        return $this->belongsTo(Dishes::class, 'dish_id', 'dish_id');
    }
}
