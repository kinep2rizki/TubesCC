@extends('layouts.app')

@section('content')
<div x-data="dashboardState()" class="flex flex-col gap-lg">
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
            <h2 class="font-display-lg-mobile lg:font-display-lg text-on-surface mb-sm" x-text="activeCommunity ? activeCommunity.name : 'Loading...'"></h2>
            <p class="font-headline-sm text-headline-sm text-on-surface-variant max-w-xl" x-text="activeCommunity ? activeCommunity.description : 'Platform Event Teknologi Aktivitas. Monitor, manage, and scale your technology events with precision.'"></p>
        </div>
        <div class="flex gap-md">
            <template x-if="canEditGuidelines">
                <button @click="showGuidelinesModal = true" class="bg-transparent border border-outline-variant text-on-surface font-label-caps text-label-caps px-lg py-sm rounded-lg hover:bg-surface-variant transition-colors active:scale-95 flex items-center gap-sm">
                    <span class="material-symbols-outlined text-[18px]">article</span> Guidelines
                </button>
            </template>
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
            <span class="text-3xl font-bold text-on-surface tracking-tight" x-text="data.metrics ? data.metrics.totalEvents : 0"></span>
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
            <span class="text-3xl font-bold text-on-surface tracking-tight" x-text="data.metrics ? data.metrics.totalParticipants : 0"></span>
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
            <span class="text-3xl font-bold text-on-surface tracking-tight" x-text="(data.metrics ? data.metrics.attendanceRate : 0) + '%'"></span>
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
            <span class="text-3xl font-bold text-on-surface tracking-tight" x-text="data.metrics ? data.metrics.certificatesGenerated : 0"></span>
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
                <template x-for="event in data.upcomingEvents" :key="event.id">
                    <!-- Event Item -->
                    <div class="flex gap-md p-sm rounded-lg hover:bg-surface-variant/50 cursor-pointer transition-colors border border-transparent hover:border-outline-variant/30 group">
                        <div class="w-12 h-12 rounded bg-surface-variant flex flex-col items-center justify-center border border-outline-variant/30">
                            <span class="text-[10px] text-on-surface-variant font-label-caps uppercase" x-text="new Date(event.start_date).toLocaleString('en-US', { month: 'short' })"></span>
                            <span class="text-lg font-bold text-primary leading-none mt-0.5" x-text="new Date(event.start_date).getDate().toString().padStart(2, '0')"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-on-surface font-body-sm font-bold truncate group-hover:text-primary transition-colors" x-text="event.title"></h4>
                            <p class="text-outline text-xs truncate" x-text="(event.location || 'Online') + ' • ' + new Date(event.start_date).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })"></p>
                        </div>
                    </div>
                </template>
                <template x-if="data.upcomingEvents && data.upcomingEvents.length === 0">
                    <div class="p-sm text-sm text-on-surface-variant text-center">No upcoming events found.</div>
                </template>
                <template x-if="!data.upcomingEvents">
                    <div class="p-sm text-sm text-on-surface-variant text-center">Loading...</div>
                </template>
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
                    <template x-for="(activity, index) in data.recentActivities" :key="activity.id || index">
                        <!-- Activity Item -->
                        <div class="relative pl-lg">
                            <div class="absolute left-[-5px] top-1 w-2.5 h-2.5 rounded-full z-10 border-2 border-surface-container" :class="index === 0 ? 'bg-primary shadow-[0_0_8px_rgba(173,198,255,0.6)]' : 'bg-outline-variant'"></div>
                            <p class="text-xs text-outline mb-0.5 font-mono-code" x-text="formatDateRelatively(activity.created_at)"></p>
                            <p class="text-sm text-on-surface">
                                <span class="font-bold" x-text="(activity.user && activity.user.name) ? activity.user.name : 'System'"></span>
                                <span x-text="activity.description"></span>
                            </p>
                        </div>
                    </template>
                    <template x-if="data.recentActivities && data.recentActivities.length === 0">
                        <div class="p-sm text-sm text-on-surface-variant">No recent activities found.</div>
                    </template>
                    <template x-if="!data.recentActivities">
                        <div class="p-sm text-sm text-on-surface-variant text-center">Loading...</div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<template x-if="activeCommunity">
    <x-community-guidelines-modal :community="null" />
</template>
</div>

