@extends('layouts.app')

@section('title', 'Advanced Analytics')

@section('content')
<style>
    .chart-grid { stroke: theme('colors.outline-variant'); stroke-width: 0.5; opacity: 0.3; }
    .chart-area { fill: url(#primary-gradient); opacity: 0.2; }
</style>

<div x-data="{ 
    showExportModal: false,
    isExporting: false,
    async exportReport(e) {
        this.isExporting = true;
        try {
            const formData = new FormData(e.target);
            const format = formData.get('format') || 'csv';
            const communityId = localStorage.getItem('active_community_id');
            
            const res = await window.apiFetch(`/api/analytics/${communityId}/export?format=${format}`, 'POST', {
                include_metrics: true
            });
            
            if (res.ok) {
                const blob = await res.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'analytics_report.' + format;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                this.showExportModal = false;
            } else {
                alert('Gagal mendownload report');
            }
        } catch (error) {
            console.error(error);
            alert('Kesalahan jaringan saat mendownload');
        } finally {
            this.isExporting = false;
        }
    }
}" class="max-w-container-max mx-auto w-full flex flex-col gap-xl">
    
    <!-- Page Header -->
    <div class="flex justify-between items-center pb-sm border-b border-outline-variant/30">
        <h1 class="font-display-lg text-display-lg text-on-surface tracking-tight">Advanced Analytics</h1>
    </div>

    <!-- Filters Bar -->
    <div class="glass-panel rounded-xl p-sm flex flex-wrap gap-md items-center justify-between">
        <div class="flex flex-wrap gap-sm">
            <div class="relative">
                <select class="appearance-none bg-surface-container-high border border-outline-variant/50 text-on-surface font-body-sm text-body-sm rounded-lg pl-md pr-xl py-xs focus:ring-1 focus:ring-primary focus:border-primary outline-none">
                    <option>Last 30 Days</option>
                    <option>Last Quarter</option>
                    <option>Year to Date</option>
                </select>
                <span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[18px]">calendar_today</span>
            </div>
            <div class="relative">
                <select class="appearance-none bg-surface-container-high border border-outline-variant/50 text-on-surface font-body-sm text-body-sm rounded-lg pl-md pr-xl py-xs focus:ring-1 focus:ring-primary focus:border-primary outline-none">
                    <option>All Categories</option>
                    <option>Workshops</option>
                    <option>Hackathons</option>
                </select>
                <span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[18px]">category</span>
            </div>
        </div>
        <button @click="showExportModal = true" class="bg-primary text-on-primary font-label-caps text-label-caps px-md py-sm rounded-lg hover:bg-primary-container transition-colors flex items-center gap-xs">
            <span class="material-symbols-outlined text-[16px]">download</span>
            Export Report
        </button>
    </div>

    <!-- KPI Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
        <!-- KPI 1 -->
        <div class="glass-panel rounded-xl p-lg flex flex-col relative overflow-hidden">
            <div class="flex justify-between items-start mb-md">
                <h3 class="font-label-caps text-label-caps text-on-surface-variant">Total Participants</h3>
                <span class="text-primary bg-primary/10 px-unit py-xs rounded text-[10px] font-mono-code flex items-center gap-xs">+12.5% <span class="material-symbols-outlined text-[12px]">trending_up</span></span>
            </div>
            <div class="font-display-lg text-display-lg text-on-surface mb-sm">{{ number_format($totalParticipants) }}</div>
            <!-- Mini Sparkline -->
            <div class="h-16 w-full mt-auto relative">
                <svg class="w-full h-full" preserveaspectratio="none" viewbox="0 0 100 30">
                    <defs>
                        <lineargradient id="spark-grad" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="theme('colors.primary')" stop-opacity="0.3"></stop>
                            <stop offset="100%" stop-color="theme('colors.primary')" stop-opacity="0"></stop>
                        </lineargradient>
                    </defs>
                    <path d="M0,25 Q10,15 20,20 T40,10 T60,18 T80,5 T100,15 L100,30 L0,30 Z" fill="url(#spark-grad)"></path>
                    <path d="M0,25 Q10,15 20,20 T40,10 T60,18 T80,5 T100,15" fill="none" stroke="theme('colors.primary')" stroke-width="1.5"></path>
                </svg>
            </div>
        </div>

        <!-- KPI 2 -->
        <div class="glass-panel rounded-xl p-lg flex flex-col">
            <div class="flex justify-between items-start mb-md">
                <h3 class="font-label-caps text-label-caps text-on-surface-variant">Event Success Rate</h3>
                <span class="material-symbols-outlined text-outline-variant text-[16px]">info</span>
            </div>
            <div class="flex items-center justify-between mt-auto">
                <div class="font-display-lg text-display-lg text-on-surface">{{ number_format($successRate, 1) }}<span class="text-headline-sm text-outline">%</span></div>
                <!-- Simple CSS Gauge -->
                <div class="relative w-16 h-16 rounded-full flex items-center justify-center" style="background: conic-gradient(theme('colors.primary') {{ $successRate }}%, theme('colors.surface-container-high') 0);">
                    <div class="w-12 h-12 bg-surface-container-lowest rounded-full flex items-center justify-center absolute">
                        <span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3 -->
        <div class="glass-panel rounded-xl p-lg flex flex-col">
            <div class="flex justify-between items-start mb-md">
                <h3 class="font-label-caps text-label-caps text-on-surface-variant">Avg. Attendance</h3>
                <span class="text-error bg-error/10 px-unit py-xs rounded text-[10px] font-mono-code flex items-center gap-xs">-2.1% <span class="material-symbols-outlined text-[12px]">trending_down</span></span>
            </div>
            <div class="font-display-lg text-display-lg text-on-surface mb-sm">{{ number_format($avgAttendance, 1) }}<span class="text-headline-sm text-outline">%</span></div>
            <!-- Mini Bar Chart -->
            <div class="flex items-end gap-unit h-12 mt-auto">
                <div class="w-1/6 bg-surface-container-high h-[40%] rounded-t-sm"></div>
                <div class="w-1/6 bg-surface-container-high h-[60%] rounded-t-sm"></div>
                <div class="w-1/6 bg-surface-container-high h-[80%] rounded-t-sm"></div>
                <div class="w-1/6 bg-primary h-[95%] rounded-t-sm"></div>
                <div class="w-1/6 bg-surface-container-high h-[70%] rounded-t-sm"></div>
                <div class="w-1/6 bg-surface-container-high h-[50%] rounded-t-sm"></div>
            </div>
        </div>
    </div>

    <!-- Main Charts Area - Bento Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
        <!-- Main Line Chart (Spans 2 cols) -->
        <div class="glass-panel rounded-xl p-lg lg:col-span-2 flex flex-col min-h-[400px]">
            <div class="flex justify-between items-center mb-xl border-b border-outline-variant/30 pb-sm">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Participation Growth</h3>
                <div class="flex gap-sm">
                    <span class="flex items-center gap-xs text-[10px] font-mono-code text-on-surface-variant"><div class="w-2 h-2 rounded-full bg-primary"></div> Unique</span>
                    <span class="flex items-center gap-xs text-[10px] font-mono-code text-on-surface-variant"><div class="w-2 h-2 rounded-full bg-tertiary"></div> Returning</span>
                </div>
            </div>
            <div class="flex-1 relative w-full h-full pt-sm">
                <!-- Canvas for Chart.js -->
                <canvas id="participationGrowthChart"></canvas>
            </div>
        </div>

        <!-- Right Column (Stacked Charts) -->
        <div class="flex flex-col gap-lg">
            <!-- Donut Chart -->
            <div class="glass-panel rounded-xl p-lg flex-1 flex flex-col">
                <div class="mb-md border-b border-outline-variant/30 pb-sm">
                    <h3 class="font-body-base text-body-base font-semibold text-on-surface">Certificate Status</h3>
                </div>
                <div class="flex-1 relative w-full h-full pt-sm min-h-[150px]">
                    <canvas id="certificateStatusChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-2">
                        <span class="font-headline-sm text-headline-sm text-on-surface">{{ $certificateIssuedPercentage }}%</span>
                        <span class="text-[10px] font-label-caps text-outline">Issued</span>
                    </div>
                </div>
                <div class="flex justify-center gap-md mt-sm">
                    <div class="flex items-center gap-xs text-[10px] font-label-caps text-on-surface-variant"><div class="w-2 h-2 rounded bg-primary"></div> Issued</div>
                    <div class="flex items-center gap-xs text-[10px] font-label-caps text-on-surface-variant"><div class="w-2 h-2 rounded bg-surface-container-high border border-outline-variant"></div> Pending</div>
                </div>
            </div>

            <!-- Horizontal Bar Chart -->
            <div class="glass-panel rounded-xl p-lg flex-1 flex flex-col">
                <div class="mb-md border-b border-outline-variant/30 pb-sm">
                    <h3 class="font-body-base text-body-base font-semibold text-on-surface">Top Categories</h3>
                </div>
                <div class="flex-1 flex flex-col justify-center gap-sm">
                    <div class="w-full">
                        <div class="flex justify-between text-[10px] font-mono-code text-on-surface-variant mb-unit">
                            <span>Web Dev</span> <span>3,240</span>
                        </div>
                        <div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
                            <div class="bg-primary h-full rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="flex justify-between text-[10px] font-mono-code text-on-surface-variant mb-unit">
                            <span>AI/ML</span> <span>2,890</span>
                        </div>
                        <div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
                            <div class="bg-tertiary-container h-full rounded-full" style="width: 65%"></div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="flex justify-between text-[10px] font-mono-code text-on-surface-variant mb-unit">
                            <span>Design</span> <span>1,450</span>
                        </div>
                        <div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
                            <div class="bg-secondary h-full rounded-full" style="width: 40%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Section -->
    <div class="glass-panel rounded-xl overflow-hidden mt-md">
        <div class="p-md border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-low/50">
            <h3 class="font-headline-sm text-headline-sm text-on-surface">Recent Event Performance</h3>
            <button class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[20px]">filter_list</span>
            </button>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant/30 bg-surface-container-lowest/50 text-[10px] font-label-caps text-on-surface-variant">
                        <th class="p-sm font-semibold pl-lg">Event Name</th>
                        <th class="p-sm font-semibold">Date</th>
                        <th class="p-sm font-semibold">Registrations</th>
                        <th class="p-sm font-semibold">Turnout</th>
                        <th class="p-sm font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="text-body-sm font-body-sm">
                    @forelse($recentEvents as $event)
                    <tr class="border-b border-outline-variant/10 hover:bg-white/[0.03] transition-colors">
                        <td class="p-sm pl-lg text-on-surface">{{ $event->title }}</td>
                        <td class="p-sm text-outline font-mono-code">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</td>
                        <td class="p-sm text-on-surface font-mono-code">{{ number_format($event->participants_count) }}</td>
                        <td class="p-sm text-on-surface font-mono-code">--</td>
                        <td class="p-sm">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-label-caps {{ $event->status == 'Completed' ? 'bg-primary/10 text-primary border border-primary/20' : ($event->status == 'Live Now' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-tertiary/10 text-tertiary border border-tertiary/20') }}">{{ $event->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center p-md text-on-surface-variant">No events found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modals -->
    <x-export-report-modal />
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 3. Participation Growth Chart (Analytics)
        const participationGrowthCtx = document.getElementById('participationGrowthChart');
        if (participationGrowthCtx) {
            new Chart(participationGrowthCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($growthLabels) !!},
                    datasets: [
                        {
                            label: 'Unique',
                            data: {!! json_encode($uniqueData) !!},
                            borderColor: '#adc6ff',
                            backgroundColor: 'rgba(173, 198, 255, 0.2)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#131315',
                            pointBorderColor: '#adc6ff',
                            pointBorderWidth: 1.5,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Returning',
                            data: {!! json_encode($returningData) !!},
                            borderColor: '#adc6ff',
                            borderWidth: 2,
                            borderDash: [4, 4],
                            fill: false,
                            tension: 0.4,
                            pointBackgroundColor: '#131315',
                            pointBorderColor: '#adc6ff',
                            pointBorderWidth: 1.5,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: '#2a2a2c',
                            titleColor: '#e5e1e4',
                            bodyColor: '#e5e1e4',
                            borderColor: '#424754',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: '#8c909f', font: { family: 'JetBrains Mono', size: 10 } }
                        },
                        y: {
                            display: false,
                            min: 0,
                            suggestedMax: Math.max(...{!! json_encode($uniqueData) !!}, 10) + 5
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        }

        // 4. Certificate Status Donut Chart (Analytics)
        const certificateStatusCtx = document.getElementById('certificateStatusChart');
        if (certificateStatusCtx) {
            new Chart(certificateStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Issued', 'Pending'],
                    datasets: [{
                        data: [{{ $issuedCertificates }}, {{ $pendingCertificates }}],
                        backgroundColor: ['#adc6ff', 'rgba(66, 71, 84, 0.3)'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#2a2a2c',
                            titleColor: '#e5e1e4',
                            bodyColor: '#e5e1e4',
                            borderColor: '#424754',
                            borderWidth: 1
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
