<?php

namespace App\Domains\Financials\Models;

use App\Domains\Shipments\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Escrow extends Model
{
    use HasFactory;

    /**
     * @var array<string>
     */
    protected $fillable = [
        'shipment_id',
        'balance',
        'currency',
        'status',
        'released_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'released_at' => 'datetime',
        'balance' => 'decimal:2',
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }
}
