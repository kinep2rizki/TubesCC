@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div x-data="eventManagementState()" class="max-w-container-max mx-auto space-y-xl pb-32 md:pb-2xl w-full">
    <!-- Page Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-md">
        <div>
            <h2 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-on-surface">Events</h2>
            <p class="font-body-base text-body-base text-on-surface-variant mt-xs">Manage upcoming tech events and hackathons.</p>
        </div>
        
        <form @submit.prevent="submitForm()" class="flex flex-col sm:flex-row gap-sm items-start sm:items-center w-full md:w-auto">
            <!-- Search -->
            <div class="relative w-full sm:w-auto flex-1">
                <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                <input x-model="search" @keydown.enter.prevent="submitForm()" class="w-full bg-surface-container rounded-lg py-2 pl-xl pr-sm text-body-sm font-body-sm text-on-surface border border-outline-variant/30 focus:border-primary focus:outline-none" placeholder="Search events..." type="text"/>
            </div>
            
            <!-- Filter by Status -->
            <div class="relative w-full sm:w-auto">
                <select x-model="status" @change="submitForm()" class="w-full appearance-none bg-surface-container rounded-lg py-2 pl-md pr-xl text-body-sm font-body-sm text-on-surface border border-outline-variant/30 focus:border-primary focus:outline-none cursor-pointer">
                    <option value="All">All Status</option>
                    <option value="Upcoming">Upcoming</option>
                    <option value="Live Now">Live Now</option>
                    <option value="Finished">Finished</option>
                </select>
                <span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[18px]">arrow_drop_down</span>
            </div>
            
            <!-- Primary Blue Button -->
            <template x-if="canManageEvent">
                <button type="button" @click="showCreateEventModal = true" class="flex items-center gap-xs px-md py-2 rounded-lg bg-gradient-to-r from-primary-container to-blue-600 text-white font-label-caps text-label-caps w-full sm:w-auto justify-center shadow-[0_0_15px_rgba(77,142,255,0.3)] hover:shadow-[0_0_20px_rgba(77,142,255,0.5)] transition-shadow">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Create Event
                </button>
            </template>
        </form>
    </div>

    <!-- Events Grid (Bento/Card Layout) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
        <template x-for="event in filteredEvents" :key="event.id">
            <a :href="'/events/' + event.id" class="glass-panel rounded-xl overflow-hidden group hover:-translate-y-1 transition-transform duration-300 block">
                <!-- Image Banner Placeholder -->
                <div class="h-32 w-full bg-surface-variant relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-900/40 to-blue-900/40 mix-blend-overlay z-10"></div>
                    <img alt="Event Banner" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-500 group-hover:scale-105 transform" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCCJzpsovxejyPZao4ZlZ6jp2V_ooh-F6tRVEq_s-y7ZfXOewlRmauNdg_e0b9T9CF3KapW1we9FZoNHCKfC9vLvTGpzA9UPyOV3oCtOWrfIusmzJBMfWI5I10f_Lmbr4qx0dYKDBnWnm7uVkbQoZYOJq_fGIQ-j6Y-9qXGq_wa4ErxsJ2yAdiVMLvON3KnJB2d59B9cG_puGMXs6ozBSsoZOHtjSC6c9M8173lkkl8q0PnoLpTW0AWi7SirXWAzIdDp0bPFIpOkoN0"/>
                    <div class="absolute top-sm right-sm z-20 bg-surface-container/90 backdrop-blur-sm px-xs py-[2px] rounded text-primary border border-primary/30 font-label-caps text-[10px]" x-text="event.status">
                    </div>
                </div>
                <div class="p-md flex flex-col gap-sm">
                    <div class="flex justify-between items-start">
                        <span class="font-label-caps text-label-caps text-primary bg-primary/10 px-2 py-1 rounded-sm" x-text="event.community ? event.community.name : 'General'"></span>
                        <span class="font-mono-code text-mono-code text-on-surface-variant flex items-center gap-xs">
                            <span class="material-symbols-outlined text-[14px]">calendar_today</span> 
                            <span x-text="new Date(event.start_date).toLocaleString('en-US', { month: 'short', day: 'numeric' })"></span>
                        </span>
                    </div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface truncate" x-text="event.title"></h3>
                    <div class="flex items-center gap-md mt-xs">
                        <template x-if="event.participants_count > 3">
                            <div class="flex -space-x-2">
                                <div class="w-6 h-6 rounded-full border border-surface bg-surface-container z-30"></div>
                                <div class="w-6 h-6 rounded-full border border-surface bg-surface-variant z-20"></div>
                                <div class="w-6 h-6 rounded-full border border-surface bg-surface-bright z-10 flex items-center justify-center text-[8px] font-bold" x-text="'+' + (event.participants_count - 3)"></div>
                            </div>
                        </template>
                        <span class="font-body-sm text-body-sm text-on-surface-variant" x-text="event.participants_count + ' Participants'"></span>
                    </div>
                    <div class="mt-md space-y-xs">
                        <div class="flex justify-between font-label-caps text-label-caps text-on-surface-variant">
                            <span>Attendance Target</span>
                            <span class="text-primary" x-text="event.attendance_rate + '%'"></span>
                        </div>
                        <div class="h-1.5 w-full bg-surface-container-high rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full relative" :style="`width: ${event.attendance_rate}%`">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/30"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </template>
        <template x-if="!isLoading && filteredEvents.length === 0">
            <div class="col-span-full text-center text-on-surface-variant p-lg bg-surface-container rounded-xl border border-outline-variant/30">
                No events found.
            </div>
        </template>
        <template x-if="isLoading">
            <div class="col-span-full text-center text-on-surface-variant p-lg bg-surface-container rounded-xl border border-outline-variant/30">
                Loading events...
            </div>
        </template>
    </div>
    <x-create-event-modal />
</div>

@push('scripts')
<script>
function eventManagementState() {
    return {
        showCreateEventModal: false,
        eventsList: [],
        search: '',
        status: 'All',
        activeCommunityId: localStorage.getItem('active_community_id') || null,
        canManageEvent: true, // simplified for now, ideally check user roles
        isLoading: true,
        
        async init() {
            if (!this.activeCommunityId) {
                this.isLoading = false;
                return;
            }
            
            // Read query params from URL for initial state
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('search')) this.search = urlParams.get('search');
            if (urlParams.has('status')) this.status = urlParams.get('status');

            await this.fetchEvents();
        },
        
        async fetchEvents() {
            try {
                this.isLoading = true;
                const res = await window.apiFetch(`/api/events?community_id=${this.activeCommunityId}`);
                if (res.ok) {
                    const result = await res.json();
                    this.eventsList = result.data.data || result.data;
                }
            } catch (e) {
                console.error("Failed fetching events", e);
            } finally {
                this.isLoading = false;
            }
        },
        
        submitForm() {
            // Update URL without reload
            const url = new URL(window.location);
            if (this.search) url.searchParams.set('search', this.search);
            else url.searchParams.delete('search');
            
            if (this.status && this.status !== 'All') url.searchParams.set('status', this.status);
            else url.searchParams.delete('status');
            
            window.history.pushState({}, '', url);
        },
        
        get filteredEvents() {
            return this.eventsList.filter(e => {
                const matchesSearch = e.title.toLowerCase().includes(this.search.toLowerCase());
                const matchesStatus = this.status === 'All' || e.status === this.status;
                return matchesSearch && matchesStatus;
            });
        }
    }
}
</script>
@endpush
@endsection
