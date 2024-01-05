<?php

namespace App\Http\Controllers\Auth;

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
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\VerificationRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\PatientAccountRequest;
use App\Http\Requests\Auth\ServiceProviderAccountRequest;

class AuthController extends Controller
{
    use FileTrait;

    public function storePatient(PatientAccountRequest $request)
    {
        try{
            $user = User::create($request->validated());
            $user->forceFill(['type' => UserType::PATIENT->value, 'ip' => $request->ip()])->save();
            return $this->returnJSON(new UserResource(User::findOrFail($user->id)), 'Your data saved successfully');
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
            DB::commit();
            return $this->returnJSON(new UserResource(User::findOrFail($user->id)), 'Your data saved successfully');
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }

    public function login(LoginRequest $request){
        try {
            $user = User::where('email', $request->email)->first();
            if (is_null($user)) {
                return $this->returnWrong('Email doesn\'t exist.', 401);
            }

            if ($user->activated === 0) {
                return $this->returnWrong('You\'re not activated', 401);
            }

            if (!Hash::check($request->password, $user->password))
                return $this->returnWrong('Incorrect password');

            if (!$user->last_code_sent_at || isTimePassed(30, $user->last_code_sent_at)) {

                // Generate and send the code to the user's email
                $code = generateRandomNumber(4);
                Cache::put($user->ip, $request->remember_me, 200); // 2 minutes
                //TODO: Send code to user email

                $user->forceFill([
                    'verification_code' => $code,
                    'last_code_sent_at' => now(),
                    'login_attempts' => 0,
                ])->save();

                return $this->returnSuccess('code sent to your email');
            } else {
                return $this->returnWrong('Wait for 30 minutes before requesting a new code.', 422);
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

            $isRemember = Cache::get($user->ip);
            if ($isRemember) {
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
            'last_code_sent_at' => null,
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
