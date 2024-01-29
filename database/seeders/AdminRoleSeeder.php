<?php

namespace Database\Seeders;

use App\Enums\AdminRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // foreach (AdminRole::cases() as $role) {
        //     DB::table('roles')->insert([
        //         'name' => $role->value,
        //         'guard_name' => 'api',
        //     ]);
        // }
    }
}
