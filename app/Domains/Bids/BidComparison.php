<?php

namespace App\Domains\Bids\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BidComparison extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'bid_id_1',
        'bid_id_2',
        'difference',
        'comparison_details',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'comparison_details' => 'array',
    ];

    /**
     * Get the first bid for the comparison.
     */
    public function bid1(): BelongsTo
    {
        return $this->belongsTo(Bid::class, 'bid_id_1');
    }

    /**
     * Get the second bid for the comparison.
     */
    public function bid2(): BelongsTo
    {
        return $this->belongsTo(Bid::class, 'bid_id_2');
    }
}
