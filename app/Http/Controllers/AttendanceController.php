<?php

namespace App\Http\Controllers;

use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index($eventId)
    {
        $event = \App\Models\Event::findOrFail($eventId);
        $attendances = \App\Models\Attendance::with(['participant.user'])
            ->whereHas('participant', function($query) use ($eventId) {
                $query->where('event_id', $eventId);
            })->latest()->get();
            
        $presentCount = $attendances->count();
        $expectedCount = \App\Models\EventParticipant::where('event_id', $eventId)->count();
        
        return view('Pages.Attendance', compact('event', 'attendances', 'presentCount', 'expectedCount'));
    }

    public function store(\Illuminate\Http\Request $request, $eventId)
    {
        $request->validate(['email' => 'required|email']);

        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) return back()->withErrors(['email' => 'User not found.']);

        $participant = \App\Models\EventParticipant::where('event_id', $eventId)
            ->where('user_id', $user->id)
            ->first();

        if (!$participant) return back()->withErrors(['email' => 'User is not registered for this event.']);

        \App\Models\Attendance::firstOrCreate([
            'event_participant_id' => $participant->id,
            'check_in_time' => now(),
            'check_in_method' => 'Manual'
        ]);

        $participant->update(['status' => 'Attended']);

        return back()->with('success', 'Participant checked in successfully.');
    }
}
