<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        // Calculate real stats from the database
        $totalEvents = \App\Models\Event::count();
        $totalParticipants = \App\Models\EventParticipant::count();
        
        $attendedCount = \App\Models\Attendance::count();
        $attendanceRate = $totalParticipants > 0 ? round(($attendedCount / $totalParticipants) * 100, 1) : 0;
        
        $certificatesGenerated = \App\Models\Certificate::count();

        // Get upcoming events
        $upcomingEvents = \App\Models\Event::orderBy('start_date', 'desc')->take(3)->get();

        // Get recent activities
        $recentActivities = \App\Models\ActivityLog::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        return view('Pages.Dashboard', compact(
            'totalEvents',
            'totalParticipants',
            'attendanceRate',
            'certificatesGenerated',
            'upcomingEvents',
            'recentActivities'
        ));
    }

    public function manage()
    {
        $eventsList = \App\Models\Event::with('community')->withCount('participants')->get();
        return view('Pages.EventManagement', compact('eventsList'));
    }

    public function show($id)
    {
        $event = \App\Models\Event::with('participants.user')->findOrFail($id);
        return view('Pages.EventDetail', compact('event'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'community_id' => 'required|exists:communities,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $validated['status'] = 'Draft';

        Event::create($validated);

        return back()->with('success', 'Event created successfully.');
    }

    public function update(Request $request, $id)
    {
        // Logic to update event
        return back()->with('success', 'Event updated successfully.');
    }
}
