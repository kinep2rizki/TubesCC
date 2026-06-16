@props(['event', 'activeTab' => 'overview'])

@php
    $user = auth()->user();
    $canManageEvent = $user && $event->community ? $user->canManageEvent($event->community_id) : false;
    $canManageCertificates = $user && $event->community ? $user->canManageCertificates($event->community_id) : false;
@endphp

<!-- Back Button -->
<a href="{{ route('events') }}" class="inline-flex items-center gap-xs text-on-surface-variant hover:text-primary transition-colors font-label-caps text-label-caps w-fit group mb-lg">
    <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
    Back to Events
</a>

<!-- Event Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-md mb-xl">
    <div class="flex flex-col gap-sm">
        <div class="flex items-center gap-sm">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold"
                  :class="event && event.status === 'Live Now' ? 'bg-primary/20 text-primary border border-primary/30' : 'bg-surface-variant text-on-surface-variant border border-outline-variant/30'">
                <template x-if="event && event.status === 'Live Now'">
                    <span class="w-1.5 h-1.5 bg-primary rounded-full mr-1.5 animate-pulse"></span>
                </template>
                <span x-text="event ? event.status : 'Loading...'"></span>
            </span>
            <span class="text-on-surface-variant text-body-sm font-body-sm font-mono-code" x-text="event ? 'ID: EVT-' + event.id : '...'"></span>
        </div>
        <h2 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary" x-text="event ? event.title : 'Loading Event...'"></h2>
        <p class="text-on-surface-variant text-body-base font-body-base max-w-2xl" x-text="event && event.description ? event.description : 'No description provided.'"></p>
    </div>
    
    <div x-data="{
        copyLink() {
            navigator.clipboard.writeText(window.location.origin + '/events/' + eventId);
            alert('Event link copied to clipboard!');
        }
    }" class="flex items-center gap-sm mt-4 md:mt-0 w-full md:w-auto">
        <button @click="copyLink()" class="flex-1 md:flex-none flex items-center justify-center gap-xs px-md py-sm rounded-lg border border-outline-variant bg-surface-container-low text-on-surface hover:bg-surface-variant transition-colors font-label-caps text-label-caps">
            <span class="material-symbols-outlined text-[18px]" data-icon="share">share</span>
            Share
        </button>
        
        <template x-if="canManageEvent">
            <button @click="showEditEventModal = true" class="flex-1 md:flex-none flex items-center justify-center gap-xs px-md py-sm rounded-lg bg-primary text-on-primary font-label-caps text-label-caps hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-[18px]" data-icon="edit">edit</span>
                Edit Event
            </button>
        </template>
    </div>
</div>

<!-- In-Page Tabs -->
<div class="sticky top-0 z-30 shrink-0 bg-[#0F1014] pt-sm pb-sm border-b border-outline-variant/30 mb-lg flex overflow-x-auto no-scrollbar shadow-sm">
    <a :href="'/events/' + eventId" class="px-md py-sm font-body-base text-body-base {{ $activeTab === 'overview' ? 'text-primary font-semibold border-b-2 border-primary' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-variant/20 transition-colors' }} whitespace-nowrap">Overview</a>
    
    <a :href="'/events/' + eventId + '/participants'" class="px-md py-sm font-body-base text-body-base {{ $activeTab === 'participants' ? 'text-primary font-semibold border-b-2 border-primary' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-variant/20 transition-colors' }} whitespace-nowrap">Participants</a>
    
    <a :href="'/events/' + eventId + '/attendance'" class="px-md py-sm font-body-base text-body-base {{ $activeTab === 'attendance' ? 'text-primary font-semibold border-b-2 border-primary' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-variant/20 transition-colors' }} whitespace-nowrap">Attendance</a>
    
    <template x-if="canManageCertificates">
        <a :href="'/events/' + eventId + '/certificates'" class="px-md py-sm font-body-base text-body-base {{ $activeTab === 'certificates' ? 'text-primary font-semibold border-b-2 border-primary' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-variant/20 transition-colors' }} whitespace-nowrap">Certificates</a>
    </template>
</div>
