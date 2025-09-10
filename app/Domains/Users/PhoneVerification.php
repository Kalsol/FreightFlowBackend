<?php

namespace App\Domains\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneVerification extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @var array<string>
     */
    protected $fillable = [
        'purpose',
        'phone_number',
        'otp_code',
        'created_at',
        'expires_at',
        'is_used',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];
}


