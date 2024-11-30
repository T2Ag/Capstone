<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return Inertia::render('Users/Register',[
            'roles' => $roles,
            'success' => session('success') 
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
            'role' => ['required'], 
        ],[
            'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).'
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($request->role);

        // Only send verification for admin users
        if ($user->hasRole('admin')) {
            event(new Registered($user));
        }

        return redirect()->back()->with('success', 'User registered successfully.');
    }
}
