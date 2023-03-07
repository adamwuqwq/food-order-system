<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dishes extends Model
{
    use HasFactory;

    protected $table = 'dishes';

    protected $primaryKey = 'dish_id';

    protected $fillable = [
        'restaurant_id',
        'dish_name',
        'image_url',
        'dish_category',
        'dish_description',
        'dish_price',
        'available_num',
    ];

    protected $attributes = [
        'image_url' => null,
        'dish_category' => 'unspecified',
        'available_num' => 0,
        'dish_price' => 0,
        'dish_description' => null,
    ];

    public function restaurants(): BelongsTo
    {
        return $this->belongsTo(Restaurants::class, 'restaurant_id', 'restaurant_id');
    }

    public function orderedDishes(): HasMany
    {
        return $this->hasMany(OrderedDishes::class, 'dish_id', 'dish_id');
    }
}
