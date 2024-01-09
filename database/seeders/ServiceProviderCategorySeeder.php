<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\ServiceProviderCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceProviderCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (ServiceProviderCategory::cases() as $item) {
            DB::table('service_provider_categories')->insert([
                'name' => $item->value,
                'created_at' => now(),
            ]);
        }
    }
}
