<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Product $product): bool
    {
        return $user->id === $product->provider_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Product $product): bool
    {
        return $user->id === $product->provider_id;
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->id === $product->provider_id;
    }

    public function restore(User $user, Product $product): bool
    {
        return true;
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return true;
    }
}
