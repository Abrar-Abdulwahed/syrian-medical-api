<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use App\Enums\UserType;
use Illuminate\Http\Request;
use App\Http\Traits\FileTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Auth\VerificationRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\PatientAccountRequest;
use App\Http\Requests\Auth\ServiceProviderAccountRequest;
use App\Notifications\RegistrationConfirmationNotification;

class AuthController extends Controller
{
    use FileTrait;

    public function storePatient(PatientAccountRequest $request)
    {
        try{
            $user = User::create($request->validated());
            $user->forceFill(['type' => UserType::PATIENT->value, 'ip' => $request->ip()])->save();
            event(new Registered($user));
            return $this->returnJSON(new UserResource(User::findOrFail($user->id)), 'Your data saved successfully, review your email');
        }catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function storeServiceProvider(ServiceProviderAccountRequest $request)
    {
        DB::beginTransaction();
        try{
            $user = User::create($request->validated());
            $user->forceFill([
                'type' => UserType::SERVICE_PROVIDER->value,
                'ip' => $request->ip(),
                'activated' => 0,
            ])->save();
            if($request->hasFile('evidence'))
                $fileName = $this->uploadFile($request->file('evidence'), $user->attachment_path);

            $user->serviceProviderProfile()->create([
                'bank_name' => $request->bank_name,
                'iban_number' => $request->iban_number,
                'swift_code' => $request->swift_code,
                'evidence' => $fileName,
            ]);
            event(new Registered($user));
            DB::commit();
            return $this->returnJSON(new UserResource(User::findOrFail($user->id)), 'Your data saved successfully, review your email');
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }

    public function EmailVerify(VerificationRequest $request)
    {
        try{
            $user = User::where('ip', $request->ip())->first();

            if (!$user)
                return $this->returnWrong('User not found', 404);

            $toVerify = DB::table('email_verify_codes')->where('email', $user->email)->first();
            if(!$toVerify)
                return $this->returnWrong('Something went wrong', 422);

            if($request->verification_code !== $toVerify->code)
                return $this->returnWrong('Invalid or Incorrect  Code. Try Again!', 400);

            // delete the code record
            DB::table('email_verify_codes')->where('email', $user->email)->delete();

            // verify user
            $user->forceFill(['email_verified_at' => now()])->save();
            return $this->returnSuccess('Your Email verified successfully');
        }catch(\Exception $e){
            return $this->returnWrong($e->getMessage());
        }
    }

    public function login(LoginRequest $request){
        try {
            $user = User::where('email', $request->email)->first();
            if (is_null($user)) {
                return $this->returnWrong($request->email, 401);
            }

            if(!$user->hasVerifiedEmail())
                return $this->returnWrong('Your Email is not verified', 401);

            if ($user->activated === 0) {
                return $this->returnWrong('You\'re not activated', 401);
            }

            if (!Hash::check($request->password, $user->password))
                return $this->returnWrong('Incorrect password');

            $key = '2FA_verify' . $user->id;
            $executed = RateLimiter::attempt(
                $key,
                $perMinutes = 1,
                function() use($user, $key) {
                    $code = generateRandomNumber(4);
                    // TODO: Send code to user email

                    // Update user details and reset login attempts
                    $user->forceFill([
                        'verification_code' => $code,
                        'login_attempts' => 0,
                    ])->save();
                    RateLimiter::clear($key);
                },
                $decayRate = 1800, // 30 minutes
            );
            if (!$executed) {
                return $this->returnWrong('You may wait '. ceil(RateLimiter::availableIn($key)/60).' minutes before re-send new code', 422);
            }else{
                Cache::put($user->ip, $request->remember_me, 120); // 2 minutes
                return $this->returnSuccess('code sent to your email');
            }
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function verify2FA(VerificationRequest $request)
    {
        try{
            $user = User::where('ip', $request->ip())->first();
            if (!$user)
                return $this->returnWrong('User not found', 404);
            if (!$user || $user->verification_code !== $request->verification_code) {
                $user->forceFill(['login_attempts' => $user->login_attempts+1])->save();

                if ($user->login_attempts > 3) {
                    $this->reset2FA($user);
                    return $this->returnWrong('Verification code expired', 422);
                }

                return $this->returnWrong('Invalid verification code', 422);
            }
            //Reset 2FA on success verification
            $this->reset2FA($user);
            RateLimiter::clear('2FA_verify' . $user->id);

            if (Cache::has($user->ip) && Cache::get($user->ip)) {
                $token = $user->createToken('auth', ['remember'])->plainTextToken;

            }else{
                $token = $user->createToken('auth', ['*'], now()->addYear())->plainTextToken;
            }
            return $this->returnJSON($token, 'You have logged in successfully');
        }catch(\Exception $e){
            return $this->returnWrong($e->getMessage());
        }
    }

    private function reset2FA($user){
        $user->forceFill([
            'verification_code' => null,
            'login_attempts' => 0,
        ])->save();
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        //! this task doesn't have mobile UI
        try{
            $user = $request->user();
            $user->update([
                'password'  => Hash::make($request->new_password)
            ]);
            return $this->returnSuccess('Password Changed Successfully');
        }catch(\Exception $e){
            return $this->returnWrong($e->getMessage());
        }
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return $this->returnJson([], 'logged out successfully', 'success', 201);
    }
}
