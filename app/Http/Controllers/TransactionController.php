<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('client', 'client.payment_method')->orderBy('created_at', 'desc')->get();

        return Inertia::render('Clients_/Transaction',[
            'transactions' => $transactions,
        ]);
    }

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

        // Get all other logs for the same client, excluding the log that was just updated
        $otherLogs = Log::where('client_id', $validatedData['client_id'])
        ->where('id', '!=', $validatedData['log_id'])
        ->where(function ($query) use ($transaction) {
            // Check if the log's date is within the transaction's start and end date
            $query->whereBetween('date', [$transaction->start_date, $transaction->end_date]);
        })
        ->get();

        // Update all logs that are within the date range to have the same transaction ID
        foreach ($otherLogs as $log) {
            $log->update([
                'transaction_id' => $transaction->id
            ]);
        }

        return redirect()->route('pending')->with('success', 'Transaction Successful.');
    }

    public function createTransactionWithLogs(Request $request) 
    {
        $validatedData = $request->validate([
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

        Log::create([
            'client_id' => $validatedData['client_id'],
            'transaction_id' => $transaction->id,
            'date' => now()
        ]);

        return back()->with('success', 'Transaction Successful.');
    }
}
