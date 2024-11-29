<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\TodoList;
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
            'latestMonthlyTransaction' => $latestMonthlyTransaction,
        ]);
    }

    public function userToDoList(Request $request)
    {
        $user = User::with('client')->find($request->user()->id);

        $todos = TodoList::where('client_id', $user->client->id)->get();

        return Inertia::render('UserPage/ToDoList', [
            'user' => $user,
            'todos' => $todos
        ]);
    }
}
