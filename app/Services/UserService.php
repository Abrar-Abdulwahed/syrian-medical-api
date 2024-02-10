<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function createUser($data, $type, $ip, $activated): User
    {
        $user = User::create($data);
        $user->forceFill(['type' => $type, 'ip' => $ip, 'activated' => $activated])->save();
        return $user;
    }

    public function createProfile(User $user, array $profileData): void
    {
        $user->profile()->create($profileData);
    }
}
