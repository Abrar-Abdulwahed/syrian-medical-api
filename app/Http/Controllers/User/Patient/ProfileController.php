<?php

namespace App\Http\Controllers\User\Patient;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PictureStoreRequest;
use App\Http\Requests\LocationStoreRequest;
use App\Http\Requests\Auth\PatientAccountRequest;
use App\Http\Controllers\User\BaseProfileController;

class ProfileController extends BaseProfileController
{
    public function updateDetails(PatientAccountRequest $request)
    {
        try {
            $user = $request->user();
            $names = explode(' ', $request->username);
            $firstName = $names[0] ?? null;
            $lastName = $names[1] ?? null;
            $changes = collect([
                'firstname' => $firstName && $firstName !== $user->firstname
                    ? $firstName
                    : null,
                'lastname' => $lastName && $lastName !== $user->lastname
                    ? $lastName
                    : null,
                'email' => $request->email !== $user->email
                    ? $request->email
                    : null,
            ])->filter();
            if ($changes->isEmpty()) {
                return $this->returnSuccess('No changes were made');
            }
            $userChanges = collect($changes)->only(['firstname', 'lastname', 'email'])->all();
            $request->user()->update($userChanges);
            return $this->returnSuccess('Your data has been updated successfully');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}