<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CoachController extends Controller
{
    public function index()
    {
        $coaches = Coach::with('user')->paginate(15);
     
        return Inertia::render('Coach/List',[
            'coaches' => $coaches,
        ]);
    }
}
