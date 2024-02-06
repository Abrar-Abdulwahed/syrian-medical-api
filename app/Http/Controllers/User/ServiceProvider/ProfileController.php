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
            $firstName = $names[0] ?? $user->firstname;
            $lastName = $names[1] ?? $user->lastname;
            $user->fill([
                'firstname' => $firstName,
                'lastname' => $lastName,
                'email' => $request->email,
            ]);
            if ($user->isServiceProvider()) {
                $user->serviceProviderProfile->fill([
                    'bank_name' => $request->bank_name,
                    'iban_number' => $request->iban_number,
                    'swift_code' => $request->swift_code,
                ]);
            }
            if ($user->isClean() && $user->serviceProviderProfile->isClean()) {
                return $this->returnSuccess(__('message.no_found', ['item' => __('message.changes')]));
            }
            $user->pendingUpdateProfileRequest()->updateOrCreate(
                ['user_id' => $user->id],
                ['changes' => collect($user->getDirty())
                    ->merge($user->serviceProviderProfile->getDirty())
                    ->toJson()]
            );
            return $this->returnSuccess(__('message.wait_for_admin_updates_review'));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
