<?php

namespace App\Domains\Review\Models;

use App\Domains\Shipments\Models\Shipment;
use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RatingReview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'rater_id',
        'rated_id',
        'shipment_id',
        'rating',
        'review',
    ];

    /**
     * Get the user who submitted the rating.
     */
    public function rater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rater_id');
    }

    /**
     * Get the user who was rated.
     */
    public function rated(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rated_id');
    }

    /**
     * Get the shipment that the rating and review belongs to.
     */
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }
}
