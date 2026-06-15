@extends('layouts.app')

@section('title', 'Event Detail')

@section('content')
<div x-data="{ showEditEventModal: false }" class="max-w-container-max mx-auto w-full flex flex-col gap-lg">
    <x-event-header :event="$event" activeTab="overview" />
    
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
                    <span class="font-display-lg-mobile text-display-lg-mobile text-primary font-bold">{{ $registeredCount }}</span>
                </div>
                <div class="flex flex-col gap-xs p-md bg-surface-container rounded-lg border border-outline-variant/20">
                    <span class="text-on-surface-variant text-body-sm font-body-sm font-medium">Waitlisted</span>
                    <span class="font-display-lg-mobile text-display-lg-mobile text-secondary font-bold">{{ $waitlistedCount }}</span>
                </div>
                <div class="flex flex-col gap-xs p-md bg-surface-container rounded-lg border border-outline-variant/20">
                    <span class="text-on-surface-variant text-body-sm font-body-sm font-medium">Conversion Rate</span>
                    <span class="font-display-lg-mobile text-display-lg-mobile text-tertiary font-bold">{{ $conversionRate }}%</span>
                </div>
            </div>
            <!-- Line Chart -->
            <div class="flex-1 min-h-[240px] bg-surface-container-lowest rounded-lg border border-outline-variant/20 relative flex items-center justify-center overflow-hidden z-10 p-md">
                <canvas id="registrationChart"></canvas>
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
                    <p class="font-mono-code text-mono-code text-on-surface truncate">Auto_Generated.pdf</p>
                </div>
                <button class="w-full flex items-center justify-center gap-xs px-md py-sm rounded-lg border border-outline-variant bg-surface-container text-on-surface hover:bg-surface-variant transition-colors font-label-caps text-label-caps">
                    <span class="material-symbols-outlined text-[18px]" data-icon="settings">settings</span>
                    Configure Rules
                </button>
            </div>
            
            <!-- Demographic Donut Chart -->
            <div class="flex-1 bg-surface-container-low backdrop-blur-md rounded-xl border border-outline-variant/30 p-md shadow-sm flex flex-col">
                <h4 class="font-body-base text-body-base font-semibold text-on-surface mb-sm">Demographics</h4>
                <div class="flex-1 flex items-center justify-center relative my-sm">
                    <!-- CSS simulated donut chart -->
                    <div class="w-32 h-32 rounded-full relative flex items-center justify-center" style="background: conic-gradient(theme('colors.emerald.500') 0% {{ $attendedPct }}%, theme('colors.amber.500') {{ $attendedPct }}% {{ $attendedPct + $registeredPct }}%, theme('colors.gray.500') {{ $attendedPct + $registeredPct }}% 100%);">
                        <div class="w-24 h-24 bg-surface-container-low rounded-full flex flex-col items-center justify-center z-10">
                            <span class="font-body-sm text-body-sm text-on-surface-variant text-center leading-tight">Top Group<br>{{ $topGroupName }}</span>
                            <span class="font-headline-sm text-headline-sm text-primary font-bold">{{ $topGroupPct }}%</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center gap-md mt-sm">
                    <div class="flex items-center gap-xs"><span class="w-3 h-3 rounded-full bg-emerald-500"></span><span class="text-xs text-on-surface-variant">Attended</span></div>
                    <div class="flex items-center gap-xs"><span class="w-3 h-3 rounded-full bg-amber-500"></span><span class="text-xs text-on-surface-variant">Registered</span></div>
                    <div class="flex items-center gap-xs"><span class="w-3 h-3 rounded-full bg-gray-500"></span><span class="text-xs text-on-surface-variant">Other</span></div>
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
                    @forelse($recentParticipants as $participant)
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
    
    <x-edit-event-modal :event="$event" />
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('registrationChart');
        if (!ctx) return;
        
        // CSS variables for chart colors
        const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--color-primary') || '#4F46E5';
        const onSurfaceColor = getComputedStyle(document.documentElement).getPropertyValue('--color-on-surface-variant') || '#9CA3AF';
        const gridColor = getComputedStyle(document.documentElement).getPropertyValue('--color-outline-variant') || 'rgba(255,255,255,0.1)';
        
        // Create gradient
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.3)'); 
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($registrationDates) !!},
                datasets: [{
                    label: 'Registrations',
                    data: {!! json_encode($registrationCounts) !!},
                    borderColor: primaryColor,
                    backgroundColor: gradient,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: primaryColor,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleFont: { size: 13, family: 'Inter' },
                        bodyFont: { size: 14, family: 'Inter', weight: 'bold' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) { return context.parsed.y + ' registrations'; }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: onSurfaceColor, font: { family: 'Inter', size: 11 } }
                    },
                    y: {
                        grid: { color: gridColor, borderDash: [5, 5], drawBorder: false },
                        ticks: { color: onSurfaceColor, font: { family: 'Inter', size: 11 }, precision: 0, beginAtZero: true }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    });
</script>
@endpush
@endsection
