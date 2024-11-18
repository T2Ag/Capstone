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
    public function index()
    {
        $trainingTransactions = TrainingTransaction::with('training','training.coach', 'client')->get();

        return Inertia::render('Coach/TrainingTransaction',[
            'trainingTransactions' => $trainingTransactions
        ]);
    }

    public function destroy(TrainingTransaction $tt)
    {
        $tt->delete();

        return redirect()->route('trainingTransactions.index')->with('success', 'Trainor deleted successfully.');
    }
}
