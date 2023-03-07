<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function orderedDishes(): HasMany
    {
        return $this->hasMany(OrderedDishes::class, 'order_id', 'order_id');
    }

    public function seats(): BelongsTo
    {
        return $this->belongsTo(Seats::class, 'seat_id', 'seat_id');
    }

    public function restaurants(): BelongsTo
    {
        return $this->belongsTo(Restaurants::class, 'restaurant_id', 'restaurant_id');
    }
}
