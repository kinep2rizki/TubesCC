@extends('layouts.app')

@section('title', 'Event Detail')

@section('content')
<div class="max-w-container-max mx-auto w-full flex flex-col gap-lg">
    <!-- Back Button -->
    <a href="{{ route('events') }}" class="inline-flex items-center gap-xs text-on-surface-variant hover:text-primary transition-colors font-label-caps text-label-caps w-fit group">
        <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
        Back to Events
    </a>

    <!-- Event Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-md mb-xl">
        <div class="flex flex-col gap-sm">
            <div class="flex items-center gap-sm">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $event->status === 'Live Now' ? 'bg-primary/20 text-primary border border-primary/30' : 'bg-surface-variant text-on-surface-variant border border-outline-variant/30' }}">
                    @if($event->status === 'Live Now')
                    <span class="w-1.5 h-1.5 bg-primary rounded-full mr-1.5 animate-pulse"></span>
                    @endif
                    {{ $event->status }}
                </span>
                <span class="text-on-surface-variant text-body-sm font-body-sm font-mono-code">ID: EVT-{{ $event->id }}</span>
            </div>
            <h2 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">{{ $event->title }}</h2>
            <p class="text-on-surface-variant text-body-base font-body-base max-w-2xl">{{ $event->description ?? 'No description provided.' }}</p>
        </div>
        <div class="flex items-center gap-sm mt-4 md:mt-0 w-full md:w-auto">
            <button class="flex-1 md:flex-none flex items-center justify-center gap-xs px-md py-sm rounded-lg border border-outline-variant bg-surface-container-low text-on-surface hover:bg-surface-variant transition-colors font-label-caps text-label-caps">
                <span class="material-symbols-outlined text-[18px]" data-icon="share">share</span>
                Share
            </button>
            <button class="flex-1 md:flex-none flex items-center justify-center gap-xs px-md py-sm rounded-lg bg-primary text-on-primary font-label-caps text-label-caps hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-[18px]" data-icon="edit">edit</span>
                Edit Event
            </button>
        </div>
    </div>
    
    <!-- In-Page Tabs -->
    <div class="w-full border-b border-outline-variant/30 mb-lg flex overflow-x-auto no-scrollbar">
        <button class="px-md py-sm font-body-base text-body-base text-primary font-semibold border-b-2 border-primary whitespace-nowrap">Overview</button>
        <button class="px-md py-sm font-body-base text-body-base text-on-surface-variant hover:text-on-surface hover:bg-surface-variant/20 transition-colors whitespace-nowrap">Participants</button>
        <button class="px-md py-sm font-body-base text-body-base text-on-surface-variant hover:text-on-surface hover:bg-surface-variant/20 transition-colors whitespace-nowrap">Attendance</button>
        <button class="px-md py-sm font-body-base text-body-base text-on-surface-variant hover:text-on-surface hover:bg-surface-variant/20 transition-colors whitespace-nowrap">Certificates</button>
    </div>
    
    <!-- Bento Grid Content -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-md">
        <!-- Registration Stats (Large Card) -->
        <div class="md:col-span-2 bg-surface-container-low backdrop-blur-md rounded-xl border border-outline-variant/30 p-lg shadow-sm flex flex-col relative overflow-hidden">
            <div class="flex justify-between items-center mb-md border-b border-outline-variant/20 pb-sm relative z-10">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Registration Dynamics</h3>
                <button class="text-on-surface-variant hover:text-primary"><span class="material-symbols-outlined" data-icon="more_horiz">more_horiz</span></button>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-md mb-lg relative z-10">
                <div class="flex flex-col gap-xs p-md bg-surface-container rounded-lg border border-outline-variant/20">
                    <span class="text-on-surface-variant text-body-sm font-body-sm font-medium">Total Capacity</span>
                    <span class="font-display-lg-mobile text-display-lg-mobile text-on-surface font-bold">{{ $event->capacity ?? 500 }}</span>
                </div>
                <div class="flex flex-col gap-xs p-md bg-surface-container rounded-lg border border-outline-variant/20">
                    <span class="text-on-surface-variant text-body-sm font-body-sm font-medium">Registered</span>
                    <span class="font-display-lg-mobile text-display-lg-mobile text-primary font-bold">{{ $event->participants->count() }}</span>
                </div>
                <div class="flex flex-col gap-xs p-md bg-surface-container rounded-lg border border-outline-variant/20">
                    <span class="text-on-surface-variant text-body-sm font-body-sm font-medium">Waitlisted</span>
                    <span class="font-display-lg-mobile text-display-lg-mobile text-secondary font-bold">45</span>
                </div>
                <div class="flex flex-col gap-xs p-md bg-surface-container rounded-lg border border-outline-variant/20">
                    <span class="text-on-surface-variant text-body-sm font-body-sm font-medium">Conversion Rate</span>
                    <span class="font-display-lg-mobile text-display-lg-mobile text-tertiary font-bold">96%</span>
                </div>
            </div>
            <!-- Placeholder for Line Chart -->
            <div class="flex-1 min-h-[240px] bg-surface-container-lowest rounded-lg border border-outline-variant/20 relative flex items-center justify-center overflow-hidden z-10">
                <!-- CSS Pattern for chart background -->
                <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(theme('colors.on-surface') 1px, transparent 1px), linear-gradient(90deg, theme('colors.on-surface') 1px, transparent 1px); background-size: 40px 40px;"></div>
                <!-- Simulated Chart Line -->
                <svg class="absolute inset-0 w-full h-full" preserveaspectratio="none" viewbox="0 0 100 100">
                    <defs>
                        <lineargradient id="chartGrad" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="theme('colors.primary')" stop-opacity="0.2"></stop>
                            <stop offset="100%" stop-color="theme('colors.primary')" stop-opacity="0"></stop>
                        </lineargradient>
                    </defs>
                    <path d="M0,100 L0,80 C20,70 30,90 50,50 C70,10 80,40 100,20 L100,100 Z" fill="url(#chartGrad)"></path>
                    <path class="drop-shadow-[0_4px_8px_rgba(173,198,255,0.2)]" d="M0,80 C20,70 30,90 50,50 C70,10 80,40 100,20" fill="none" stroke="theme('colors.primary')" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
                <span class="text-on-surface-variant text-body-sm font-body-sm z-10 bg-surface-container/80 px-3 py-1.5 rounded border border-outline-variant/30 backdrop-blur-md">Chart Rendering...</span>
            </div>
        </div>
        
        <!-- Right Column (Stacked Cards) -->
        <div class="flex flex-col gap-md">
            <!-- QR Check-in Toggle -->
            <div class="bg-surface-container-low backdrop-blur-md rounded-xl border border-outline-variant/30 p-md shadow-sm flex items-center justify-between group">
                <div class="flex items-center gap-md">
                    <div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined" data-icon="qr_code_scanner">qr_code_scanner</span>
                    </div>
                    <div>
                        <h4 class="font-body-base text-body-base font-semibold text-on-surface">QR Check-in</h4>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Self-service active</p>
                    </div>
                </div>
                <!-- Toggle Switch -->
                <label class="relative inline-flex items-center cursor-pointer">
                    <input checked="" class="sr-only peer" type="checkbox" value=""/>
                    <div class="w-11 h-6 bg-surface-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </label>
            </div>
            
            <!-- Certificate Automation Status -->
            <div class="bg-surface-container-low backdrop-blur-md rounded-xl border border-outline-variant/30 p-md shadow-sm relative overflow-hidden">
                <h4 class="font-body-base text-body-base font-semibold text-on-surface mb-xs">Certificate Automation</h4>
                <p class="font-body-sm text-body-sm text-on-surface-variant mb-md">Triggers on checkout</p>
                <div class="bg-surface-container rounded-lg p-sm border border-outline-variant/20 mb-md">
                    <div class="flex justify-between items-center mb-1">
                        <span class="font-label-caps text-label-caps text-on-surface-variant">Template</span>
                        <span class="font-label-caps text-label-caps text-secondary">Verified</span>
                    </div>
                    <p class="font-mono-code text-mono-code text-on-surface truncate">Hackathon_Standard_v2.pdf</p>
                </div>
                <button class="w-full flex items-center justify-center gap-xs px-md py-sm rounded-lg border border-outline-variant bg-surface-container text-on-surface hover:bg-surface-variant transition-colors font-label-caps text-label-caps">
                    <span class="material-symbols-outlined text-[18px]" data-icon="settings">settings</span>
                    Configure Rules
                </button>
            </div>
            
            <!-- Demographic Donut Chart (Placeholder) -->
            <div class="flex-1 bg-surface-container-low backdrop-blur-md rounded-xl border border-outline-variant/30 p-md shadow-sm flex flex-col">
                <h4 class="font-body-base text-body-base font-semibold text-on-surface mb-sm">Demographics</h4>
                <div class="flex-1 flex items-center justify-center relative my-sm">
                    <!-- CSS simulated donut chart -->
                    <div class="w-32 h-32 rounded-full relative flex items-center justify-center" style="background: conic-gradient(theme('colors.primary') 0% 60%, theme('colors.secondary') 60% 85%, theme('colors.tertiary') 85% 100%);">
                        <div class="w-24 h-24 bg-surface-container-low rounded-full flex flex-col items-center justify-center z-10">
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Top Group</span>
                            <span class="font-headline-sm text-headline-sm text-primary font-bold">60%</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center gap-md mt-sm">
                    <div class="flex items-center gap-xs"><span class="w-3 h-3 rounded-full bg-primary"></span><span class="text-xs text-on-surface-variant">Students</span></div>
                    <div class="flex items-center gap-xs"><span class="w-3 h-3 rounded-full bg-secondary"></span><span class="text-xs text-on-surface-variant">Pros</span></div>
                    <div class="flex items-center gap-xs"><span class="w-3 h-3 rounded-full bg-tertiary"></span><span class="text-xs text-on-surface-variant">Other</span></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions / Table Preview Area -->
    <div class="mt-md bg-surface-container-low backdrop-blur-md rounded-xl border border-outline-variant/30 overflow-hidden shadow-sm">
        <div class="p-md border-b border-outline-variant/30 flex justify-between items-center bg-surface-container/50">
            <h3 class="font-headline-sm text-headline-sm text-on-surface">Recent Registrations</h3>
            <button class="text-primary hover:text-primary-container text-body-sm font-body-sm font-semibold transition-colors">View All</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-lowest/50 text-on-surface-variant font-label-caps text-label-caps border-b border-outline-variant/30">
                        <th class="p-sm md:p-md font-semibold">Name</th>
                        <th class="p-sm md:p-md font-semibold">Email</th>
                        <th class="p-sm md:p-md font-semibold">Ticket Type</th>
                        <th class="p-sm md:p-md font-semibold text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="text-body-sm font-body-sm">
                    @forelse($event->participants->take(5) as $participant)
                    <tr class="border-b border-outline-variant/20 hover:bg-surface-variant/30 transition-colors">
                        <td class="p-sm md:p-md text-on-surface flex items-center gap-sm">
                            <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant font-medium text-xs">{{ substr($participant->user->name ?? 'U', 0, 2) }}</div>
                            {{ $participant->user->name ?? 'Unknown' }}
                        </td>
                        <td class="p-sm md:p-md text-on-surface-variant">{{ $participant->user->email ?? 'N/A' }}</td>
                        <td class="p-sm md:p-md"><span class="px-2 py-1 rounded bg-surface-container-highest text-on-surface-variant text-xs border border-outline-variant/30">Standard</span></td>
                        <td class="p-sm md:p-md text-right">
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-medium {{ $participant->status == 'Attended' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">{{ $participant->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center p-md text-on-surface-variant border-b border-outline-variant/20">No participants yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
