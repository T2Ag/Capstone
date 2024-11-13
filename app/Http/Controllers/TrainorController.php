<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Trainor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class TrainorController extends Controller
{
    public function index()
    {
        $trainors = Trainor::with('user')->paginate(15);

     
        return Inertia::render('Trainor/List',[
            'trainors' => $trainors,
            
        ]);
    }

    // public function coachingList()
    // {
        // $trainors = Trainor::with(['user', 'clients'])
        // ->withCount('clients as total_clients')
        // ->paginate(15);

    //     $clients = Client::get();

    //     return Inertia::render('Trainor/CoachingList', [
    //         'trainors' => $trainors,
    //         'stats' => [
    //             'total_trainors' => Trainor::count(),
    //             'active_trainors' => Trainor::has('clients')->count(),
    //             'total_clients' => Client::count()
    //         ],
    //         'clients' => $clients,
    //     ]);
    // }

    public function store(Request $request)
    {
        $validatedData = $request->validate([

            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_initial' => 'required|string',
            'gender' => 'required|string',
            'price' => 'required|numeric',

        ]);

        $validatedUserData = $request->validate([

            'username' => 'nullable|string|unique:users,username',
            'password' => 'nullable|string|confirmed|min:8',
        ]);
        
        $user = User::create([
            'username' => $validatedUserData['username'],
            'password' => Hash::make($validatedUserData['password']),
        ]);
        
        $user->assignRole('trainor');

        Trainor::create($validatedData);

        return redirect()->route('trainors.index')->with('success', 'Trainor created successfully.');

    }

    public function update(Request $request, Trainor $trainor)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_initial' => 'required|string',
            'gender' => 'required|string',
            'price' => 'required|numeric',

        ]);

        $trainor->update($validatedData);

        return redirect()->route('trainors.index')->with('success', 'Trainor updated successfully.');
    }

    public function destroy(Trainor $trainor) 
    {
        $trainor->delete();

        return redirect()->route('trainors.index')->with('success', 'Trainor Deleted successfully.');
    }
}
