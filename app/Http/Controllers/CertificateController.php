<?php

namespace App\Http\Controllers;

use App\Models\Certificate;

class CertificateController extends Controller
{
    public function index($eventId)
    {
        $event = \App\Models\Event::findOrFail($eventId);
        return view('Pages.Certificate', compact('event'));
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
