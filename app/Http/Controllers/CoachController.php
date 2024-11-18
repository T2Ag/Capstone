<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class CoachController extends Controller
{
    public function index()
    {
        $coaches = Coach::with('user')->paginate(15);
     
        return Inertia::render('Coach/List',[
            'coaches' => $coaches,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_initial' => 'nullable|string',
            'gender' => 'required|string',
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

        $validatedData['user_id'] = $user->id;

        Coach::create($validatedData);

        return redirect()->route('coaches.index')->with('success', 'Trainor created successfully.');

    }

    public function update(Request $request, Coach $coach)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_initial' => 'nullable|string',
            'gender' => 'required|string',
        ]);

        $coach->update($validatedData);

        return redirect()->route('coaches.index')->with('success', 'Trainor updated successfully.');
    }
}
