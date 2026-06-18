@props(['event'])

<div x-show="showEditEventModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- Backdrop -->
    <div x-show="showEditEventModal" 
         x-transition.opacity 
         @click="showEditEventModal = false" 
         class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    
    <!-- Modal Content -->
    <div x-show="showEditEventModal" 
         x-transition.scale.origin.bottom 
         class="relative w-full max-w-2xl bg-surface-container-high border border-outline-variant/30 rounded-2xl shadow-2xl overflow-hidden z-10 m-4 flex flex-col max-h-[90vh]">
         
        <div class="p-lg border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-highest/50">
            <h2 class="font-headline-sm text-headline-sm text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">edit_calendar</span> Edit Event
            </h2>
            <button @click="showEditEventModal = false" class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form action="{{ route('events.update', $event->id) }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')
            <div class="p-lg flex-1 overflow-y-auto custom-scrollbar flex flex-col gap-md">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <!-- Title Input -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="font-label-md text-label-md text-on-surface block mb-1">Event Title <span class="text-error">*</span></label>
                        <input type="text" name="title" required :value="event ? event.title : '{{ $event->title }}'" 
                               class="w-full bg-surface-container border border-outline-variant/50 text-on-surface rounded-lg py-2.5 px-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                    </div>

                    <!-- Start Date Input -->
                    <div class="space-y-1.5">
                        <label class="font-label-md text-label-md text-on-surface block mb-1">Start Date & Time <span class="text-error">*</span></label>
                        <input type="datetime-local" name="start_date" required :value="event ? event.start_date.replace(' ', 'T').substring(0, 16) : '{{ \Carbon\Carbon::parse($event->start_date)->format('Y-m-d\TH:i') }}'" 
                               class="w-full bg-surface-container border border-outline-variant/50 text-on-surface rounded-lg py-2.5 px-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                    </div>

                    <!-- End Date Input -->
                    <div class="space-y-1.5">
                        <label class="font-label-md text-label-md text-on-surface block mb-1">End Date & Time <span class="text-error">*</span></label>
                        <input type="datetime-local" name="end_date" required :value="event ? event.end_date.replace(' ', 'T').substring(0, 16) : '{{ \Carbon\Carbon::parse($event->end_date)->format('Y-m-d\TH:i') }}'" 
                               class="w-full bg-surface-container border border-outline-variant/50 text-on-surface rounded-lg py-2.5 px-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                    </div>
                </div>

                <!-- Location Input -->
                <div class="space-y-1.5">
                    <label class="font-label-md text-label-md text-on-surface block mb-1">Location</label>
                    <input type="text" name="location" :value="event ? event.location : '{{ $event->location }}'" 
                           class="w-full bg-surface-container border border-outline-variant/50 text-on-surface rounded-lg py-2.5 px-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                </div>

                <!-- Description Input -->
                <div class="space-y-1.5">
                    <label class="font-label-md text-label-md text-on-surface block mb-1">Description</label>
                    <textarea name="description" rows="3" :value="event ? event.description : '{{ $event->description }}'"
                              class="w-full bg-surface-container border border-outline-variant/50 text-on-surface rounded-lg py-2.5 px-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"></textarea>
                </div>
            </div>

            <div class="p-md border-t border-outline-variant/30 bg-surface-container-highest/50 flex justify-end gap-sm">
                <button type="button" @click="showEditEventModal = false" class="px-md py-2 font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors">Cancel</button>
                <button type="submit" class="bg-primary hover:bg-primary/90 text-on-primary font-label-md text-label-md px-lg py-2 rounded-lg transition-colors shadow-md">Save Changes</button>
            </div>
        </form>
    </div>
</div>
