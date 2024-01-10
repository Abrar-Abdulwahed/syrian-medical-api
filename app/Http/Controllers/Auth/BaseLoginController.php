<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class BaseLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'admin.logout']);
        $this->middleware(['auth:sanctum'])->only(['logout', 'admin.logout']);
    }

    public function authenticate($email, $password, $isAdmin)
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

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return $this->returnSuccess('logged out successfully', 'success', 201);
    }
}
