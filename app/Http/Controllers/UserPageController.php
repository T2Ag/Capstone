<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Log;
use App\Models\TodoList;
use App\Models\Training;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserPageController extends Controller
{
    public function userDashboard(Request $request)
    {
        $user = User::with('client')->find($request->user()->id);
        $totalGymVisits = $user->client ? $user->client->logs->count() : 0;

        $paymentMethod = $user->client ? $user->client->payment_method : null;

        $training = Training::with('coach')->find($user->client->training_id);

        // Get the latest monthly transaction for the user's client
        $latestMonthlyTransaction = $user->client
        ? $user->client->transactions()
            ->whereHas('payment_method', function ($query) {
                $query->where('type', 'monthly'); // Adjust this to match the exact column name and value
            })
            ->latest()
            ->first()
        : null;
        
        // Get the first announcement (oldest)
        $firstAnnouncement = Announcement::orderBy('created_at', 'desc')->first();
        // Get all announcements except the first one
        $announcements = Announcement::where('id', '!=', optional($firstAnnouncement)->id)
            ->orderBy('created_at', 'desc')
            ->paginate(3);
            
    
        return Inertia::render('UserPage/UserIndex', [
            'user' => $user,
            'totalGymVisits' => $totalGymVisits,
            'firstAnnouncement' => $firstAnnouncement,
            'announcements' => $announcements,
            'paymentMethod' => $paymentMethod,
            'training' => $training,
            'latestMonthlyTransaction' => $latestMonthlyTransaction,
        ]);
    }

    public function userToDoList(Request $request)
    {
        $user = User::with('client')->find($request->user()->id);

        // Check if the client exists
        if (!$user->client) {
            return Inertia::render('UserPage/ToDoList', [
                'user' => $user,
                'todos' => null 
            ]);
        }

        $todos = TodoList::where('client_id', $user->client->id)->get();

        // Load the training and its coach for the user's client
        $training = Training::with('coach')->find($user->client->training_id);

        return Inertia::render('UserPage/ToDoList', [
            'user' => $user,
            'todos' => $todos,
            'training' => $training,
            'success' => session('success')
        ]);
    }

    public function userHistory(Request $request)
    {
        $user = User::with('client')->find($request->user()->id);

        // Ensure the user's client relationship exists
        $client = $user->client;

        if (!$client) {
            return Inertia::render('UserPage/History', [
                'user' => $user,
                'logs' => [],
                'transactions' => [],
                'success' => session('success'),
            ]);
        }

        // Fetch logs for the user's client
        $logs = Log::with('client', 'payment_method')
        ->where('client_id', $client->id) 
        ->orderBy('date', 'desc')
        ->paginate(10)
        ->withQueryString();

        // Fetch transactions for the user's client
        $transactions = Transaction::with('client', 'payment_method')
        ->where('client_id', $client->id) 
        ->orderBy('date', 'desc')
        ->paginate(10)
        ->withQueryString();
        
        return Inertia::render('UserPage/History', [
            'user' => $user,
            'logs' => $logs,
            'transactions' => $transactions,
            'success' => session('success')
        ]);
    }

    public function store(Request $request)
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
 
        return redirect()->back()->with('success', 'To Do List added successfully');
    }

    public function update(TodoList $todo)
    {
        $todo->update([
            'is_completed' => request('is_completed'),
            'title' => request('title', $todo->title)
        ]);
 
        return redirect()->back()->with('success', 'To Do List updated successfully');
    }

    public function destroy(TodoList $todo)
    {
        $todo->delete();
        return redirect()->back()->with('success', 'To Do List deleted successfully');
    }
}
