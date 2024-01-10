<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\ChangePasswordRequest;



class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
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
