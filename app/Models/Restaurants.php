<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurants extends Model
{
    use HasFactory;

    protected $table = 'restaurants';

    protected $primaryKey = 'restaurant_id';

    protected $fillable = [
        'restaurant_name',
        'restaurant_address',
        'restaurant_image_url',
    ];

    protected $attributes = [
        'restaurant_address' => null,
        'restaurant_image_url' => null,
    ];

    public function orderedDishes(): HasMany
    {
        return $this->hasMany(OrderedDishes::class, 'restaurant_id', 'restaurant_id');
    }

    public function dishes(): HasMany
    {
        return $this->hasMany(Dishes::class, 'restaurant_id', 'restaurant_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Orders::class, 'restaurant_id', 'restaurant_id');
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seats::class, 'restaurant_id', 'restaurant_id');
    }

    public function adminRestaurantRelationships(): HasMany
    {
        return $this->hasMany(AdminRestaurantRelationships::class, 'restaurant_id', 'restaurant_id');
    }
}
