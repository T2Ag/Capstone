<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function exportMonthlyReport(Request $request)
    {
        $year = $request->input('year', now()->year);

        // Fetch gym visit data
        $gymVisits = Log::selectRaw(
            "strftime('%m', date) as month, COUNT(*) as total_visits"
        )
        ->whereYear('date', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $totalEarnings = Transaction::sum('total_amount');
        $walkInVisits = Client::whereHas('payment_method', fn($query) => $query->where('type', 'walk-in'))->count();
        $monthlyVisits = Client::whereHas('payment_method', fn($query) => $query->where('type', 'monthly'))->count();
        $totalEarningsThisMonth = Transaction::whereBetween('transaction_date', [now()->startOfMonth(), now()->endOfMonth()])->sum('total_amount');
        
        $currentMonth = now()->format('F');

        $clientVisits = Log::select('client_id')
        ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
        ->groupBy('client_id')
        ->selectRaw('client_id, COUNT(*) as visit_count')
        ->with('client:id,first_name,middle_initial,last_name') 
        ->get();

        $data = [
            'totalEarningsThisMonth' => $totalEarningsThisMonth,
            'walkInVisits' => $walkInVisits,
            'monthlyVisits' => $monthlyVisits,
            'currentMonth' => $currentMonth,
            'clientVisits' => $clientVisits,
        ];

        $pdf = Pdf::loadView('pdf.monthly_report', $data);

        return $pdf->download('monthly_report.pdf');
    }
}
