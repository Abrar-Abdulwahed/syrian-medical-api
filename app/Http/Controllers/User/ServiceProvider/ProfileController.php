<?php

namespace App\Http\Controllers\User\ServiceProvider;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use App\Http\Requests\Auth\ServiceProviderAccountRequest;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
    }

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
        try {
            $user = $request->user();
            $names = explode(' ', $request->username);
            $firstName = $names[0] ?? null;
            $lastName = $names[1] ?? null;
            $updates = collect([
                'firstname' => $firstName && $firstName !== $user->firstname
                    ? $firstName
                    : null,
                'lastname' => $lastName && $lastName !== $user->lastname
                    ? $lastName
                    : null,
                'email' => $request->email !== $user->email
                    ? $request->email
                    : null,
                'bank_name' => $request->bank_name !== $user->serviceProviderProfile->bank_name
                    ? $request->bank_name
                    : null,
                'iban_number' => $request->iban_number !== $user->serviceProviderProfile->iban_number
                    ? $request->iban_number
                    : null,
                'swift_code' => $request->swift_code !== $user->serviceProviderProfile->swift_code
                    ? $request->swift_code
                    : null,
            ])->filter();
            if ($updates->isEmpty()) {
                return $this->returnSuccess('No changes were made');
            }
            $user->pendingUpdateProfileRequest()->updateOrCreate(
                ['user_id' => $user->id],
                ['updates' => $updates->toJson()]
            );
            return $this->returnSuccess('Wait for the administrator to approve your edits');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
