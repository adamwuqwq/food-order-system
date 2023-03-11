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
        'is_order_finished',
        'is_paid',
        'paid_at',
    ];

    protected $attributes = [
        'is_order_finished' => false,
        'is_paid' => false,
        'paid_at' => null,
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
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
