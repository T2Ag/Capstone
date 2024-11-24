<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->paginate(5);

        return Inertia::render('Announcement/Index', [
            'announcements' => $announcements
        ]);
    }

    public function store(Request $request)
    {
    $validated = $request->validate([
        'title' => 'required|max:255',
        'content' => 'required',
    ]);

    Announcement::create([
        'title' => $validated['title'],
        'content' => $validated['content'],
        'created_at' => now()
    ]);

    return redirect()->back()->with('success', 'Announcement created successfully');
    }

    
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $announcement->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return redirect()->back()->with('success', 'Announcement updated successfully');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        
        return redirect()->back()->with('success', 'Announcement deleted successfully');
    }
}
