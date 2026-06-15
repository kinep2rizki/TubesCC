@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative rounded-xl overflow-hidden border border-outline-variant/30 bg-surface-container shadow-sm group">
    <div class="absolute inset-0 bg-cover bg-center opacity-20 transition-opacity duration-700 group-hover:opacity-30" style="background-image: url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=2070&auto=format&fit=crop');"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-background via-background/80 to-transparent"></div>
    <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-primary/10 to-transparent"></div>
    <div class="relative z-10 p-lg lg:p-2xl flex flex-col lg:flex-row items-start lg:items-center justify-between gap-lg">
        <div>
            <div class="flex items-center gap-sm mb-sm">
                <span class="px-2 py-1 bg-primary/10 text-primary border border-primary/20 rounded font-label-caps text-[10px] tracking-wider uppercase backdrop-blur-sm">System Overview</span>
                <span class="flex items-center gap-1 text-on-surface-variant font-label-caps text-[10px]">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Systems Operational
                </span>
            </div>
            <h2 class="font-display-lg-mobile lg:font-display-lg text-on-surface mb-sm">PETA</h2>
            <p class="font-headline-sm text-headline-sm text-on-surface-variant max-w-xl">Platform Event Teknologi Aktivitas. Monitor, manage, and scale your technology events with precision.</p>
        </div>
        <div class="flex gap-md">
            <button class="bg-primary text-on-primary font-label-caps text-label-caps px-lg py-sm rounded-lg hover:bg-primary/90 transition-colors shadow-[0_0_20px_rgba(173,198,255,0.3)] hover:shadow-[0_0_25px_rgba(173,198,255,0.5)] active:scale-95 flex items-center gap-sm">
                <span class="material-symbols-outlined text-[18px]">add_circle</span> New Event
            </button>
            <button class="bg-transparent border border-outline-variant text-on-surface font-label-caps text-label-caps px-lg py-sm rounded-lg hover:bg-surface-variant transition-colors active:scale-95">
                Generate Report
            </button>
        </div>
    </div>
</section>

<!-- KPI Grid -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md">
    <!-- KPI 1 -->
    <div class="bg-surface-container border border-outline-variant/30 rounded-xl p-md flex flex-col gap-sm relative overflow-hidden group hover:border-primary/50 transition-colors">
        <div class="absolute -right-4 -top-4 w-16 h-16 bg-primary/5 rounded-full blur-xl group-hover:bg-primary/10 transition-colors"></div>
        <div class="flex justify-between items-start">
            <span class="text-on-surface-variant font-body-sm">Total Events</span>
            <div class="p-1.5 bg-surface-variant rounded-md text-on-surface">
                <span class="material-symbols-outlined text-[18px]">event_note</span>
            </div>
        </div>
        <div class="flex items-baseline gap-sm">
            <span class="text-3xl font-bold text-on-surface tracking-tight">{{ number_format($totalEvents) }}</span>
        </div>
        <div class="flex items-center gap-xs text-xs">
            <span class="text-emerald-400 flex items-center bg-emerald-400/10 px-1 rounded"><span class="material-symbols-outlined text-[12px]">trending_up</span> 12.5%</span>
            <span class="text-outline">vs last month</span>
        </div>
    </div>
    <!-- KPI 2 -->
    <div class="bg-surface-container border border-outline-variant/30 rounded-xl p-md flex flex-col gap-sm relative overflow-hidden group hover:border-primary/50 transition-colors">
        <div class="absolute -right-4 -top-4 w-16 h-16 bg-primary/5 rounded-full blur-xl group-hover:bg-primary/10 transition-colors"></div>
        <div class="flex justify-between items-start">
            <span class="text-on-surface-variant font-body-sm">Active Participants</span>
            <div class="p-1.5 bg-surface-variant rounded-md text-on-surface">
                <span class="material-symbols-outlined text-[18px]">group</span>
            </div>
        </div>
        <div class="flex items-baseline gap-sm">
            <span class="text-3xl font-bold text-on-surface tracking-tight">{{ number_format($totalParticipants) }}</span>
        </div>
        <div class="flex items-center gap-xs text-xs">
            <span class="text-emerald-400 flex items-center bg-emerald-400/10 px-1 rounded"><span class="material-symbols-outlined text-[12px]">trending_up</span> 8.1%</span>
            <span class="text-outline">vs last month</span>
        </div>
    </div>
    <!-- KPI 3 -->
    <div class="bg-surface-container border border-outline-variant/30 rounded-xl p-md flex flex-col gap-sm relative overflow-hidden group hover:border-primary/50 transition-colors">
        <div class="absolute -right-4 -top-4 w-16 h-16 bg-primary/5 rounded-full blur-xl group-hover:bg-primary/10 transition-colors"></div>
        <div class="flex justify-between items-start">
            <span class="text-on-surface-variant font-body-sm">Attendance Rate</span>
            <div class="p-1.5 bg-surface-variant rounded-md text-on-surface">
                <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
            </div>
        </div>
        <div class="flex items-baseline gap-sm">
            <span class="text-3xl font-bold text-on-surface tracking-tight">{{ $attendanceRate }}%</span>
        </div>
        <div class="flex items-center gap-xs text-xs">
            <span class="text-error flex items-center bg-error/10 px-1 rounded"><span class="material-symbols-outlined text-[12px]">trending_down</span> 1.2%</span>
            <span class="text-outline">vs last month</span>
        </div>
    </div>
    <!-- KPI 4 -->
    <div class="bg-surface-container border border-outline-variant/30 rounded-xl p-md flex flex-col gap-sm relative overflow-hidden group hover:border-primary/50 transition-colors">
        <div class="absolute -right-4 -top-4 w-16 h-16 bg-primary/5 rounded-full blur-xl group-hover:bg-primary/10 transition-colors"></div>
        <div class="flex justify-between items-start">
            <span class="text-on-surface-variant font-body-sm">Certificates Generated</span>
            <div class="p-1.5 bg-surface-variant rounded-md text-on-surface">
                <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
            </div>
        </div>
        <div class="flex items-baseline gap-sm">
            <span class="text-3xl font-bold text-on-surface tracking-tight">{{ number_format($certificatesGenerated) }}</span>
        </div>
        <div class="flex items-center gap-xs text-xs">
            <span class="text-emerald-400 flex items-center bg-emerald-400/10 px-1 rounded"><span class="material-symbols-outlined text-[12px]">trending_up</span> 24.3%</span>
            <span class="text-outline">vs last month</span>
        </div>
    </div>
