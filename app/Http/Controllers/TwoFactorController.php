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

        session(['two_factor_in_progress' => true]);

        // If user is already two-factor authenticated, redirect to dashboard
        if (session('two_factor_authenticated')) {
            return redirect()->route('dashboard');
        }
    
        // Only generate a new code if one doesn't exist
        if (!$user->two_factor_code) {
            $code = rand(100000, 999999);
            $user->two_factor_code = $code;
            $user->save();
    
            // Send email only if code is newly generated
            Mail::raw("Your two-factor authentication code is $code", function($message) use ($user){
                $message->to($user->email)->subject('Two-Factor Authentication Code');
            });
        }
    
        return Inertia::render('Two-Factor',[
            'status' => session('status')
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|integer'
        ]);

        $user = auth()->user();

        if($request->code == $user->two_factor_code) {
            session(['two_factor_authenticated' => true]);

            // Clear the code and progress flags after successful verification
            $user->two_factor_code = null;
            $user->save();

            session()->forget(['two_factor_in_progress']);

            return redirect()->intended('/dashboard');
        }

        return redirect()->route('two-factor.index')->withErrors(['code' => 'The provided code is incorrect.']);
    }

    public function resendCode()
    {
        $user = auth()->user();

        if (!session('two_factor_in_progress')) {
            return redirect()->route('two-factor.index')
                ->withErrors(['resend' => 'Unauthorized resend attempt.']);
        }

        // If user is already two-factor authenticated, redirect to dashboard
        if (session('two_factor_authenticated')) {
            return redirect()->route('dashboard');
        }

        // Generate a new code
        $code = rand(100000, 999999);
        $user->two_factor_code = $code;
        $user->save();

        // Send new code via email
        Mail::raw("Your two-factor authentication code is $code", function($message) use ($user){
            $message->to($user->email)->subject('Two-Factor Authentication Code');
        });

        return redirect()->route('two-factor.index')->with('status', 'A new code has been sent to your email.');
    }

}
