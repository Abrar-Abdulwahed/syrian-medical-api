<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Admin;
use App\Enums\UserType;
use App\Services\UserService;
use App\Http\Traits\FileTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
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
        try {
            $user = $this->userService->createUser($request->validated(), UserType::PATIENT->value, $request->ip(), true);
            $this->userService->createProfile($user, []);
            event(new Registered($user));
            return $this->returnSuccess(__('message.successfully_saved_review'));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function storeServiceProvider(ServiceProviderAccountRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->createUser($request->validated(), UserType::SERVICE_PROVIDER->value, $request->ip(), false);
            if ($request->hasFile('evidence'))
                $fileName = $this->uploadFile($request->file('evidence'), $user->attachment_path);

            $this->userService->createProfile($user, array_merge($request->only('bank_name', 'iban_number', 'swift_code'), ['evidence' => $fileName]));
            event(new Registered($user));
            Admin::findOrFail(1)->notify(new NewApplicantNotificationMail($user->id));
            DB::commit();
            return $this->returnSuccess(__('message.successfully_saved_review'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }

    public function EmailVerify(VerificationRequest $request)
    {
        try {
            // $user = User::where('ip', $request->ip())->first();
            $user = User::find(58);
            if (!$user)
                return $this->returnWrong(__('message.user_not_found'), 404);

            $toVerify = DB::table('email_verify_codes')->where('email', $user->email)->first();

            if (!$toVerify)
                return $this->returnWrong(__('message.went_wrong'), 422);

            if ($request->verification_code !== $toVerify->code)
                return $this->returnWrong(__('message.invalid_code'), 400);

            // delete the code record
            DB::table('email_verify_codes')->where('email', $user->email)->delete();

            // verify user
            $user->forceFill(['email_verified_at' => now()])->save();
            return $this->returnSuccess(__('message.successfully_email_verified'));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
