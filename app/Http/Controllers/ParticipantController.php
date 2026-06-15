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

    public function store(\Illuminate\Http\Request $request, $eventId)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'status' => 'required|in:Registered,Attended',
        ]);

        $user = \App\Models\User::firstOrCreate(
            ['email' => $validated['email']],
            ['name' => $validated['name'], 'password' => \Illuminate\Support\Facades\Hash::make('password')]
        );

        EventParticipant::firstOrCreate([
            'event_id' => $eventId,
            'user_id' => $user->id,
        ], [
            'status' => $validated['status']
        ]);

        return back()->with('success', 'Participant added successfully.');
    }

    public function export($eventId)
    {
        // Logic to export participants to CSV
        return response()->download('path/to/csv');
    }
}
