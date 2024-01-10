<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Authenticator
{
    public static function authenticate($email, $password, $isAdmin)
    {
        $model = $isAdmin ? Admin::class : User::class;
        $user = $model::where('email', $email)->first();

        if (!$user) {
            return 'Email doesn\'t exist.';
        }

        if (!$user->hasVerifiedEmail()) {
            return 'Your Email is not verified';
        }

        if (!$user->activated) {
            return 'You\'re not activated';
        }

        if (!Hash::check($password, $user->password)) {
            return 'Incorrect password';
        }

        return $user;
    }
}