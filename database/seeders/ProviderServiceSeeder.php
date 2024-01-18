<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserType;
use App\Models\Service;
use App\Models\ProviderService;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProviderServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providerIds = User::where('type', UserType::SERVICE_PROVIDER)->pluck('id')->toArray();
        $serviceIds =  Service::pluck('id')->toArray();

        foreach ($providerIds as $providerId) {
            $randomKeys = array_rand($serviceIds, 8);
            $randomServiceIds = array_intersect_key($serviceIds, array_flip($randomKeys));

            foreach ($randomServiceIds as $serviceId) {
                ProviderService::create([
                    'service_id' => $serviceId,
                    'provider_id' => $providerId,
                    'description' => fake()->sentence,
                    'price' => fake()->randomFloat(2, 0, 1000),
                    'discount' => fake()->randomFloat(2, 0, 50),
                ]);
            }
        }
    }
}
