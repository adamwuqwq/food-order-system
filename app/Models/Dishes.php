<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
