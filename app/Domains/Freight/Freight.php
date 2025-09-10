<?php

namespace App\Domains\Freight;

use App\Domains\Bids\Bid;
use App\Domains\Freight\PriceOptimization;
use App\Domains\Shipments\Shipment;
use App\Domains\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Casts\GeometryCast;

class Freight extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'load_owner_id',
        'origin_location',
        'destination_location',
        'origin_coord',
        'dest_coord',
        'pickup_date',
        'delivery_date',
        'weight',
        'weight_unit',
        'dimensions',
        'dimension_unit',
        'freight_type',
        'description',
        'desired_price',
        'special_instructions',
        'bid_deadline',
        'status',
        'required_equipment',
        'uuid',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pickup_date' => 'date',
        'delivery_date' => 'date',
        'bid_deadline' => 'datetime',
        'required_equipment' => 'array',
        'origin_coord' => GeometryCast::class,
        'dest_coord' => GeometryCast::class,
    ];

    // --- Relationships ---

    /**
     * Get the user that owns the freight (the load owner).
     */
    public function loadOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'load_owner_id');
    }

    /**
     * Get the bids for the freight.
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Get the shipment created from this freight.
     */
    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class);
    }

    /**
     * Get the price optimization record for the freight.
     */
    public function priceOptimization(): HasOne
    {
        return $this->hasOne(PriceOptimization::class);
    }

    /**
     * Get the attachments for the freight.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(FreightAttachment::class);
    }

      protected static function booted()
    {
        parent::booted();

        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
