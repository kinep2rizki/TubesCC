<?php

namespace App\Http\Controllers;

use App\Models\Certificate;

class CertificateController extends Controller
{
    public function index($eventId)
    {
        $event = \App\Models\Event::findOrFail($eventId);
        $allEvents = \App\Models\Event::all();
        $participants = \App\Models\EventParticipant::with('user')
            ->where('event_id', $eventId)
            ->where('status', 'Attended')
            ->get();
            
        return view('Pages.Certificate', compact('event', 'allEvents', 'participants'));
    }

    public function generate(Request $request, $eventId)
    {
        $validated = $request->validate([
            'template' => 'required|string',
        ]);

        // Logic to batch generate certificates for attended participants
        return back()->with('success', 'Certificates generated successfully.');
    }

    public function download($certificateId)
    {
        // Logic to download a specific certificate PDF
        return response()->download('path/to/certificate.pdf');
    }
}
