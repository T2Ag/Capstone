<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Log;
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

    public function pending()
    {
        $today = now()->toDateString();
    
        $logs = Log::with(['client', 'client.registration', 'client.payment_method'])
            ->whereDoesntHave('transaction')
            ->orderBy('created_at', 'desc')
            ->get();
    
        return Inertia::render('Clients_/Pending', [
            'logs' => $logs,
        ]);
    }
    

    public function list()
    {

        $clients = Client::get();

        $logs = Log::with('client')->orderBy('created_at', 'desc')->get();

        return Inertia::render('Logs_/List', [
            'logs' => $logs,
            'clients' => $clients,
        ]);
    }

    public function store(Request $request)
    {
        $client_id = $request->input('client_id');

        // Check if the client exists
        $client = Client::find($client_id);

        if (!$client) {
            return back()->with('error', 'Client does not exist')->with('client', $client);
        }

        // Create a new log entry
        Log::create([
            'client_id' => $client_id,
            'date' => now()
        ]);

        return Inertia::render('Logs_/Index', [
            'success' => 'Log created successfully.',
            'client' => $client,
            'logs' => Log::with('client')->get()
        ]);
    }

    public function manualStore(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required',
            'transaction_id' => 'nullable'
        ]);
    
        $validatedData['date'] = now();
        
        Log::create($validatedData);
    
        return redirect()->route('logs.list')->with('success', 'Log created successfully.');
    }    

    public function destroy(Log $log)
    {
        $log->delete();

        return redirect()->route('logs.list')->with('success', 'Log Deleted successfully.');
    }
}
