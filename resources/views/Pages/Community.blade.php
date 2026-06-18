@extends('layouts.app')

@section('title', 'Community Management')

@section('content')
<div x-data="communityPageState()" class="max-w-container-max mx-auto w-full flex flex-col gap-lg">
    
    <!-- Page Header -->
    <div class="mb-xl pb-sm border-b border-outline-variant/30 flex justify-between items-end">
        <h1 class="font-display-lg text-display-lg text-on-surface tracking-tight">Community Management</h1>
        <button @click="showCreateCommunityModal = true" class="bg-primary text-on-primary px-lg py-sm rounded-lg font-label-caps text-label-caps hover:opacity-90 transition-opacity shadow-lg flex items-center gap-xs">
            <span class="material-symbols-outlined text-[18px]">add</span> New Community
        </button>
    </div>

    <!-- Community Header Profile -->
    <section class="mb-xl bg-surface-container-low rounded-xl border border-outline-variant/30 p-lg flex flex-col md:flex-row gap-lg items-start md:items-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(circle at top right, theme('colors.primary'), transparent 40%);"></div>
        <div class="relative w-24 h-24 md:w-32 md:h-32 rounded-xl overflow-hidden border-2 border-surface-variant shrink-0 shadow-lg">
            <img alt="Community Avatar" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBud5_jHf_KxDU2z-5bD_eC-bnbUbIO_hbCcVzMeq3XtT-auyZnBJDbeisPDxdKltt8TcbBYO8ea7J9OGhMSOF2BCffNJgIBqRP2PngFYdd3716FdI9GQU9HJk1k6Kv3WCk_3zVM-EPtiSmnE3TNMf2Cl8MRVQZHzVQn9tepM7fmIvcYK_D5gonsqkOYz-gBWURZpx79J7_JnjmG1elSTxGRDBGAVkqY_IFw2UHQZO3eAWYGaQMY7o3gCCwdb4Uu9VuCole81kxlQPI"/>
        </div>
        <div class="flex-1 relative z-10">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-md mb-sm">
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface mb-xs flex items-center gap-sm">
                        <span x-text="activeCommunity ? activeCommunity.name : 'Loading...'"></span>
                        <span class="material-symbols-outlined text-primary text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>
                    </h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant max-w-2xl" x-text="activeCommunity ? activeCommunity.description : ''">
                    </p>
                </div>
                <div class="flex gap-sm shrink-0">
                    <button @click="showGuidelinesModal = true" class="bg-surface-variant text-on-surface-variant px-lg py-sm rounded-lg font-label-caps text-label-caps hover:bg-surface-variant/80 transition-colors">
                        Edit Profile
                    </button>
                </div>
            </div>
            <div class="flex gap-lg mt-md pt-md border-t border-outline-variant/20">
                <div class="flex flex-col">
                    <span class="font-display-lg-mobile text-display-lg-mobile text-on-surface" x-text="analytics.total_members || members.length || 0"></span>
                    <span class="font-label-caps text-label-caps text-outline">Total Members</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-display-lg-mobile text-display-lg-mobile text-on-surface" x-text="analytics.active_today || 0"></span>
                    <span class="font-label-caps text-label-caps text-outline">Active Today</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-display-lg-mobile text-display-lg-mobile text-primary" x-text="(analytics.growth > 0 ? '+' : '') + (analytics.growth || 0) + '%'"></span>
                    <span class="font-label-caps text-label-caps text-outline">Growth (30d)</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Bento Grid Layout for Management Features -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
        <!-- Member Management List (Spans 2 columns) -->
        <div class="lg:col-span-2 glass-panel rounded-xl flex flex-col h-[500px]">
            <div class="p-md border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-lowest/50 rounded-t-xl">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Member Management</h3>
                <div class="flex gap-sm">
                    <button class="text-on-surface-variant hover:text-primary transition-colors"><span class="material-symbols-outlined">filter_list</span></button>
                    <button class="text-on-surface-variant hover:text-primary transition-colors"><span class="material-symbols-outlined">more_vert</span></button>
                </div>
            </div>
            <!-- Table Header -->
            <div class="grid grid-cols-12 gap-sm px-md py-xs border-b border-outline-variant/20 font-label-caps text-label-caps text-outline">
                <div class="col-span-6 md:col-span-5">Member</div>
                <div class="col-span-3 hidden md:block">Joined</div>
                <div class="col-span-4 md:col-span-3 text-center">Role</div>
                <div class="col-span-2 md:col-span-1 text-right">Actions</div>
            </div>
            <!-- Member List (Scrollable) -->
            <div class="flex-1 overflow-y-auto p-xs">
                <template x-for="member in members" :key="member.id">
                    <!-- Member Row -->
                    <div class="grid grid-cols-12 gap-sm px-md py-sm items-center hover:bg-white/[0.03] rounded-lg transition-colors group border-b border-outline-variant/10 last:border-0">
                        <div class="col-span-6 md:col-span-5 flex items-center gap-md">
                            <div class="w-8 h-8 rounded-full bg-surface-variant flex items-center justify-center text-on-surface-variant font-label-caps uppercase" x-text="member.user_detail?.name ? member.user_detail.name.substring(0, 2) : 'U'"></div>
                            <div class="flex flex-col">
                                <span class="font-body-sm text-body-sm text-on-surface" x-text="member.user_detail?.name || 'Unknown User'"></span>
                                <span class="font-mono-code text-mono-code text-outline-variant text-[11px]" x-text="'@' + (member.user_detail?.name ? member.user_detail.name.toLowerCase().replace(/\s+/g, '_') : 'user')"></span>
                            </div>
                        </div>
                        <div class="col-span-3 hidden md:block font-body-sm text-body-sm text-on-surface-variant" x-text="new Date(member.created_at).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})"></div>
                        <div class="col-span-4 md:col-span-3 flex justify-center">
                            <template x-if="member.role === 'Admin' || member.role === 'Owner'">
                                <span class="bg-error-container/20 text-error border border-error/30 px-sm py-xs rounded font-label-caps text-[10px]" x-text="member.role"></span>
                            </template>
                            <template x-if="member.role === 'Moderator'">
                                <span class="bg-tertiary-container/20 text-tertiary border border-tertiary/30 px-sm py-xs rounded font-label-caps text-[10px]" x-text="member.role"></span>
                            </template>
                            <template x-if="member.role !== 'Admin' && member.role !== 'Owner' && member.role !== 'Moderator'">
                                <span class="bg-surface-variant text-on-surface-variant border border-outline-variant/50 px-sm py-xs rounded font-label-caps text-[10px]" x-text="member.role"></span>
                            </template>
                        </div>
                        <div class="col-span-2 md:col-span-1 flex justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="text-on-surface-variant hover:text-primary"><span class="material-symbols-outlined text-sm">edit</span></button>
                        </div>
                    </div>
                </template>
                <template x-if="members.length === 0 && !isLoading">
                    <div class="p-md text-center text-on-surface-variant text-sm">No members found.</div>
                </template>
                <template x-if="isLoading">
                    <div class="p-md text-center text-on-surface-variant text-sm">Loading members...</div>
                </template>
            </div>
        </div>

        <!-- Right Column: Analytics & Settings -->
        <div class="lg:col-span-1 flex flex-col gap-lg h-[500px]">
            <!-- Community Analytics Mini-Chart -->
            <div class="glass-panel rounded-xl p-md flex flex-col h-1/2">
                <h3 class="font-headline-sm text-headline-sm text-on-surface mb-sm flex items-center gap-sm">
                    <span class="material-symbols-outlined text-primary">trending_up</span> Growth
                </h3>
                <div class="flex-1 relative mt-sm rounded border border-outline-variant/20 bg-surface-container-lowest overflow-hidden flex items-end">
                    <!-- CSS Simulated Line Chart for visual structure -->
                    <div class="w-full h-full relative" style="background: linear-gradient(to top, rgba(77, 142, 255, 0.1) 0%, transparent 100%); border-bottom: 2px solid theme('colors.primary');">
                        <svg class="w-full h-full" preserveaspectratio="none" viewbox="0 0 100 50">
                            <path d="M0,40 Q10,35 20,45 T40,20 T60,30 T80,10 T100,5" fill="none" stroke="theme('colors.primary')" stroke-width="2"></path>
                        </svg>
                        <div class="absolute bottom-2 left-2 font-mono-code text-mono-code text-outline-variant text-[10px]">Jan</div>
                        <div class="absolute bottom-2 right-2 font-mono-code text-mono-code text-outline-variant text-[10px]">Today</div>
                    </div>
                </div>
            </div>

            <!-- Role Permissions Settings -->
            <div class="bg-surface-container-high rounded-xl p-md flex flex-col h-1/2 border border-outline-variant/30">
                <div class="flex justify-between items-center mb-md">
                    <h3 class="font-headline-sm text-headline-sm text-on-surface flex items-center gap-sm">
                        <span class="material-symbols-outlined text-tertiary">admin_panel_settings</span> Permissions
                    </h3>
                    <button @click="showRoleModal = true" class="text-xs font-label-caps text-label-caps text-primary hover:text-primary-container transition-colors flex items-center gap-1 bg-primary/10 px-2 py-1 rounded border border-primary/20">
                        <span class="material-symbols-outlined text-[14px]">add</span> New Role
                    </button>
                </div>
                <div class="flex-1 flex flex-col gap-sm overflow-y-auto">
                    <!-- Toggle Item 1 -->
                    <div class="flex justify-between items-center p-sm rounded hover:bg-white/5 transition-colors">
                        <div class="flex flex-col">
                            <span class="font-body-sm text-body-sm text-on-surface">Allow public joining</span>
                            <span class="font-label-caps text-label-caps text-outline-variant text-[10px]">Members can join without approval</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input checked="" class="sr-only peer" type="checkbox" value=""/>
                            <div class="w-9 h-5 bg-surface-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                    <!-- Toggle Item 2 -->
                    <div class="flex justify-between items-center p-sm rounded hover:bg-white/5 transition-colors">
                        <div class="flex flex-col">
                            <span class="font-body-sm text-body-sm text-on-surface">Content moderation</span>
                            <span class="font-label-caps text-label-caps text-outline-variant text-[10px]">Require mod approval for links</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input class="sr-only peer" type="checkbox" value=""/>
                            <div class="w-9 h-5 bg-surface-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                    <!-- Toggle Item 3 -->
                    <div class="flex justify-between items-center p-sm rounded hover:bg-white/5 transition-colors">
                        <div class="flex flex-col">
                            <span class="font-body-sm text-body-sm text-on-surface">Member directory</span>
                            <span class="font-label-caps text-label-caps text-outline-variant text-[10px]">Visible to non-members</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input checked="" class="sr-only peer" type="checkbox" value=""/>
                            <div class="w-9 h-5 bg-surface-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <x-role-builder-modal />
    <x-create-community-modal />
