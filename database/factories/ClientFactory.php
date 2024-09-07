<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use App\Models\Registration;
use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::pluck('id')->random(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'middle_initial' => fake()->randomLetter(),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'registration_id' => Registration::pluck('id')->random(),
            'payment_method_id' => PaymentMethod::pluck('id')->random(),
            'date' => now()->toDateString(),
        ];
    }
}
