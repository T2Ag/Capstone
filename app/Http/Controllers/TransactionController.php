<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Log;
use App\Models\PaymentMethod;
use App\Models\Registration;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with('client', 'client.payment_method')->orderBy('created_at', 'desc')
        ->filter([
            'year_filter' => $request->input('year_filter'),
            'month_filter' => $request->input('month_filter'),
            'registration_type' => $request->input('registration_type'),
            'payment_method' => $request->input('payment_method'),
            'search' => $request->input('search')
            // 'member_filter' => $request->boolean('member_filter'),
            // 'date_filter' => $request->input('date_filter')
        ])
        ->paginate(15);

        $registrations = Registration::all(); 
        $paymentMethods = PaymentMethod::all();

        return Inertia::render('Clients_/Transaction',[
            'transactions' => $transactions,
            'registrations' => $registrations,
            'paymentMethods' => $paymentMethods,
            'year_filter' => $request->year_filter,
            'month_filter' => $request->month_filter,
            'registration_type' => $request->registration_type,
            'payment_method' => $request->payment_method,
            'search' => $request->search,
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

        $log = Log::create([
            'client_id' => $validatedData['client_id'],
            'transaction_id' => $transaction->id,
            'date' => now()
        ]);

        return redirect()->back()->with([
            'success' => 'Transaction and Log created successfully.',
        ]);
    }
}
