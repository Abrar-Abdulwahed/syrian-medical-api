<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

class UserService
{
    public function createUser($data, $type, $ip, $activated): User
    {
        $user = User::create($data);
        $user->forceFill(['type' => $type, 'ip' => $ip, 'activated' => $activated])->save();
        return $user;
    }

    public function createProfile(User $user, array $profileData, string $fileName): void
    {
        $user->serviceProviderProfile()->create(array_merge($profileData, ['evidence' => $fileName]));
    }
}