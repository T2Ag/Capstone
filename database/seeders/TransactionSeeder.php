<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Log;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have some clients, payment methods, and registrations
        $clientCount = Client::count();
        $paymentMethodCount = PaymentMethod::count();

        if ($clientCount == 0 || $paymentMethodCount == 0) {
            $this->command->warn('Please seed clients and payment methods first.');
            return;
        }

        // Start from October 1st of the current year
        $startDate = Carbon::create(now()->year, 10, 1);
        $endDate = now();

        // Iterate through weeks
        $currentWeek = $startDate->copy();
        while ($currentWeek->lte($endDate)) {
            // Number of transactions for this week (3-5)
            $transactionCount = rand(3, 5);

            for ($i = 0; $i < $transactionCount; $i++) {
                // Select a random client
                $client = Client::inRandomOrder()->first();

                // Select a random payment method from the existing types
                $paymentMethod = PaymentMethod::inRandomOrder()->first();

                // Generate a transaction date within the current week
                $transactionDate = $currentWeek->copy()->addDays(rand(0, 6));

                // Create transaction
                $transaction = Transaction::create([
                    'client_id' => $client->id,
                    'payment_method_id' => $paymentMethod->id,
                    'description' => $this->generateTransactionDescription($client),
                    'transaction_date' => $transactionDate,
                    'start_date' => $transactionDate,
                    'end_date' => $transactionDate->copy()->addMonths(rand(1, 6)),
                    'total_amount' => $this->generateRandomAmount(),
                ]);

                // Create corresponding log
                Log::create([
                    'client_id' => $client->id,
                    'transaction_id' => $transaction->id,
                    'payment_method_id' => $paymentMethod->id,
                    'date' => $transactionDate,
                ]);
            }

            // Move to next week
            $currentWeek->addWeek();
        }
    }

    /**
     * Generate a random transaction description
     * 
     * @param Client $client
     * @return string
     */
    private function generateTransactionDescription(Client $client): string
    {
        $description = match($client->payment_method->type) {
            'monthly' => 'Monthly Payment for ' . ($client->first_name . ' ' . ($client->middle_initial ?? '') . ' ' . $client->last_name),
            'walk-in' => 'Walk-in Payment for ' . ($client->first_name . ' ' . ($client->middle_initial ?? '') . ' ' . $client->last_name),
            default => 'General Payment'
        };

        return $description;
    }

    /**
     * Generate a random transaction amount
     * 
     * @return float
     */
    private function generateRandomAmount(): float
    {
        // Generate amounts between 50 and 500
        return round(rand(50 * 100, 500 * 100) / 100, 2);
    }
}