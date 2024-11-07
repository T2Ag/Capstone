<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Log;
use App\Models\PaymentMethod;
use App\Models\Registration;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogController extends Controller
{
    public function index()
    {

        $clients = Client::get();

        $logs = Log::with('client')->get();

        return Inertia::render('Logs_/Index', [
            'logs' => $logs,
            'clients' => $clients,
        ]);
    }

    public function pending(Request $request)
    {
    
        // Retrieve logs that don't have any transactions, regardless of payment method type
        $noTransactionLogs = Log::with(['client', 'client.registration', 'client.payment_method', 'client.transactions'])
            ->filter([
                'year_filter' => $request->input('year_filter'),
                'month_filter' => $request->input('month_filter'),
                'registration_type' => $request->input('registration_type'),
                'payment_method' => $request->input('payment_method'),
                // 'member_filter' => $request->boolean('member_filter'),
                // 'date_filter' => $request->input('date_filter')
            ])
            ->whereNull('transaction_id')
            ->orderBy('date', 'desc')
            ->get();

        $registrations = Registration::all(); 
        $paymentMethods = PaymentMethod::all();

        return Inertia::render('Clients_/Pending', [
            'logs' => $noTransactionLogs,
            'registrations' => $registrations,
            'paymentMethods' => $paymentMethods,
            'year_filter' => $request->year_filter,
            'month_filter' => $request->month_filter,
            'registration_type' => $request->registration_type,
            'payment_method' => $request->payment_method,
        ]);
    }
    

    public function list(Request $request)
    {
        $clients = Client::get();
        $logs = Log::with('client', 'client.payment_method')->orderBy('created_at', 'desc')
        ->filter([
            'year_filter' => $request->input('year_filter'),
            'month_filter' => $request->input('month_filter'),
            'registration_type' => $request->input('registration_type'),
            'payment_method' => $request->input('payment_method'),
            // 'member_filter' => $request->boolean('member_filter'),
            // 'date_filter' => $request->input('date_filter')
        ])
        ->paginate(10);

        $registrations = Registration::all(); 
        $paymentMethods = PaymentMethod::all();
    
        return Inertia::render('Logs_/List', [
            'logs' => $logs,
            'clients' => $clients,
            'registrations' => $registrations,
            'paymentMethods' => $paymentMethods,
            'createdLog' => session('createdLog'),
            'createdClient' => session('createdClient'),
            'success' => session('success'),
            'year_filter' => $request->year_filter,
            'month_filter' => $request->month_filter,
            'registration_type' => $request->registration_type,
            'payment_method' => $request->payment_method,

        ]);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'transaction_id' => 'nullable'
        ]);

        $client = Client::with('payment_method')->find($request->client_id);

        $validatedData['date'] = now();

        $latestTransaction = Transaction::where('client_id', $validatedData['client_id'])
        ->latest('transaction_date')
        ->first();

        if (
            $latestTransaction && 
            !is_null($latestTransaction->start_date) && 
            !is_null($latestTransaction->end_date) && 
            $validatedData['date']->between($latestTransaction->start_date, $latestTransaction->end_date)
        ) {
            $validatedData['transaction_id'] = $latestTransaction->id;
        }

        $log = Log::create($validatedData);

        return Inertia::render('Logs_/Index', [
            'success' => 'Log created successfully.',
            'logs' => Log::with('client','client.payment_method')->get(),
            'client' => $client,
            'log' => Log::with('client', 'client.payment_method')->find($log->id)
        ]);
    }

    public function manualStore(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'transaction_id' => 'nullable'
        ]);
    
        $validatedData['date'] = now();
    
        $latestTransaction = Transaction::where('client_id', $validatedData['client_id'])
            ->latest('transaction_date')
            ->first();
    
        if (
            $latestTransaction && 
            !is_null($latestTransaction->start_date) && 
            !is_null($latestTransaction->end_date) && 
            $validatedData['date']->between($latestTransaction->start_date, $latestTransaction->end_date)
        ) {
            $validatedData['transaction_id'] = $latestTransaction->id;
        }
        
        $log = Log::create($validatedData);
        
        $client = Client::find($validatedData['client_id']);
        $log = Log::with('client', 'client.payment_method')->find($log->id);
    
        return redirect()->route('logs.list')->with([
            'success' => 'Log created successfully.',
            'createdLog' => $log,
            'createdClient' => $client
        ]);
    }

    public function destroy(Log $log)
    {
        $log->delete();

        return redirect()->route('logs.list')->with('success', 'Log Deleted successfully.');
    }
}
