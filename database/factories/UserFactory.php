<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\UserType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => fake()->name(),
            'lastname' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
            'activated' => random_int(0, 1),
            'ip' => '127.0.0.1',
            'type'=> $this->faker->randomElement([UserType::ADMIN->value, UserType::PATIENT->value, UserType::SERVICE_PROVIDER->value])
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            if($user->type === UserType::SERVICE_PROVIDER->value)
                $user->serviceProviderProfile()->create([
                    'user_id' => $user->id,
                    'bank_name' => '',
                    'iban_number' => '',
                    'swift_code' => '',
               ]);
        });
    }
    // public function configure()
    // {
    //     return $this->afterCreating(function (User $user) {
    //         $user->assignRole($this->faker->randomElement(['patient', 'service-provider']));
    //     });
    // }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
