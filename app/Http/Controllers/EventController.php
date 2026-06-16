<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return view('Pages.Dashboard');
    }

    public function manage(\Illuminate\Http\Request $request)
    {
        return view('Pages.EventManagement');
    }

    public function show($id)
    {
        return view('Pages.EventDetail', compact('id'));
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

        if (!auth()->user()->canManageEvent($validated['community_id'])) {
            abort(403, 'Unauthorized to create events for this community.');
        }

        $event = Event::create($validated);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'community_id' => $validated['community_id'],
            'action' => 'created_event',
            'description' => "created a new event '{$event->title}'",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Event created successfully.');
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        if (!auth()->user()->canManageEvent($event->community_id)) {
            abort(403, 'Unauthorized to edit this event.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $event->update($validated);

        return back()->with('success', 'Event updated successfully.');
    }
}
