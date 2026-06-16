<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function index(Request $request, $eventId)
    {
        return view('Pages.ParticipantsPage', compact('eventId'));
    }
}
