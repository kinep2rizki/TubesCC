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

    public function checkIn(Request $request)
    {
        $validated = $request->validate([
            'ticket_number' => 'required|string',
            'method' => 'required|string', // QR or Manual
        ]);

        // Logic to record attendance
        // Attendance::create([...]);

        return back()->with('success', 'Participant checked in successfully.');
    }
}
