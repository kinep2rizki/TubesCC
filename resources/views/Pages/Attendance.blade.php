@extends('layouts.app')

@section('title', 'Attendance Check-in')

@section('content')
<div x-data="{ showManualCheckinModal: false }" class="max-w-container-max mx-auto flex flex-col gap-lg h-full pb-32 md:pb-2xl">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-sm">
        <div>
            <h2 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-on-surface">Live Check-in</h2>
            <p class="font-body-base text-body-base text-on-surface-variant mt-xs">{{ $event->title }}</p>
        </div>
        
        <div class="flex items-center gap-md">
            <button @click="showManualCheckinModal = true" class="flex items-center gap-xs px-md py-2 rounded-lg border border-outline-variant/50 text-on-surface-variant hover:bg-surface-variant transition-colors font-label-caps text-label-caps">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                Manual Check-in
            </button>
            <div class="flex items-center gap-sm bg-surface-container-low px-md py-2 rounded-lg border border-outline-variant/30">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                </span>
                <span class="font-label-caps text-label-caps text-primary">SCANNER ACTIVE</span>
            </div>
        </div>
    </div>
    <!-- Main Grid: Scanner (Left) & Feed/Stats (Right) -->
    <!-- ... (rest of the file content until the end) ... -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg h-[600px]">
        
        <!-- Left Column: QR Scanner -->
        <div class="lg:col-span-7 flex flex-col gap-md h-full">
            <div class="bg-surface-container-low border border-outline-variant/30 rounded-xl relative overflow-hidden flex-1 flex flex-col items-center justify-center group">
                <!-- Atmospheric Blur Elements -->
                <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-primary/10 rounded-full blur-3xl mix-blend-screen pointer-events-none"></div>
                <div class="absolute bottom-1/4 right-1/4 w-48 h-48 bg-secondary/10 rounded-full blur-2xl mix-blend-screen pointer-events-none"></div>
                
                <!-- Scanner Viewfinder -->
                <div class="relative w-64 h-64 md:w-80 md:h-80 border-2 border-dashed border-outline-variant rounded-lg flex items-center justify-center bg-black/40 backdrop-blur-sm z-10">
                    <!-- Corner Markers -->
                    <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-primary rounded-tl-lg"></div>
                    <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-primary rounded-tr-lg"></div>
                    <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-primary rounded-bl-lg"></div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-primary rounded-br-lg"></div>
                    
                    <!-- Animated Scan Beam -->
                    <div class="absolute left-0 w-full h-1 bg-primary scanner-beam shadow-[0_0_15px_rgba(77,142,255,0.8)] z-20"></div>
                    
                    <span class="material-symbols-outlined text-outline-variant text-[48px] opacity-50 group-hover:scale-110 transition-transform duration-500">qr_code_scanner</span>
                </div>
                
                <div class="absolute bottom-lg z-10 flex items-center gap-sm bg-surface-container/80 backdrop-blur-md px-md py-sm rounded-full border border-outline-variant/30">
                    <span class="material-symbols-outlined text-primary text-[20px]">videocam</span>
                    <span class="font-body-sm text-body-sm text-on-surface">Camera 01: Main Entrance</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Stats & Feed -->
        <div class="lg:col-span-5 flex flex-col gap-lg h-full">
            <!-- Stats Bento -->
            <div class="grid grid-cols-2 gap-sm">
                <div class="bg-surface-container-low border border-outline-variant/30 rounded-xl p-md flex flex-col justify-between relative overflow-hidden">
                    <div class="flex justify-between items-start mb-lg">
                        <span class="font-label-caps text-label-caps text-on-surface-variant">Present</span>
                        <span class="material-symbols-outlined text-primary">how_to_reg</span>
                    </div>
                    <div>
                        <div class="font-headline-sm text-headline-sm text-on-surface">{{ number_format($presentCount) }}</div>
                        <div class="font-body-sm text-body-sm text-primary mt-xs flex items-center gap-xs">
                            <span class="material-symbols-outlined text-[16px]">trending_up</span> Live
                        </div>
                    </div>
                </div>
                <div class="bg-surface-container-low border border-outline-variant/30 rounded-xl p-md flex flex-col justify-between relative overflow-hidden">
                    <div class="flex justify-between items-start mb-lg">
                        <span class="font-label-caps text-label-caps text-on-surface-variant">Expected</span>
                        <span class="material-symbols-outlined text-outline-variant">person_off</span>
                    </div>
                    <div>
                        <div class="font-headline-sm text-headline-sm text-on-surface">{{ number_format($expectedCount) }}</div>
                        <div class="font-body-sm text-body-sm text-outline-variant mt-xs">{{ $expectedCount > 0 ? number_format(($presentCount / $expectedCount) * 100, 1) : 0 }}% Capacity</div>
                    </div>
                    <div class="absolute bottom-0 left-0 h-1 bg-surface-variant w-full">
                        <div class="h-full bg-outline-variant" style="width: {{ $expectedCount > 0 ? ($presentCount / $expectedCount) * 100 : 0 }}%;"></div>
                    </div>
                </div>
            </div>

            <!-- Live Feed -->
            <div class="bg-surface-container-low border border-outline-variant/30 rounded-xl flex flex-col flex-1 overflow-hidden relative">
                <div class="p-md border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-low z-10">
                    <h3 class="font-label-caps text-label-caps text-on-surface">Live Feed</h3>
                    <button class="text-primary hover:text-primary-container transition-colors font-body-sm text-body-sm flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">filter_list</span> Filter
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-md flex flex-col gap-unit custom-scrollbar">
                    @forelse($attendances->take(5) as $attendance)
                    <x-feed-item 
                        type="success" 
                        initials="{{ substr($attendance->participant->user->name ?? 'U', 0, 2) }}" 
                        name="{{ $attendance->participant->user->name ?? 'Unknown' }}" 
                        description="{{ $attendance->participant->status }} • {{ $attendance->check_in_time ? $attendance->check_in_time->format('h:i A') : $attendance->created_at->format('h:i A') }}" 
                    />
                    @empty
                    <div class="text-center text-on-surface-variant p-md">No logs available.</div>
                    @endforelse
                </div>
                
                <!-- Gradient fade at bottom -->
                <div class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-surface-container-low to-transparent pointer-events-none"></div>
            </div>
        </div>
    </div>

    <!-- Bottom Section: History Table -->
    <div class="bg-surface-container-low border border-outline-variant/30 rounded-xl overflow-hidden mt-xl">
        <div class="p-md border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-low">
            <h3 class="font-label-caps text-label-caps text-on-surface">Recent Check-in Logs</h3>
            <button class="bg-transparent border border-outline-variant/50 text-on-surface hover:bg-surface-variant px-md py-sm rounded-lg font-body-sm text-body-sm transition-colors">
                Export CSV
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant/30 bg-surface-container-lowest/50">
                        <th class="py-sm px-md font-label-caps text-label-caps text-on-surface-variant">Timestamp</th>
                        <th class="py-sm px-md font-label-caps text-label-caps text-on-surface-variant">Name</th>
                        <th class="py-sm px-md font-label-caps text-label-caps text-on-surface-variant">Ticket Type</th>
                        <th class="py-sm px-md font-label-caps text-label-caps text-on-surface-variant">Status</th>
                        <th class="py-sm px-md font-label-caps text-label-caps text-on-surface-variant text-right">Gate</th>
                    </tr>
                </thead>
                <tbody class="font-mono-code text-mono-code text-on-surface">
                    @forelse($attendances->take(10) as $attendance)
                    <x-log-row 
                        timestamp="{{ $attendance->check_in_time ? $attendance->check_in_time->format('h:i:s') : $attendance->created_at->format('h:i:s') }}"
                        name="{{ $attendance->participant->user->name ?? 'Unknown' }}"
                        ticketType="Standard"
                        status="granted"
                        gate="G-01"
                    />
                    @empty
                    <tr>
                        <td colspan="5" class="text-center p-md text-on-surface-variant">No check-in logs.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <x-manual-checkin-modal :event="$event" />
</div>
@endsection
