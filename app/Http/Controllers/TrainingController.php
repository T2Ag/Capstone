<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Coach;
use App\Models\Training;
use App\Models\TrainingTransaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::with('coach', 'clients')->paginate(10);

        $coaches = Coach::get();

        return Inertia::render('Coach/Training',[
            'trainings' => $trainings,
            'coaches' => $coaches
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([

            'coach_id' => 'required|exists:coaches,id',
            'name' => 'required|string',
            'price' => 'required|numeric',

        ]);

        Training::create($validatedData);

        return back()->with('success', 'Trainor created successfully.');
    }

    public function view($id)
    {
        $training = Training::with('coach', 'clients')->findORFail($id);
        $clients = Client::get();

        return Inertia::render('Coach/TrainingView',[
            'training' => $training,
            'clients' => $clients
        ]);
    }

    public function update(Request $request, Training $training)
    {
        $validatedData = $request->validate([

            'coach_id' => 'required|exists:coaches,id',
            'name' => 'required|string',
            'price' => 'required|numeric',

        ]);

        $training->update($validatedData);

        return back()->with('success', 'Client added to training successfully.');
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

    public function destroy(Training $training)
    {
        $training->delete();

        return back()->with('success', 'Trainor deleted successfully.');
    }


}
