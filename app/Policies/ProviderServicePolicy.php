<?php

namespace App\Policies;

use App\Models\User;

class ProviderServicePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, ProviderProfileService $service)
    {
        // Authorization logic for updating a service
        return $user->serviceProviderProfile()->id === $service->provider_profile_id; // Or use your own logic
    }

    public function delete(User $user, ProviderProfileService $service)
    {
        dd($user);
        // Authorization logic for deleting a service
        return $user->serviceProviderProfile()->id === $service->provider_profile_id; // Or use your own logic
    }
}
