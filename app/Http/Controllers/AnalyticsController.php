<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        $totalParticipants = \App\Models\EventParticipant::count();
        $totalAttendances = \App\Models\Attendance::count();
        $avgAttendance = $totalParticipants > 0 ? ($totalAttendances / $totalParticipants) * 100 : 0;
        
        $completedEvents = \App\Models\Event::where('status', 'Completed')->count();
        $totalEvents = \App\Models\Event::count();
        $successRate = $totalEvents > 0 ? ($completedEvents / $totalEvents) * 100 : 0;

        $recentEvents = \App\Models\Event::withCount('participants')->latest()->take(5)->get();

        return view('Pages.Analytics', compact('totalParticipants', 'avgAttendance', 'successRate', 'recentEvents'));
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|in:pdf,csv,excel',
            'data_points' => 'required|array',
        ]);

        // Logic to generate and download analytics report based on selected options
        // return Excel::download(new AnalyticsExport($validated), 'analytics.' . $validated['format']);

        return back()->with('success', 'Report export started.');
    }
}
