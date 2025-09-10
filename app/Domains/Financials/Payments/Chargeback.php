<?php

namespace App\Domains\Financials\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chargeback extends Model
{
    use HasFactory;

    /**
     * @var array<string>
     */
    protected $fillable = [
        'payment_id',
        'amount',
        'currency',
        'reason',
        'status',
        'processed_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}


