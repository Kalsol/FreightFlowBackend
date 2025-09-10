<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
// DB is no longer needed directly in the model for accessors with this approach

class Freight extends Model
{
    use HasFactory;

    protected $table = 'freights'; // Explicitly define table name

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'load_owner_id',
        'origin_location',
        'destination_location',
        'origin_coord', // Retain for saving the binary GEOMETRY
        'dest_coord',   // Retain for saving the binary GEOMETRY
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pickup_date' => 'date',         // Casts to Carbon date object
        'delivery_date' => 'date',       // Casts to Carbon date object
        'bid_deadline' => 'datetime',    // Casts to Carbon datetime object
        'required_equipment' => 'array', // Crucial for handling JSON column
    ];

    /**
     * The "booting" method of the model.
     * Automatically sets UUID on creation.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) { // Check if UUID is not already set
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Define a relationship to FreightAttachment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(FreightAttachment::class);
    }

    /**
     * Define a relationship to the User (load owner).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loadOwner()
    {
        return $this->belongsTo(User::class, 'load_owner_id');
    }
}
