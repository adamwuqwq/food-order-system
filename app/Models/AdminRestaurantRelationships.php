<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminRestaurantRelationships extends Model
{
    use HasFactory;

    protected $table = 'admin_restaurant_relationships';

    protected $primaryKey = 'relationship_id';

    protected $fillable = [
        'admin_id',
        'restaurant_id',
    ];

    public function admins(): BelongsTo
    {
        return $this->belongsTo(Admins::class, 'admin_id', 'admin_id');
    }

    public function restaurants(): BelongsTo
    {
        return $this->belongsTo(Restaurants::class, 'restaurant_id', 'restaurant_id');
    }
}
