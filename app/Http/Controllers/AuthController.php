<?php

namespace App\Http\Controllers;

use App\Mail\HelloMail;
use App\Models\Announcement;
use App\Models\Client;
use App\Models\Coach;
use App\Models\Log;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
                return redirect()->route('trainerDashboard'); // Route for trainor
            }  elseif ($user->hasRole('user')) {
                return redirect()->route('userDashboard'); // Route for trainor
            } 
        }
    
        return Inertia::render('Login');
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
                return redirect()->intended('trainerDashboard'); 
            }     
            if ($user->hasRole('user')) {
                return redirect()->intended('userDashboard'); 
            }               
            
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.'
        ])->onlyInput('username');
    }

    public function logout()
    {
        $user = Auth::user();

        // Flush the two-factor authentication session
        Session::forget('two_factor_authenticated');
    
        // Clear the two-factor code
        if ($user) {
            $user->two_factor_code = null;
            $user->save();
        }

        Session::flush();
        Auth::logout();
        return redirect()->route('login');
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

        $totalActiveMonthlyClients = Client::whereHas('transactions', function($query) {
            $query->whereHas('client.payment_method', function($query) {
                $query->where('type', 'monthly');
            })
            ->whereDate('start_date', '<=', now()->endOfDay())  
            ->whereDate('end_date', '>=', now()->startOfDay()); 
        })->count();

        $activeMonthlyClients = Client::whereHas('transactions', function($query) {
            $query->whereHas('client.payment_method', function($query) {
                $query->where('type', 'monthly');
            })
            ->whereDate('start_date', '<=', now()->endOfDay())  
            ->whereDate('end_date', '>=', now()->startOfDay()); 
        })->get();

        $totalCoaches = Coach::count();
    
        $totalClients = Client::count();

        $totalMembers = Client::whereNotNull('user_id')->count();
    
        $totalEarnings = Transaction::sum('total_amount');
    
        // Calculate total number of logs for the current week
        $totalLogsThisMonth = Log::whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])->count();
    
        $totalEarningsThisMonth = Transaction::whereBetween('transaction_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('total_amount');
    
        // Get the first announcement (oldest)
        $firstAnnouncement = Announcement::orderBy('created_at', 'desc')->first();
    
        // Get all announcements except the first one
        $announcements = Announcement::where('id', '!=', optional($firstAnnouncement)->id)
            ->orderBy('created_at', 'desc')
            ->get();
    
        return Inertia::render('Admin/AdminIndex', [
            'months' => $months,
            'totals' => $totals,
            'totalClients' => $totalClients,
            'totalMembers' => $totalMembers,
            'totalEarnings' => $totalEarnings,
            'totalCoaches' => $totalCoaches,
            'totalLogsThisMonth' => $totalLogsThisMonth,
            'totalEarningsThisMonth' => $totalEarningsThisMonth,
            'firstAnnouncement' => $firstAnnouncement,
            'announcements' => $announcements,
            'totalActiveMonthlyClients' => $totalActiveMonthlyClients,
            'activeMonthlyClients' => $activeMonthlyClients,

        ]);
    }
}
