<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\VerificationRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try{
            $user = User::where('email', $request->email)->first();
            if (!$user)
                return $this->returnWrong('User not found', 404);

            $code = generateRandomNumber(4);
            DB::table('password_resets')->updateOrInsert(
                ['email' => $user->email],
                ['code' => $code, 'created_at' => now()]
            );

            //TODO: send code to user

            //TODO: remove code from the return
            return $this->returnJson($code, 'Enter the code that you received on your email.');
        }catch(\Exception $e){
            return $this->returnWrong($e->getMessage());
        }
    }

    public function verify(VerificationRequest $request){
        try{
            $user = User::where('ip', $request->ip())->first();
            if (!$user)
                return $this->returnWrong('User not found', 404);
            $passwordReset = DB::table('password_resets')->where(['code' => $request->verification_code, 'email' => $user->email ])->first();

            if (!$passwordReset || now()->subHours(2)->gt($passwordReset->created_at)) {
                return $this->returnWrong('Invalid or Expired  Code. Try Again!', 400);
            }
            return $this->returnSuccess('Verification code is valid!');
        }catch(\Exception $e){
            return $this->returnWrong($e->getMessage());
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try{
            $user = User::where('ip', $request->ip())->first();
            if (!$user)
                return $this->returnWrong('User not found', 404);
            $user->update(['password' => Hash::make($request->password)]);
            DB::table('password_resets')->where('email', $user->email)->delete();
            return $this->returnSuccess('Password reset successfully');
        }catch(\Exception $e){
            return $this->returnWrong($e->getMessage());
        }
    }
}
