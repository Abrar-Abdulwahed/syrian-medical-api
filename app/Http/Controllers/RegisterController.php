<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponseTrait;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\RegisterPatientRequest;


class RegisterController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected CreateNewUser $createNewUserAction){}

    public function storePatient(RegisterPatientRequest $request)
    {
        try{
            $user = $this->createNewUserAction->create($request->validated());
            $user->assignRole('patient');
            return $this->returnJSON(new UserResource($user), 'Your data saved successfully');
        }catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
