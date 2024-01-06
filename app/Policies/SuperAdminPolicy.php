<?php

namespace App\Policies;

use App\Models\Admin;
use App\Enums\AdminRole;

class SuperAdminPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function isSuperAdmin(Admin $admin): bool
    {
        return $admin->role === AdminRole::SUPER_ADMIN->value;
    }
}
