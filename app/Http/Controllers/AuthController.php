<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return Inertia::render('Home');
        }

        return Inertia::render('Login');
    }

    public function dashboard()
    {
        $gymVisits = Log::selectRaw(
            "strftime('%m', date) as month, COUNT(DISTINCT client_id) as total_visits"
        )
        ->whereNotNull('transaction_id')
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    
        $months = $gymVisits->map(fn($item) => Carbon::createFromFormat('m', $item->month)->format('F'));
        $totals = $gymVisits->pluck('total_visits'); 
    
        return Inertia::render('Home', [
            'months' => $months,
            'totals' => $totals,
        ]);
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('username', 'password');
        
        if (Auth::attempt($credentials)) {

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.'
        ])->onlyInput('username');
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('login');
    }
}
