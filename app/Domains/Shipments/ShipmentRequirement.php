<?php

namespace App\Domains\Shipments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentRequirement extends Model
{
    use HasFactory;

    /**
     * @var array<string>
     */
    protected $fillable = [
        'shipment_id',
        'requirement_type',
        'details',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'details' => 'array',
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }
}
