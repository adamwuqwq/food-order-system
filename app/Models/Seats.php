<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seats extends Model
{
    use HasFactory;

    protected $table = 'seats';

    protected $primaryKey = 'seat_id';

    protected $fillable = [
        'restaurant_id',
        'seat_name',
        'qr_code_token',
        'is_available',
    ];

    protected $attributes = [
        'is_available' => true,
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Orders::class, 'seat_id', 'seat_id');
    }

    public function restaurants(): BelongsTo
    {
        return $this->belongsTo(Restaurants::class, 'restaurant_id', 'restaurant_id');
    }
}
