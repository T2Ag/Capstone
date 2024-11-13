<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Log;
use App\Models\PaymentMethod;
use App\Models\Registration;
use App\Models\Training;
use App\Models\Trainor;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::with('user', 'registration', 'payment_method')
        ->filter([
            'year_filter' => $request->input('year_filter'),
            'month_filter' => $request->input('month_filter'),
            'registration_type' => $request->input('registration_type'),
            'member_filter' => $request->boolean('member_filter'),
            'date_filter' => $request->input('date_filter'),
            'search' => $request->input('search')
        ])
        ->paginate(10);
            
        $users = User::all();
        $registrations = Registration::all(); 
        $payment_methods = PaymentMethod::all();
        $roles = Role::all();
    
        return Inertia::render('Clients_/Index', [
            'clients' => $clients,
            'users' => $users,
            'registrations' => $registrations,
            'payment_methods' => $payment_methods,
            'roles' => $roles,
            'year_filter' => $request->year_filter,
            'month_filter' => $request->month_filter,
            'registration_type' => $request->registration_type,
            'member_filter' => $request->member_filter,
            'date_filter' => $request->date_filter,
            'search' => $request->search,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|string|exists:users,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_initial' => 'required|string',
            'gender' => 'required|string',
            'registration_id' => 'required|exists:registrations,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'date' => 'nullable|date',
        ]);

        $validatedUserData = $request->validate([

            'username' => 'nullable|string|unique:users,username',
            'password' => 'nullable|string|confirmed|min:8',
        ]);
    

        if ($request->has('username') && $request->filled('username')) {
            // Create the user
            $user = User::create([
                'username' => $validatedUserData['username'],
                'password' => Hash::make($validatedUserData['password']),
            ]);

            // Assign the role to the user
            $user->assignRole('user');

            // Update the user_id in the validated data
            $validatedData['user_id'] = $user->id;
        }

        $validatedData['date'] = now();

        // Create the client
        Client::create($validatedData);
    
        return redirect()->route('clients')->with('success', 'Client created successfully.');
    }

    public function view($id)
    {   
        $payment_methods = PaymentMethod::all();
        $client = Client::with(['payment_method', 'transactions', 'user', 'registration', 'training', 'logs'])->findOrFail($id);
        
        $logs = Log::with('client')->where('client_id', $id)->paginate(10);

        $transactions = Transaction::with('client')->where('client_id', $id)->paginate(10);

        return Inertia::render('Clients_/View', [
            'client' => $client,
            'payment_methods' => $payment_methods,
            'logs' => $logs,
            'transactions' => $transactions,

        ]);
    }

    public function updatePayment(Client $client)
    {
        $newPaymentMethodType = $client->payment_method->type === 'walk-in' ? 'monthly' : 'walk-in';
        $newPaymentMethod = PaymentMethod::where('type', $newPaymentMethodType)->first();

        $client->update(['payment_method_id' => $newPaymentMethod->id]);

        return redirect()->back()->with([
            'success' => 'Log created successfully.',
        ]);
    }

    public function destroy(Client $client) 
    {
        $client->delete();

        return redirect()->route('clients')->with('success', 'Client Deleted successfully.');
    }

    public function destroyLog(Log $log)
    {
        if ($log->transaction) {
            $log->transaction->delete();
        }
        $log->delete();

        return redirect()->back()->with('success', 'Log Deleted successfully.');
    }

    public function destroyTransaction(Transaction $transaction)
    {

        $transaction->delete();

        return redirect()->back()->with('success', 'Transaction Deleted successfully.');
    }
}
