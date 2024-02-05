<?php

namespace App\Http\Controllers\User\ServiceProvider;

use App\Http\Controllers\User\BaseProfileController;
use App\Http\Requests\Auth\ServiceProviderAccountUpdateRequest;

class ProfileController extends BaseProfileController
{

    public function updateDetails(ServiceProviderAccountUpdateRequest $request)
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
            if ($changes->isEmpty()) {
                return $this->returnSuccess(__('message.no_found', ['item' => __('message.changes')]));
            }
            $user->pendingUpdateProfileRequest()->updateOrCreate(
                ['user_id' => $user->id],
                ['changes' => $changes->toJson()]
            );
            return $this->returnSuccess(__('message.wait_for_admin_updates_review'));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
