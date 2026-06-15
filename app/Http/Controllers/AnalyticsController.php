<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        $activeCommunityId = session('active_community_id');
        if (!$activeCommunityId) {
            abort(403, 'No active community selected.');
        }

        if (!auth()->user()->hasRole('Super Admin') && !auth()->user()->hasCommunityRole($activeCommunityId, 'Owner')) {
            abort(403, 'Only Community Owners and Super Admins can access analytics.');
        }

        // Base query for events in the active community
        $communityEventsQuery = \App\Models\Event::where('community_id', $activeCommunityId);

        $totalParticipants = \App\Models\EventParticipant::whereHas('event', function($q) use ($activeCommunityId) {
            $q->where('community_id', $activeCommunityId);
        })->count();
        
        $totalAttendances = \App\Models\Attendance::whereHas('participant.event', function($q) use ($activeCommunityId) {
            $q->where('community_id', $activeCommunityId);
        })->count();
        
        $avgAttendance = $totalParticipants > 0 ? ($totalAttendances / $totalParticipants) * 100 : 0;
        
        // Count finished events based on end_date (since getStatusAttribute returns 'Finished')
        $completedEvents = (clone $communityEventsQuery)->where('end_date', '<', now())->count();
        $totalEvents = (clone $communityEventsQuery)->count();
        $successRate = $totalEvents > 0 ? ($completedEvents / $totalEvents) * 100 : 0;

        $recentEvents = (clone $communityEventsQuery)->withCount('participants')->latest()->take(5)->get();

        // Certificates Data
        $issuedCertificates = \App\Models\Certificate::whereHas('participant.event', function($q) use ($activeCommunityId) {
            $q->where('community_id', $activeCommunityId);
        })->count();
        // Since we don't track "Pending" certificates explicitly in a table, we can estimate it as total participants minus issued.
        $pendingCertificates = max(0, $totalParticipants - $issuedCertificates);
        $certificateIssuedPercentage = $totalParticipants > 0 ? round(($issuedCertificates / $totalParticipants) * 100) : 0;

        // Participation Growth Data (Unique vs Returning) - Last 6 Months
        $growthLabels = [];
        $uniqueData = [];
        $returningData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = \Carbon\Carbon::now()->subMonths($i);
            $growthLabels[] = $month->format('M');
            
            // Get participants for events starting this month
            $participantsThisMonth = \App\Models\EventParticipant::whereHas('event', function($q) use ($activeCommunityId, $month) {
                $q->where('community_id', $activeCommunityId)
                  ->whereMonth('start_date', $month->month)
                  ->whereYear('start_date', $month->year);
            })->get();

            // Total participants this month
            $totalThisMonth = $participantsThisMonth->count();
            // Approximating returning (e.g. if the user_id appeared before this month)
            // For simplicity, let's just make unique = total, returning = 0 for now unless we do heavy querying.
            // Actually, let's query users who have participated in an earlier event.
            $returningCount = 0;
            foreach ($participantsThisMonth as $p) {
                if ($p->user_id) {
                    $pastParticipation = \App\Models\EventParticipant::where('user_id', $p->user_id)
                        ->where('id', '<', $p->id)
                        ->whereHas('event', function($q) use ($activeCommunityId) {
                            $q->where('community_id', $activeCommunityId);
                        })->exists();
                    if ($pastParticipation) {
                        $returningCount++;
                    }
                }
            }
            $uniqueCount = max(0, $totalThisMonth - $returningCount);

            $uniqueData[] = $uniqueCount;
            $returningData[] = $returningCount;
        }

        return view('Pages.Analytics', compact(
            'totalParticipants', 'avgAttendance', 'successRate', 'recentEvents',
            'issuedCertificates', 'pendingCertificates', 'certificateIssuedPercentage',
            'growthLabels', 'uniqueData', 'returningData'
        ));
    }

    public function export(Request $request)
    {
        $activeCommunityId = session('active_community_id');
        if (!$activeCommunityId) {
            abort(403, 'No active community selected.');
        }

        if (!auth()->user()->hasRole('Super Admin') && !auth()->user()->hasCommunityRole($activeCommunityId, 'Owner')) {
            abort(403, 'Only Community Owners and Super Admins can access analytics.');
        }

        $format = $request->input('format', 'csv');
        $community = \App\Models\Community::find($activeCommunityId);
        $events = \App\Models\Event::where('community_id', $activeCommunityId)
            ->withCount('participants')
            ->withCount(['participants as attended_count' => function ($query) {
                $query->where('status', 'Attended');
            }])
            ->get();

        $data = [];
        foreach ($events as $event) {
            $attendanceRate = $event->participants_count > 0 ? round(($event->attended_count / $event->participants_count) * 100) : 0;
            $data[] = [
                'Event Title' => $event->title,
                'Start Date' => \Carbon\Carbon::parse($event->start_date)->format('Y-m-d H:i'),
                'Total Participants' => $event->participants_count,
                'Attended' => $event->attended_count,
                'Attendance Rate (%)' => $attendanceRate,
                'Status' => $event->status
            ];
        }

        $filename = 'analytics_report_' . date('Y-m-d');

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('Pages.AnalyticsExportPdf', compact('data', 'community'));
            return $pdf->download($filename . '.pdf');
        } else {
            // Both CSV and Excel will use CSV format since we can't install PhpSpreadsheet due to missing extensions
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use($data) {
                $file = fopen('php://output', 'w');
                if (count($data) > 0) {
                    fputcsv($file, array_keys($data[0]));
                    foreach ($data as $row) {
                        fputcsv($file, $row);
                    }
                } else {
                    fputcsv($file, ['No data available']);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
    }
}
