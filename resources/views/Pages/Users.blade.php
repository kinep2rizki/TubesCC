@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div x-data="{ 
    showEditModal: false, 
    editUserId: null, 
    editUserName: '', 
    editUserRole: '',
    editUserMemberships: {},
    openEditModal(id, name, role, memberships) {
        this.editUserId = id;
        this.editUserName = name;
        this.editUserRole = role;
        
        let allMemberships = {};
        let rawRole, role_capitalized;
        @foreach($communities as $comm)
            rawRole = memberships['{{ $comm->id }}'] || memberships[{{ $comm->id }}];
            role_capitalized = rawRole ? (rawRole.charAt(0).toUpperCase() + rawRole.slice(1)) : 'Not a Member';
            allMemberships['{{ $comm->id }}'] = role_capitalized;
        @endforeach
        
        this.editUserMemberships = allMemberships;
        this.showEditModal = true;
    }
}" class="max-w-container-max mx-auto w-full flex flex-col gap-xl">
    
    <!-- Page Header -->
    <div class="mb-lg pb-sm border-b border-outline-variant/30 flex flex-col md:flex-row md:justify-between md:items-end gap-md">
        <div>
            <h1 class="font-display-lg text-display-lg text-on-surface tracking-tight">User Management</h1>
            <p class="font-body-base text-body-base text-on-surface-variant mt-xs">Manage users and roles across your communities</p>
        </div>
        
        <!-- Community Selector Form -->
        <form id="communityForm" method="GET" action="{{ route('users') }}" class="flex items-center gap-sm" 
            x-data="{
                activeCommId: localStorage.getItem('active_community_id') || '{{ $communities->first()->id ?? 'all' }}',
                init() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const urlCommId = urlParams.get('community_id');
                    
                    // Allow 'all' for superadmins
                    if (!urlCommId && this.activeCommId) {
                        urlParams.set('community_id', this.activeCommId);
                        window.location.search = urlParams.toString();
                    } else if (urlCommId && urlCommId !== this.activeCommId && urlCommId !== 'all') {
                        // If url has community_id but it differs from local storage, sync local storage to URL (or vice versa).
                        // Since topbar drives local storage, let's sync URL to local storage.
                        urlParams.set('community_id', this.activeCommId);
                        window.location.search = urlParams.toString();
                    }

                    window.addEventListener('community-changed', (e) => {
                        urlParams.set('community_id', e.detail.id);
                        window.location.search = urlParams.toString();
                    });
                }
            }">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <label for="community_id" class="font-label-caps text-label-caps text-on-surface-variant shrink-0">Community Context:</label>
            @if($isSuperAdmin)
                <select name="community_id" id="community_id" onchange="localStorage.setItem('active_community_id', this.value); document.getElementById('communityForm').submit()" class="bg-surface-container-low border border-outline-variant/50 text-on-surface font-body-sm text-body-sm rounded-lg px-md py-sm focus:ring-1 focus:ring-primary focus:outline-none min-w-[200px]">
                    <option value="all" {{ request('community_id') === 'all' ? 'selected' : '' }}>All Communities (Overview)</option>
                    @foreach($communities as $comm)
                    <option value="{{ $comm->id }}" {{ (string)request('community_id') === (string)$comm->id ? 'selected' : '' }}>{{ $comm->name }}</option>
                    @endforeach
                </select>
            @else
                <input type="hidden" name="community_id" :value="activeCommId">
                <div class="bg-surface-container-low border border-outline-variant/50 text-on-surface font-body-sm text-body-sm rounded-lg px-md py-sm min-w-[200px] cursor-not-allowed opacity-80" title="Switch your active community from the top menu to change this context">
                    <span x-text="document.querySelector(`[data-comm-id='${activeCommId}']`)?.innerText || '{{ $communities->first()->name ?? 'Unknown Community' }}'"></span>
                    <!-- Fallback invisible elements to grab name -->
                    <div class="hidden">
                        @foreach($communities as $comm)
                            <span data-comm-id="{{ $comm->id }}">{{ $comm->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </form>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="bg-primary/20 text-primary border border-primary/50 px-md py-sm rounded-lg font-body-sm text-body-sm">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-error/20 text-error border border-error/50 px-md py-sm rounded-lg font-body-sm text-body-sm">
        {{ session('error') }}
    </div>
    @endif

    <!-- Toolbar / Filters -->
    <form method="GET" action="{{ route('users') }}" class="flex flex-col sm:flex-row justify-between items-center bg-surface-container-lowest p-xs rounded-xl border border-outline-variant/30 gap-sm mb-lg shadow-sm">
        <input type="hidden" name="community_id" value="{{ $communityId }}">
        
        <!-- Search -->
        <div class="relative w-full sm:max-w-md flex items-center">
            <span class="material-symbols-outlined absolute left-3 text-outline-variant text-[20px]">search</span>
            <input name="search" value="{{ request('search') }}" onchange="this.form.submit()" class="w-full bg-surface-container-low border-none rounded-lg pl-10 pr-4 py-2 text-on-surface placeholder-outline-variant focus:ring-1 focus:ring-primary focus:bg-surface-container transition-all font-body-sm text-body-sm" placeholder="Search by name or email..." type="text"/>
        </div>
        
        <!-- Filters (Placeholder for future Role filter) -->
        <div class="flex items-center gap-sm w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0 hide-scrollbar no-scrollbar">
            <a href="{{ route('users', ['community_id' => $communityId]) }}" class="flex items-center gap-2 px-3 py-1.5 rounded-md text-primary hover:bg-primary-container/10 transition-colors font-body-sm text-body-sm whitespace-nowrap">
                <span class="material-symbols-outlined text-[16px]">filter_list_off</span>
                Clear
            </a>
        </div>
    </form>

    <!-- Data Table -->
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl overflow-hidden shadow-[0_8px_30px_rgba(0,0,0,0.5)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant/30 bg-surface-container/30">
                        <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant">Name</th>
                        <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant">Email</th>
                        <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant">Assigned Communities</th>
                        <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant hidden md:table-cell">Joined Date</th>
                        <th class="px-md py-sm font-label-caps text-label-caps text-on-surface-variant text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse($users as $u)
                    <tr class="hover:bg-white/[0.03] transition-colors group">
                        <td class="px-md py-sm">
                            <div class="flex items-center gap-md">
                                <div class="w-8 h-8 rounded-full bg-surface-variant flex items-center justify-center text-on-surface-variant font-label-caps uppercase shrink-0">
                                    {{ substr($u->name, 0, 2) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-body-sm text-body-sm text-on-surface">{{ $u->name }}</span>
                                    <span class="font-mono-code text-mono-code text-outline-variant text-[11px]">{{ '@' . strtolower(str_replace(' ', '_', $u->name)) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-md py-sm font-body-sm text-body-sm text-on-surface-variant">
                            {{ $u->email }}
                        </td>
                        <td class="px-md py-sm">
                            <div class="flex flex-wrap gap-xs">
                                @forelse($u->communityMemberships as $membership)
                                    @if($communityId === 'all' || $communityId == $membership->community_id)
                                        @php $displayRole = ucfirst($membership->role); @endphp
                                        <div class="flex items-center gap-1 bg-surface-container-high border border-outline-variant/30 px-2 py-1 rounded text-[11px] font-label-caps">
                                            <span class="text-on-surface truncate max-w-[120px]">{{ $membership->community->name }}</span>
                                            <span class="text-outline-variant">•</span>
                                            <span class="
                                                {{ $displayRole === 'Owner' ? 'text-pink-500' : '' }}
                                                {{ $displayRole === 'Admin' ? 'text-orange-500' : '' }}
                                                {{ $displayRole === 'Moderator' ? 'text-amber-500' : '' }}
                                                {{ $displayRole === 'Member' ? 'text-teal-500' : '' }}
                                            ">{{ $displayRole }}</span>
                                        </div>
                                    @endif
                                @empty
                                    <span class="text-outline-variant text-[11px] font-label-caps">No Communities</span>
                                @endforelse
                                
                                @if(collect($u->roles ?? [])->contains('name', 'Super Admin'))
                                <div class="flex items-center gap-1 bg-error/20 border border-error/30 px-2 py-1 rounded text-[11px] font-label-caps">
                                    <span class="text-error font-bold">System</span>
                                    <span class="text-error/70">•</span>
                                    <span class="text-error font-bold">Super Admin</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-md py-sm font-body-sm text-body-sm text-on-surface-variant hidden md:table-cell">
                            {{ $u->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-md py-sm text-center">
                            @if($isSuperAdmin || $isOwner)
                                @php
                                    $currentRole = 'User';
                                    if (collect($u->roles ?? [])->contains('name', 'Super Admin')) $currentRole = 'Super Admin';
                                @endphp
                                <button @click="openEditModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ $currentRole }}', {{ json_encode($u->communityMemberships->pluck('role', 'community_id')) }})" class="text-on-surface-variant hover:text-primary transition-colors p-1 rounded-full hover:bg-surface-variant/50 opacity-0 group-hover:opacity-100 focus:opacity-100">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center p-xl text-on-surface-variant">
                            <span class="material-symbols-outlined text-[48px] opacity-20 mb-sm">group_off</span>
                            <p class="font-body-base text-body-base">No users found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Footer -->
        <div class="flex items-center justify-between px-md py-sm bg-surface-container/30 border-t border-outline-variant/30">
            <span class="font-body-sm text-body-sm text-on-surface-variant">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
            </span>
            <div class="flex items-center gap-xs">
                <a href="{{ $users->previousPageUrl() }}" class="p-1 rounded-md text-on-surface-variant hover:bg-white/5 transition-colors {{ $users->onFirstPage() ? 'opacity-50 pointer-events-none' : '' }}">
                    <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                </a>
                
                @if($users->lastPage() > 1)
                    @foreach ($users->links()->elements[0] as $page => $url)
                        <a href="{{ $url }}" class="w-8 h-8 rounded-md {{ $page == $users->currentPage() ? 'bg-primary-container/20 text-primary border border-primary-container/30' : 'text-on-surface-variant hover:bg-white/5 border border-transparent' }} font-body-sm text-body-sm flex items-center justify-center transition-colors">
                            {{ $page }}
                        </a>
                    @endforeach
                @endif

                <a href="{{ $users->nextPageUrl() }}" class="p-1 rounded-md text-on-surface-variant hover:bg-white/5 transition-colors {{ !$users->hasMorePages() ? 'opacity-50 pointer-events-none' : '' }}">
                    <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center">
        <div x-show="showEditModal" x-transition.opacity @click="showEditModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <div x-show="showEditModal" x-transition.scale.origin.bottom class="relative bg-surface-container-low rounded-xl border border-outline-variant/30 shadow-2xl w-full max-w-md overflow-hidden flex flex-col">
            <div class="p-lg border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-lowest/50">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Edit User Role</h3>
                <button @click="showEditModal = false" class="text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form :action="`/users/${editUserId}/role`" method="POST" class="flex flex-col">
                @csrf
                @method('PUT')
                
                <div class="p-lg flex flex-col gap-md">
                    <div>
                        <label class="font-label-caps text-label-caps text-outline block mb-xs">Editing User</label>
                        <div class="font-body-base text-body-base text-on-surface font-semibold" x-text="editUserName"></div>
                    </div>
                    
                    @if($isSuperAdmin)
                    <div>
                        <label class="font-label-caps text-label-caps text-outline block mb-xs">Global Role</label>
                        <select name="role" x-model="editUserRole" class="w-full bg-surface-container border border-outline-variant/50 rounded-lg px-md py-sm text-on-surface font-body-sm text-body-sm focus:ring-1 focus:ring-primary focus:outline-none">
                            <option value="User">Regular User</option>
                            <option value="Super Admin">Super Admin</option>
                        </select>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-2 text-xs">Assigning a global role grants platform-wide permissions.</p>
                    </div>
                    @endif

                    <div>
                        <label class="font-label-caps text-label-caps text-outline block mb-xs">Community Memberships</label>
                        <div class="max-h-48 overflow-y-auto custom-scrollbar flex flex-col gap-sm border border-outline-variant/30 rounded-lg p-sm bg-surface-container-lowest">
                            @foreach($communities as $comm)
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-surface-container-low px-sm py-2 rounded-md gap-2">
                                <span class="text-on-surface font-body-sm text-body-sm truncate" title="{{ $comm->name }}">{{ $comm->name }}</span>
                                <select name="communities[{{ $comm->id }}]" x-model="editUserMemberships['{{ $comm->id }}']" class="bg-surface-container border border-outline-variant/50 rounded text-on-surface font-body-sm text-body-sm px-2 py-1 focus:ring-1 focus:ring-primary focus:outline-none sm:w-auto w-full">
                                    <option value="Not a Member">Not a Member</option>
                                    <option value="Member">Member</option>
                                    <option value="Moderator">Moderator</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Owner">Owner</option>
                                </select>
                            </div>
                            @endforeach
                            @if($communities->isEmpty())
                                <p class="text-on-surface-variant text-body-sm font-body-sm text-center p-sm">No communities available.</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="p-md border-t border-outline-variant/30 bg-surface-container/50 flex justify-end gap-sm">
                    <button type="button" @click="showEditModal = false" class="px-md py-sm rounded-lg font-label-caps text-label-caps text-on-surface hover:bg-white/5 transition-colors">Cancel</button>
                    <button type="submit" class="px-md py-sm rounded-lg font-label-caps text-label-caps bg-primary text-on-primary hover:opacity-90 transition-opacity">Save Role</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
