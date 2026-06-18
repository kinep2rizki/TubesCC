<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PETA Dashboard Overview')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body-base text-body-base bg-background text-on-background flex h-screen overflow-hidden selection:bg-primary-container selection:text-on-primary-container" x-data="globalAppState()">

    <!-- Sidebar -->
    <x-sidebar />

    <!-- Main Content Canvas Wrapper -->
    <div class="flex-1 flex flex-col w-full md:ml-64">
        
        <!-- Top Navbar -->
        <x-topbar />

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-md lg:p-xl flex flex-col gap-lg">
            @yield('content')
            
            <!-- Footer subtle note -->
            <div class="text-center text-xs text-outline font-mono-code mt-lg pb-lg">
                PETA Dashboard Component • Rendered v2.4.1 • All Systems Nominal
            </div>
        </main>
    </div>

    <!-- Global Helpers and State -->
    <script>
        // Global Fetch Helper to auto-inject JWT token
        window.apiFetch = async function(endpoint, method = 'GET', bodyData = null) {
            const token = localStorage.getItem('jwt_token');
            const options = {
                method: method,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            };

            if (token) {
                options.headers['Authorization'] = 'Bearer ' + token;
            }

            if (bodyData instanceof FormData) {
                delete options.headers['Content-Type'];
                options.body = bodyData;
            } else if (bodyData) {
                options.body = JSON.stringify(bodyData);
            }

            // Route dynamically based on endpoints
            // Assuming Auth Service is 8001, Project Service is 8002
            let baseUrl = 'http://127.0.0.1:8002'; // Default to Project Service
            if(endpoint.startsWith('/api/auth')) {
                baseUrl = 'http://127.0.0.1:8001';
            }

            const response = await fetch(baseUrl + endpoint, options);
            
            if (response.status === 401 && !endpoint.includes('/login')) {
                // Token invalid or expired, redirect to login
                localStorage.removeItem('jwt_token');
                window.location.href = '/login';
            }

            return response;
        };

        // Helper wrapper that automatically parses JSON response
        // Used by Participants, Attendance, and Certificate pages
        window.fetchApi = async function(endpoint, method = 'GET', bodyData = null) {
            try {
                const res = await window.apiFetch(endpoint, method, bodyData);
                const data = await res.json();
                return data;
            } catch (error) {
                console.error("fetchApi error:", error);
                return { success: false, message: error.message };
            }
        };

        function globalAppState() {
            return {
                sidebarOpen: false,
                user: null,
                isLoadingUser: true,
                userCommunities: [],
                userMemberships: [],
                activeCommunityId: localStorage.getItem('active_community_id') || null,
                
                async init() {
                    const token = localStorage.getItem('jwt_token');
                    if (!token) {
                        // Not logged in, if not on login page, redirect
                        if(window.location.pathname !== '/login' && window.location.pathname !== '/register') {
                            window.location.href = '/login';
                        }
                        this.isLoadingUser = false;
                        return;
                    }

                    // Fetch current user from Auth Service
                    try {
                        const res = await window.apiFetch('/api/auth/me', 'GET');
                        if (res.ok) {
                            this.user = await res.json();
                        } else {
                            // Token probably expired
                            localStorage.removeItem('jwt_token');
                            window.location.href = '/login';
                        }
                    } catch (err) {
                        console.error('Failed to fetch user profile', err);
                    } finally {
                        this.isLoadingUser = false;
                    }

                    // Only fetch communities if logged in
                    if (this.user) {
                        try {
                            const commRes = await window.apiFetch('/api/communities', 'GET');
                            if (commRes.ok) {
                                const resData = await commRes.json();
                                let arr = resData.data || resData;
                                if (arr && Array.isArray(arr.data)) {
                                    arr = arr.data;
                                }
                                this.userCommunities = arr;
                                // If no active community is set but we have communities, set the first one
                                if (!this.activeCommunityId && this.userCommunities.length > 0) {
                                    this.switchCommunity(this.userCommunities[0].id);
                                }
                            }
                            
                            // Fetch memberships for roles
                            const memRes = await window.apiFetch('/api/communities/my-memberships', 'GET');
                            if (memRes.ok) {
                                const mData = await memRes.json();
                                this.userMemberships = mData.data || [];
                            }
                        } catch (err) {
                            console.error('Failed to fetch communities', err);
                        }
                    }
                },
                
                switchCommunity(id) {
                    this.activeCommunityId = id;
                    localStorage.setItem('active_community_id', id);
                    window.dispatchEvent(new CustomEvent('community-changed', { detail: { id: id } }));
                    
                    const urlParams = new URLSearchParams(window.location.search);
                    urlParams.set('community_id', id);
                    window.location.search = urlParams.toString();
                },
                
                async doLogout() {
                    try {
                        await window.apiFetch('/api/auth/logout', 'POST');
                    } catch(e) {}
                    localStorage.removeItem('jwt_token');
                    window.location.href = '/login';
                }
            }
        }
    </script>

    <x-live-chat />
    @stack('scripts')
</body>
</html>
