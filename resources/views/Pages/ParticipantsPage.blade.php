@extends('layouts.app')

@section('title', 'Participants')

@section('content')
<div x-data="{ 
        selectAll: false, 
        selected: [],
        participants: ['p1', 'p2', 'p3', 'p4'],
        showExportModal: false,
        showAddModal: false,
        toggleAll() {
            if (this.selectAll) {
                this.selected = [...this.participants];
            } else {
                this.selected = [];
            }
        }
    }" 
    x-init="$watch('selected', value => { selectAll = value.length === participants.length })"
    class="max-w-container-max mx-auto w-full flex flex-col gap-xl">
    
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md">
        <div>
            <h1 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-on-surface">Participants</h1>
            <p class="font-body-base text-body-base text-on-surface-variant mt-1">Manage attendees, view statuses, and export data.</p>
        </div>
        
        <div class="flex items-center gap-sm w-full sm:w-auto">
            <button @click="showExportModal = true" class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-surface-container border border-outline-variant/50 text-on-surface font-body-sm text-body-sm hover:bg-surface-container-highest transition-colors flex-1 sm:flex-none">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Export CSV
            </button>
            <button @click="showAddModal = true" class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-primary-container text-on-primary-container font-body-sm text-body-sm hover:bg-primary-fixed transition-colors shadow-[0_0_15px_rgba(77,142,255,0.2)] flex-1 sm:flex-none">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                Add New
            </button>
        </div>
    </div>

    <!-- Alpine.js Table Context -->
    <div>

        <!-- Toolbar / Filters -->
        <div class="flex flex-col sm:flex-row justify-between items-center bg-surface-container-lowest p-xs rounded-xl border border-outline-variant/30 gap-sm mb-lg">
            <!-- Table specific search -->
            <div class="relative w-full sm:max-w-xs flex items-center">
                <span class="material-symbols-outlined absolute left-3 text-outline-variant text-[20px]">search</span>
                <input class="w-full bg-surface-container-low border-none rounded-lg pl-10 pr-4 py-2 text-on-surface placeholder-outline-variant focus:ring-1 focus:ring-primary focus:bg-surface-container transition-all font-body-sm text-body-sm" placeholder="Search participants..." type="text"/>
            </div>
            
            <!-- Filters -->
            <div class="flex items-center gap-sm w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0 hide-scrollbar no-scrollbar">
                <button class="flex items-center gap-2 px-3 py-1.5 rounded-md border border-outline-variant/30 text-on-surface-variant hover:text-on-surface hover:bg-white/5 transition-colors font-body-sm text-body-sm whitespace-nowrap">
                    Role
                    <span class="material-symbols-outlined text-[16px]">expand_more</span>
                </button>
                <button class="flex items-center gap-2 px-3 py-1.5 rounded-md border border-outline-variant/30 text-on-surface-variant hover:text-on-surface hover:bg-white/5 transition-colors font-body-sm text-body-sm whitespace-nowrap">
                    Institution
                    <span class="material-symbols-outlined text-[16px]">expand_more</span>
                </button>
                <button class="flex items-center gap-2 px-3 py-1.5 rounded-md border border-outline-variant/30 text-on-surface-variant hover:text-on-surface hover:bg-white/5 transition-colors font-body-sm text-body-sm whitespace-nowrap">
                    Status
                    <span class="material-symbols-outlined text-[16px]">expand_more</span>
                </button>
                <div class="w-px h-6 bg-outline-variant/50 mx-xs hidden sm:block"></div>
                <button class="flex items-center gap-2 px-3 py-1.5 rounded-md text-primary hover:bg-primary-container/10 transition-colors font-body-sm text-body-sm whitespace-nowrap">
                    <span class="material-symbols-outlined text-[16px]">filter_list_off</span>
                    Clear
                </button>
            </div>
        </div>

        <!-- Data Table (SaaS Style) -->
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl overflow-hidden shadow-[0_8px_30px_rgba(0,0,0,0.5)]">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-outline-variant/30 bg-surface-container/30">
                            <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant w-12">
                                <div class="flex items-center justify-center">
                                    <input x-model="selectAll" @change="toggleAll" class="rounded border-outline-variant bg-surface-container-high focus:ring-primary text-primary w-4 h-4 cursor-pointer" type="checkbox"/>
                                </div>
                            </th>
                            <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant">Name</th>
                            <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant">Email</th>
                            <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant">Institution</th>
                            <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant">Status</th>
                            <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant hidden md:table-cell">Registration Date</th>
                            <th class="px-md py-sm w-12"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20">
                        @forelse($participants as $participant)
                        <x-participant-row 
                            id="p{{ $participant->id }}"
                            name="{{ $participant->user->name ?? 'Unknown' }}"
                            role="Attendee"
                            email="{{ $participant->user->email ?? 'N/A' }}"
                            institution="General Participant"
                            status="{{ $participant->status }}"
                            date="{{ $participant->created_at->format('M d, Y') }}"
                            avatarInitials="{{ substr($participant->user->name ?? 'U', 0, 2) }}"
                        />
                        @empty
                        <tr>
                            <td colspan="7" class="text-center p-md text-on-surface-variant">No participants found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Footer -->
            <div class="flex items-center justify-between px-md py-sm bg-surface-container/30 border-t border-outline-variant/30">
                <span class="font-body-sm text-body-sm text-on-surface-variant">Showing <span x-text="selected.length ? selected.length + ' selected' : '1 to 4 of 128 results'"></span></span>
                <div class="flex items-center gap-xs">
                    <button class="p-1 rounded-md text-on-surface-variant hover:bg-white/5 disabled:opacity-50 transition-colors" disabled>
                        <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                    </button>
                    <button class="w-8 h-8 rounded-md bg-primary-container/20 text-primary border border-primary-container/30 font-body-sm text-body-sm flex items-center justify-center">1</button>
                    <button class="w-8 h-8 rounded-md text-on-surface-variant hover:bg-white/5 border border-transparent font-body-sm text-body-sm flex items-center justify-center transition-colors">2</button>
                    <button class="w-8 h-8 rounded-md text-on-surface-variant hover:bg-white/5 border border-transparent font-body-sm text-body-sm flex items-center justify-center transition-colors">3</button>
                    <span class="text-on-surface-variant px-1">...</span>
                    <button class="p-1 rounded-md text-on-surface-variant hover:bg-white/5 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <x-export-csv-modal />
    <x-add-participant-modal :event="$event" />
</div>
@endsection
