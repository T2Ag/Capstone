<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
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

       return back();
   }

   public function update(TodoList $todo)
   {
       $todo->update([
           'is_completed' => request('is_completed'),
           'title' => request('title', $todo->title)
       ]);

       return back();
   }
}
