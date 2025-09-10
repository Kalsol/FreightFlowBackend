<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\PhoneVerification;

 
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'uuid',
        'name',
        'phone_number',
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

    protected $hidden = [
        'password',
        'remember_token',
        'mfa_secret',
        'recovery_codes',
        'last_login_at',
        'payment_details'
    ];

    protected function casts(): array
    {
        return [
            'phone_number_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $dates = [
        'phone_number_verified_at',
        'archived_at',
        'last_login_at',
    ];


    //Relations
    public function freights(): HasMany
    {
        return $this->hasMany(Freight::class, 'load_owner_id');
    }
    public function freights_attachments(): HasMany
    {
        return $this->hasMany(FreightAttachment::class, 'uploaded_by');
    }

    protected function is_active(): bool {
        return $this->status === 'active';
    }

    protected function is_archived(): bool {
        return $this->archived_at !== null;
    }

    protected function is_suspended(): bool {
        return $this->status === 'suspended';
    }

    protected function is_mfa_enabled(): bool {
        return $this->mfa_secret !== null;
    }

    protected function is_password_set(): bool {
        return $this->password !== null;
    }

    protected function is_verified(): bool {
        return $this->phone_number_verified_at !== null;
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
