<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'attach_detach_permission',
                'description' => 'Allowing attach or detach permissions to/from supervisor'
            ],
            [
                'name' => 'accept_registration_request',
                'description' => 'Allowing acceptance of registration requests'
            ],
            [
                'name' => 'add_supervisor',
                'description' => 'Allow adding new supervisors'
            ],
            [
                'name' => 'modify_user_data',
                'description' => 'Allow modification of user data'
            ],
            [
                'name' => 'block_user',
                'description' => 'Allow blocking users'
            ],
        ];

        foreach ($permissions as $key => $value) {
            Permission::create($value);
        }
    }
}
