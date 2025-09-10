<?php

namespace App\Domains\Shipments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentVersion extends Model
{
    use HasFactory;

    /**
     * @var array<string>
     */
    protected $fillable = [
        'shipment_id',
        'version_number',
        'changes',
        'created_by',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'changes' => 'array',
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }
}


