<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
