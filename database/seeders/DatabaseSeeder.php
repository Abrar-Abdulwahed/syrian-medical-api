<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // AdminRoleSeeder::class,
            PermissionSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            DoctorSpecializationSeeder::class,
            ServiceProviderCategorySeeder::class,
            ServiceSeeder::class,
            ProductSeeder::class,
            ProviderServiceSeeder::class,
            PaymentMethodSeeder::class,
        ]);
    }
}
