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
        $transactions = Transaction::with('client', 'client.payment_method', 'payment_method')->orderBy('created_at', 'desc')
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

        $totalEarnings = Transaction::filter([
            'year_filter' => $request->input('year_filter'),
            'month_filter' => $request->input('month_filter'),
            'registration_type' => $request->input('registration_type'),
            'payment_method' => $request->input('payment_method'),
            'search' => $request->input('search')
        ])->sum('total_amount');

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
            'totalEarnings' => $totalEarnings,
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

        $client = Client::findOrFail($validatedData['client_id']);

        $log = Log::findOrFail($validatedData['log_id']);

        $validatedData['payment_method_id'] = $log->payment_method_id;

        $description = match($client->payment_method->type) {
            'monthly' => 'Monthly Payment',
            'walk-in' => 'Walk-in Payment',
            default => 'General Payment'
        };

        // Create the transaction
        $transaction = Transaction::create([
            'client_id' => $validatedData['client_id'],
            'description' => $description,
            'transaction_date' => now(),
            'start_date' => $validatedData['startDate'],
            'end_date' => $validatedData['endDate'],
            'total_amount' => $validatedData['totalAmount'],
        ]);

        $log->update(['transaction_id' => $transaction->id]);

        $otherLogs = Log::where('client_id', $validatedData['client_id'])
        ->where('id', '!=', $validatedData['log_id'])
        ->whereHas('payment_method', function ($query) {
            $query->where('type', 'monthly');
        })
        ->where(function ($query) use ($transaction) {
            $query->whereBetween('date', [$transaction->start_date, $transaction->end_date])
                    ->whereNull('transaction_id');
        })
        ->get();

        // Update other logs with the transaction ID
        foreach ($otherLogs as $otherLog) {
            $otherLog->update(['transaction_id' => $transaction->id]);
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

        $client = Client::findOrFail($validatedData['client_id']);

        $description = match($client->payment_method->type) {
            'monthly' => 'Monthly Payment',
            'walk-in' => 'Walk-in Payment',
            default => 'General Payment'
        };

        // Create the transaction
        $transaction = Transaction::create([
            'client_id' => $validatedData['client_id'],
            'description' => $description,
            'payment_method_id' => $client->payment_method_id,
            'transaction_date' => now(),
            'start_date' => $validatedData['startDate'],
            'end_date' => $validatedData['endDate'],
            'total_amount' => $validatedData['totalAmount'],
        ]);

        $log = Log::create([
            'client_id' => $validatedData['client_id'],
            'payment_method_id' => $client->payment_method_id,
            'transaction_id' => $transaction->id,
            'date' => now()
        ]);

        $otherLogs = Log::where('client_id', $validatedData['client_id'])
        ->where('id', '!=', $log->id)
        ->whereHas('payment_method', function ($query) {
            $query->where('type', 'monthly');
        })
        ->where(function ($query) use ($transaction) {
            $query->whereBetween('date', [$transaction->start_date, $transaction->end_date])
                    ->whereNull('transaction_id');
        })
        ->get();

        // Update other logs with the transaction ID
        foreach ($otherLogs as $otherLog) {
            $otherLog->update(['transaction_id' => $transaction->id]);
        }

        return redirect()->back()->with([
            'success' => 'Transaction and Log created successfully.',
        ]);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->back()->with('success', 'Transaction Deleted successfully.');
    }
}
