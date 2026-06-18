@extends('layouts.app')

@section('title', 'Certificates')

@section('content')
<div x-data="{ 
        event: @js($event),
        eventId: {{ $event->id }},
        template: 'modern',
        isGenerating: false,
        showModal: false,
        selectedEventId: {{ $event->id }},
        selectedParticipants: [],
        selectAll: false,
        
        toggleSelectAll() {
            this.selectAll = !this.selectAll;
            if (this.selectAll) {
                // Collect all checkboxes that are rendered
                this.selectedParticipants = Array.from(document.querySelectorAll('.participant-cb')).map(cb => parseInt(cb.value));
            } else {
                this.selectedParticipants = [];
            }
        },

        async generateCertificates() {
            this.isGenerating = true;
            try {
                const result = await fetchApi('/api/certificates/generate', 'POST', {
                    event_id: this.selectedEventId,
                    template_style: this.template,
                    participant_ids: this.selectedParticipants.length > 0 ? this.selectedParticipants : null
                });
                
                if (result.success) {
                    alert('Sukses: ' + result.message);
                    this.showModal = false;
                } else {
                    alert('Gagal: ' + (result.message || 'Unauthorized'));
                }
            } catch (error) {
                alert('Terjadi kesalahan koneksi.');
            } finally {
                this.isGenerating = false;
            }
        }
    }" class="max-w-container-max w-full mx-auto flex flex-col gap-lg h-full pb-32 md:pb-2xl">
    
    <!-- Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" style="display: none;">
        <div @click.away="showModal = false" class="bg-surface p-xl rounded-2xl shadow-xl w-full max-w-lg border border-outline-variant/30">
            <div class="flex justify-between items-center mb-md border-b border-outline-variant/30 pb-sm">
                <h3 class="font-headline-sm text-on-surface">Generate Settings</h3>
                <button @click="showModal = false" class="text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="flex flex-col gap-md mb-xl">
                <div>
                    <label class="block font-label-sm text-on-surface-variant mb-xs">Select Event</label>
                    <select x-model="selectedEventId" @change="window.location.href='/events/'+selectedEventId+'/certificates'" class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg p-sm text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                        @foreach($allEvents as $ev)
                            <option value="{{ $ev->id }}">{{ $ev->title }}</option>
                        @endforeach
                    </select>
                    <p class="text-[12px] text-on-surface-variant mt-1">Change event will reload the page.</p>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-xs">
                        <label class="font-label-sm text-on-surface-variant">Select Participants</label>
                        <label class="flex items-center gap-2 cursor-pointer text-sm">
                            <input type="checkbox" @click="toggleSelectAll()" :checked="selectAll" class="rounded border-outline-variant text-primary focus:ring-primary">
                            <span class="text-on-surface-variant">Select All</span>
                        </label>
                    </div>
                    
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg max-h-48 overflow-y-auto p-xs">
                        @forelse($participants as $p)
                        <label class="flex items-center gap-3 p-2 hover:bg-surface-variant rounded cursor-pointer">
                            <input type="checkbox" x-model="selectedParticipants" value="{{ $p->id }}" class="participant-cb rounded border-outline-variant text-primary focus:ring-primary">
                            <div>
                                <p class="font-label-md text-on-surface">{{ $p->user->name }}</p>
                                <p class="text-[12px] text-on-surface-variant">{{ $p->user->email }}</p>
                            </div>
                        </label>
                        @empty
                        <p class="p-sm text-on-surface-variant text-sm text-center">No attended participants found.</p>
                        @endforelse
                    </div>
                    <p class="text-[12px] text-on-surface-variant mt-1">If none selected, it will generate for all attended participants.</p>
                </div>
            </div>

            <div class="flex justify-end gap-sm">
                <button @click="showModal = false" class="px-md py-sm rounded-lg font-label-md text-on-surface hover:bg-surface-variant transition-colors border border-outline-variant/30">Cancel</button>
                <button @click="generateCertificates()" :disabled="isGenerating" class="bg-primary text-on-primary px-md py-sm rounded-lg font-label-md hover:opacity-90 transition-opacity flex items-center gap-2 disabled:opacity-50">
                    <span class="material-symbols-outlined text-[18px]" x-text="isGenerating ? 'hourglass_empty' : 'bolt'"></span>
                    <span x-text="isGenerating ? 'Generating...' : 'Start Generate'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Event Header -->
    <x-event-header :event="$event" activeTab="certificates" />

    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-md mt-sm">
        <div>
            <h3 class="font-headline-sm text-headline-sm text-on-surface">Certificates</h3>
        </div>
        
        <div class="flex items-center gap-sm w-full md:w-auto">
            @php
                $userRoles = session('user_roles', []);
                $activeRole = session('active_community_role');
                $canGenerate = in_array('Super Admin', $userRoles) || in_array($activeRole, ['Owner', 'Admin']);
            @endphp
            @if($canGenerate)
            <button @click="showModal = true" class="flex-1 md:flex-none flex items-center justify-center gap-xs px-md py-sm rounded-lg bg-primary text-on-primary font-label-caps text-label-caps hover:bg-primary/90 transition-colors shadow-[0_0_15px_rgba(77,142,255,0.3)]">
                <span class="material-symbols-outlined text-[18px]">settings</span> 
                <span>Generate Settings</span>
            </button>
            @endif
        </div>
    </div>

    <!-- Bento Layout: Gallery & Preview -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg h-full pb-2xl">
        <!-- Gallery Column -->
        <div class="col-span-1 lg:col-span-4 flex flex-col gap-md">
            <h3 class="font-headline-sm text-headline-sm text-on-surface border-b border-outline-variant/30 pb-sm mb-sm">Templates</h3>
            <div class="grid grid-cols-2 gap-sm">
                <!-- Template Card 1 -->
                <div @click="template = 'modern'" :class="template === 'modern' ? 'border-primary ring-1 ring-primary bg-surface-container-highest' : 'border-transparent hover:border-outline-variant/50'" class="glass-panel p-xs rounded-xl cursor-pointer transition-all relative overflow-hidden group">
                    <div class="aspect-video bg-surface-container-lowest rounded-lg border border-outline-variant/50 flex flex-col items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent"></div>
                        <div class="w-16 h-1 bg-outline-variant/30 mb-2"></div>
                        <div class="w-24 h-1.5 bg-primary/40 mb-1"></div>
                        <div class="w-12 h-1 bg-outline-variant/30"></div>
                    </div>
                    <div class="p-sm flex justify-between items-center mt-xs">
                        <span class="font-label-caps text-label-caps transition-colors" :class="template === 'modern' ? 'text-primary' : 'text-on-surface-variant'">Modern Tech</span>
                        <span class="material-symbols-outlined text-[16px] text-primary transition-opacity" :class="template === 'modern' ? 'opacity-100' : 'opacity-0'" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    </div>
                </div>

                <!-- Template Card 2 -->
                <div @click="template = 'classic'" :class="template === 'classic' ? 'border-primary ring-1 ring-primary bg-surface-container-highest' : 'border-transparent hover:border-outline-variant/50'" class="glass-panel p-xs rounded-xl cursor-pointer transition-all group">
                    <div class="aspect-video bg-surface-container-lowest rounded-lg border border-outline-variant/20 flex flex-col items-center justify-center relative overflow-hidden group-hover:border-outline-variant/50 transition-colors">
                        <div class="w-20 h-1.5 bg-outline-variant/50 mb-2"></div>
                        <div class="w-16 h-1 bg-outline-variant/30 mb-1"></div>
                        <div class="w-24 h-1 bg-outline-variant/30"></div>
                    </div>
                    <div class="p-sm flex justify-between items-center mt-xs">
                        <span class="font-label-caps text-label-caps transition-colors" :class="template === 'classic' ? 'text-primary' : 'text-on-surface-variant'">Classic Minimal</span>
                        <span class="material-symbols-outlined text-[16px] text-primary transition-opacity" :class="template === 'classic' ? 'opacity-100' : 'opacity-0'" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    </div>
                </div>

                <!-- Template Card 3 -->
                <div @click="template = 'accent'" :class="template === 'accent' ? 'border-primary ring-1 ring-primary bg-surface-container-highest' : 'border-transparent hover:border-outline-variant/50'" class="glass-panel p-xs rounded-xl cursor-pointer transition-all group">
                    <div class="aspect-video bg-surface-container-lowest rounded-lg border border-outline-variant/20 flex flex-col items-start justify-center p-md relative overflow-hidden group-hover:border-outline-variant/50 transition-colors">
                        <div class="absolute left-0 top-0 bottom-0 w-2 bg-secondary-container/50"></div>
                        <div class="w-16 h-1.5 bg-outline-variant/50 mb-2"></div>
                        <div class="w-10 h-1 bg-outline-variant/30"></div>
                    </div>
                    <div class="p-sm flex justify-between items-center mt-xs">
                        <span class="font-label-caps text-label-caps transition-colors" :class="template === 'accent' ? 'text-primary' : 'text-on-surface-variant'">Side Accent</span>
                        <span class="material-symbols-outlined text-[16px] text-primary transition-opacity" :class="template === 'accent' ? 'opacity-100' : 'opacity-0'" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    </div>
                </div>

                <!-- Template Card 4 -->
                <div @click="template = 'centric'" :class="template === 'centric' ? 'border-primary ring-1 ring-primary bg-surface-container-highest' : 'border-transparent hover:border-outline-variant/50'" class="glass-panel p-xs rounded-xl cursor-pointer transition-all group">
                    <div class="aspect-video bg-surface-container-lowest rounded-lg border border-outline-variant/20 flex items-center justify-center relative overflow-hidden group-hover:border-outline-variant/50 transition-colors">
                        <div class="w-12 h-12 rounded-full border border-outline-variant/30 flex items-center justify-center">
                            <div class="w-6 h-6 rounded-full bg-outline-variant/20"></div>
                        </div>
                    </div>
                    <div class="p-sm flex justify-between items-center mt-xs">
                        <span class="font-label-caps text-label-caps transition-colors" :class="template === 'centric' ? 'text-primary' : 'text-on-surface-variant'">Centric Seal</span>
                        <span class="material-symbols-outlined text-[16px] text-primary transition-opacity" :class="template === 'centric' ? 'opacity-100' : 'opacity-0'" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Preview Column -->
        <div class="col-span-1 lg:col-span-8 flex flex-col h-[600px] lg:h-auto">
            <div class="glass-panel rounded-xl flex-1 flex flex-col overflow-hidden">
                <div class="px-lg py-sm border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-lowest/50">
                    <div class="flex items-center gap-sm">
                        <span class="material-symbols-outlined text-primary text-[20px]">visibility</span>
                        <span class="font-label-caps text-label-caps text-on-surface">Live Preview</span>
                    </div>
                    <div class="flex gap-sm">
                        <button class="p-xs hover:bg-surface-variant rounded transition-colors text-on-surface-variant"><span class="material-symbols-outlined text-[18px]">zoom_out</span></button>
                        <button class="p-xs hover:bg-surface-variant rounded transition-colors text-on-surface-variant"><span class="material-symbols-outlined text-[18px]">zoom_in</span></button>
                    </div>
                </div>

                <!-- Canvas Area -->
                <div class="flex-1 bg-surface-container-lowest p-lg md:p-2xl flex items-center justify-center overflow-auto relative">
                    <!-- Background Grid Pattern -->
                    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#8c909f 1px, transparent 1px); background-size: 24px 24px;"></div>
                    
                    <!-- Certificate Document Form (Modern Tech) -->
                    <div x-show="template === 'modern'" x-transition class="w-full max-w-3xl aspect-[1.414/1] bg-white text-gray-800 rounded shadow-2xl relative flex flex-col justify-center items-center p-2xl border border-outline-variant/20 scale-95 md:scale-100 transform origin-center">
                        <!-- Decorative Elements -->
                        <div class="absolute top-0 left-0 w-full h-4 bg-primary/90 rounded-t"></div>
                        <div class="absolute top-4 left-4 right-4 bottom-4 border border-outline/20"></div>
                        
                        <!-- Logo Placeholder -->
                        <div class="w-16 h-16 rounded-lg bg-surface-container-high/10 border border-outline/30 flex items-center justify-center mb-xl">
                            <span class="material-symbols-outlined text-outline text-[32px]">webhook</span>
                        </div>
                        
                        <div class="text-center w-full max-w-lg z-10">
                            <h4 class="font-label-caps text-label-caps tracking-widest text-outline mb-md uppercase">Certificate of Completion</h4>
                            <p class="font-body-sm text-body-sm text-gray-500 mb-sm">This is to certify that</p>
                            
                            <!-- Dynamic Field -->
                            <div class="border-b border-primary/40 pb-sm mb-lg relative group cursor-text">
                                <h1 class="font-display-lg-mobile text-display-lg-mobile font-bold text-gray-900">{Participant Name}</h1>
                                <div class="absolute -right-8 top-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="material-symbols-outlined text-primary text-[16px]">edit</span>
                                </div>
                            </div>
                            
                            <p class="font-body-base text-body-base text-gray-600 leading-relaxed">
                                has successfully completed all requirements for the <br/>
                                <span class="font-semibold text-gray-900">{{ $event->title }}</span> <br/>
                                held on <span class="font-mono-code text-mono-code">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</span>.
                            </p>
                        </div>
                        
                        <!-- Signatures -->
                        <div class="absolute bottom-12 left-12 right-12 flex justify-between items-end px-xl border-t border-outline/20 pt-md">
                            <div class="text-center">
                                <div class="h-8 border-b border-outline/40 w-32 mb-xs"></div>
                                <p class="font-label-caps text-label-caps text-outline text-[10px]">Program Director</p>
                            </div>
                            <div class="w-20 h-20 rounded-full border border-primary/30 bg-primary/5 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary/40 text-[32px]">verified</span>
                            </div>
                            <div class="text-center">
                                <div class="h-8 border-b border-outline/40 w-32 mb-xs"></div>
                                <p class="font-label-caps text-label-caps text-outline text-[10px]">Lead Instructor</p>
                            </div>
                        </div>
                    </div>

                    <!-- Classic Minimal -->
                    <div x-show="template === 'classic'" style="display: none;" x-transition class="w-full max-w-3xl aspect-[1.414/1] bg-white text-gray-900 rounded shadow-2xl relative flex flex-col justify-center items-center p-2xl border-8 border-double border-gray-300 scale-95 md:scale-100 transform origin-center font-serif">
                        <div class="text-center w-full max-w-lg z-10">
                            <h4 class="text-xl tracking-[0.2em] text-gray-500 mb-xl uppercase border-b border-gray-300 pb-2">Certificate of Completion</h4>
                            <p class="text-lg italic text-gray-600 mb-sm">This is to certify that</p>
                            
                            <div class="mb-lg relative group cursor-text border-b border-gray-300/50 pb-sm">
                                <h1 class="text-5xl font-bold text-gray-900 mb-2">{Participant Name}</h1>
                            </div>
                            
                            <p class="text-base text-gray-700 leading-relaxed max-w-md mx-auto mt-lg">
                                has successfully completed all requirements for the <br/>
                                <span class="font-bold">{{ $event->title }}</span> <br/>
                                held on <span class="text-sm">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</span>.
                            </p>
                        </div>
                        
                        <div class="absolute bottom-16 left-16 right-16 flex justify-between items-end px-xl">
                            <div class="text-center">
                                <div class="h-8 border-b border-gray-400 w-40 mb-2"></div>
                                <p class="text-[10px] tracking-widest uppercase text-gray-500">Program Director</p>
                            </div>
                            <div class="text-center">
                                <div class="h-8 border-b border-gray-400 w-40 mb-2"></div>
                                <p class="text-[10px] tracking-widest uppercase text-gray-500">Lead Instructor</p>
                            </div>
                        </div>
                    </div>

                    <!-- Side Accent -->
                    <div x-show="template === 'accent'" style="display: none;" x-transition class="w-full max-w-3xl aspect-[1.414/1] bg-white text-gray-800 rounded shadow-2xl relative flex p-0 overflow-hidden border border-gray-200 scale-95 md:scale-100 transform origin-center">
                        <!-- Left sidebar -->
                        <div class="w-1/4 bg-primary flex flex-col items-center py-xl px-md h-full relative">
                            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-auto mt-4">
                                <span class="material-symbols-outlined text-white text-[32px]">emoji_events</span>
                            </div>
                            <!-- Side decoration -->
                            <div class="absolute bottom-0 left-0 w-full h-32 bg-primary-container/30 skew-y-12 translate-y-10"></div>
                        </div>
                        
                        <!-- Content -->
                        <div class="w-3/4 flex flex-col justify-center items-start p-2xl pl-xl">
                            <h4 class="font-label-caps text-label-caps tracking-widest text-primary mb-xl uppercase">Certificate</h4>
                            <p class="font-body-base text-body-base text-gray-500 mb-xs">Presented to</p>
                            
                            <h1 class="text-5xl font-bold text-gray-900 mb-lg border-l-4 border-primary pl-4">{Participant Name}</h1>
                            
                            <p class="font-body-base text-body-base text-gray-600 leading-relaxed max-w-sm mb-xl">
                                For successful completion of <br/>
                                <span class="font-semibold text-gray-900">{{ $event->title }}</span> <br/>
                                on <span class="font-mono-code">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</span>.
                            </p>
                            
                            <!-- Signatures -->
                            <div class="mt-auto w-full flex justify-start gap-xl pt-md">
                                <div>
                                    <div class="h-8 border-b border-gray-300 w-40 mb-1"></div>
                                    <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">Authorized Signature</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Centric Seal -->
                    <div x-show="template === 'centric'" style="display: none;" x-transition class="w-full max-w-3xl aspect-[1.414/1] bg-white text-gray-900 rounded shadow-2xl relative flex flex-col justify-center items-center p-2xl border-4 border-primary/20 scale-95 md:scale-100 transform origin-center">
                        <!-- Decorative Border -->
                        <div class="absolute inset-4 border border-primary/10"></div>
                        
                        <!-- Centric Seal -->
                        <div class="w-24 h-24 rounded-full border-2 border-primary/30 bg-white flex items-center justify-center shadow-[0_0_20px_rgba(0,0,0,0.05)] mb-md z-10">
                            <div class="w-20 h-20 rounded-full border border-dashed border-primary/40 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-[40px]">workspace_premium</span>
                            </div>
                        </div>
                        
                        <div class="text-center w-full max-w-lg z-10">
                            <h4 class="font-display-lg text-display-lg text-primary mb-md">CERTIFICATE</h4>
                            <p class="font-body-base text-gray-500 mb-xl tracking-widest uppercase text-sm">Of Achievement</p>
                            
                            <p class="font-body-sm text-gray-400 mb-xs italic">Proudly presented to</p>
                            
                            <div class="mb-lg">
                                <h1 class="text-5xl font-serif text-gray-900 mb-2">{Participant Name}</h1>
                                <div class="w-full max-w-sm mx-auto h-px bg-gradient-to-r from-transparent via-primary/30 to-transparent"></div>
                            </div>
                            
                            <p class="font-body-sm text-gray-600 leading-relaxed max-w-md mx-auto mb-xl">
                                For demonstrating exceptional skill and completing <br/>
                                <span class="font-bold text-gray-900">{{ $event->title }}</span> <br/>
                                on <span class="text-gray-500">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</span>.
                            </p>
                        </div>
                        
                        <!-- Signatures -->
                        <div class="absolute bottom-10 left-0 right-0 flex justify-center gap-2xl px-xl">
                            <div class="text-center">
                                <div class="h-8 border-b border-gray-400 w-40 mb-2"></div>
                                <p class="text-[10px] tracking-widest uppercase text-gray-400">Director</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
