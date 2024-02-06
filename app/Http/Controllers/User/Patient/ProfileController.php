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
            $firstName = $names[0] ?? $user->firstname;
            $lastName = $names[1] ?? $user->lastname;
            $user->fill([
                'firstname' => $firstName,
                'lastname' => $lastName,
                'email' => $request->email,
            ]);
            if ($user->isClean()) {
                return $this->returnSuccess(__('message.no_found', ['item' => __('message.changes')]));
            }
            $user->save();
            return $this->returnSuccess(__('message.completed_edits'));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
