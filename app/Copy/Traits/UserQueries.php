<?php

namespace App\Traits;

use App\Models\User;

trait UserQueries
{
    protected function findUserByPhoneNumber(string $phoneNumber): ?User
    {
        return User::where('phone_number', $phoneNumber)->first();
    }

    protected function checkUserIsVerified(string $phoneNumber): bool
    {
        $user = $this->findUserByPhoneNumber($phoneNumber);
        return $user && $user->phone_number_verified_at !== null;
    }
}