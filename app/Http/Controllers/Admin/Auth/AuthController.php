<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        try {
            $user = Admin::where('email', $request->email)->first();
            if (!$user) {
                return $this->returnWrong('Email doesn\'t exist.', 401);
            }

            if(!$user->hasVerifiedEmail())
                return $this->returnWrong('Your Email is not verified', 401);

            if ($user->activated === 0) {
                return $this->returnWrong('You\'re not activated', 401);
            }

            if (!Hash::check($request->password, $user->password))
                return $this->returnWrong('Incorrect password');

            $token = $user->createToken('auth', ['*'], now()->addYear())->plainTextToken;
            return $this->returnJSON($token, 'You have logged in successfully');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
