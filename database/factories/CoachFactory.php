<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coach>
 */
class CoachFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'middle_initial' => fake()->randomLetter(),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'user_id' => User::factory()
        ];
    }
}
