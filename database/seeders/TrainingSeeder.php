<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Coach;
use App\Models\Training;
use App\Models\TrainingTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create trainings with attached coaches
        Training::factory(3)->create()->each(function ($training) {
            // Attach clients to each training
            Client::factory(2)->create()->each(function ($client) use ($training) {
                $client->training_id = $training->id;
                $client->save();

                TrainingTransaction::create([
                    'client_id' => $client->id,
                    'training_id' => $training->id,
                    'start_date' => now()->subDays(rand(1, 30)),
                    'end_date' => now()->addDays(rand(30, 90)),
                    'transaction_date' => now(),
                    'total_amount' => $training->price,
                ]);
            });
        });
    }
}
