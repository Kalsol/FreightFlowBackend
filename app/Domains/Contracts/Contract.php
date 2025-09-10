<?php

namespace App\Domains\Contracts\Models;

use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'load_owner_id',
        'truck_owner_id',
        'status',
        'effective_date',
        'expiry_date',
        'contract_terms',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'effective_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Get the user who owns the load.
     */
    public function loadOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'load_owner_id');
    }

    /**
     * Get the user who owns the truck.
     */
    public function truckOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'truck_owner_id');
    }

    /**
     * Get the accessorial charges for the contract.
     */
    public function accessorialCharges(): HasMany
    {
        return $this->hasMany(AccessorialCharge::class);
    }
}
