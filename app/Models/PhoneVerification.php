<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneVerification extends Model 
{
    protected $table = 'phone_verifications';

    protected $fillable = [
        'purpose',
        'phone_number',
        'otp_code',
        'created_at',
        'expires_at',
        'is_used',
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $hidden = [
        'otp_code',
    ];

    
    
}
