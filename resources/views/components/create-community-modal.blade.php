<div x-show="showCreateCommunityModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- Backdrop -->
    <div x-show="showCreateCommunityModal" 
         x-transition.opacity 
         @click="showCreateCommunityModal = false" 
         class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    
    <!-- Modal Content -->
    <div x-show="showCreateCommunityModal" 
         x-transition.scale.origin.bottom 
         class="relative w-full max-w-lg bg-surface-container-high border border-outline-variant/30 rounded-2xl shadow-2xl overflow-hidden z-10 m-4 flex flex-col max-h-[90vh]">
         
        <div class="p-lg border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-highest/50">
            <h2 class="font-headline-sm text-headline-sm text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">add_circle</span> Create New Community
            </h2>
            <button @click="showCreateCommunityModal = false" class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form @submit.prevent="createCommunity" class="flex flex-col flex-1 overflow-hidden">
            <div class="p-lg flex-1 overflow-y-auto custom-scrollbar flex flex-col gap-md">
                
                <!-- Error Message Alert -->
                <div x-show="createError" style="display: none;" class="p-3 bg-error/10 border border-error/20 rounded-lg flex items-start gap-2 mb-2">
                    <span class="material-symbols-outlined text-error text-sm mt-0.5">error</span>
                    <div class="text-error text-sm" x-text="createError"></div>
                </div>

                <!-- Name Input -->
                <div class="space-y-1.5">
                    <label class="font-label-md text-label-md text-on-surface block mb-1">Community Name <span class="text-error">*</span></label>
                    <input type="text" x-model="newCommunityForm.name" required placeholder="e.g. Frontend Developers ID" 
                           class="w-full bg-surface-container border border-outline-variant/50 text-on-surface rounded-lg py-2.5 px-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder:text-outline">
                </div>

                <!-- Description Input -->
                <div class="space-y-1.5">
                    <label class="font-label-md text-label-md text-on-surface block mb-1">Description</label>
                    <textarea x-model="newCommunityForm.description" rows="3" placeholder="Briefly describe the purpose of this community..." 
                              class="w-full bg-surface-container border border-outline-variant/50 text-on-surface rounded-lg py-2.5 px-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder:text-outline"></textarea>
                </div>

                <!-- Password Input (Optional) -->
                <div class="space-y-1.5" x-data="{ show: false }">
                    <label class="font-label-md text-label-md text-on-surface block mb-1">Join Password (Optional)</label>
                    <p class="text-on-surface-variant text-body-sm mb-1">If set, users must enter this password to join the community during registration.</p>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors z-10" style="left: 12px;">key</span>
                        <input :type="show ? 'text' : 'password'" x-model="newCommunityForm.password" placeholder="Enter an access code" 
                               class="w-full bg-surface-container border border-outline-variant/50 text-on-surface rounded-lg py-2.5 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder:text-outline" style="padding-left: 40px; padding-right: 40px;">
                        <button type="button" @click="show = !show" class="absolute top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface flex items-center justify-center z-10" style="right: 12px;">
                            <span class="material-symbols-outlined text-[18px]" x-text="show ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-md border-t border-outline-variant/30 bg-surface-container-highest/50 flex justify-end gap-sm">
                <button type="button" @click="showCreateCommunityModal = false" class="px-md py-2 font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors">Cancel</button>
                <button type="submit" class="bg-primary hover:bg-primary/90 text-on-primary font-label-md text-label-md px-lg py-2 rounded-lg transition-colors shadow-md">Create Community</button>
            </div>
        </form>
    </div>
</div>
