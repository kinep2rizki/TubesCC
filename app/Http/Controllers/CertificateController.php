<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CertificateController extends Controller
{
    public function index($eventId)
    {
        $token = session('jwt_token') ?? '';
        
        // 1. Get Event Detail
        $resEvent = Http::withToken($token)->get("http://127.0.0.1:8002/api/events/{$eventId}");
        
        if ($resEvent->status() === 401) {
            return redirect('/login')->with('error', 'Session expired. Please login again.');
        }

        if (!$resEvent->successful()) {
            abort(404, 'Event not found.');
        }
        
        $eventData = $resEvent->json()['data'] ?? [];
        $event = json_decode(json_encode($eventData));

        // 2. Get All Events
        $resEvents = Http::withToken($token)->get("http://127.0.0.1:8002/api/events", ['per_page' => 100]);
        $allEventsData = $resEvents->json()['data']['data'] ?? [];
        $allEvents = json_decode(json_encode($allEventsData));

        // 3. Get Participants (Attended)
        $resParticipants = Http::withToken($token)->get("http://127.0.0.1:8002/api/events/{$eventId}/participants", [
            'status' => 'Attended',
            'per_page' => 1000
        ]);
        $participantsData = $resParticipants->json()['data']['data'] ?? [];
        
        $participants = array_map(function($p) {
            $obj = new \stdClass();
            $obj->id = $p['id'];
            $obj->user = new \stdClass();
            $obj->user->name = $p['user_detail']['name'] ?? 'Unknown';
            $obj->user->email = $p['user_detail']['email'] ?? 'Unknown';
            return $obj;
        }, $participantsData);
            
        return view('Pages.Certificate', compact('event', 'allEvents', 'participants'));
    }
}
