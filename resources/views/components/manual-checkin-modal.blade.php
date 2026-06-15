<div x-show="showManualCheckinModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- Backdrop -->
    <div x-show="showManualCheckinModal" 
         x-transition.opacity 
         @click="showManualCheckinModal = false" 
         class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    
    <!-- Modal Content -->
    <div x-show="showManualCheckinModal" 
         x-transition.scale.origin.bottom 
         class="relative w-full max-w-lg bg-surface-container-high border border-outline-variant/30 rounded-2xl shadow-2xl overflow-hidden z-10 m-4 flex flex-col max-h-[90vh]">
         
        <div class="p-lg border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-highest/50">
            <h2 class="font-headline-sm text-headline-sm text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person_check</span> Manual Check-in
            </h2>
            <button @click="showManualCheckinModal = false" class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form action="{{ route('attendance.store', ['eventId' => $event->id ?? 1]) }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="p-lg flex-1 overflow-y-auto custom-scrollbar flex flex-col gap-md">
                
                <p class="text-body-sm text-on-surface-variant mb-2">
                    Enter the participant's email address to manually record their attendance for this event.
                </p>

                <!-- Email Input -->
                <div class="space-y-1.5">
                    <label class="font-label-md text-label-md text-on-surface block mb-1">Participant Email <span class="text-error">*</span></label>
                    <input type="email" name="email" required placeholder="participant@example.com" 
                           class="w-full bg-surface-container border border-outline-variant/50 text-on-surface rounded-lg py-2.5 px-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder:text-outline">
                </div>

            </div>

            <div class="p-md border-t border-outline-variant/30 bg-surface-container-highest/50 flex justify-end gap-sm">
                <button type="button" @click="showManualCheckinModal = false" class="px-md py-2 font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors">Cancel</button>
                <button type="submit" class="bg-primary hover:bg-primary/90 text-on-primary font-label-md text-label-md px-lg py-2 rounded-lg transition-colors shadow-md">Check In</button>
            </div>
        </form>
    </div>
</div>