</div>

@push('scripts')
<script>
function communityPageState() {
    return {
        showRoleModal: false,
        showGuidelinesModal: false,
        showCreateCommunityModal: false,
        
        activeCommunityId: localStorage.getItem('active_community_id') || null,
        activeCommunity: null,
        members: [],
        analytics: {},
        isLoading: true,
        newCommunityForm: { name: '', description: '', password: '' },
        createError: '',
        
        async init() {
            if (!this.activeCommunityId) {
                this.isLoading = false;
                return; // Wait for Topbar to set it
            }
            
            try {
                // Fetch communities to find the active one's details
                const commRes = await window.apiFetch('/api/communities');
                if (commRes.ok) {
                    const resData = await commRes.json();
                    let allComms = resData.data || resData;
                    if (allComms && Array.isArray(allComms.data)) {
                        allComms = allComms.data;
                    }
                    this.activeCommunity = allComms.find(c => c.id == this.activeCommunityId);
                }

                // Fetch stitched members list
                const membersRes = await window.apiFetch(`/api/communities/${this.activeCommunityId}/members`);
                if (membersRes.ok) {
                    const mData = await membersRes.json();
                    let mArr = mData.data || mData;
                    if (mArr && Array.isArray(mArr.data)) mArr = mArr.data;
                    this.members = Array.isArray(mArr) ? mArr : [];
                }

                // Fetch analytics dashboard stats
                const analyticsRes = await window.apiFetch(`/api/analytics/${this.activeCommunityId}/dashboard`);
                if (analyticsRes.ok) {
                    const aData = await analyticsRes.json();
                    this.analytics = aData.data || aData;
                }
            } catch (err) {
                console.error("Error loading community data:", err);
            } finally {
                this.isLoading = false;
            }
        },

        async createCommunity() {
            this.createError = '';
            try {
                const res = await window.apiFetch('/api/communities', 'POST', this.newCommunityForm);
                if (res.ok) {
                    const newComm = await res.json();
                    // Set active and reload to see new community globally
                    localStorage.setItem('active_community_id', newComm.community.id);
                    window.location.reload();
                } else {
                    const errData = await res.json();
                    this.createError = errData.message || 'Failed to create community.';
                }
            } catch (err) {
                this.createError = 'A network error occurred.';
            }
        }
    }
}
</script>
@endpush

@endsection
