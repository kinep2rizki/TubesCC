<?php

namespace App\Http\Controllers;

use App\Models\Certificate;

class CertificateController extends Controller
{
    public function index($eventId)
    {
        $event = \App\Models\Event::findOrFail($eventId);
        
        $activeCommunityId = session('active_community_id');
        if ($event->community_id != $activeCommunityId) {
            return redirect()->route('events')->with('error', 'The event belongs to a different community.');
        }

        if (!auth()->user()->hasCommunityRole($activeCommunityId, ['Owner', 'Admin'])) {
            return abort(403, 'You do not have permission to manage certificates for this community.');
        }

        $allEvents = \App\Models\Event::where('community_id', $activeCommunityId)->get();
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

        $event = \App\Models\Event::findOrFail($eventId);
        
        $activeCommunityId = session('active_community_id');
        if ($event->community_id != $activeCommunityId) {
            return redirect()->route('events')->with('error', 'The event belongs to a different community.');
        }

        if (!auth()->user()->hasCommunityRole($activeCommunityId, ['Owner', 'Admin'])) {
            return abort(403, 'You do not have permission to generate certificates for this community.');
        }
        
        // Dispatch the job to run in the background
        \App\Jobs\GenerateCertificatesJob::dispatch($eventId, $validated['template']);

        return back()->with('success', 'Certificate generation has started in the background. Participants will receive a notification when it is ready.');
    }

    public function download($certificateId)
    {
        // Logic to download a specific certificate PDF
        return response()->download('path/to/certificate.pdf');
    }
}
