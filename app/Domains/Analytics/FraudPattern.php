<?php

namespace App\Domains\AI\Models;

use App\Domains\Shipments\Models\Shipment;
use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FraudPattern extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'shipment_id',
        'user_id',
        'pattern_type',
        'risk_score',
        'details',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'details' => 'array',
        'risk_score' => 'decimal:4',
    ];

    /**
     * Get the shipment that the fraud pattern belongs to.
     */
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Get the user associated with the fraud pattern.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
