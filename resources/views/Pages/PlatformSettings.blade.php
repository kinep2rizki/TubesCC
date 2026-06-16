@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div x-data="settingsState()" x-init="initData()" class="max-w-container-max mx-auto w-full">
    <!-- Page Header -->
    <header class="mb-xl">
        <h1 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-on-surface">Settings</h1>
        <p class="font-body-base text-body-base text-on-surface-variant mt-sm max-w-2xl">Manage your account settings, security preferences, and API integrations.</p>
    </header>
    
    <!-- Settings Layout (Sidebar + Content) -->
    <div class="flex flex-col md:flex-row gap-2xl items-start relative">
        <!-- Secondary Sidebar for Settings Sections (Stripe-style) -->
        <nav class="w-full md:w-56 flex-shrink-0 sticky top-xl flex flex-row md:flex-col gap-xs overflow-x-auto md:overflow-visible pb-md md:pb-0 border-b md:border-b-0 border-outline-variant/30 z-10 bg-background/95 backdrop-blur-sm md:bg-transparent md:backdrop-blur-none">
            <a class="px-md py-sm text-on-surface font-body-sm font-semibold bg-surface-container-high/50 rounded-lg whitespace-nowrap transition-colors" href="#profile">Profile</a>
            <a class="px-md py-sm text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low rounded-lg font-body-sm transition-colors whitespace-nowrap" href="#security">Security</a>
            <a class="px-md py-sm text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low rounded-lg font-body-sm transition-colors whitespace-nowrap" href="#certificates">My Certificates</a>
        </nav>
        
        <!-- Settings Forms Area -->
        <div class="flex-1 w-full max-w-3xl space-y-2xl pb-2xl">
            <!-- Profile Section -->
            <section class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden scroll-mt-2xl" id="profile">
                <div class="px-lg py-md border-b border-outline-variant/30 bg-surface-container-lowest/50">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Profile</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Update your personal information and avatar.</p>
                </div>
                <div class="p-lg space-y-xl">
                    <!-- Avatar Upload -->
                    <div class="flex items-center gap-lg">
                        <div class="relative w-20 h-20 rounded-full border border-outline-variant overflow-hidden bg-surface-container-highest flex-shrink-0">
                            <img alt="User Avatar" class="w-full h-full object-cover" :src="profile.avatar_url"/>
                        </div>
                        <div class="flex gap-sm">
                            <label class="bg-surface-bright text-on-surface border border-outline-variant px-md py-sm rounded-lg font-label-caps text-label-caps hover:bg-surface-container-highest transition-colors cursor-pointer inline-flex items-center">
                                Change
                                <input type="file" class="hidden" accept="image/*" @change="handleAvatarChange" />
                            </label>
                        </div>
                    </div>
                    <!-- Name Field -->
                    <div class="space-y-sm">
                        <label class="block font-label-caps text-label-caps text-on-surface-variant" for="fullName">Full Name</label>
                        <input x-model="profile.name" class="w-full bg-background border border-outline-variant rounded-lg px-md py-sm text-body-base font-body-base text-on-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none transition-all placeholder-on-surface-variant/50" id="fullName" type="text"/>
                    </div>
                    <!-- Email Field -->
                    <div class="space-y-sm">
                        <label class="block font-label-caps text-label-caps text-on-surface-variant" for="emailAddr">Email Address</label>
                        <input x-model="profile.email" disabled class="w-full bg-surface-container border border-outline-variant rounded-lg px-md py-sm text-body-base font-body-base text-on-surface/70 outline-none cursor-not-allowed" id="emailAddr" type="email"/>
                    </div>
                    <!-- Bio Field -->
                    <div class="space-y-sm">
                        <label class="block font-label-caps text-label-caps text-on-surface-variant" for="bioText">Bio</label>
                        <textarea x-model="profile.bio" class="w-full bg-background border border-outline-variant rounded-lg px-md py-sm text-body-base font-body-base text-on-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none transition-all placeholder-on-surface-variant/50 resize-y" id="bioText" rows="3"></textarea>
                    </div>
                </div>
                <div class="px-lg py-md border-t border-outline-variant/30 bg-surface-container-lowest/50 flex justify-end">
                    <button @click="saveProfile()" :disabled="isSavingProfile" class="bg-primary-container text-on-primary-container px-lg py-sm rounded-lg font-label-caps text-label-caps hover:bg-primary-container/90 transition-colors shadow-sm disabled:opacity-50" x-text="isSavingProfile ? 'Saving...' : 'Save Changes'"></button>
                </div>
            </section>
            
            <!-- Security Section -->
            <section class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden scroll-mt-2xl" id="security">
                <div class="px-lg py-md border-b border-outline-variant/30 bg-surface-container-lowest/50">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">Security</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Manage your password and secure your account.</p>
                </div>
                <div class="p-lg space-y-xl">
                    <!-- Password Change -->
                    <div class="space-y-md">
                        <h3 class="font-body-base font-semibold text-on-surface">Change Password</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                            <div class="space-y-sm">
                                <label class="block font-label-caps text-label-caps text-on-surface-variant" for="currentPass">Current Password</label>
                                <input x-model="password.current_password" class="w-full bg-background border border-outline-variant rounded-lg px-md py-sm text-body-base font-body-base text-on-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none transition-all" id="currentPass" placeholder="••••••••" type="password"/>
                            </div>
                            <div class="space-y-sm">
                                <label class="block font-label-caps text-label-caps text-on-surface-variant" for="newPass">New Password</label>
                                <input x-model="password.new_password" class="w-full bg-background border border-outline-variant rounded-lg px-md py-sm text-body-base font-body-base text-on-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none transition-all" id="newPass" placeholder="••••••••" type="password"/>
                            </div>
                            <div class="space-y-sm md:col-span-2">
                                <label class="block font-label-caps text-label-caps text-on-surface-variant" for="newPassConfirm">Confirm Password</label>
                                <input x-model="password.new_password_confirmation" class="w-full bg-background border border-outline-variant rounded-lg px-md py-sm text-body-base font-body-base text-on-surface focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none transition-all" id="newPassConfirm" placeholder="••••••••" type="password"/>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button @click="updatePassword()" :disabled="isSavingPassword" class="bg-surface-bright text-on-surface border border-outline-variant px-md py-sm rounded-lg font-label-caps text-label-caps hover:bg-surface-container-highest transition-colors disabled:opacity-50" x-text="isSavingPassword ? 'Updating...' : 'Update Password'"></button>
                        </div>
                    </div>
                    <hr class="border-outline-variant/30"/>
                    <!-- 2FA Toggle -->
                    <div class="flex items-start justify-between gap-lg">
                        <div>
                            <h3 class="font-body-base font-semibold text-on-surface">Two-Factor Authentication</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Add an extra layer of security to your account by requiring a verification code upon login.</p>
                        </div>
                        <!-- Toggle Switch UI -->
                        <button aria-checked="true" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-container focus:ring-offset-2 focus:ring-offset-background bg-primary-container" role="switch" type="button">
                            <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 translate-x-5 transform rounded-full bg-on-primary-container shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>
                </div>
            </section>
            
            <!-- Certificates Section -->
            <section class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden scroll-mt-2xl" id="certificates">
                <div class="px-lg py-md border-b border-outline-variant/30 bg-surface-container-lowest/50">
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">My Certificates</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">View and download your event certificates grouped by community.</p>
                </div>
                <div class="p-lg space-y-xl">
                    @forelse($certificatesByCommunity as $communityName => $certs)
                        <div class="space-y-sm">
                            <h3 class="font-label-caps text-label-caps text-on-surface-variant">{{ $communityName }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-md">
                                @foreach($certs as $cert)
                                    <div class="bg-surface-container-low rounded-xl border border-outline-variant/30 p-md flex items-center justify-between group hover:bg-surface-container transition-colors">
                                        <div class="flex flex-col gap-xs">
                                            <span class="font-body-base font-semibold text-on-surface">{{ $cert->participant->event->title ?? 'Event' }}</span>
                                            <span class="font-body-sm text-on-surface-variant">{{ \Carbon\Carbon::parse($cert->issued_at)->format('M d, Y') }}</span>
                                        </div>
                                        <a href="{{ $cert->file_url }}" target="_blank" class="w-10 h-10 rounded-full bg-surface flex items-center justify-center border border-outline-variant text-on-surface-variant hover:text-primary-container hover:border-primary-container transition-colors opacity-0 group-hover:opacity-100 focus:opacity-100">
                                            <span class="material-symbols-outlined text-[20px]" data-icon="download">download</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-xl">
                            <div class="w-16 h-16 rounded-full bg-surface-container mx-auto flex items-center justify-center text-on-surface-variant mb-md">
                                <span class="material-symbols-outlined text-[32px]" data-icon="workspace_premium">workspace_premium</span>
                            </div>
                            <h3 class="font-body-base font-semibold text-on-surface">No Certificates Yet</h3>
                            <p class="font-body-sm text-on-surface-variant mt-xs">Attend events and wait for the organizers to issue certificates.</p>
                        </div>
                    @endforelse
                </div>
            </section>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function settingsState() {
        return {
            profile: {
                name: '',
                email: '',
                bio: '',
                avatar_url: 'https://ui-avatars.com/api/?name=User&background=random',
                avatarFile: null
            },
            password: {
                current_password: '',
                new_password: '',
                new_password_confirmation: ''
            },
            isSavingProfile: false,
            isSavingPassword: false,
            
            initData() {
                this.fetchProfile();
            },
            
            async fetchProfile() {
                try {
                    const res = await window.apiFetch('/api/auth/me');
                    if (res.ok) {
                        const data = await res.json();
                        this.profile.name = data.name || '';
                        this.profile.email = data.email || '';
                        this.profile.bio = data.bio || '';
                        if (data.avatar_url) {
                            this.profile.avatar_url = data.avatar_url;
                        }
                    }
                } catch (e) {
                    console.error('Fetch profile error:', e);
                }
            },
            
            handleAvatarChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.profile.avatarFile = file;
                    this.profile.avatar_url = URL.createObjectURL(file);
                }
            },
            
            async saveProfile() {
                this.isSavingProfile = true;
                try {
                    const formData = new FormData();
                    formData.append('name', this.profile.name);
                    formData.append('bio', this.profile.bio);
                    if (this.profile.avatarFile) {
                        formData.append('avatar', this.profile.avatarFile);
                    }
                    
                    const res = await window.apiFetch('/api/auth/profile', 'POST', formData);
                    if (res.ok) {
                        alert('Profile updated successfully!');
                        this.fetchProfile(); // reload
                    } else {
                        const err = await res.json();
                        alert('Error: ' + JSON.stringify(err));
                    }
                } catch (e) {
                    alert('Network error');
                } finally {
                    this.isSavingProfile = false;
                }
            },
            
            async updatePassword() {
                if (this.password.new_password !== this.password.new_password_confirmation) {
                    alert('New password confirmation does not match');
                    return;
                }
                
                this.isSavingPassword = true;
                try {
                    const payload = {
                        current_password: this.password.current_password,
                        new_password: this.password.new_password,
                        new_password_confirmation: this.password.new_password_confirmation
                    };
                    const res = await window.apiFetch('/api/auth/profile/password', 'PUT', payload);
                    if (res.ok) {
                        alert('Password updated successfully!');
                        this.password = { current_password: '', new_password: '', new_password_confirmation: '' };
                    } else {
                        const err = await res.json();
                        alert('Error: ' + JSON.stringify(err));
                    }
                } catch (e) {
                    alert('Network error');
                } finally {
                    this.isSavingPassword = false;
                }
            }
        };
    }
