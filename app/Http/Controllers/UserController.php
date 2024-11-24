<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Coach;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index() {
        
        $users = User::with('roles')->get();
        $roles = Role::all();
    
        return Inertia::render('Users/User', [
            'users' => $users,
            'user_roles' => $roles,
        ]);
    }

    public function edit(Request $request)
    {   
        $userID = $request->user()->id;

        $qrCode = QrCode::format('svg')
        ->size(200)
        ->generate($userID);

        $qrCodeSvgString = (string)$qrCode;

        return Inertia::render('Edit',[
            'user' => User::with('client','coach')->find($request->user()->id),
            'qrCode' => $qrCodeSvgString
        ]);

    }

    public function editCoach(Request $request)
    {   
        $userID = $request->user()->id;

        $qrCode = QrCode::format('svg')
        ->size(200)
        ->generate($userID);

        $qrCodeSvgString = (string)$qrCode;

        return Inertia::render('EditAsCoach',[
            'user' => User::with('coach')->find($request->user()->id),
            'qrCode' => $qrCodeSvgString
        ]);

    }

    public function editProfile(Request $request, User $user)
    {
        $validatedUserData = $request->validate([
            'username' => 'required|string|unique:users,username,'.$user->id
        ]);

        $validatedData = $request->validate(([
            'client_id' => 'nullable|exists:clients,id',
        ]));
    
        $validatedClientData = $request->validate([
            'first_name' => 'required|string',
            'middle_initial' => 'nullable|string',
            'last_name' => 'required|string',
        ]);
    
        $user->update($validatedUserData);
    
        $client = Client::findOrFail($validatedData['client_id']);

        $client->update($validatedClientData);
    
        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function editCoachProfile(Request $request, User $user)
    {
        $validatedUserData = $request->validate([
            'username' => 'required|string|unique:users,username,'.$user->id
        ]);

        $validatedData = $request->validate(([
            'coach_id' => 'nullable|exists:coaches,id',
        ]));
    
        $validatedCoachData = $request->validate([
            'first_name' => 'required|string',
            'middle_initial' => 'nullable|string',
            'last_name' => 'required|string',
        ]);
    
        $user->update($validatedUserData);
    
        $coach = Coach::findOrFail($validatedData['coach_id']);

        $coach->update($validatedCoachData);
    
        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function changePassword(Request $request)
    {
        return Inertia::render('ChangePassword', [
            'user' => User::find($request->user()->id)
        ]);
    }

    public function updatePassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:8', 'different:current_password'],
            'verify_password' => ['required', 'same:new_password'],
        ]);

        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return redirect()->back()->with('success', 'Password updated successfully');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required'
        ]);
    
        $validatedData['password'] = Hash::make($validatedData['password']);
    
        $user = User::create($validatedData);
        
        $user->assignRole($request->role);
        
        return redirect()->route('users')->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|unique:users,username'. $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required'
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        $user->syncRoles([$request->role]);

        return redirect()->route('users')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users')->with('success', 'User Deleted successfully.');
    }
}
