<?php

namespace App\Domains\Vehicles;

use App\Domains\Drivers\Driver;
use App\Domains\Users\User;
use App\Domains\Logistics\Telematics;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'truck_owner_id',
        'current_driver_id',
        'plate_number',
        'make',
        'model',
        'year',
        'type',
        'capacity',
        'status',
    ];

    /**
     * Get the user who owns the truck.
     */
    public function truckOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'truck_owner_id');
    }

    /**
     * Get the driver who is currently operating the vehicle.
     */
    public function currentDriver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'current_driver_id');
    }

    /**
     * Get the telematics data for the vehicle.
     */
    public function telematics(): HasMany
    {
        return $this->hasMany(Telematics::class);
    }

    /**
     * Get the maintenance logs for the vehicle.
     */
    public function maintenance(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * Get the fuel logs for the vehicle.
     */
    public function fuelLogs(): HasMany
    {
        return $this->hasMany(FuelLog::class);
    }
}
