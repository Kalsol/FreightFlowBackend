<?php

namespace App\Domains\Shipments;

use App\Domains\Bids\Bid;
use App\Domains\Contracts\Models\Contract;
use App\Domains\Financials\Models\CostAllocation;
// use App\Domains\Financials\Models\Escrow; // Duplicate removed
use App\Domains\Financials\Payments\Invoice;
use App\Domains\Freight\Freight;
use App\Domains\Shipments\HazardousMaterial;
use App\Domains\Shipments\TemperatureLog;
use App\Domains\Shipments\TrackingUpdate;
use App\Domains\Shipments\ShipmentAttachment;
use App\Domains\Shipments\ShipmentException;
use App\Domains\Shipments\CustomsDoc;
use App\Domains\Shipments\ShipmentPrediction;
use App\Domains\Shipments\ShipmentVersion;
use App\Domains\Disputes\Dispute;
use App\Domains\Financials\Escrow;
use App\Domains\Communication\Message;
use App\Domains\Review\RatingReview;
use App\Domains\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shipment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'freight_id',
        'load_owner_id',
        'transporter_id',
        'assigned_bid_id',
        'pickup_date',
        'delivery_date',
        'actual_pickup_date',
        'actual_delivery_date',
        'status',
        'tracking_number',
        'origin_coord',
        'dest_coord',
        'current_location_coord',
        'current_location_name',
        'estimated_eta',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pickup_date' => 'date',
        'delivery_date' => 'date',
        'actual_pickup_date' => 'datetime',
        'actual_delivery_date' => 'datetime',
        'estimated_eta' => 'datetime',
        'origin_coord' => 'array',
        'dest_coord' => 'array',
        'current_location_coord' => 'array',
    ];

    // --- Relationships ---

    /**
     * Get the freight that created this shipment.
     */
    public function freight(): BelongsTo
    {
        return $this->belongsTo(Freight::class);
    }

    /**
     * Get the bid that was assigned to this shipment.
     */
    public function assignedBid(): BelongsTo
    {
        return $this->belongsTo(Bid::class, 'assigned_bid_id');
    }

    /**
     * Get the user that owns the load.
     */
    public function loadOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'load_owner_id');
    }

    /**
     * Get the user that owns the truck.
     */
    public function truckOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'truck_owner_id');
    }

    /**
     * Get the invoice for this shipment.
     */
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Get the escrow account for this shipment.
     */
    public function escrow(): HasOne
    {
        return $this->hasOne(Escrow::class);
    }

    /**
     * Get the attachments for this shipment.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(ShipmentAttachment::class);
    }

    /**
     * Get the tracking updates for this shipment.
     */
    public function trackingUpdates(): HasMany
    {
        return $this->hasMany(TrackingUpdate::class);
    }

    /**
     * Get the exceptions for this shipment.
     */
    public function exceptions(): HasMany
    {
        return $this->hasMany(ShipmentException::class);
    }

    /**
     * Get the hazardous material records for this shipment.
     */
    public function hazardousMaterials(): HasMany
    {
        return $this->hasMany(HazardousMaterial::class);
    }

    /**
     * Get the temperature logs for this shipment.
     */
    public function temperatureLogs(): HasMany
    {
        return $this->hasMany(TemperatureLog::class);
    }

    /**
     * Get the disputes for this shipment.
     */
    public function disputes(): HasMany
    {
        return $this->hasMany(Dispute::class);
    }

    /**
     * Get the ratings and reviews for this shipment.
     */
    public function ratingsReviews(): HasMany
    {
        return $this->hasMany(RatingReview::class);
    }

    /**
     * Get the customs documents for this shipment.
     */
    public function customsDocs(): HasMany
    {
        return $this->hasMany(CustomsDoc::class);
    }

    /**
     * Get the messages for this shipment.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the shipment predictions for this shipment.
     */
    public function shipmentPredictions(): HasMany
    {
        return $this->hasMany(ShipmentPrediction::class);
    }

    /**
     * Get the shipment versions for this shipment.
     */
    public function shipmentVersions(): HasMany
    {
        return $this->hasMany(ShipmentVersion::class);
    }
}
