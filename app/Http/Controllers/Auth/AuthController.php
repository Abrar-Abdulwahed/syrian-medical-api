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
            if (!is_null($user)) {
                if ($user->activated === 0) {
                    return $this->returnWrong('You\'re not activated', 401);
                }

                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('auth')->plainTextToken;
                    return $this->returnJSON($token, 'You have logged in successfully');
                } else {
                    return $this->returnWrong('Incorrect password');
                }
            }
            return $this->returnWrong('Email doesn\'t exist.', 401);
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
