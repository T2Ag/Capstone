<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Training;
use App\Models\Trainor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingTransaction>
 */
class TrainingTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $startDate = Carbon::now(); // Starts today
        $endDate = Carbon::now()->addMonths(fake()->numberBetween(1, 6));

        return [
            'client_id' => Client::factory(),
            'training_id' => Training::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'transaction_date' => Carbon::now()
        ];
    }
}
