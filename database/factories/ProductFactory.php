<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\UserType;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $providerIds = User::where('type', UserType::SERVICE_PROVIDER)->pluck('id')->toArray();
        $categoriesId = Category::pluck('id')->toArray();
        return [
            'provider_id' => fake()->randomElement($providerIds),
            'title_en' => fake()->word . '_en',
            'title_ar' => fake()->word . '_ar',
            'description_en' => fake()->sentence . '_en',
            'description_ar' => fake()->sentence . '_ar',
            'thumbnail' => fake()->imageUrl(),
            'price' => fake()->randomFloat(2, 0, 1000),
            'discount' => fake()->randomFloat(2, 0, 50),
        ];
    }
}
