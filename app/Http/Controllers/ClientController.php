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
    public function index()
    {
        $clients = Client::with('user', 'registration', 'payment_method', 'training')->get();
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
            'payment_method_id' => 'nullable',
            'date' => 'nullable|date',
        ]);

        $validatedUserDate = $request->validate([

            'username' => 'nullable|string|unique:users,username',
            'password' => 'nullable|string|confirmed|min:8',
            'role' => 'nullable|string|exists:roles,name',
        ]);
    

        if ($request->has('username') && $request->filled('username')) {
            // Create the user
            $user = User::create([
                'username' => $validatedUserDate['username'],
                'password' => Hash::make($validatedUserDate['password']),
            ]);

            // Assign the role to the user
            $user->assignRole($validatedUserDate['role']);

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
        $client = Client::findOrFail($id);

        return Inertia::render('Clients_/View', ['client' => $client]);
    }

    public function destroy(Client $client) 
    {
        $client->delete();

        return redirect()->route('clients')->with('success', 'Client Deleted successfully.');
    }
}
