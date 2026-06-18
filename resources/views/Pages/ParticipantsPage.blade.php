@extends('layouts.app')

@section('title', 'Participants')

@section('content')
<div x-data="participantsState({{ $eventId }})" 
    x-init="init()"
    class="max-w-container-max mx-auto w-full flex flex-col gap-xl">
    
    <!-- Event Header (Will adapt to JS state instead of PHP $event) -->
    <x-event-header eventId="{{ $eventId }}" activeTab="participants" />

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md mt-sm">
        <div>
            <h3 class="font-headline-sm text-headline-sm text-on-surface">Participant List</h3>
        </div>
        
        <div class="flex items-center gap-sm w-full sm:w-auto" x-show="canManage">
            <button @click="exportCSV()" class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-surface-container border border-outline-variant/50 text-on-surface font-body-sm text-body-sm hover:bg-surface-container-highest transition-colors flex-1 sm:flex-none">
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
    <div class="relative">
        
        <!-- Loading Overlay -->
        <div x-show="isLoading" class="absolute inset-0 z-50 flex items-center justify-center bg-surface-container-lowest/50 backdrop-blur-sm rounded-xl">
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary border-t-transparent"></div>
        </div>

        <!-- Toolbar / Filters -->
        <div class="flex flex-col sm:flex-row justify-between items-center bg-surface-container-lowest p-xs rounded-xl border border-outline-variant/30 gap-sm mb-lg">
            <!-- Table specific search -->
            <div class="relative w-full sm:max-w-xs flex items-center">
                <span class="material-symbols-outlined absolute left-3 text-outline-variant text-[20px]">search</span>
                <input x-model.debounce.500ms="filters.search" class="w-full bg-surface-container-low border-none rounded-lg pl-10 pr-4 py-2 text-on-surface placeholder-outline-variant focus:ring-1 focus:ring-primary focus:bg-surface-container transition-all font-body-sm text-body-sm" placeholder="Search participants..." type="text"/>
            </div>
            
            <!-- Filters -->
            <div class="flex items-center gap-sm w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0 hide-scrollbar no-scrollbar">
                <select x-model="filters.status" class="bg-transparent border border-outline-variant/30 text-on-surface-variant hover:text-on-surface hover:bg-white/5 transition-colors font-body-sm text-body-sm rounded-md px-3 py-1.5 focus:outline-none">
                    <option value="">Status (All)</option>
                    <option value="Registered">Registered</option>
                    <option value="Attended">Attended</option>
                </select>
                <div class="w-px h-6 bg-outline-variant/50 mx-xs hidden sm:block"></div>
                <button @click="clearFilters()" class="flex items-center gap-2 px-3 py-1.5 rounded-md text-primary hover:bg-primary-container/10 transition-colors font-body-sm text-body-sm whitespace-nowrap">
                    <span class="material-symbols-outlined text-[16px]">filter_list_off</span>
                    Clear
                </button>
            </div>
        </div>

        <!-- Data Table (SaaS Style) -->
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl shadow-[0_8px_30px_rgba(0,0,0,0.5)]">
            <div class="overflow-x-auto pb-32">
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
                        <template x-for="participant in participants" :key="participant.id">
                            <x-participant-row-js />
                        </template>
                        
                        <tr x-show="participants.length === 0 && !isLoading">
                            <td colspan="7" class="text-center p-md text-on-surface-variant">No participants found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Footer -->
            <div class="flex items-center justify-between px-md py-sm bg-surface-container/30 border-t border-outline-variant/30">
                <span class="font-body-sm text-body-sm text-on-surface-variant">
                    Showing <span x-text="selected.length ? selected.length + ' selected' : (pagination.from || 0) + ' to ' + (pagination.to || 0) + ' of ' + pagination.total + ' results'"></span>
                </span>
                <div class="flex items-center gap-xs">
                    <button @click="changePage(pagination.current_page - 1)" :disabled="!pagination.prev_page_url" :class="!pagination.prev_page_url ? 'opacity-50 pointer-events-none' : ''" class="p-1 rounded-md text-on-surface-variant hover:bg-white/5 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                    </button>
                    
                    <template x-for="page in pagination.last_page" :key="page">
                        <button @click="changePage(page)" x-text="page" :class="page === pagination.current_page ? 'bg-primary-container/20 text-primary border border-primary-container/30' : 'text-on-surface-variant hover:bg-white/5 border border-transparent'" class="w-8 h-8 rounded-md font-body-sm text-body-sm flex items-center justify-center transition-colors">
                        </button>
                    </template>

                    <button @click="changePage(pagination.current_page + 1)" :disabled="!pagination.next_page_url" :class="!pagination.next_page_url ? 'opacity-50 pointer-events-none' : ''" class="p-1 rounded-md text-on-surface-variant hover:bg-white/5 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Participant Modal -->
    <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showAddModal = false"></div>
        <div class="relative bg-surface-container-low border border-outline-variant/30 rounded-2xl w-full max-w-md shadow-2xl p-lg z-10 mx-4" x-transition>
            <div class="flex justify-between items-center mb-md">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Add Participant</h3>
                <button @click="showAddModal = false" class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form @submit.prevent="submitAddParticipant">
                <div class="flex flex-col gap-md mb-lg">
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Select Member</label>
                        <select x-model="addForm.user_id" required class="w-full bg-surface-container border border-outline-variant/30 rounded-lg px-4 py-2 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                            <option value="">-- Choose Community Member --</option>
                            <template x-for="member in communityMembers" :key="member.user_id">
                                <option :value="member.user_id" x-text="member.user_detail ? member.user_detail.name + ' (' + member.user_detail.email + ')' : 'User ID ' + member.user_id"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Status</label>
                        <select x-model="addForm.status" required class="w-full bg-surface-container border border-outline-variant/30 rounded-lg px-4 py-2 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary transition-all appearance-none">
                            <option value="Registered">Registered</option>
                            <option value="Attended">Attended</option>
                            <option value="Waitlisted">Waitlisted</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end gap-sm">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 rounded-lg font-label-lg text-label-lg text-on-surface-variant hover:bg-surface-variant transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-lg font-label-lg text-label-lg bg-primary text-on-primary hover:bg-primary/90 transition-colors flex items-center gap-2" :disabled="isSubmitting">
                        <span x-show="isSubmitting" class="animate-spin rounded-full h-4 w-4 border-2 border-on-primary border-t-transparent"></span>
                        <span x-text="isSubmitting ? 'Saving...' : 'Add Participant'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('participantsState', (eventId) => ({
        eventId: eventId,
        participants: [],
        isLoading: true,
        isSubmitting: false,
        canManage: false,
        canManageEvent: false,
        canManageCertificates: false,
        selectAll: false,
        selected: [],
        showAddModal: false,
        pagination: {
            current_page: 1,
            last_page: 1,
            total: 0,
            from: 0,
            to: 0,
            prev_page_url: null,
            next_page_url: null
        },
        filters: {
            search: '',
            status: ''
        },
        communityMembers: [],
        addForm: {
            user_id: '',
            status: 'Registered'
        },

        init() {
            this.checkPermissions();
            this.fetchData();

            this.$watch('filters.search', () => { this.pagination.current_page = 1; this.fetchData(); });
            this.$watch('filters.status', () => { this.pagination.current_page = 1; this.fetchData(); });
            this.$watch('selected', value => { this.selectAll = value.length === this.participants.length && this.participants.length > 0; });
        },

        async checkPermissions() {
            try {
                const eventRes = await fetchApi('/api/events/' + this.eventId);
                if (eventRes.success) {
                    const communityId = eventRes.data.community_id;
                    const membersRes = await fetchApi(`/api/communities/${communityId}/members`);
                    if (membersRes.success) {
                        this.communityMembers = membersRes.data;
                    }
                    this.canManage = true; // Placeholder, in real life check roles
                    this.canManageEvent = true;
                    this.canManageCertificates = true;
                }
            } catch (e) { console.error(e); }
        },

        async fetchData() {
            this.isLoading = true;
            try {
                const query = new URLSearchParams({
                    page: this.pagination.current_page,
                    ...(this.filters.search && { search: this.filters.search }),
                    ...(this.filters.status && { status: this.filters.status })
                });

                const response = await fetchApi('/api/events/' + this.eventId + '/participants?' + query.toString());
                
                if (response.success) {
                    this.participants = response.data.data;
                    this.pagination = {
                        current_page: response.data.current_page,
                        last_page: response.data.last_page,
                        total: response.data.total,
                        from: response.data.from,
                        to: response.data.to,
                        prev_page_url: response.data.prev_page_url,
                        next_page_url: response.data.next_page_url
                    };
                    this.selected = [];
                }
            } catch (error) {
                console.error("Failed to load participants:", error);
            } finally {
                this.isLoading = false;
            }
        },

        changePage(page) {
            if (page < 1 || page > this.pagination.last_page) return;
            this.pagination.current_page = page;
            this.fetchData();
        },

        clearFilters() {
            this.filters.search = '';
            this.filters.status = '';
        },

        toggleAll() {
            if (this.selectAll) {
                this.selected = this.participants.map(p => p.id);
            } else {
                this.selected = [];
            }
        },

        async submitAddParticipant() {
            this.isSubmitting = true;
            try {
                const res = await fetchApi('/api/events/' + this.eventId + '/participants', 'POST', this.addForm);
                if (res.success) {
                    this.showAddModal = false;
                    this.addForm = { user_id: '', status: 'Registered' };
                    this.fetchData(); // reload table
                } else {
                    alert('Error: ' + (res.message || 'Failed to add participant'));
                }
            } catch (err) {
                alert('Connection error');
            } finally {
                this.isSubmitting = false;
            }
        },
        
        async exportCSV() {
            try {
                const url = `http://127.0.0.1:8002/api/events/${this.eventId}/participants/export`;
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('jwt_token')
                    }
                });

                if (response.ok) {
                    const blob = await response.blob();
                    const downloadUrl = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = downloadUrl;
                    a.download = `participants_event_${this.eventId}.csv`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(downloadUrl);
                    alert('Berhasil mengexport data partisipan!');
                } else {
                    alert('Gagal mengekspor data partisipan.');
                }
            } catch (e) {
                console.error("Export failed", e);
                alert('Kesalahan jaringan saat mengekspor data.');
            }
        },
        
        async updateStatus(participantId, newStatus) {
            try {
                const res = await fetchApi(`/api/events/${this.eventId}/participants/${participantId}`, 'PUT', { status: newStatus });
                if (res.success) {
                    const p = this.participants.find(p => p.id === participantId);
                    if (p) p.status = newStatus;
                }
            } catch (e) {
                console.error("Update failed", e);
            }
        }
    }));
});
</script>
@endsection
