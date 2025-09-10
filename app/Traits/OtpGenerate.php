<?php

namespace App\Traits;

trait OtpGenerate
{
    public function generateOtp(): string
    {
        // Use a cryptographically secure random number generator
        $otp = random_int(100000, 999999);
        return (string) $otp;
    }
}
