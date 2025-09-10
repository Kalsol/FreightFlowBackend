<?php

namespace App\Domains\Users;

use App\Domains\Bids\Bid;
use App\Domains\Users\Models\UserProfile;
use App\Domains\Users\Models\UserPreference;
use App\Domains\Contracts\Models\Contract;
use App\Domains\Disputes\Models\Dispute;
use App\Domains\Vehicles\Vehicle;
use App\Domains\Drivers\Driver;
use App\Domains\Freight\Models\Freight;
use App\Domains\Communication\Notification;
use App\Domains\Review\Models\RatingReview;
use App\Domains\Shipments\Models\Shipment;
use App\Domains\Subscriptions\Models\Subscription as UserSubscription;
use App\Domains\Security\Models\ApiKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'contact_number',
        'phone_number_verified_at',
        'password',
        'company_name',
        'fleet_details',
        'service_areas',
        'payment_details',
        'mfa_secret',
        'recovery_codes',
        'archived_at',
        'last_login_at',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone_number_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'archived_at' => 'datetime',
        'service_areas' => 'array',
        'fleet_details' => 'array',
        'payment_details' => 'array',
        'recovery_codes' => 'array',
    ];

    // --- Relationships ---

    /**
     * Get the user's profile.
     */
    public function userProfile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Get the user's preferences.
     */
    public function userPreferences(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }

    /**
     * Get the user's subscription.
     */
    public function subscription(): HasOne
    {
        return $this->hasOne(UserSubscription::class);
    }

    /**
     * Get the API keys associated with the user.
     */
    public function apiKeys(): HasMany
    {
        return $this->hasMany(ApiKey::class);
    }

    /**
     * Get the contracts created by the user as a load owner.
     */
    public function contractsAsLoadOwner(): HasMany
    {
        return $this->hasMany(Contract::class, 'load_owner_id');
    }

    /**
     * Get the contracts for the user as a truck owner.
     */
    public function contractsAsTruckOwner(): HasMany
    {
        return $this->hasMany(Contract::class, 'truck_owner_id');
    }

    /**
     * Get the freight items posted by the user.
     */
    public function freight(): HasMany
    {
        return $this->hasMany(Freight::class, 'load_owner_id');
    }

    /**
     * Get the shipments associated with the user as a load owner.
     */
    public function shipmentsAsLoadOwner(): HasMany
    {
        return $this->hasMany(Shipment::class, 'load_owner_id');
    }

    /**
     * Get the shipments associated with the user as a truck owner.
     */
    public function shipmentsAsTruckOwner(): HasMany
    {
        return $this->hasMany(Shipment::class, 'truck_owner_id');
    }

    /**
     * Get the bids made by the user.
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class, 'truck_owner_id');
    }

    /**
     * Get the vehicles owned by the user.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'truck_owner_id');
    }

    /**
     * Get the drivers associated with the user.
     */
    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, 'truck_owner_id');
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the ratings and reviews given by the user.
     */
    public function givenRatingsReviews(): HasMany
    {
        return $this->hasMany(RatingReview::class, 'rater_id');
    }

    /**
     * Get the ratings and reviews received by the user.
     */
    public function receivedRatingsReviews(): HasMany
    {
        return $this->hasMany(RatingReview::class, 'rated_id');
    }

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
