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
        $token = session('jwt_token') ?? '';
        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->get("http://127.0.0.1:8002/api/events/{$id}");
            
        if ($response->status() === 401) {
            return redirect('/login')->with('error', 'Session expired. Please login again.');
        }

        if (!$response->successful()) {
            abort(404, 'Event not found. (Status: ' . $response->status() . ')');
        }
        
        $data = $response->json()['data'] ?? [];
        $event = json_decode(json_encode($data));
        
        return view('Pages.EventDetail', compact('id', 'event'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'community_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $token = session('jwt_token') ?? '';
        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->post('http://127.0.0.1:8002/api/events', $validated);

        if ($response->status() === 401) {
            return redirect('/login')->with('error', 'Session expired. Please login again.');
        }

        if (!$response->successful()) {
            $errorMsg = $response->json('message') ?? 'Unknown error';
            $detailedError = $response->json('error') ?? '';
            abort($response->status(), "Gagal membuat event: {$errorMsg}. Detail: {$detailedError}");
        }

        return back()->with('success', 'Event created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $token = session('jwt_token') ?? '';
        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->put("http://127.0.0.1:8002/api/events/{$id}", $validated);

        if ($response->status() === 401) {
            return redirect('/login')->with('error', 'Session expired. Please login again.');
        }

        if (!$response->successful()) {
            abort($response->status(), 'Gagal mengupdate event: ' . ($response->json('message') ?? 'Unknown error'));
        }

        return back()->with('success', 'Event updated successfully.');
    }
}
