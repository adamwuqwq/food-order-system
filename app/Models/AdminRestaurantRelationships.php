<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRestaurantRelationships extends Model
{
    use HasFactory;

    protected $table = 'admin_restaurant_relationships';

    protected $primaryKey = 'relationship_id';

    protected $fillable = [
        'admin_id',
        'restaurant_id',
    ];
}