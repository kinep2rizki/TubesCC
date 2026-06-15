<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventParticipant;
use App\Models\Event;
use App\Models\User;

class ParticipantController extends Controller
{
    public function index($eventId)
    {
        $event = Event::findOrFail($eventId);
        $participants = EventParticipant::with('user')->where('event_id', $eventId)->get();
        return response()->json(['data' => $participants], 200);
    }

    public function store(Request $request, $eventId)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
        ]);

        $event = Event::findOrFail($eventId);

        $user = User::firstOrCreate(
            ['email' => $request->email],
            ['name' => $request->name, 'password' => bcrypt('password')]
        );

        $participant = EventParticipant::firstOrCreate([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'ticket_number' => 'TKT-' . strtoupper(uniqid()),
            'status' => 'Registered'
        ]);

        return response()->json(['message' => 'Participant added successfully', 'data' => $participant], 201);
    }

    public function export($eventId)
    {
        // Logic to generate CSV or return data for export
        return response()->json(['message' => 'Export initiated'], 200);
    }
}
