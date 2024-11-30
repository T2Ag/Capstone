<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class TwoFactorController extends Controller
{

    public function index()
    {
        $user = auth()->user();
    
        if ($user->hasRole('trainor')) {
            return redirect()->route('trainerDashboard'); 
        }  elseif ($user->hasRole('user')) {
            return redirect()->route('userDashboard'); 
        } 

        // If user is already two-factor authenticated, redirect to dashboard
        if (session('two_factor_authenticated')) {
            return redirect()->route('dashboard');
        }
    
        // Only generate a new code if one doesn't exist or if the user has logged out (two_factor_authenticated session is false)
        if (!$user->two_factor_code || !session('two_factor_authenticated')) {
            $code = rand(100000, 999999);
            $user->two_factor_code = $code;
            $user->save();
    
            Mail::raw("Your two-factor authentication code is $code", function($message) use ($user){
                $message->to($user->email)->subject('Two-Factor Authentication Code');
            });
        }
    
        return Inertia::render('Two-Factor');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|integer'
        ]);

        $user = auth()->user();

        if($request->code == $user->two_factor_code) {
            session(['two_factor_authenticated' => true]);
            return redirect()->intended('/dashboard');
        }

        return redirect()->route('two-factor.index')->withErrors(['code' => 'The provided code is incorrect.']);
    }

}
