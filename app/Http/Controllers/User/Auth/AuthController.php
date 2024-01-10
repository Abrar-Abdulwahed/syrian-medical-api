<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use App\Models\Admin;
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
use App\Notifications\NewApplicantNotificationMail;
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
            Notification::send(Admin::findOrFail(1), new NewApplicantNotificationMail($user->id));
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
}
