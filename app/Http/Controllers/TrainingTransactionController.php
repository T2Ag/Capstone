<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Trainor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrainingTransactionController extends Controller
{
    public function index()
    {
        $trainors = Trainor::with(['user', 'client' => function ($query) {
            $query->whereHas('trainingTransactions', function ($query) {
                $query->where('start_date', '<=', Carbon::now())
                      ->where('end_date', '>=', Carbon::now());
            });
        }])
        ->withCount(['client as total_active_clients' => function ($query) {
            $query->whereHas('trainingTransactions', function ($query) {
                $query->where('start_date', '<=', Carbon::now())
                      ->where('end_date', '>=', Carbon::now());
            });
        }])
        ->withCount('client as total_clients')
        ->paginate(15);
    
        $stats = [
            'total_trainors' => Trainor::count(),
            'total_clients' => Client::count(),
        ];
    
        return Inertia::render('Trainor/CoachingList', [
            'trainors' => $trainors,
            'stats' => $stats,
        ]);
    }
}
