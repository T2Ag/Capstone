<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\TrainingTransaction;
use App\Models\Trainor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrainingTransactionController extends Controller
{
    public function index(Request $request)
    {
        $trainingTransactions = TrainingTransaction::with('training','training.coach', 'client')->orderBy('created_at', 'desc')
        ->filter([
            'year_filter' => $request->input('year_filter'),
            'month_filter' => $request->input('month_filter'),
            'search' => $request->input('search')
        ])
        ->paginate(10);

        $totalEarnings = TrainingTransaction::filter([
            'year_filter' => $request->input('year_filter'),
            'month_filter' => $request->input('month_filter'),
            'search' => $request->input('search')
        ])->sum('total_amount');

        return Inertia::render('Coach/TrainingTransaction',[
            'trainingTransactions' => $trainingTransactions,
            'year_filter' => $request->year_filter,
            'month_filter' => $request->month_filter,
            'search' => $request->search,
            'totalEarnings' => $totalEarnings
        ]);
    }

    public function destroy(TrainingTransaction $trainingTransaction)
    {
        $trainingTransaction->delete();

        return redirect()->route('trainingTransactions.index')->with('success', 'Trainor deleted successfully.');
    }
}