</section>

<!-- Main Layout Split: Charts & Feed -->
<div class="flex flex-col lg:flex-row gap-lg">
    <!-- Left Column: Charts Area -->
    <div class="flex-1 flex flex-col gap-lg">
        <!-- Chart Card: Monthly Events Area Chart -->
        <div class="bg-surface-container border border-outline-variant/30 rounded-xl p-md flex flex-col h-80">
            <div class="flex justify-between items-center mb-md border-b border-outline-variant/20 pb-sm">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Monthly Events</h3>
                <button class="text-on-surface-variant hover:text-on-surface text-sm flex items-center gap-xs">
                    Year to Date <span class="material-symbols-outlined text-[16px]">expand_more</span>
                </button>
            </div>
            
            <div class="flex-1 relative w-full h-full pt-sm">
                <!-- Canvas for Chart.js -->
                <canvas id="monthlyEventsChart"></canvas>
            </div>
        </div>

        <!-- Chart Card: Attendance Trends -->
        <div class="bg-surface-container border border-outline-variant/30 rounded-xl p-md flex flex-col h-72">
            <div class="flex justify-between items-center mb-md border-b border-outline-variant/20 pb-sm">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Attendance Trends</h3>
                <div class="flex gap-sm">
                    <div class="flex items-center gap-xs"><span class="w-2 h-2 rounded-full bg-primary"></span><span class="text-xs text-on-surface-variant">Registered</span></div>
                    <div class="flex items-center gap-xs"><span class="w-2 h-2 rounded-full bg-surface-variant border border-outline-variant"></span><span class="text-xs text-on-surface-variant">Attended</span></div>
                </div>
            </div>
            <div class="flex-1 relative w-full h-full px-sm">
                <!-- Canvas for Chart.js Bar Chart -->
                <canvas id="attendanceTrendsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Right Column: Sidebar/Feed -->
    <div class="w-full lg:w-80 flex flex-col gap-lg">
        <!-- Upcoming Events Widget -->
        <div class="bg-surface-container border border-outline-variant/30 rounded-xl p-md flex flex-col">
            <div class="flex justify-between items-center mb-sm pb-sm border-b border-outline-variant/20">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Upcoming Events</h3>
                <button class="text-primary hover:underline text-sm font-label-caps">View All</button>
            </div>
            <div class="flex flex-col gap-sm">
                @forelse($upcomingEvents as $event)
                <!-- Event Item -->
                <div class="flex gap-md p-sm rounded-lg hover:bg-surface-variant/50 cursor-pointer transition-colors border border-transparent hover:border-outline-variant/30 group">
                    <div class="w-12 h-12 rounded bg-surface-variant flex flex-col items-center justify-center border border-outline-variant/30">
                        <span class="text-[10px] text-on-surface-variant font-label-caps uppercase">{{ \Carbon\Carbon::parse($event->start_date)->format('M') }}</span>
                        <span class="text-lg font-bold text-primary leading-none mt-0.5">{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-on-surface font-body-sm font-bold truncate group-hover:text-primary transition-colors">{{ $event->title }}</h4>
                        <p class="text-outline text-xs truncate">{{ $event->location ?? 'Online' }} • {{ \Carbon\Carbon::parse($event->start_date)->format('H:i A') }}</p>
                    </div>
                </div>
                @empty
                <div class="p-sm text-sm text-on-surface-variant text-center">No upcoming events found.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Activities Feed -->
        <div class="bg-surface-container border border-outline-variant/30 rounded-xl p-md flex flex-col flex-1">
            <div class="flex justify-between items-center mb-sm pb-sm border-b border-outline-variant/20">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Recent Activities</h3>
            </div>
            <div class="relative pl-sm mt-sm flex-1">
                <!-- Timeline Line -->
                <div class="absolute top-2 bottom-2 left-[15px] w-px bg-outline-variant/30"></div>
                <div class="flex flex-col gap-md">
                    @forelse($recentActivities as $index => $activity)
                    <!-- Activity Item -->
                    <div class="relative pl-lg">
                        <div class="absolute left-[-5px] top-1 w-2.5 h-2.5 rounded-full {{ $index === 0 ? 'bg-primary shadow-[0_0_8px_rgba(173,198,255,0.6)]' : 'bg-outline-variant' }} z-10 border-2 border-surface-container"></div>
                        <p class="text-xs text-outline mb-0.5 font-mono-code">{{ $activity->created_at->diffForHumans() }}</p>
                        <p class="text-sm text-on-surface">
                            <span class="font-bold">{{ $activity->user->name ?? 'System' }}</span>
                            {{ $activity->description }}
                        </p>
                    </div>
                    @empty
                    <div class="p-sm text-sm text-on-surface-variant">No recent activities found.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
