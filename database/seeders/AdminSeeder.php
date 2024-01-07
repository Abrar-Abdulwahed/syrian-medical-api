<?php

namespace Database\Seeders;

use App\Models\Admin;
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
            'username'  => 'SuperAdmin',
            'phone'     => '736565237',
            'email'     => 'admin@admin.com',
            'password'  => '12345678',
            'activated' => 1,
            'email_verified_at' => now(),
            'ip'        => '127.0.0.1',
        ];

        $admin = Admin::create($adminInfo);
    }
}
