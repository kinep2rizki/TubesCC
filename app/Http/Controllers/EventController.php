<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $activeCommunityId = session('active_community_id');

        // Calculate real stats from the database for the active community
        $totalEvents = \App\Models\Event::where('community_id', $activeCommunityId)->count();
        $totalParticipants = \App\Models\EventParticipant::whereHas('event', function($q) use ($activeCommunityId) {
            $q->where('community_id', $activeCommunityId);
        })->count();
        
        $attendedCount = \App\Models\Attendance::whereHas('participant.event', function($q) use ($activeCommunityId) {
            $q->where('community_id', $activeCommunityId);
        })->count();
        
        $attendanceRate = $totalParticipants > 0 ? round(($attendedCount / $totalParticipants) * 100, 1) : 0;
        
        $certificatesGenerated = \App\Models\Certificate::whereHas('participant.event', function($q) use ($activeCommunityId) {
            $q->where('community_id', $activeCommunityId);
        })->count();

        // Calculate Monthly Events Data (Last 6 Months)
        $monthlyEventsData = [];
        $monthlyEventsLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = \Carbon\Carbon::now()->subMonths($i);
            $monthlyEventsLabels[] = $month->format('M');
            $monthlyEventsData[] = \App\Models\Event::where('community_id', $activeCommunityId)
                ->whereMonth('start_date', $month->month)
                ->whereYear('start_date', $month->year)
                ->count();
        }

        // Calculate Attendance Trends (Last 5 Events)
        $recentEvents = \App\Models\Event::where('community_id', $activeCommunityId)
            ->orderBy('start_date', 'desc')
            ->take(5)
            ->get()
            ->reverse()
            ->values();
            
        $attendanceTrendsLabels = [];
        $attendanceTrendsRegistered = [];
        $attendanceTrendsAttended = [];
        
        foreach ($recentEvents as $idx => $ev) {
            $attendanceTrendsLabels[] = 'E' . ($idx + 1); // Or $ev->title
            $registered = $ev->participants()->where('status', 'Registered')->count();
            $attended = $ev->participants()->where('status', 'Attended')->count();
            $attendanceTrendsRegistered[] = $registered;
            $attendanceTrendsAttended[] = $attended;
        }

        // Pad if less than 5 events
        while (count($attendanceTrendsLabels) < 5) {
            $attendanceTrendsLabels[] = '-';
            $attendanceTrendsRegistered[] = 0;
            $attendanceTrendsAttended[] = 0;
        }

        // Get upcoming events
        $upcomingEvents = \App\Models\Event::where('community_id', $activeCommunityId)
                            ->orderBy('start_date', 'desc')->take(3)->get();

        // Get recent activities related to the active community
        $recentActivities = \App\Models\ActivityLog::with('user')
            ->where('community_id', $activeCommunityId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $activeCommunity = \App\Models\Community::find($activeCommunityId);

        return view('Pages.Dashboard', compact(
            'totalEvents',
            'totalParticipants',
            'attendanceRate',
            'certificatesGenerated',
            'upcomingEvents',
            'recentActivities',
            'activeCommunity',
            'monthlyEventsLabels',
            'monthlyEventsData',
            'attendanceTrendsLabels',
            'attendanceTrendsRegistered',
            'attendanceTrendsAttended'
        ));
    }

    public function manage(\Illuminate\Http\Request $request)
    {
        $activeCommunityId = session('active_community_id');
        
        $query = \App\Models\Event::where('community_id', $activeCommunityId)
                        ->with('community')
                        ->withCount('participants')
                        ->withCount(['participants as attended_count' => function ($query) {
                            $query->where('status', 'Attended');
                        }]);

        // Filter by Search Query
        if ($request->filled('search')) {
            $query->where('title', 'ilike', '%' . $request->search . '%');
        }

        // Filter by Status
        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        $eventsList = $query->orderBy('start_date', 'desc')->get();

        // Calculate attendance rate for each event
        foreach ($eventsList as $event) {
            $event->attendance_rate = $event->participants_count > 0 
                ? round(($event->attended_count / $event->participants_count) * 100) 
                : 0;
        }

        return view('Pages.EventManagement', compact('eventsList'));
    }

    public function show($id)
    {
        $event = \App\Models\Event::findOrFail($id);
        
        $activeCommunityId = session('active_community_id');
        if ($event->community_id != $activeCommunityId) {
            return redirect()->route('events')->with('error', 'The event belongs to a different community.');
        }
        
        // Use database queries instead of loading all participants into memory
        $registeredCount = $event->participants()->where('status', 'Registered')->count();
        $attendedCount = $event->participants()->where('status', 'Attended')->count();
        $waitlistedCount = $event->participants()->where('status', 'Waitlisted')->count();
        $notAttendingCount = $event->participants()->where('status', 'Not Attending')->count();
        
        $totalForConversion = $registeredCount + $attendedCount;
        $conversionRate = $event->capacity > 0 ? round(($totalForConversion / $event->capacity) * 100, 1) : 0;

        // Calculate demographics (Status distribution)
        $totalParticipants = $event->participants()->count();
        $attendedPct = $totalParticipants > 0 ? round(($attendedCount / $totalParticipants) * 100) : 0;
        $registeredPct = $totalParticipants > 0 ? round(($registeredCount / $totalParticipants) * 100) : 0;
        $otherPct = 100 - $attendedPct - $registeredPct;
        if ($totalParticipants == 0) $otherPct = 0;
        
        $topGroupPct = max($attendedPct, $registeredPct, $otherPct);
        $topGroupName = $topGroupPct == $attendedPct ? 'Attended' : ($topGroupPct == $registeredPct ? 'Registered' : 'Other');

        // Registration growth over last 7 days
        $registrationDates = [];
        $registrationCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i)->format('Y-m-d');
            $count = $event->participants()->whereDate('created_at', '<=', $date)->count();
            $registrationDates[] = \Carbon\Carbon::now()->subDays($i)->format('M d');
            $registrationCounts[] = $count;
        }

        // Fetch recent participants for the table
        $recentParticipants = $event->participants()->with('user')->latest()->take(5)->get();

        return view('Pages.EventDetail', compact(
            'event', 'registeredCount', 'attendedCount', 'waitlistedCount', 
            'conversionRate', 'attendedPct', 'registeredPct', 'otherPct', 
            'topGroupPct', 'topGroupName', 'registrationDates', 'registrationCounts',
            'recentParticipants'
        ));
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

        Event::create($validated);

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
