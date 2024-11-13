<?php

namespace Database\Seeders;

use App\Models\Coach;
use App\Models\Training;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coach::all()->each(function ($coach) {
            Training::factory(1)->create([
                'coach_id' => $coach->id
            ]);
        });
    }
}
