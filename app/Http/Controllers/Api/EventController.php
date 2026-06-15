<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('community')->latest()->get();
        return response()->json(['data' => $events], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'community_id' => 'required|exists:communities,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
        ]);

        $validated['status'] = 'Draft';
        $event = Event::create($validated);

        return response()->json(['message' => 'Event created successfully', 'data' => $event], 201);
    }

    public function show($id)
    {
        $event = Event::with(['community'])->findOrFail($id);
        return response()->json(['data' => $event], 200);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $validated = $request->validate([
            'title' => 'string|max:255',
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date',
            'location' => 'string|max:255',
            'status' => 'string|in:Draft,Published,Completed',
        ]);

        $event->update($validated);

        return response()->json(['message' => 'Event updated successfully', 'data' => $event], 200);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(['message' => 'Event deleted successfully'], 200);
    }
}
