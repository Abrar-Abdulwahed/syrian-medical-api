<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\FileTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\VerificationRequest;
use App\Http\Requests\PatientAccountRequest;
use App\Http\Requests\ServiceProviderAccountRequest;

class AuthController extends Controller
{
    use FileTrait;

    public function storePatient(PatientAccountRequest $request)
    {
        try{
            $user = User::create($request->validated());
            $user->assignRole('patient');
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
                'activated' => 0,
            ])->save();

            if($request->hasFile('evidence'))
                $fileName = $this->uploadFile($request->file('evidence'), $user->attachment_path);

            $user->ServiceProviderProfile()->create([
                'bank_name' => $request->bank_name,
                'iban_number' => $request->iban_number,
                'swift_code' => $request->swift_code,
                'evidence' => $fileName,
            ]);
            $user->assignRole('service-provider');
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
            // $values = [
            //     'ip' => $request->ip(),
            //     'code' => '343',
            //     'isRemember' =>  $request->remember_me,
            // ];
            // Cache::put('remember_2fa', $values);
            // $Remember = Cache::get('remember_2fa');
            // return $this->returnWrong($Remember['code'], 401);
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
                $values = [
                    'ip' => $request->ip(),
                    'code' => $code,
                    'isRemember' =>  $request->remember_me,
                ];
                Cache::put('remember_2fa', $values, 1000); // 10 minutes
                $isRemember = Cache::get('remember_2fa');
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
        $user = User::where('email', $request->email)->first();
        if (!$user || $user->verification_code !== $request->verification_code) {
            $user->login_attempts += 1;
            $user->save();

            if ($user->login_attempts > 3) {
                return $this->returnWrong('Verification code expired', 422);
            }

            return $this->returnWrong('Invalid verification code', 422);
        }

        // Reset 2FA on successful verification
        $user->forceFill([
            'verification_code' => null,
            'last_code_sent_at' => null,
            'login_attempts' => 0,
        ])->save();
        $abilities = ['*'];
        $remember2FA = Cache::get('remember_2fa');

        if ($remember2FA && $remember2FA['isRemember'] == 1) {
            $abilities = ['remember'];
        }
        $token = $user->createToken('auth', $abilities, now()->addWeek())->plainTextToken;

        return $this->returnJSON($token, 'You have logged in successfully');
    }
}
