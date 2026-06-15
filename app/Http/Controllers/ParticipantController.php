<?php

namespace App\Http\Controllers;

use App\Models\EventParticipant;

class ParticipantController extends Controller
{
    public function index($eventId)
    {
        $event = \App\Models\Event::findOrFail($eventId);
        $participants = \App\Models\EventParticipant::with('user')->where('event_id', $eventId)->get();
        return view('Pages.ParticipantsPage', compact('event', 'participants'));
    }

    public function store(Request $request, $eventId)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
        ]);

        // Logic to register a participant to an event
        return back()->with('success', 'Participant added successfully.');
    }

    public function export($eventId)
    {
        // Logic to export participants to CSV
        return response()->download('path/to/csv');
    }
}
