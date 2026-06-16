<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        // Try to get certificates locally from Tubes DB
        // Assuming certificates are stored locally or we just bypass it
        // Actually, let's just query what's available
        $certificatesByCommunity = [];

        // Check if user is authenticated (frontend session)
        $user = auth()->user();
        if ($user) {
            try {
                $certificates = \App\Models\Certificate::whereHas('participant', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->with('participant.event.community')->get();

                foreach ($certificates as $cert) {
                    $communityName = $cert->participant->event->community->name ?? 'Unknown Community';
                    $certificatesByCommunity[$communityName][] = $cert;
                }
            } catch (\Exception $e) {
                // Ignore DB error if tables don't exist in monolith yet
            }
        }

        return view('Pages.PlatformSettings', compact('certificatesByCommunity'));
    }
}
