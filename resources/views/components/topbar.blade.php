<header class="w-full sticky top-0 z-20 bg-background/70 backdrop-blur-xl border-b border-outline-variant/30 shadow-sm flex justify-between items-center px-lg py-sm" x-data="{ openCommunityMenu: false, openNotifications: false, openAccountMenu: false }">
    <!-- Left Side: Mobile Menu Toggle & Search -->
    <div class="flex items-center gap-md">
        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-on-surface hover:bg-surface-variant/50 transition-colors p-sm rounded cursor-pointer active:scale-95 transition-transform">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <div class="relative hidden sm:block">
            <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-[20px]">search</span>
            <input class="bg-surface-container border border-outline-variant/50 rounded-lg pl-xl pr-md py-xs text-sm font-body-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary placeholder:text-on-surface-variant w-64 transition-all bg-opacity-50" placeholder="Search events, participants..." type="text"/>
        </div>
    </div>
    <!-- Right Side: Actions & Secondary -->
    <div class="flex items-center gap-sm">
        <div x-data="{ openCommunityMenu: false }" class="relative mr-sm">
            <button @click="openCommunityMenu = !openCommunityMenu" @click.outside="openCommunityMenu = false" class="flex items-center gap-xs bg-surface-container border border-outline-variant/50 px-sm py-xs rounded hover:bg-surface-variant/50 transition-colors cursor-pointer active:scale-95 transition-transform">
                <div class="w-5 h-5 rounded bg-primary/20 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[14px]">groups</span>
                </div>
                <span class="font-label-caps text-label-caps text-on-surface ml-1" x-text="userCommunities.find(c => c.id == activeCommunityId)?.name || 'No Community'"></span>
                <span class="material-symbols-outlined text-[16px] text-on-surface-variant transition-transform" :class="openCommunityMenu ? 'rotate-180' : ''">expand_more</span>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="openCommunityMenu" x-transition.origin.top.right style="display: none;" class="absolute top-full right-0 mt-2 w-64 bg-surface-container-high border border-outline-variant/30 rounded-lg shadow-xl overflow-hidden py-2 z-50">
                <div class="px-md py-xs">
                    <p class="text-[10px] font-label-caps text-on-surface-variant mb-2">YOUR COMMUNITIES</p>
                </div>
                
                <template x-for="community in userCommunities" :key="community.id">
                    <div>
                        <!-- Active Community -->
                        <template x-if="community.id == activeCommunityId">
                            <button class="w-full flex items-center justify-between px-md py-sm bg-primary/10 hover:bg-primary/20 transition-colors group cursor-default">
                                <div class="flex items-center gap-md">
                                    <div class="w-8 h-8 rounded bg-primary flex items-center justify-center text-on-primary shadow-sm flex-shrink-0">
                                        <span class="font-bold text-xs" x-text="community.name.substring(0, 2).toUpperCase()"></span>
                                    </div>
                                    <div class="flex flex-col text-left">
                                        <span class="font-body-sm text-body-sm text-on-surface font-bold leading-tight" x-text="community.name"></span>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-primary text-[18px]">check</span>
                            </button>
                        </template>
                        
                        <!-- Inactive Community -->
                        <template x-if="community.id != activeCommunityId">
                            <button @click="switchCommunity(community.id)" class="w-full flex items-center gap-md px-md py-sm hover:bg-white/5 transition-colors group text-left">
                                <div class="w-8 h-8 rounded bg-surface-variant flex items-center justify-center text-on-surface shadow-sm flex-shrink-0">
                                    <span class="font-bold text-xs" x-text="community.name.substring(0, 2).toUpperCase()"></span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-body-sm text-body-sm text-on-surface font-semibold leading-tight group-hover:text-primary transition-colors" x-text="community.name"></span>
                                </div>
                            </button>
                        </template>
                    </div>
                </template>
                
                <template x-if="userCommunities.length === 0">
                    <div class="px-md py-sm text-center text-on-surface-variant text-xs">
                        No communities found.
                    </div>
                </template>

                <div class="h-px w-full bg-outline-variant/30 my-2"></div>
                
                <!-- Create New Community Action -->
                <a href="{{ route('communities') }}" class="w-full flex items-center gap-md px-md py-sm hover:bg-white/5 text-on-surface transition-colors group text-left">
                    <div class="w-8 h-8 rounded border border-dashed border-outline-variant group-hover:border-primary flex items-center justify-center text-on-surface-variant group-hover:text-primary transition-colors flex-shrink-0">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                    </div>
                    <span class="font-body-sm text-body-sm font-semibold group-hover:text-primary transition-colors">Create New Community</span>
                </a>
            </div>
        </div>
        <div class="flex items-center gap-xs border-l border-outline-variant/30 pl-sm">
            <div x-data="{ openNotifications: false }" class="relative">
                <button @click="openNotifications = !openNotifications" @click.outside="openNotifications = false" :class="openNotifications ? 'bg-surface-variant/50 text-on-surface' : 'text-on-surface-variant hover:bg-surface-variant/50'" class="transition-colors p-sm rounded cursor-pointer active:scale-95 transition-transform relative">
                    <span class="material-symbols-outlined" :class="openNotifications ? 'fill' : ''" :style="openNotifications ? 'font-variation-settings:\'FILL\' 1' : ''">notifications</span>
                    <span class="absolute top-[6px] right-[6px] w-2 h-2 bg-primary rounded-full"></span>
                </button>

                <!-- Popup Panel -->
                <div x-show="openNotifications" x-transition.origin.top.right style="display: none;" class="absolute top-full right-0 mt-2 w-80 bg-surface-container-lowest border border-outline-variant/30 rounded-xl shadow-2xl z-50 overflow-hidden text-left">
                    <div class="px-md py-sm border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-highest/50">
                        <h3 class="font-body-base font-bold text-on-surface">Notifications</h3>
                        <button class="text-[10px] font-label-caps text-primary hover:underline">Mark all read</button>
                    </div>
                    <div class="max-h-80 overflow-y-auto custom-scrollbar">
                        <div class="p-md text-center">
                            <p class="text-xs text-on-surface-variant">Notifications will be integrated later.</p>
                        </div>
                    </div>
                    <div class="p-xs text-center border-t border-outline-variant/30 bg-surface-container-highest/20">
                        <a href="#" class="text-[10px] font-label-caps text-on-surface-variant hover:text-primary transition-colors py-1 block">View All Notifications</a>
                    </div>
                </div>
            </div>
            <button class="text-on-surface-variant hover:bg-surface-variant/50 transition-colors p-sm rounded cursor-pointer active:scale-95 transition-transform hidden sm:block">
                <span class="material-symbols-outlined">apps</span>
            </button>
            <div x-data="{ openAccountMenu: false }" class="relative ml-xs">
                <button @click="openAccountMenu = !openAccountMenu" @click.outside="openAccountMenu = false" class="rounded-full overflow-hidden border border-outline-variant/50 hover:border-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background flex items-center justify-center">
                    <img alt="User profile" class="w-8 h-8 object-cover" :src="'https://ui-avatars.com/api/?name=' + encodeURIComponent(user ? user.name : 'User') + '&background=4d8eff&color=fff'"/>
                </button>

                <!-- Pop-up Menu -->
                <div x-show="openAccountMenu" x-transition.origin.top.right style="display: none;" class="absolute top-full right-0 mt-2 w-56 bg-surface-container-high border border-outline-variant/30 rounded-lg shadow-xl overflow-hidden py-1 z-50">
                    <div class="px-md py-sm border-b border-outline-variant/30 mb-1">
                        <p class="font-body-sm text-body-sm text-on-surface font-semibold leading-tight" x-text="user ? user.name : 'Loading...'"></p>
                        <p class="text-[10px] text-on-surface-variant font-mono-code leading-tight mt-1" x-text="user ? user.email : '...'"></p>
                        @php
                            $rawRoles = session('user_roles', []);
                            $displayRoles = [];
                            foreach ($rawRoles as $r) {
                                $displayRoles[] = is_array($r) ? ($r['name'] ?? 'User') : $r;
                            }
                            $communityRole = session('active_community_role');
                            if ($communityRole) {
                                $displayRoles[] = "Community " . $communityRole;
                            }
                        @endphp
                        <div class="mt-2 flex flex-col gap-1 items-start">
                            @php
                                $globalRoles = array_filter($displayRoles, fn($r) => !str_starts_with($r, 'Community '));
                            @endphp
                            <div class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-surface-variant/50 text-on-surface-variant border-surface-variant">
                                Global: {{ count($globalRoles) > 0 ? implode(', ', $globalRoles) : 'User' }}
                            </div>
                            <template x-if="userMemberships && userMemberships.find(m => m.community_id == activeCommunityId)">
                                <div class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-primary/20 text-primary border-primary/30"
                                     x-text="'Local: ' + userMemberships.find(m => m.community_id == activeCommunityId).role">
                                </div>
                            </template>
                        </div>
                    </div>
                    <a href="#" class="flex items-center gap-3 px-md py-sm hover:bg-white/5 transition-colors text-on-surface">
                        <span class="material-symbols-outlined text-[18px]">person</span>
                        <span class="font-body-sm text-body-sm">My Profile</span>
                    </a>
                    <div class="h-px w-full bg-outline-variant/30 my-1"></div>
                    
                    <button type="button" @click="doLogout" class="w-full flex items-center gap-3 px-md py-sm hover:bg-error/10 text-error transition-colors group text-left">
                        <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">logout</span>
                        <span class="font-body-sm text-body-sm font-semibold">Log Out</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
