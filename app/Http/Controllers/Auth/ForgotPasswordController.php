<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->returnWrong('User not found', 404);
        }

        $token = Str::random(60);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => now()]
        );

        //TODO: send token to user

        //TODO: remove token from the return 
        return $this->returnJson($token, 'Password reset link sent to your email');
    }

    public function resetPassword(ResetPasswordRequest $request, $token)
    {
        $passwordReset = DB::table('password_resets')->where('token', $token)->first();

        if (!$passwordReset || now()->subHours(2)->gt($passwordReset->created_at)) {
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }

        $user = User::where('email', $passwordReset->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where('email', $passwordReset->email)->delete();

        return $this->returnSuccess('Password reset successfully');
    }
}
