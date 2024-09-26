<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function createTransaction(Request $request)
    {

        $validatedData = $request->validate([
            'log_id' => 'required|exists:logs,id',
            'client_id' => 'required|exists:clients,id',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date',
            'totalAmount' => 'required|numeric|min:0',
        ]);

        // Create the transaction
        $transaction = Transaction::create([
            'client_id' => $validatedData['client_id'],
            'transaction_date' => now(),
            'start_date' => $validatedData['startDate'],
            'end_date' => $validatedData['endDate'],
            'total_amount' => $validatedData['totalAmount'],
        ]);

        Log::where('id', $validatedData['log_id'])->update([
            'transaction_id' => $transaction->id
        ]);

        return redirect()->route('pending')->with('success', 'Transaction Successful.');
    }
}
