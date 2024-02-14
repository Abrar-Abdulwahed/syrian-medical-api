<?php

namespace App\Services;

use App\Models\Admin;

class AdminService
{
    protected $adminConfig;

    public function __construct(array $adminConfig)
    {
        $this->adminConfig = $adminConfig;
    }

    public function getAdminForPurpose(string $purpose): Admin
    {
        $adminId = $this->adminConfig[$purpose] ?? null;

        if (!$adminId) {
            throw new \Exception("Admin not configured for purpose: {$purpose}");
        }

        return Admin::findOrFail($adminId);
    }
}
