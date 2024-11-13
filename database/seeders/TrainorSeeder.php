<?php

namespace Database\Seeders;

use App\Models\Trainor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrainorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Trainor::factory()->count(2)->create();
    }
}
