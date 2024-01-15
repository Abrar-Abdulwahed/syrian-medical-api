<?php

namespace App\Policies;

use App\Models\ProviderService;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProviderServicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProviderService $providerService): bool
    {
        return $user->id === $providerService->provider_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProviderService $providerService): bool
    {
        return $user->id === $providerService->provider_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProviderService $providerService): bool
    {
        return $user->id === $providerService->provider_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProviderService $providerService): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProviderService $providerService): bool
    {
        //
    }
}