@push('scripts')
<script>
function dashboardState() {
    return {
        showGuidelinesModal: false,
        canEditGuidelines: false,
        activeCommunityId: localStorage.getItem('active_community_id') || null,
        activeCommunity: null,
        data: {
            metrics: {
                totalEvents: 0,
                totalParticipants: 0,
                attendanceRate: 0,
                certificatesGenerated: 0
            },
            upcomingEvents: [],
            recentActivities: [],
            monthlyEvents: { labels: [], data: [] },
            attendanceTrends: { labels: [], data: { registered: [], attended: [] } }
        },
        
        formatDateRelatively(dateStr) {
            const date = new Date(dateStr);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);
            
            if (diffInSeconds < 60) return 'Just now';
            if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + 'm ago';
            if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + 'h ago';
            return Math.floor(diffInSeconds / 86400) + 'd ago';
        },

        async init() {
            if (!this.activeCommunityId) return;

            // Fetch active community info
            const commRes = await window.apiFetch('/api/communities');
            if (commRes.ok) {
                const allComms = await commRes.json();
                this.activeCommunity = allComms.find(c => c.id == this.activeCommunityId);
                
                // TODO: Set canEditGuidelines properly if needed
                // For now, let's just show it if owner/admin.
                this.canEditGuidelines = true; 
            }

            // Fetch Analytics Dashboard Data
            const dashboardRes = await window.apiFetch(`/api/analytics/${this.activeCommunityId}/dashboard`);
            if (dashboardRes.ok) {
                const result = await dashboardRes.json();
                this.data = result.data;
                this.renderCharts();
                this.listenForActivities();
            }
        },

        renderCharts() {
            // 1. Monthly Events Area Chart
            const monthlyEventsCtx = document.getElementById('monthlyEventsChart');
            if (monthlyEventsCtx) {
                new Chart(monthlyEventsCtx, {
                    type: 'line',
                    data: {
                        labels: this.data.monthlyEvents.labels,
                        datasets: [{
                            label: 'Monthly Events',
                            data: this.data.monthlyEvents.data,
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
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false, drawBorder: false }, ticks: { color: '#8c909f', font: { family: 'JetBrains Mono', size: 10 } } },
                            y: { display: false, min: 0, suggestedMax: Math.max(...this.data.monthlyEvents.data, 5) + 2 }
                        }
                    }
                });
            }

            // 2. Attendance Trends Bar Chart
            const attendanceTrendsCtx = document.getElementById('attendanceTrendsChart');
            if (attendanceTrendsCtx) {
                new Chart(attendanceTrendsCtx, {
                    type: 'bar',
                    data: {
                        labels: this.data.attendanceTrends.labels,
                        datasets: [
                            {
                                label: 'Registered',
                                data: this.data.attendanceTrends.data ? this.data.attendanceTrends.data.registered || this.data.attendanceTrends.data : [],
                                backgroundColor: 'rgba(173, 198, 255, 0.2)',
                                hoverBackgroundColor: 'rgba(173, 198, 255, 0.3)',
                                borderRadius: { topLeft: 4, topRight: 4, bottomLeft: 0, bottomRight: 0 },
                                barPercentage: 0.6,
                                categoryPercentage: 0.8
                            },
                            {
                                label: 'Attended',
                                data: this.data.attendanceTrends.data ? this.data.attendanceTrends.data.attended || this.data.attendanceTrends.data : [],
                                backgroundColor: '#adc6ff',
                                borderRadius: { topLeft: 4, topRight: 4, bottomLeft: 0, bottomRight: 0 },
                                barPercentage: 0.6,
                                categoryPercentage: 0.8
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false, drawBorder: false }, ticks: { color: '#8c909f', font: { family: 'JetBrains Mono', size: 10 } } },
                            y: { display: false, min: 0, suggestedMax: 10 }
                        }
                    }
                });
            }
        },

        listenForActivities() {
            if (this.activeCommunityId && window.Echo) {
                window.Echo.channel(`community.${this.activeCommunityId}.activities`)
                    .listen('NewActivityLogged', (e) => {
                        const newActivity = {
                            description: e.log.description,
                            created_at: new Date().toISOString(),
                            user: e.log.user || { name: 'System' }
                        };
                        this.data.recentActivities.unshift(newActivity);
                        if (this.data.recentActivities.length > 10) {
                            this.data.recentActivities.pop();
                        }
                    });
            }
        }
    };
}
</script>
@endpush
@endsection
