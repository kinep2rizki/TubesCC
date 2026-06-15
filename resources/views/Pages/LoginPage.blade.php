<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETA - Authentication</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background font-body-base h-screen overflow-hidden selection:bg-primary/30 selection:text-primary">

    <div class="flex h-full w-full" x-data="{ isLogin: true }">
        
        <!-- Left Banner (Branding) -->
        <div class="hidden lg:flex w-1/2 relative flex-col justify-between p-2xl overflow-hidden">
            <!-- Background Image & Overlays -->
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2070&auto=format&fit=crop');"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-background/90 via-background/60 to-primary/30 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent"></div>
            
            <!-- Animated decorative elements -->
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/20 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-tertiary/20 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>

            <!-- Content -->
            <div class="relative z-10">
                <div class="flex items-center gap-sm mb-lg">
                    <div class="w-12 h-12 rounded-xl bg-primary flex items-center justify-center shadow-[0_0_20px_rgba(173,198,255,0.4)]">
                        <span class="material-symbols-outlined text-on-primary text-2xl">event</span>
                    </div>
                    <h1 class="font-display-md text-display-md font-black text-white tracking-tight">PETA</h1>
                </div>
            </div>

            <div class="relative z-10 max-w-lg">
                <h2 class="font-display-sm text-display-sm text-white font-bold mb-md leading-tight" x-text="isLogin ? 'Welcome back to the command center.' : 'Join the future of event management.'"></h2>
                <p class="font-body-lg text-body-lg text-white/70">
                    Platform Event Teknologi Aktivitas. Monitor, manage, and scale your technology events with precision and elegant simplicity.
                </p>
                
                <!-- Testimonial/Stat Card -->
                <div class="mt-xl glass-panel p-md rounded-2xl border border-white/10 flex items-center gap-md backdrop-blur-md">
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-background object-cover" src="https://i.pravatar.cc/100?img=1" alt="Avatar">
                        <img class="w-10 h-10 rounded-full border-2 border-background object-cover" src="https://i.pravatar.cc/100?img=2" alt="Avatar">
                        <img class="w-10 h-10 rounded-full border-2 border-background object-cover" src="https://i.pravatar.cc/100?img=3" alt="Avatar">
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Trusted by 10,000+ organizers</p>
                        <p class="text-white/60 text-xs">Managing over 500 tech communities globally.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Form Area -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-md sm:p-xl relative bg-surface">
            <div class="w-full max-w-md relative z-10 flex flex-col items-center">

                <!-- CARD WRAPPER -->
                <div class="w-full bg-surface-container-low border border-outline-variant/30 rounded-2xl p-6 sm:p-8 shadow-2xl">
                    
                    <!-- Logo Placement (Inside Card, Center Top) -->
                    <div class="flex items-center justify-center gap-4 mb-8">
                        <div class="w-14 h-14 rounded-2xl bg-primary flex items-center justify-center shadow-[0_0_20px_rgba(173,198,255,0.5)]">
                            <span class="material-symbols-outlined text-on-primary" style="font-size: 32px;">event</span>
                        </div>
                        <h1 class="font-black text-primary tracking-tight" style="font-size: 42px; line-height: 1;">PETA</h1>
                    </div>

                    <!-- Form Toggle Tabs -->
                    <div class="flex p-1 bg-surface-container rounded-lg mb-8 border border-outline-variant/30">
                        <button @click="isLogin = true" 
                                :class="isLogin ? 'bg-surface border border-outline-variant/50 shadow-sm text-primary' : 'text-on-surface-variant hover:text-on-surface'"
                                class="flex-1 py-2 font-label-lg text-label-lg rounded-md transition-colors">
                            Sign In
                        </button>
                        <button @click="isLogin = false" 
                                :class="!isLogin ? 'bg-surface border border-outline-variant/50 shadow-sm text-primary' : 'text-on-surface-variant hover:text-on-surface'"
                                class="flex-1 py-2 font-label-lg text-label-lg rounded-md transition-colors">
                            Register
                        </button>
                    </div>

                    <!-- LOGIN FORM -->
                    <div x-show="isLogin">
                        <div class="mb-6">
                            <h2 class="font-headline-lg text-headline-lg text-on-surface mb-1">Sign In</h2>
                            <p class="text-on-surface-variant text-body-md">Enter your credentials to access the dashboard.</p>
                        </div>

                        @if($errors->any())
                        <div class="mb-6 p-3 bg-error/10 border border-error/20 rounded-lg flex items-start gap-2">
                            <span class="material-symbols-outlined text-error text-sm mt-0.5">error</span>
                            <div class="text-error text-sm">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST" class="flex flex-col gap-5">
                            @csrf
                            
                            <!-- Email Input -->
                            <div class="space-y-1.5">
                                <label class="font-label-md text-label-md text-on-surface block mb-1">Email Address</label>
                                <div class="relative group">
                                    <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors z-10" style="left: 16px;">mail</span>
                                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@peta.com" 
                                           class="w-full bg-surface-container border border-outline-variant/50 text-on-surface rounded-lg py-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder:text-outline relative z-0" style="padding-left: 48px; padding-right: 16px;">
                                </div>
                            </div>

                            <!-- Password Input -->
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between mb-1">
                                    <label class="font-label-md text-label-md text-on-surface">Password</label>
                                    <a href="#" class="font-label-sm text-label-sm text-primary hover:underline">Forgot password?</a>
                                </div>
                                <div class="relative group" x-data="{ show: false }">
                                    <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors z-10" style="left: 16px;">lock</span>
                                    <input :type="show ? 'text' : 'password'" name="password" required placeholder="••••••••" 
                                           class="w-full bg-surface-container border border-outline-variant/50 text-on-surface rounded-lg py-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder:text-outline relative z-0" style="padding-left: 48px; padding-right: 48px;">
                                    <button type="button" @click="show = !show" class="absolute top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface flex items-center justify-center z-10" style="right: 16px;">
                                        <span class="material-symbols-outlined text-[18px]" x-text="show ? 'visibility_off' : 'visibility'"></span>
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 mt-1">
                                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-outline-variant/50 bg-surface-container text-primary focus:ring-primary focus:ring-offset-surface">
                                <label for="remember" class="font-body-sm text-body-sm text-on-surface-variant cursor-pointer">Remember me for 30 days</label>
                            </div>

                            <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-on-primary font-label-lg text-label-lg py-3 rounded-lg mt-2 transition-all duration-200 shadow-[0_4px_14px_rgba(173,198,255,0.2)] hover:shadow-[0_6px_20px_rgba(173,198,255,0.3)] hover:-translate-y-0.5 active:translate-y-0">
                                Sign In to Workspace
                            </button>
                            
                            <!-- Testing Bypass Button -->
                            <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center gap-2 bg-surface-variant hover:bg-surface-variant/80 text-on-surface-variant font-label-lg text-label-lg py-3 rounded-lg border border-outline-variant/30 transition-colors mt-[-4px]">
                                <span class="material-symbols-outlined text-[18px]">bug_report</span>
                                Masuk Tanpa Daftar (Testing)
                            </a>
                        </form>
                    </div>

                    <!-- REGISTER FORM -->
                    <div x-show="!isLogin" style="display: none;">
                        <div class="mb-6">
                            <h2 class="font-headline-lg text-headline-lg text-on-surface mb-1">Create Account</h2>
                            <p class="text-on-surface-variant text-body-md">Start managing your events perfectly.</p>
                        </div>

                        <form action="#" method="POST" class="flex flex-col gap-5">
                            @csrf
                            
                            <!-- Name Input -->
                            <div class="space-y-1.5">
                                <label class="font-label-md text-label-md text-on-surface block mb-1">Full Name</label>
                                <div class="relative group">
                                    <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors z-10" style="left: 16px;">person</span>
                                    <input type="text" name="name" required placeholder="John Doe" 
                                           class="w-full bg-surface-container border border-outline-variant/50 text-on-surface rounded-lg py-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder:text-outline relative z-0" style="padding-left: 48px; padding-right: 16px;">
                                </div>
                            </div>

                            <!-- Email Input -->
                            <div class="space-y-1.5">
                                <label class="font-label-md text-label-md text-on-surface block mb-1">Email Address</label>
                                <div class="relative group">
                                    <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors z-10" style="left: 16px;">mail</span>
                                    <input type="email" name="email" required placeholder="john@example.com" 
                                           class="w-full bg-surface-container border border-outline-variant/50 text-on-surface rounded-lg py-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder:text-outline relative z-0" style="padding-left: 48px; padding-right: 16px;">
                                </div>
                            </div>

                            <!-- Password Input -->
                            <div class="space-y-1.5">
                                <label class="font-label-md text-label-md text-on-surface block mb-1">Password</label>
                                <div class="relative group" x-data="{ show: false }">
                                    <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors z-10" style="left: 16px;">lock</span>
                                    <input :type="show ? 'text' : 'password'" name="password" required placeholder="Create a strong password" 
                                           class="w-full bg-surface-container border border-outline-variant/50 text-on-surface rounded-lg py-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder:text-outline relative z-0" style="padding-left: 48px; padding-right: 48px;">
                                    <button type="button" @click="show = !show" class="absolute top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface flex items-center justify-center z-10" style="right: 16px;">
                                        <span class="material-symbols-outlined text-[18px]" x-text="show ? 'visibility_off' : 'visibility'"></span>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-on-primary font-label-lg text-label-lg py-3 rounded-lg mt-2 transition-all duration-200 shadow-[0_4px_14px_rgba(173,198,255,0.2)] hover:shadow-[0_6px_20px_rgba(173,198,255,0.3)] hover:-translate-y-0.5 active:translate-y-0">
                                Create Account
                            </button>
                            
                            <p class="text-center text-xs text-on-surface-variant mt-2">
                                By registering, you agree to our <a href="#" class="text-primary hover:underline">Terms of Service</a> and <a href="#" class="text-primary hover:underline">Privacy Policy</a>.
                            </p>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>
</html>
