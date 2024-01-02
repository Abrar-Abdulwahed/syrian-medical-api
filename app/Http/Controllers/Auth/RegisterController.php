<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Traits\FileTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponseTrait;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\RegisterPatientRequest;
use App\Http\Requests\RegisterServiceProviderRequest;


class RegisterController extends Controller
{
    use FileTrait;

    public function __construct(protected CreateNewUser $createNewUserAction){}

    public function storePatient(RegisterPatientRequest $request)
    {
        try{
            $user = $this->createNewUserAction->create($request->validated());
            $user->assignRole('patient');
            return $this->returnJSON(new UserResource(User::findOrFail($user->id)), 'Your data saved successfully');
        }catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function storeServiceProvider(RegisterServiceProviderRequest $request)
    {
        DB::beginTransaction();
        try{
            $user = $this->createNewUserAction->create($request->validated());
            $user->forceFill([
                'activated' => 0,
            ])->save();

            if($request->hasFile('evidence'))
                $fileName = $this->uploadFile($request->file('evidence'), $user->attachment_path);

            $user->ServiceProviderProfile()->create([
                'bank_name' => $request->input('bank_name'),
                'iban_number' => $request->input('iban_number'),
                'swift_code' => $request->input('swift_code'),
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
}
