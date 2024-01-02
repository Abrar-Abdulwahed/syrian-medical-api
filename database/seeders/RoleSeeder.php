<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = ['patient', 'service-provider'];

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }
    }
}
