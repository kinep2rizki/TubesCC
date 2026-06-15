@extends('layouts.app')

@section('title', 'Community Management')

@section('content')
<div x-data="{ showRoleModal: false, showGuidelinesModal: false }" class="max-w-container-max mx-auto w-full flex flex-col gap-lg">
    
    <!-- Page Header -->
    <div class="mb-xl pb-sm border-b border-outline-variant/30">
        <h1 class="font-display-lg text-display-lg text-on-surface tracking-tight">Community Management</h1>
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
                        {{ $community->name }}
                        <span class="material-symbols-outlined text-primary text-sm" style="font-variation-settings: 'FILL' 1;">verified</span>
                    </h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant max-w-2xl">
                        {{ $community->description }}
                    </p>
                </div>
                <div class="flex gap-sm shrink-0">
                    <button @click="showGuidelinesModal = true" class="bg-primary text-on-primary px-lg py-sm rounded-lg font-label-caps text-label-caps hover:opacity-90 transition-opacity">
                        Edit Profile
                    </button>
                </div>
            </div>
            <div class="flex gap-lg mt-md pt-md border-t border-outline-variant/20">
                <div class="flex flex-col">
                    <span class="font-display-lg-mobile text-display-lg-mobile text-on-surface">{{ number_format($community->members->count()) }}</span>
                    <span class="font-label-caps text-label-caps text-outline">Total Members</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-display-lg-mobile text-display-lg-mobile text-on-surface">{{ number_format((int)($community->members->count() * 0.4)) }}</span>
                    <span class="font-label-caps text-label-caps text-outline">Active Today</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-display-lg-mobile text-display-lg-mobile text-primary">+14%</span>
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
                @forelse($community->members as $member)
                <!-- Member Row -->
                <div class="grid grid-cols-12 gap-sm px-md py-sm items-center hover:bg-white/[0.03] rounded-lg transition-colors group border-b border-outline-variant/10 last:border-0">
                    <div class="col-span-6 md:col-span-5 flex items-center gap-md">
                        <div class="w-8 h-8 rounded-full bg-surface-variant flex items-center justify-center text-on-surface-variant font-label-caps uppercase">{{ substr($member->user->name, 0, 2) }}</div>
                        <div class="flex flex-col">
                            <span class="font-body-sm text-body-sm text-on-surface">{{ $member->user->name }}</span>
                            <span class="font-mono-code text-mono-code text-outline-variant text-[11px]">{{ '@' . strtolower(str_replace(' ', '_', $member->user->name)) }}</span>
                        </div>
                    </div>
                    <div class="col-span-3 hidden md:block font-body-sm text-body-sm text-on-surface-variant">{{ $member->created_at->format('M d, Y') }}</div>
                    <div class="col-span-4 md:col-span-3 flex justify-center">
                        @if($member->role === 'Admin' || $member->role === 'Owner')
                        <span class="bg-error-container/20 text-error border border-error/30 px-sm py-xs rounded font-label-caps text-[10px]">{{ $member->role }}</span>
                        @elseif($member->role === 'Moderator')
                        <span class="bg-tertiary-container/20 text-tertiary border border-tertiary/30 px-sm py-xs rounded font-label-caps text-[10px]">{{ $member->role }}</span>
                        @else
                        <span class="bg-surface-variant text-on-surface-variant border border-outline-variant/50 px-sm py-xs rounded font-label-caps text-[10px]">{{ $member->role }}</span>
                        @endif
                    </div>
                    <div class="col-span-2 md:col-span-1 flex justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="text-on-surface-variant hover:text-primary"><span class="material-symbols-outlined text-sm">edit</span></button>
                    </div>
                </div>
                @empty
                <div class="p-md text-center text-on-surface-variant text-sm">No members found.</div>
                @endforelse
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
    <x-community-guidelines-modal />
</div>
@endsection
