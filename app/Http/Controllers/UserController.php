<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
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
