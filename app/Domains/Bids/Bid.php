<?php

namespace App\Domains\Bids;

use App\Domains\Freight\Freight;
use App\Domains\Users\User;
use App\Domains\Shipments\Shipment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne  ;


class Bid extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'freight_id',
        'user_id',
        'bid_price',
        'estimated_delivery_time',
        'notes',
        'bid_strategy',
        'fuel_surcharge_model',
    ];

    // --- Relationships ---

    /**
     * Get the freight that the bid belongs to.
     */
    public function freight(): BelongsTo
    {
        return $this->belongsTo(Freight::class);
    }

    /**
     * Get the user that made the bid (the truck owner).
     */
    public function truckOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the shipment created from this bid.
     */
    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class, 'assigned_bid_id');
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
