@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div x-data="{ showCreateEventModal: false }" class="max-w-container-max mx-auto space-y-xl pb-32 md:pb-2xl w-full">
    <!-- Page Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-md">
        <div>
            <h2 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-on-surface">Events</h2>
            <p class="font-body-base text-body-base text-on-surface-variant mt-xs">Manage upcoming tech events and hackathons.</p>
        </div>
        
        <form action="{{ route('events') }}" method="GET" class="flex flex-col sm:flex-row gap-sm items-start sm:items-center w-full md:w-auto" x-data="{
            submitForm() {
                this.$el.submit();
            }
        }">
            <!-- Search -->
            <div class="relative w-full sm:w-auto flex-1">
                <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                <input name="search" value="{{ request('search') }}" @keydown.enter.prevent="submitForm()" class="w-full bg-surface-container rounded-lg py-2 pl-xl pr-sm text-body-sm font-body-sm text-on-surface border border-outline-variant/30 focus:border-primary focus:outline-none" placeholder="Search events..." type="text"/>
            </div>
            
            <!-- Filter by Status -->
            <div class="relative w-full sm:w-auto">
                <select name="status" @change="submitForm()" class="w-full appearance-none bg-surface-container rounded-lg py-2 pl-md pr-xl text-body-sm font-body-sm text-on-surface border border-outline-variant/30 focus:border-primary focus:outline-none cursor-pointer">
                    <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All Status</option>
                    <option value="Upcoming" {{ request('status') == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="Live Now" {{ request('status') == 'Live Now' ? 'selected' : '' }}>Live Now</option>
                    <option value="Finished" {{ request('status') == 'Finished' ? 'selected' : '' }}>Finished</option>
                </select>
                <span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[18px]">arrow_drop_down</span>
            </div>
            
            <!-- Primary Blue Button -->
            @if(auth()->user() && auth()->user()->canManageEvent(session('active_community_id')))
            <button type="button" @click="showCreateEventModal = true" class="flex items-center gap-xs px-md py-2 rounded-lg bg-gradient-to-r from-primary-container to-blue-600 text-white font-label-caps text-label-caps w-full sm:w-auto justify-center shadow-[0_0_15px_rgba(77,142,255,0.3)] hover:shadow-[0_0_20px_rgba(77,142,255,0.5)] transition-shadow">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Create Event
            </button>
            @endif
        </form>
    </div>

    <!-- Events Grid (Bento/Card Layout) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
        @forelse($eventsList as $event)
        <x-event-card 
            title="{{ $event->title }}"
            type="{{ $event->community->name ?? 'General' }}"
            date="{{ \Carbon\Carbon::parse($event->start_date)->format('M d') }}"
            status="{{ $event->status }}"
            participantsCount="{{ $event->participants_count }} Participants"
            avatarsLabel="+{{ max(0, $event->participants_count - 3) }}"
            progressLabel="Attendance Target"
            progressValue="{{ $event->attendance_rate }}%"
            imageUrl="https://lh3.googleusercontent.com/aida-public/AB6AXuCCJzpsovxejyPZao4ZlZ6jp2V_ooh-F6tRVEq_s-y7ZfXOewlRmauNdg_e0b9T9CF3KapW1we9FZoNHCKfC9vLvTGpzA9UPyOV3oCtOWrfIusmzJBMfWI5I10f_Lmbr4qx0dYKDBnWnm7uVkbQoZYOJq_fGIQ-j6Y-9qXGq_wa4ErxsJ2yAdiVMLvON3KnJB2d59B9cG_puGMXs6ozBSsoZOHtjSC6c9M8173lkkl8q0PnoLpTW0AWi7SirXWAzIdDp0bPFIpOkoN0"
            hasProgressGradient="true"
            link="{{ route('event-detail', $event->id) }}"
        />
        @empty
        <div class="col-span-full text-center text-on-surface-variant p-lg bg-surface-container rounded-xl border border-outline-variant/30">
            No events found.
        </div>
        @endforelse
    </div>
    <x-create-event-modal />
</div>
@endsection
