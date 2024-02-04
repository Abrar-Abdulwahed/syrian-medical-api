<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Actions\Authenticator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\RateLimiter;
use App\Notifications\TwoFactorNotification;
use App\Http\Requests\Auth\VerificationRequest;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'admin.logout']);
        $this->middleware(['auth:sanctum'])->only(['logout', 'admin.logout']);
    }

    public function login(LoginRequest $request)
    {
        try {
            $result = Authenticator::authenticate(
                $request->email,
                $request->password,
                $request->route()->getName() === 'admin.login'
            );

            if ($result instanceof User) {
                return $this->handleSending2FA($request, $result);
            }

            if ($result instanceof Admin) {
                $token = $result->createToken('auth', ['*'], now()->addWeek())->plainTextToken;
                return $this->returnJSON($token, __('message.successfully_login'));
            }

            return $this->returnWrong($result, 401);
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    private function handleSending2FA(LoginRequest $request, User $user)
    {
        $key = '2FA_verify' . $user->id;
        $executed = RateLimiter::attempt(
            $key,
            $perMinutes = 1,
            function () use ($user, $key) {
                $code = generateRandomNumber(4);
                $user->notify(new TwoFactorNotification($code));

                // Update user details and reset login attempts
                $user->forceFill([
                    'verification_code' => $code,
                    'login_attempts' => 0,
                ])->save();
                RateLimiter::clear($key);
            },
            $decayRate = 1800 // 30 minutes
        );

        if (!$executed) {
            return $this->returnWrong(__('message.resend_throttle', ['minute' => ceil(RateLimiter::availableIn($key) / 60)]), 422);
        } else {
            Cache::put($user->ip, $request->remember_me, 120); // 2 minutes
            return $this->returnSuccess(__('message.code_sent'));
        }
    }

    public function verify2FA(VerificationRequest $request)
    {
        try {
            // $user = User::where('ip', '$request->ip()')->first();
            $user = User::find(10);
            // $user = User::first();
            if (!$user)
                return $this->returnWrong(__('message.user_not_found'), 404);
            if ($user->verification_code !== $request->verification_code) {
                $user->forceFill(['login_attempts' => $user->login_attempts + 1])->save();

                if ($user->login_attempts > 3) {
                    $this->reset2FA($user);
                    return $this->returnWrong(__('message.expired_code'), 422);
                }

                return $this->returnWrong(__('message.invalid_code'), 422);
            }
            //Reset 2FA on success verification
            $this->reset2FA($user);
            RateLimiter::clear('2FA_verify' . $user->id);

            if (Cache::has($user->ip) && Cache::get($user->ip)) {
                $token = $user->createToken('auth', ['remember'])->plainTextToken;
            } else {
                $token = $user->createToken('auth', ['*'], now()->addWeek())->plainTextToken;
            }
            return $this->returnJSON($token, __('message.successfully_login'));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    private function reset2FA(User $user)
    {
        $user->forceFill([
            'verification_code' => null,
            'login_attempts' => 0,
        ])->save();
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->returnSuccess(__('message.successfully_logout'), 'success', 201);
    }
}
