<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $adminInfo = [
        //     'firstname' => 'Admin',
        //     'lastname'  => 'Alkhorasani',
        //     'email'     => 'admin@admin.com',
        //     'password'  => '12345678',
        //     'activated' => 1,
        //     'ip'        => '127.0.0.1',
        //     'type'      => 'admin',
        // ];

        // $admin = User::create($adminInfo);
        // $admin->assignRole('super-admin');

        // User::factory()->count(50)->create();
    }
}
