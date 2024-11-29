<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Client;
use App\Models\Coach;
use App\Models\TodoList;
use App\Models\Training;
use App\Models\TrainingTransaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrainerPageController extends Controller
{
    public function trainerDashboard(Request $request)
    {
        $user = $request->user();

        $coach = $user->coach;

        $earnings = TrainingTransaction::whereHas('training', function ($query) use ($coach) {
            $query->where('coach_id', $coach->id);
        })->sum('total_amount');
        
        $totalStudents = Training::whereHas('coach', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->withCount('clients')
        ->get()
        ->sum('clients_count');

        $totalTrainings = Training::whereHas('coach', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        // Get the first announcement (oldest)
        $firstAnnouncement = Announcement::orderBy('created_at', 'desc')->first();
        // Get all announcements except the first one
        $announcements = Announcement::where('id', '!=', optional($firstAnnouncement)->id)
            ->orderBy('created_at', 'desc')
            ->paginate(3);
    
        return Inertia::render('TrainerPage/TrainerIndex', [
            'totalStudents' => $totalStudents,
            'totalTrainings' => $totalTrainings,
            'announcements' => $announcements,
            'earnings' => $earnings,
            'firstAnnouncement' => $firstAnnouncement,

        ]);
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

    public function view($id)
    {
        $training = Training::with('coach', 'clients')->findORFail($id);
        $clients = Client::get();

        return Inertia::render('TrainerPage/TrainingView',[
            'training' => $training,
            'clients' => $clients
        ]);
    }

    public function clientToDoList(Client $client)
    {
        $todos = TodoList::where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->get();
    
        return Inertia::render('TrainerPage/ToDoList', [
            'todos' => $todos,
            'client' => $client
        ]);
    }
}
