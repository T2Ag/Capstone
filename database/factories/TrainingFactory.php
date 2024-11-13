<?php

namespace Database\Factories;

use App\Models\Coach;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Training>
 */
class TrainingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $trainingNames = [
            'Personal Training',
            'Group Fitness',
            'Strength Training',
            'Cardio Training',
            'HIIT Workout',
            'Yoga Class',
            'CrossFit',
            'Boxing Training'
        ];
        return [
            'coach_id' => Coach::factory(),
            'name' => fake()->randomElement($trainingNames),
            'price' => fake()->randomFloat(2, 500, 5000)
        ];
    }
}
