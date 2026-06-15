<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\EventParticipant;
use App\Models\User;

class AttendanceController extends Controller
{
    public function index($eventId)
    {
        $attendances = Attendance::with(['participant.user'])
            ->whereHas('participant', function($query) use ($eventId) {
                $query->where('event_id', $eventId);
            })->latest()->get();
            
        $presentCount = $attendances->count();
        $expectedCount = EventParticipant::where('event_id', $eventId)->count();
        
        return response()->json([
            'data' => $attendances,
            'stats' => [
                'presentCount' => $presentCount,
                'expectedCount' => $expectedCount
            ]
        ], 200);
    }

    public function checkIn(Request $request, $eventId)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) return response()->json(['error' => 'User not found.'], 404);

        $participant = EventParticipant::where('event_id', $eventId)
            ->where('user_id', $user->id)
            ->first();

        if (!$participant) return response()->json(['error' => 'User is not registered for this event.'], 404);

        $attendance = Attendance::firstOrCreate([
            'event_participant_id' => $participant->id,
            'check_in_time' => now(),
            'check_in_method' => 'Manual'
        ]);

        $participant->update(['status' => 'Attended']);

        return response()->json(['message' => 'Participant checked in successfully', 'data' => $attendance], 200);
    }
}
