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

        $announcements = Announcement::orderBy('created_at', 'desc')->paginate(3);
    
        return Inertia::render('UserPage/UserIndex', [
            'user' => $user,
            'totalGymVisits' => $totalGymVisits,
            'announcements' => $announcements
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
