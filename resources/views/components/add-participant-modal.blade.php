<div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity" @click="showAddModal = false"></div>

    <!-- Modal Panel -->
    <div x-show="showAddModal" x-transition.scale.origin.bottom class="relative transform overflow-hidden rounded-xl bg-surface-container-lowest border border-outline-variant/30 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg p-lg z-10 flex flex-col">
        <div class="flex justify-between items-center mb-md border-b border-outline-variant/20 pb-sm">
            <h3 class="font-headline-sm text-headline-sm text-on-surface" id="modal-title">Add New Participant</h3>
            <button @click="showAddModal = false" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('participants.store', ['eventId' => $event->id ?? 1]) }}" method="POST" class="flex flex-col">
            @csrf
            <div class="space-y-sm">
                <div>
                    <label class="block font-label-caps text-label-caps text-on-surface-variant mb-1">Full Name <span class="text-error">*</span></label>
                    <input type="text" name="name" required class="w-full bg-surface-container-high border border-outline-variant/50 rounded-lg px-md py-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none text-body-base" placeholder="e.g. John Doe">
                </div>
                <div>
                    <label class="block font-label-caps text-label-caps text-on-surface-variant mb-1">Email Address <span class="text-error">*</span></label>
                    <input type="email" name="email" required class="w-full bg-surface-container-high border border-outline-variant/50 rounded-lg px-md py-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none text-body-base" placeholder="e.g. john@example.com">
                </div>
                <div>
                    <label class="block font-label-caps text-label-caps text-on-surface-variant mb-1">Status</label>
                    <div class="relative">
                        <select name="status" class="w-full bg-surface-container-high border border-outline-variant/50 rounded-lg px-md pr-xl py-sm text-on-surface focus:ring-1 focus:ring-primary focus:border-primary outline-none text-body-base appearance-none">
                            <option value="Registered">Registered</option>
                            <option value="Attended">Attended</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">expand_more</span>
                    </div>
                </div>
            </div>
            <div class="mt-xl flex justify-end gap-sm border-t border-outline-variant/20 pt-md">
                <button @click="showAddModal = false" type="button" class="px-4 py-2 rounded-lg border border-outline-variant/50 text-on-surface font-body-sm hover:bg-surface-variant/50 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary-container text-on-primary-container font-body-sm hover:bg-primary-fixed transition-colors shadow-sm">Save Participant</button>
            </div>
        </form>
    </div>
</div>
