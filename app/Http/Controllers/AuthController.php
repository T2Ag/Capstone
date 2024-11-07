<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Log;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            $user = Auth::user(); 
            // Redirect based on user role
            if ($user->hasRole('admin')) {
                return redirect()->route('dashboard'); // Route for admin
            } elseif ($user->hasRole('trainor')) {
                return redirect()->route('trainorDashboard'); // Route for trainor
            } else {
                return redirect()->route('dashboard'); // Fallback for other roles
            }
        }
    
        return Inertia::render('Login');
    }

    public function trainorDashboard()
    {
        return Inertia::render('Trainor/TrainorIndex');
    }

    public function dashboard()
    {
        $gymVisits = Log::selectRaw(
            "strftime('%m', date) as month, COUNT(*) as total_visits"
        )
        ->whereNotNull('transaction_id')
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    
        $months = $gymVisits->map(fn($item) => Carbon::createFromFormat('m', $item->month)->format('F'));

        $totals = $gymVisits->pluck('total_visits');
        
        $totalClients = Client::count();

        $totalMembers = Client::whereNotNull('user_id')->count();
        
        $totalEarnings = Transaction::sum('total_amount');

        // Calculate total number of logs for the current week
        $totalLogsThisWeek = Log::whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $totalEarningsThisWeek = Transaction::whereBetween('transaction_date', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_amount');
    
        return Inertia::render('Admin/AdminIndex', [
            'months' => $months,
            'totals' => $totals,
            'totalClients' => $totalClients,
            'totalMembers' => $totalMembers,
            'totalEarnings' => $totalEarnings,
            'totalLogsThisWeek' => $totalLogsThisWeek,
            'totalEarningsThisWeek' => $totalEarningsThisWeek,

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
            $user = Auth::user();
                
            if ($user->hasRole('admin')) {
                return redirect()->intended('dashboard'); 
            }
            if ($user->hasRole('trainor')) {
                return redirect()->intended('trainorDashboard'); 
            }                 
            
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
