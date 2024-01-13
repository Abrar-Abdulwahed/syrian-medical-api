<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Enums\AdminRole;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminInfo = [
            'username'          => 'SuperAdmin',
            'phone'             => '736565237',
            'email'             => 'admin@admin.com',
            'password'          => '12345678',
            'role'              => AdminRole::SUPER_ADMIN->value,
            'activated'         => 1,
            'email_verified_at' => now(),
        ];

        $admin = Admin::create($adminInfo);
    }
}
