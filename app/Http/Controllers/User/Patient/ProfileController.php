<?php

namespace App\Http\Controllers\User\Patient;

use App\Http\Requests\Auth\PatientAccountUpdateRequest;
use App\Http\Controllers\User\BaseProfileController;

class ProfileController extends BaseProfileController
{
    public function updateDetails(PatientAccountUpdateRequest $request)
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
                return $this->returnSuccess(__('message.no_changes'));
            }
            $userChanges = collect($changes)->only(['firstname', 'lastname', 'email'])->all();
            $request->user()->update($userChanges);
            return $this->returnSuccess(__('message.completed_edits'));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
