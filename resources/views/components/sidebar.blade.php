<nav :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="bg-surface-container-lowest/80 backdrop-blur-xl h-screen w-64 fixed left-0 top-0 z-40 border-r border-outline-variant/30 shadow-xl flex flex-col p-md gap-xs md:translate-x-0 transition-transform duration-300">
    <!-- Close button for mobile -->
    <button @click="sidebarOpen = false" class="md:hidden absolute right-4 top-4 text-on-surface">
        <span class="material-symbols-outlined">close</span>
    </button>

    <!-- Header -->
    <div class="px-md py-lg mb-sm border-b border-outline-variant/30">
        <div class="flex items-center gap-sm">
            <div class="w-8 h-8 rounded bg-primary flex items-center justify-center shadow-[0_0_15px_rgba(173,198,255,0.4)]">
                <span class="material-symbols-outlined text-on-primary" style="font-size: 20px;">event</span>
            </div>
            <div>
                <h1 class="font-headline-sm text-headline-sm font-black text-primary">PETA</h1>
                <p class="font-label-caps text-label-caps text-on-surface-variant">Tech Events Platform</p>
            </div>
        </div>
    </div>
    <!-- Main Tabs -->
    <div class="flex-1 flex flex-col gap-xs overflow-y-auto">
        @php
            $activeClass = 'bg-secondary-container text-on-secondary-container rounded-lg px-md py-sm border-l-4 border-primary active:translate-x-1 transition-transform';
            $inactiveClass = 'text-on-surface-variant hover:text-on-surface px-md py-sm hover:bg-white/5 transition-all duration-200 active:translate-x-1 rounded-lg';
            
            $activeCommunityId = session('active_community_id');
            $defaultEventId = 1;
        @endphp

        <!-- Dashboard Tab -->
        <a class="flex items-center gap-md {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('dashboard') ? '1' : '0' }};">dashboard</span>
            <span class="font-label-caps text-label-caps">Dashboard</span>
        </a>
        
        <!-- Events Tab -->
        <a class="flex items-center gap-md {{ request()->routeIs('events', 'event-detail', 'participants', 'attendance', 'certificates') ? $activeClass : $inactiveClass }}" href="{{ route('events') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('events', 'event-detail', 'participants', 'attendance', 'certificates') ? '1' : '0' }};">event</span>
            <span class="font-label-caps text-label-caps">Events</span>
        </a>

        @php
            $canManageUsers = false;
            $rawRoles = session('user_roles', []);
            $userRoles = [];
            foreach ($rawRoles as $r) {
                $userRoles[] = is_array($r) ? ($r['name'] ?? '') : $r;
            }
            $activeCommunityRole = session('active_community_role');
            
            if (in_array('Super Admin', $userRoles)) {
                $canManageUsers = true;
            } elseif ($activeCommunityRole && in_array($activeCommunityRole, ['Admin', 'Owner'])) {
                $canManageUsers = true;
            }
        @endphp

        @if(in_array('Super Admin', $userRoles))
        <a class="flex items-center gap-md {{ request()->routeIs('communities') ? $activeClass : $inactiveClass }}" href="{{ route('communities') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('communities') ? '1' : '0' }};">hub</span>
            <span class="font-label-caps text-label-caps">Communities</span>
        </a>
        @endif
        
        @if($canManageUsers)
        <a class="flex items-center gap-md {{ request()->routeIs('users') ? $activeClass : $inactiveClass }}" href="{{ route('users') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('users') ? '1' : '0' }};">group</span>
            <span class="font-label-caps text-label-caps">Users</span>
        </a>
        @endif

        @php
            $isSuperOrOwner = false;
            $userRoles = session('user_roles', []);
            $activeCommunityRole = session('active_community_role');
            
            if (in_array('Super Admin', $userRoles) || $activeCommunityRole === 'Owner') {
                $isSuperOrOwner = true;
            }
        @endphp

        @if($isSuperOrOwner)
        <a class="flex items-center gap-md {{ request()->routeIs('analytics') ? $activeClass : $inactiveClass }}" href="{{ route('analytics') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('analytics') ? '1' : '0' }};">analytics</span>
            <span class="font-label-caps text-label-caps">Analytics</span>
        </a>
        @endif

        <a class="flex items-center gap-md {{ request()->routeIs('settings') ? $activeClass : $inactiveClass }}" href="{{ route('settings') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('settings') ? '1' : '0' }};">settings</span>
            <span class="font-label-caps text-label-caps">Settings</span>
        </a>
    </div>

</nav>

<!-- Backdrop for mobile sidebar -->
<div x-show="sidebarOpen" style="display: none;" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/50 z-30 md:hidden"></div>
