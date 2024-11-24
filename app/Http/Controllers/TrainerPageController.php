<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\Training;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrainerPageController extends Controller
{
    public function trainerDashboard()
    {
        return Inertia::render('TrainerPage/TrainerIndex');
    }

    public function trainingList(Request $request)
    {

        // Get the authenticated user
        $user = $request->user();
        
        // Get the coach associated with this user
        $coach = Coach::where('user_id', $user->id)->first();
        
        // Get all trainings of this coach with their clients
        $trainings = Training::with(['clients', 'coach'])
        ->where('coach_id', $coach->id)
        ->get();

        return Inertia::render('TrainerPage/TrainingList',[
            'coach' => $coach,
            'trainings' => $trainings,
        ]);
    }
}
