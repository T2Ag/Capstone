<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\Training;
use App\Models\TrainingTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class CoachController extends Controller
{
    public function index(Request $request)
    {
        $coaches = Coach::with('user')
        ->filter([
            'search' => $request->input('search')
        ])
        ->paginate(15)->withQueryString();
     
        return Inertia::render('Coach/List',[
            'coaches' => $coaches,
            'search' => $request->search,
            'success' => session('success')
        ]);
    }

    public function view($id)
    {
        $coach = Coach::with(['user'])->findOrFail(($id));

        $earnings = TrainingTransaction::whereHas('training', function ($query) use ($coach) {
            $query->where('coach_id', $coach->id);
        })->sum('total_amount');
        
        $trainings = Training::where('coach_id', $coach->id)
        ->with(['clients', 'trainingTransactions'])
        ->get();

        return Inertia::render('Coach/View',[
            'coach' => $coach,
            'earnings' => $earnings,
            'trainings' => $trainings
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
            'username' => 'required|string|unique:users,username',
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            ],
        ],[
            'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
        ]);
        
        $user = User::create([
            'username' => $validatedUserData['username'],
            'password' => Hash::make($validatedUserData['password']),
        ]);
        
        $user->assignRole('trainor');

        $validatedData['user_id'] = $user->id;

        Coach::create($validatedData);

        return redirect()->route('coaches.index')->with('success', 'Coach created successfully.');

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

        return redirect()->route('coaches.index')->with('success', 'Coach updated successfully.');
    }

    public function destroy(Coach $coach)
    {
        // If the coach has an associated user, delete the user as well
        if ($coach->user) {
            $coach->user->delete();
        }
        
        // Delete the coach
        $coach->delete();

        return redirect()->route('coaches.index')->with('success', 'Coach deleted successfully.');
    }
}
