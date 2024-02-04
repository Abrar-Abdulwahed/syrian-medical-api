<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\VerificationRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? $this->returnSuccess(__('message.code_sent'))
                : $this->returnWrong(__($status));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function verify(VerificationRequest $request)
    {
        try {
            $user = User::where('ip', $request->ip())->first();
            if (!$user)
                return $this->returnWrong(__('message.user_not_found'), 404);
            $passwordReset = DB::table('password_resets')->where(['code' => $request->verification_code, 'email' => $user->email])->first();

            if (!$passwordReset || now()->subHours(2)->gt($passwordReset->created_at)) {
                return $this->returnWrong(__('message.invalid_code'), 400);
            }
            return $this->returnSuccess(__('message.valid_code'));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::where('ip', $request->ip())->first();
            if (!$user)
                return $this->returnWrong(__('message.user_not_found'), 404);
            $user->update(['password' => $request->password]);
            DB::table('password_resets')->where('email', $user->email)->delete();
            DB::commit();
            return $this->returnSuccess(__('message.successfully_password_reset'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }
}
