<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Enums\AdminRole;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [   'username'          => 'SuperAdmin',
                'phone'             => '736565237',
                'email'             => 'admin@admin.com',
                'password'          => '12345678',
                'role'              => AdminRole::SUPER_ADMIN->value,
                'activated'         => 1,
                'email_verified_at' => now(),
            ],
            [   'username'          => 'Admin',
                'phone'             => '736565237',
                'email'             => 'admin@admin2.com',
                'password'          => '12345678',
                'role'              => AdminRole::SUPER_ADMIN->value,
                'activated'         => 1,
                'email_verified_at' => now(),
            ],
        ];
        $permissions = Permission::all();
        foreach ($admins as $key => $value) {
            $admin = Admin::create($value);
            // Attach all existing permissions to the super admin
            $admin->permissions()->attach($permissions);
        }
    }
}
