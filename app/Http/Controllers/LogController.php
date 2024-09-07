<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Log;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::get();

        return Inertia::render('Logs_/Index', [
            'logs' => $logs,
        ]);
    }

    public function store(Request $request)
    {
        $client_id = $request->input('client_id');

        // Check if the client exists
        $client = Client::find($client_id);

        if (!$client) {
            return back()->with('error', 'Client does not exist');
        }

        // Create a new log entry
        Log::create([
            'client_id' => $client_id,
            'date' => now()
        ]);

        return redirect()->route('logs')->with('success', 'Log created successfully.');
    }
}