</script>
<script>
    // Simple script to handle sub-nav highlighting based on scroll position
    // This adds that extra "Stripe-style" polish
    document.addEventListener('DOMContentLoaded', () => {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('nav[class*="sticky"] a');
        const mainScrollArea = document.querySelector('main');

        function highlightNav() {
            if (!mainScrollArea) return;
            
            let scrollY = mainScrollArea.scrollTop;
            
            sections.forEach(current => {
                const sectionHeight = current.offsetHeight;
                const sectionTop = current.offsetTop - 100; // Offset for sticky header/padding
                const sectionId = current.getAttribute('id');
                
                if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                    navLinks.forEach(link => {
                        link.classList.remove('bg-surface-container-high/50', 'text-on-surface', 'font-semibold');
                        link.classList.add('text-on-surface-variant', 'hover:bg-surface-container-low');
                        if(link.getAttribute('href') === '#' + sectionId) {
                            link.classList.add('bg-surface-container-high/50', 'text-on-surface', 'font-semibold');
                            link.classList.remove('text-on-surface-variant', 'hover:bg-surface-container-low');
                        }
                    });
                }
            });
        }

        if (mainScrollArea) {
            mainScrollArea.addEventListener('scroll', highlightNav);
            // Trigger once on load
            highlightNav();
        }
    });
</script>
@endpush
