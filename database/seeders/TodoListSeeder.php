<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\TodoList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodoListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all client IDs
        $clientIds = Client::pluck('id');

        // Create 3-7 todos for each client
        foreach ($clientIds as $clientId) {
            TodoList::factory()
                ->count(1)
                ->create([
                    'client_id' => $clientId
                ]);
        }
    }
}
