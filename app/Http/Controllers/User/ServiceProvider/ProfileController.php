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
            $names = array_pad(explode(' ', $request->username), 2, null);
            [$firstName, $lastName] = $names;

            $user->fill([
                'firstname' => $firstName ?? $user->firstname,
                'lastname' => $lastName ?? $user->lastname,
                'email' => $request->email,
            ]);


            $user->serviceProviderProfile->fill($request->safe()->only(['bank_name', 'iban_number', 'swift_code']));


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
