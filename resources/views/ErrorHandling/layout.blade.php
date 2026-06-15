<!DOCTYPE html>
<html lang="en" class="h-full bg-background dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - PETA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
        .glass-panel {
            background: rgba(26, 28, 30, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-md text-on-surface relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-primary/10 blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-error/10 blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-lg glass-panel rounded-2xl p-xl flex flex-col items-center text-center shadow-2xl relative z-10 border border-outline-variant/30">
        <div class="w-24 h-24 rounded-full flex items-center justify-center mb-lg @yield('icon_bg_class', 'bg-error-container') text-white shadow-inner">
            <span class="material-symbols-outlined text-5xl @yield('icon_text_class', 'text-on-error-container')">@yield('icon', 'error')</span>
        </div>
        
        <h1 class="font-display-md text-display-md font-bold mb-sm">@yield('code', 'Error')</h1>
        <h2 class="font-headline-sm text-headline-sm text-on-surface-variant mb-lg">@yield('message')</h2>
        
        @if(isset($exception) && config('app.debug'))
            <div class="w-full bg-surface-container-highest rounded-lg p-md mb-lg text-left overflow-x-auto text-xs font-mono text-on-surface-variant border border-outline-variant/50 max-h-48 overflow-y-auto">
                <p class="font-bold text-error mb-1">{{ $exception->getMessage() }}</p>
                <p>File: {{ $exception->getFile() }}:{{ $exception->getLine() }}</p>
            </div>
        @endif

        <div class="flex gap-md w-full sm:w-auto">
            <button onclick="window.history.back()" class="flex-1 sm:flex-none px-xl py-md rounded-xl font-label-lg font-semibold bg-surface-container-high text-on-surface hover:bg-surface-variant transition-all border border-outline-variant/50 flex items-center justify-center gap-sm">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                Go Back
            </button>
            <a href="{{ url('/') }}" class="flex-1 sm:flex-none px-xl py-md rounded-xl font-label-lg font-semibold bg-primary text-on-primary hover:bg-primary/90 transition-all shadow-[0_0_20px_rgba(77,142,255,0.3)] flex items-center justify-center gap-sm">
                <span class="material-symbols-outlined text-[20px]">home</span>
                Home
            </a>
        </div>
    </div>
</body>
</html>
