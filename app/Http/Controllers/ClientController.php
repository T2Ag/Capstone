<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Log;
use App\Models\PaymentMethod;
use App\Models\Registration;
use App\Models\TodoList;
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
        $clients = Client::with('user', 'registration', 'payment_method', 'transactions.payment_method')
        ->filter([
            'year_filter' => $request->input('year_filter'),
            'month_filter' => $request->input('month_filter'),
            'registration_type' => $request->input('registration_type'),
            'payment_method' => $request->input('payment_method'),
            'member_filter' => $request->boolean('member_filter'),
            'date_filter' => $request->input('date_filter'),
            'search' => $request->input('search')
        ])
        ->paginate(10);

        // Add the first active transaction for each client
        $clients->transform(function ($client) {
            $client->first_active_transaction = $client->transactions()
            ->where('start_date', '<=', now()->startOfDay())
            ->where('end_date', '>=', now()->startOfDay())
            ->first();
            return $client;
        });

            
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
            'payment_method' => $request->payment_method,
            'member_filter' => $request->member_filter,
            'date_filter' => $request->date_filter,
            'search' => $request->search,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_initial' => 'nullable|string',
            'gender' => 'required|string',
            'registration_id' => 'required|exists:registrations,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'date' => 'nullable|date',

        ]);

        $validatedData['date'] = now();

        // Create the client
        Client::create($validatedData);

        return redirect()->route('clients')->with('success', 'Client created successfully.');
    }

    public function update(Request $request, Client $client)
    {

        $validatedData = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_initial' => 'nullable|string',
            'gender' => 'required|string',
            'registration_id' => 'required|exists:registrations,id',
        ]);

        $client->update($validatedData);

        return redirect()->route('clients')->with('success', 'Client updated successfully.');
    }

    public function view($id)
    {   
        $payment_methods = PaymentMethod::all();
        $client = Client::with(['payment_method', 'transactions', 'user', 'registration', 'training', 'logs',])->findOrFail($id);

        $client->setAttribute('isMonthlyActive', $client->isMonthlyActive());
        
        $logs = Log::with('client','payment_method')->where('client_id', $id)->orderBy('date', 'desc')->paginate(10);

        $transactions = Transaction::with('client','payment_method')->where('client_id', $id)->orderBy('transaction_date', 'desc')->paginate(10);

        $todos = TodoList::where('client_id', $id)->get();

        $users = User::all();

        $latestMonthlyTransaction = $client->transactions()
        ->whereHas('payment_method', function ($query) {
            $query->where('type', 'monthly');
        })
        ->whereNotNull('start_date')
        ->whereNotNull('end_date')
        ->latest('transaction_date')
        ->first();

        // $trans = $client->transactions()->get();
        // foreach ($trans as $transaction) {
        //     dd([
        //         'transaction_id' => $transaction->id,
        //         'payment_method_id' => $transaction->payment_method_id,
        //         'payment_method' => $transaction->payment_method ? $transaction->payment_method->toArray() : 'No Payment Method',
        //     ]);
        // }

        // dd([
        //     'transactions' => $client->transactions()->with('payment_method')->get(),
        //     'latest_transaction_details' => $latestMonthlyTransaction
        // ]);

        $firstUnpaidMonthlyLog = $client->logs()
        ->whereHas('payment_method', function ($query) {
            $query->where('type', 'monthly');
        })
        ->whereNull('transaction_id')
        ->oldest()
        ->first();

        return Inertia::render('Clients_/View', [
            'client' => $client,
            'users' => $users,
            'payment_methods' => $payment_methods,
            'logs' => $logs,
            'transactions' => $transactions,
            'todos' => $todos,
            'latestMonthlyTransaction' => $latestMonthlyTransaction,
            'firstUnpaidMonthlyLog' => $firstUnpaidMonthlyLog
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

    public function becomeMember(Request $request, Client $client)
    {
        // Check the value of userSelected to determine which validation to apply
        if ($request->input('userSelected', true)) {
            // Validate user selection
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);
        } else {
            // Validate user creation
            $validatedUserData = $request->validate([
                'username' => 'required|string|unique:users,username',
                'password' => [
                    'required',
                    'string',
                    'confirmed',
                    'min:8',
                    'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                ],
            ], [
                'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
            ]);

            // Create the user if username is provided
            if ($request->has('username') && $request->filled('username')) {
                $user = User::create([
                    'username' => $validatedUserData['username'],
                    'password' => Hash::make($validatedUserData['password']),
                ]);

                // Assign the role to the user
                $user->assignRole('user');

                // Prepare validated data with the new user ID
                $validatedData['user_id'] = $user->id;
            }
        }

        // Validate transaction data
        $validatedTransactionData = $request->validate([
            'total_amount' => 'nullable|numeric',
        ]);

        // Update the client with validated data
        $client->update($validatedData);


        if (isset($validatedTransactionData['total_amount']) && $validatedTransactionData['total_amount'] > 0) {
            Transaction::create([
                'client_id' => $client->id,
                'description' => 'Membership Registration',
                'transaction_date' => now(),
                'total_amount' => $validatedTransactionData['total_amount'],
            ]);
        }

        return redirect()->back()->with('success', 'Membership updated successfully.');
    }

    public function revoke(Client $client) 
    {
        if (is_null($client->user_id)) {
            return redirect()->back()->with('error', 'Client Membership is already revoked.');
        }
    
        // Find and delete the associated user
        $user = User::find($client->user_id);
    
        if ($user) {
            $user->delete(); // Delete the associated user
        }
    
        // Set user_id to null for the client
        $client->user_id = null;
        $client->save();

        return redirect()->back()->with('success', 'Client Membership Revoked successfully.');
    }

    public function destroy(Client $client) 
    {
        $client->delete();

        return redirect()->route('clients')->with('success', 'Client Deleted successfully.');
    }

    public function destroyLog(Log $log)
    {

        $log->delete();

        return redirect()->back()->with('success', 'Log Deleted successfully.');
    }

    public function destroyTransaction(Transaction $transaction)
    {

        $transaction->delete();

        return redirect()->back()->with('success', 'Transaction Deleted successfully.');
    }
}
