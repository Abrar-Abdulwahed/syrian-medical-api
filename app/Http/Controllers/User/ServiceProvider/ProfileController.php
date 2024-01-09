<?php

namespace App\Http\Controllers\User\ServiceProvider;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\ServiceProviderAccountRequest;

class ProfileController extends Controller
{
    public function showDetails(Request $request)
    {
        try{
            $user = $request->user();
            return $this->returnJSON(new UserResource($user->loadMissing('serviceProviderProfile')), 'User Data retrieved!');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function updateDetails(ServiceProviderAccountRequest $request)
    {
        try{
            $user = $request->user();
            $names = explode(' ', $request->username);
            $updates = [
                'firstname' => $names[0] ?? $user->firstname,
                'lastname' => $names[1] ?? $user->lastname,
                'email'    => $request->email ?? $user->email,
                'bank_name'=> $request->bank_name ?? $user->bank_name,
                'iban_number'=> $request->iban_number ?? $user->iban_number,
                'swift_code'=> $request->swift_code ?? $user->swift_code,
                'password'  => Hash::make($request->password) ?? Hash::make($user->password),
            ];
            $user->pendingUpdateProfileRequest()->updateOrCreate(['user_id' => $user->id], ['updates' => json_encode($updates)]);
            return $this->returnSuccess('Wait for the administrator to approve your edits');
        }catch(\Exception $e){
            return $this->returnWrong($e->getMessage());
        }
    }
}
