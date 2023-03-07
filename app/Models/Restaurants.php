<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurants extends Model
{
    use HasFactory;

    protected $table = 'restaurants';

    protected $primaryKey = 'restaurant_id';

    protected $fillable = [
        'restaurant_name',
        'restaurant_admin_id',
        'restaurant_address',
        'restaurant_image_url',
    ];

    protected $attributes = [
        'restaurant_address' => null,
        'restaurant_image_url' => null,
    ];
}
