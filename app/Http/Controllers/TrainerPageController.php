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
            'success' => session('success')
        ]);
    }

    //VIEW THE inside TRAININGs
    public function view($id)
    {
        $training = Training::with('coach', 'clients')->findORFail($id);
        $clients = Client::get();

        return Inertia::render('TrainerPage/TrainingView',[
            'training' => $training,
            'clients' => $clients,
            'success' => session('success')

        ]);
    }

    public function trainingStore(Request $request)
    {
        $validatedData = $request->validate([

            'coach_id' => 'required|exists:coaches,id',
            'name' => 'required|string',
            'price' => 'required|numeric',

        ]);

        Training::create($validatedData);

        return back()->with('success', 'Training created successfully.');
    }

    public function trainingUpdate(Request $request, Training $training)
    {
        $validatedData = $request->validate([

            'coach_id' => 'required|exists:coaches,id',
            'name' => 'required|string',
            'price' => 'required|numeric',

        ]);

        $training->update($validatedData);

        return back()->with('success', 'Training updated successfully.');
    }

    public function trainingDestroy(Training $training)
    {
        $training->delete();

        return back()->with('success', 'Training deleted successfully.');
    }


    //ADD THE CONTROLLER FOR GETTING THE DATA OF THE TRAINING MOVE IT HERE SINCE WE NEED TO SEPARATE THE TRINER AND ADMIN
    //THE ADD EDIT AND UPDATE HERE

    //ToDoList Controller
    public function clientToDoList(Client $client)
    {
        $todos = TodoList::where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->get();
    
        return Inertia::render('TrainerPage/ToDoList', [
            'todos' => $todos,
            'client' => $client,
            'success' => session('success')
        ]);
    }

    public function toDoStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id'
        ]);
 
        TodoList::create([
            'title' => $request->title,
            'client_id' => $request->client_id,
            'is_completed' => false
        ]);
 
        return redirect()->back()->with('success', 'To Do List created successfully');
    }
 
    public function toDoUpdate(TodoList $todo)
    {
        $todo->update([
            'is_completed' => request('is_completed'),
            'title' => request('title', $todo->title)
        ]);
 
        return redirect()->back()->with('success', 'To Do List updated successfully');
    }

    public function toDoDestroy(TodoList $todo)
    {
        $todo->delete();
        return redirect()->back()->with('success', 'To Do List deleted successfully');
    }

    public function addClientToTraining(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'training_id' => 'required|exists:trainings,id',
        ]);
    
        $client = Client::findOrFail($validatedData['client_id']);
        $training = Training::findOrFail($validatedData['training_id']);

        if ($client->training_id == $validatedData['training_id']) {
            return back()->withErrors(['client_id' => 'The client is already in this training.']);
        }
    
        $client->training_id = $validatedData['training_id'];
        $client->save();

        // Create a TrainingTransaction
        $startDate = now();
        $endDate = now()->addMonth();
        $transactionDate = now(); 

        TrainingTransaction::create([
            'client_id' => $client->id,
            'training_id' => $training->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'transaction_date' => $transactionDate,
            'total_amount' => $training->price,
        ]);
    
        return back()->with('success', 'Client added to training successfully.');
    }

    public function removeClientFromTraining(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'training_id' => 'required|exists:trainings,id',
        ]);
    
        $client = Client::findOrFail($validatedData['client_id']);
    
        if ($client->training_id !== $validatedData['training_id']) {
            return back()->withErrors(['client_id' => 'The client is not part of this training.']);
        }
    
        $client->training_id = null;
        $client->save();
    
        return back()->with('success', 'Client removed from training successfully.');
    }
}
