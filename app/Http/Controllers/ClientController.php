<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\PaymentMethod;
use App\Models\Registration;
use App\Models\Training;
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
        $clients = Client::with('user', 'registration', 'payment_method', 'training')
        ->filter([
            'year_filter' => $request->input('year_filter'),
            'month_filter' => $request->input('month_filter'),
            'registration_type' => $request->input('registration_type'),
            'member_filter' => $request->boolean('member_filter'),
            // Add more filters here as needed
        ])
        ->get();

        $users = User::all();
        $trainings = Training::all();
        $registrations = Registration::all(); 
        $payment_methods = PaymentMethod::all();
        $roles = Role::all();
    
        return Inertia::render('Clients_/Index', [
            'clients' => $clients,
            'users' => $users,
            'trainings' => $trainings,
            'registrations' => $registrations,
            'payment_methods' => $payment_methods,
            'roles' => $roles,
            'year_filter' => $request->year_filter,
            'month_filter' => $request->month_filter,
            'registration_type' => $request->registration_type,
            'member_filter' => $request->member_filter,
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

        $validatedUserDate = $request->validate([

            'username' => 'nullable|string|unique:users,username',
            'password' => 'nullable|string|confirmed|min:8',
        ]);
    

        if ($request->has('username') && $request->filled('username')) {
            // Create the user
            $user = User::create([
                'username' => $validatedUserDate['username'],
                'password' => Hash::make($validatedUserDate['password']),
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

        return Inertia::render('Clients_/View', [
            'client' => $client,
            'payment_methods' => $payment_methods
        ]);
    }

    public function updatePayment(Client $client)
    {
        $newPaymentMethodType = $client->payment_method->type === 'walk-in' ? 'monthly' : 'walk-in';
        $newPaymentMethod = PaymentMethod::where('type', $newPaymentMethodType)->first();

        $client->update(['payment_method_id' => $newPaymentMethod->id]);

        return redirect()->back()->with('success', 'Payment method updated successfully.');
    }

    public function destroy(Client $client) 
    {
        $client->delete();

        return redirect()->route('clients')->with('success', 'Client Deleted successfully.');
    }
}
