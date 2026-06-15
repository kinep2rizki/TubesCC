@props([
    'title',
    'type',
    'date',
    'status',
    'participantsCount',
    'avatarsLabel' => null,
    'progressLabel',
    'progressValue',
    'imageUrl',
    'imageAlt' => '',
    'cardClass' => '',
    'imageContainerClass' => '',
    'overlayClass' => 'bg-gradient-to-br from-purple-900/40 to-blue-900/40 mix-blend-overlay',
    'statusClass' => 'text-primary border border-primary/30',
    'typeClass' => 'text-primary bg-primary/10',
    'progressTextClass' => 'text-primary',
    'progressBarClass' => 'bg-primary',
    'hasProgressGradient' => false,
    'titleClass' => 'text-on-surface',
    'link' => null
])

<a href="{{ $link ?? route('event-detail', ['id' => 1]) }}" class="glass-panel rounded-xl overflow-hidden group hover:-translate-y-1 transition-transform duration-300 block {{ $cardClass }}">
    <!-- Image Banner Placeholder -->
    <div class="h-32 w-full bg-surface-variant relative overflow-hidden {{ $imageContainerClass }}">
        <div class="absolute inset-0 {{ $overlayClass }} z-10"></div>
        <img alt="{{ $imageAlt }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-500 group-hover:scale-105 transform" src="{{ $imageUrl }}"/>
        <div class="absolute top-sm right-sm z-20 bg-surface-container/90 backdrop-blur-sm px-xs py-[2px] rounded {{ $statusClass }} font-label-caps text-[10px]">
            {{ $status }}
        </div>
    </div>
    <div class="p-md flex flex-col gap-sm">
        <div class="flex justify-between items-start">
            <span class="font-label-caps text-label-caps {{ $typeClass }} px-2 py-1 rounded-sm">{{ $type }}</span>
            <span class="font-mono-code text-mono-code text-on-surface-variant flex items-center gap-xs">
                <span class="material-symbols-outlined text-[14px]">calendar_today</span> {{ $date }}
            </span>
        </div>
        <h3 class="font-headline-sm text-headline-sm {{ $titleClass }} truncate">{{ $title }}</h3>
        <div class="flex items-center gap-md mt-xs">
            @if($avatarsLabel)
            <div class="flex -space-x-2">
                <div class="w-6 h-6 rounded-full border border-surface bg-surface-container z-30"></div>
                <div class="w-6 h-6 rounded-full border border-surface bg-surface-variant z-20"></div>
                <div class="w-6 h-6 rounded-full border border-surface bg-surface-bright z-10 flex items-center justify-center text-[8px] font-bold">{{ $avatarsLabel }}</div>
            </div>
            @endif
            <span class="font-body-sm text-body-sm text-on-surface-variant">{{ $participantsCount }}</span>
        </div>
        <div class="mt-md space-y-xs">
            <div class="flex justify-between font-label-caps text-label-caps text-on-surface-variant">
                <span>{{ $progressLabel }}</span>
                <span class="{{ $progressTextClass }}">{{ $progressValue }}</span>
            </div>
            <div class="h-1.5 w-full bg-surface-container-high rounded-full overflow-hidden">
                <div class="h-full {{ $progressBarClass }} rounded-full relative" style="width: {{ $progressValue }}">
                    @if($hasProgressGradient)
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/30"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</a>
