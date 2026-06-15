@extends('layouts.app')

@section('title', 'Events')

@section('content')
<div class="max-w-container-max mx-auto space-y-xl pb-32 md:pb-2xl w-full">
    <!-- Page Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-md">
        <div>
            <h2 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-on-surface">Events</h2>
            <p class="font-body-base text-body-base text-on-surface-variant mt-xs">Manage upcoming tech events and hackathons.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-sm items-start sm:items-center">
            <!-- Mobile Search (if hidden on desktop nav) -->
            <div class="md:hidden relative w-full sm:w-auto flex-1">
                <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                <input class="w-full bg-surface-container rounded-lg py-2 pl-xl pr-sm text-body-sm font-body-sm text-on-surface border border-outline-variant/30 focus:border-primary focus:outline-none" placeholder="Search..." type="text"/>
            </div>
            
            <button class="flex items-center gap-xs px-md py-2 rounded-lg border border-outline-variant/50 text-on-surface-variant hover:bg-surface-variant transition-colors font-label-caps text-label-caps w-full sm:w-auto justify-center">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                Filter
            </button>
            
            <!-- Primary Blue Button -->
            <button class="flex items-center gap-xs px-md py-2 rounded-lg bg-gradient-to-r from-primary-container to-blue-600 text-white font-label-caps text-label-caps w-full sm:w-auto justify-center shadow-[0_0_15px_rgba(77,142,255,0.3)] hover:shadow-[0_0_20px_rgba(77,142,255,0.5)] transition-shadow">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Create Event
            </button>
        </div>
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
            progressValue="85%"
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
</div>
@endsection
