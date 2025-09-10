<?php

namespace App\Domains\Logistics\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    /**
     * @var array<string>
     */
    protected $fillable = [
        'shipment_id',
        'origin_name',
        'origin_coord',
        'destination_name',
        'destination_coord',
        'distance_km',
        'eta',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'eta' => 'datetime',
        'origin_coord' => 'array',
        'destination_coord' => 'array',
    ];
}


