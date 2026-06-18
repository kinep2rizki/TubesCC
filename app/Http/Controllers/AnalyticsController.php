<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $communityId = $request->input('community_id', 'all');
        $token = session('jwt_token') ?? '';
        
        $totalParticipants = 0;
        $successRate = 0;
        $avgAttendance = 0;
        $recentEvents = [];
        $growthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $uniqueData = [0, 0, 0, 0, 0, 0];
        $returningData = [0, 0, 0, 0, 0, 0];
        $certificateIssuedPercentage = 0;
        $issuedCertificates = 0;
        $pendingCertificates = 0;

        if ($communityId !== 'all') {
            $response = \Illuminate\Support\Facades\Http::withToken($token)
                ->get("http://127.0.0.1:8002/api/analytics/{$communityId}/advanced");
                
            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];
                
                $totalParticipants = $data['totalParticipants'] ?? 0;
                $successRate = $data['successRate'] ?? 0;
                $avgAttendance = $data['avgAttendance'] ?? 0;
                
                $growthLabels = $data['growthLabels'] ?? [];
                $uniqueData = $data['uniqueData'] ?? [];
                $returningData = $data['returningData'] ?? [];
                
                $certificateIssuedPercentage = $data['certificateIssuedPercentage'] ?? 0;
                $issuedCertificates = $data['issuedCertificates'] ?? 0;
                $pendingCertificates = $data['pendingCertificates'] ?? 0;
                
                // Convert arrays back to objects for Blade
                $recentEvents = collect($data['recentEvents'] ?? [])->map(fn($e) => (object) $e);
            }
        }

        return view('Pages.Analytics', compact(
            'communityId', 'totalParticipants', 'successRate', 'avgAttendance', 'recentEvents',
            'growthLabels', 'uniqueData', 'returningData',
            'certificateIssuedPercentage', 'issuedCertificates', 'pendingCertificates'
        ));
    }

    public function export(Request $request)
    {
        // Export logic is now handled via API endpoint in Project Service
        // The frontend will just redirect or hit the API directly.
        return redirect()->back();
    }
}
