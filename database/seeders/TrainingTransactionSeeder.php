<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Training;
use App\Models\TrainingTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrainingTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Training::all()->each(function ($training) {
            // Create 5 active transactions
            TrainingTransaction::factory(1)->create([
                'training_id' => $training->id,
                'client_id' => Client::factory()
            ]);
        });
    }
}
