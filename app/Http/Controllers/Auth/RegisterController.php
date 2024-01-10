<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Admin;
use App\Enums\UserType;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Traits\FileTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Auth\VerificationRequest;
use App\Http\Requests\Auth\PatientAccountRequest;
use App\Notifications\NewApplicantNotificationMail;
use App\Http\Requests\Auth\ServiceProviderAccountRequest;

class RegisterController extends Controller
{
    use FileTrait;
    public function __construct(protected UserService $userService)
    {
        $this->middleware('guest');
    }

    public function storePatient(PatientAccountRequest $request)
    {
        try{
            $user = $this->userService->createUser($request->validated(), UserType::PATIENT->value, $request->ip(), true);
            event(new Registered($user));
            return $this->returnSuccess('Your data saved successfully, review your email');
        }catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function storeServiceProvider(ServiceProviderAccountRequest $request)
    {
        DB::beginTransaction();
        try{
            $user = $this->userService->createUser($request->validated(), UserType::SERVICE_PROVIDER->value, $request->ip(), false);
            if($request->hasFile('evidence'))
                $fileName = $this->uploadFile($request->file('evidence'), $user->attachment_path);

            $this->userService->createProfile($user, $request->only('bank_name', 'iban_number', 'swift_code'), $fileName);
            event(new Registered($user));
            Notification::send(Admin::findOrFail(1), new NewApplicantNotificationMail($user->id));
            DB::commit();
            return $this->returnSuccess('Your data saved successfully, review your email');
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

}
