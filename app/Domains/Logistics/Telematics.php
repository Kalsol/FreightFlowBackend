<?php

namespace App\Domains\Logistics;

use App\Domains\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Telematics extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'vehicle_id',
        'timestamp',
        'latitude',
        'longitude',
        'speed',
        'heading',
        'altitude',
        'can_bus_data',
        'engine_temp',
        'fuel_level',
        'tire_pressure_data',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'timestamp' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'speed' => 'decimal:2',
        'heading' => 'decimal:2',
        'altitude' => 'decimal:2',
        'engine_temp' => 'decimal:2',
        'fuel_level' => 'decimal:2',
        'can_bus_data' => 'array',
        'tire_pressure_data' => 'array',
    ];

    /**
     * Get the vehicle that the telematics data belongs to.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
