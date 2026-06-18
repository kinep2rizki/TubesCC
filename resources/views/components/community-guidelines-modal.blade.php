@props(['community'])

<div x-show="showGuidelinesModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div x-show="showGuidelinesModal" x-transition.opacity class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity" @click="showGuidelinesModal = false"></div>

    <!-- Modal Panel -->
    <div x-show="showGuidelinesModal" x-transition.scale.origin.bottom class="relative transform overflow-hidden rounded-xl bg-surface-container-lowest border border-outline-variant/30 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-3xl flex flex-col max-h-[90vh] z-10">
        
        <!-- Modal Header -->
        <div class="px-lg py-md border-b border-outline-variant/20 flex justify-between items-center bg-surface-container/50">
            <div class="flex items-center gap-sm">
                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center border border-primary/20">
                    <span class="material-symbols-outlined text-primary text-[20px]">rule</span>
                </div>
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface" id="modal-title">Guidelines & Socials</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Update your community rules and external links.</p>
                </div>
            </div>
            <button @click="showGuidelinesModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-md hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form action="#" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')
            
            <!-- Modal Body (Scrollable) -->
            <div class="p-lg overflow-y-auto flex-1 flex flex-col gap-xl">
                
                <!-- Community Profile Section -->
                <div class="space-y-sm">
                    <h4 class="font-label-caps text-label-caps text-outline uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">badge</span> Community Profile
                    </h4>
                    
                    <div class="space-y-1.5 mt-2">
                        <label class="font-label-md text-label-md text-on-surface block mb-1">Community Name <span class="text-error">*</span></label>
                        <input type="text" name="name" required value="{{ $community['name'] ?? '' }}" 
                               class="w-full bg-surface-container border border-outline-variant/50 text-on-surface rounded-lg py-2.5 px-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                    </div>
                </div>

                <!-- Guidelines Section -->
                <div class="space-y-sm">
                    <h4 class="font-label-caps text-label-caps text-outline uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">article</span> Community Guidelines / Description
                    </h4>
                    <p class="text-body-sm text-on-surface-variant mb-2">Set the tone and rules for your members. This will be displayed on your public community page.</p>
                    
                    <div class="rounded-lg border border-outline-variant/40 bg-surface-container overflow-hidden focus-within:ring-1 focus-within:ring-primary focus-within:border-primary transition-shadow">
                        <!-- Fake Rich Text Toolbar -->
                        <div class="bg-surface-container-high border-b border-outline-variant/30 px-sm py-1 flex gap-1">
                            <button type="button" class="p-1 rounded hover:bg-white/10 text-on-surface-variant transition-colors"><span class="material-symbols-outlined text-[18px]">format_bold</span></button>
                            <button type="button" class="p-1 rounded hover:bg-white/10 text-on-surface-variant transition-colors"><span class="material-symbols-outlined text-[18px]">format_italic</span></button>
                            <button type="button" class="p-1 rounded hover:bg-white/10 text-on-surface-variant transition-colors"><span class="material-symbols-outlined text-[18px]">format_list_bulleted</span></button>
                            <button type="button" class="p-1 rounded hover:bg-white/10 text-on-surface-variant transition-colors"><span class="material-symbols-outlined text-[18px]">format_list_numbered</span></button>
                            <div class="w-px h-6 bg-outline-variant/30 mx-1 my-auto"></div>
                            <button type="button" class="p-1 rounded hover:bg-white/10 text-on-surface-variant transition-colors"><span class="material-symbols-outlined text-[18px]">link</span></button>
                        </div>
                        <textarea name="description" class="w-full h-40 bg-transparent p-md text-on-surface focus:outline-none text-body-base resize-y" placeholder="1. Be respectful and welcoming...&#10;2. No spam or self-promotion...">{{ $community['description'] ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Social Links Section -->
                <div class="space-y-sm opacity-50 relative">
                    <div class="absolute inset-0 bg-background/10 z-10 flex items-center justify-center backdrop-blur-[1px] rounded-lg">
                        <span class="bg-surface px-md py-xs rounded-full text-xs font-label-caps text-on-surface border border-outline-variant/30">Coming Soon</span>
                    </div>
                    <h4 class="font-label-caps text-label-caps text-outline uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">public</span> Social Links
                    </h4>
                    <p class="text-body-sm text-on-surface-variant mb-2">Connect your community to your external platforms.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                        <!-- Discord -->
                        <div class="flex items-center bg-surface-container-high border border-outline-variant/50 rounded-lg overflow-hidden">
                            <div class="bg-surface-variant px-md py-sm flex items-center justify-center border-r border-outline-variant/50">
                                <span class="material-symbols-outlined text-primary">forum</span>
                            </div>
                            <input type="url" disabled class="w-full bg-transparent px-md py-sm text-on-surface outline-none text-body-base" placeholder="https://discord.gg/...">
                        </div>
                        <!-- Twitter / X -->
                        <div class="flex items-center bg-surface-container-high border border-outline-variant/50 rounded-lg overflow-hidden">
                            <div class="bg-surface-variant px-md py-sm flex items-center justify-center border-r border-outline-variant/50">
                                <span class="material-symbols-outlined text-primary">alternate_email</span>
                            </div>
                            <input type="url" disabled class="w-full bg-transparent px-md py-sm text-on-surface outline-none text-body-base" placeholder="https://twitter.com/...">
                        </div>
                        <!-- GitHub -->
                        <div class="flex items-center bg-surface-container-high border border-outline-variant/50 rounded-lg overflow-hidden">
                            <div class="bg-surface-variant px-md py-sm flex items-center justify-center border-r border-outline-variant/50">
                                <span class="material-symbols-outlined text-primary">code</span>
                            </div>
                            <input type="url" disabled class="w-full bg-transparent px-md py-sm text-on-surface outline-none text-body-base" placeholder="https://github.com/...">
                        </div>
                        <!-- Website -->
                        <div class="flex items-center bg-surface-container-high border border-outline-variant/50 rounded-lg overflow-hidden">
                            <div class="bg-surface-variant px-md py-sm flex items-center justify-center border-r border-outline-variant/50">
                                <span class="material-symbols-outlined text-primary">language</span>
                            </div>
                            <input type="url" disabled class="w-full bg-transparent px-md py-sm text-on-surface outline-none text-body-base" placeholder="https://yoursite.com">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-lg py-md border-t border-outline-variant/20 flex justify-end gap-sm bg-surface-container-lowest/80">
                <button @click="showGuidelinesModal = false" type="button" class="px-4 py-2 rounded-lg border border-outline-variant/50 text-on-surface font-body-sm hover:bg-surface-variant/50 transition-colors">Cancel</button>
                <button type="submit" class="flex items-center gap-xs px-4 py-2 rounded-lg bg-primary-container text-on-primary-container font-body-sm hover:bg-primary-fixed transition-colors shadow-[0_0_15px_rgba(77,142,255,0.2)]">
                    <span class="material-symbols-outlined text-[18px]">save</span> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
