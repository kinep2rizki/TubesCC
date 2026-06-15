@extends('layouts.app')

@section('title', 'Certificates')

@section('content')
<div class="max-w-container-max w-full mx-auto flex flex-col gap-lg h-full pb-32 md:pb-2xl">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-xl gap-md">
        <div>
            <h2 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-on-surface">Certificates</h2>
            <p class="font-body-base text-body-base text-on-surface-variant mt-sm">Manage, preview, and issue credentials for recent events.</p>
        </div>
        <button class="bg-primary-container text-on-primary-container font-label-caps text-label-caps px-lg py-md rounded-lg hover:bg-primary transition-colors flex items-center gap-sm shadow-lg shadow-primary-container/20 border border-primary-container/50">
            <span class="material-symbols-outlined text-[18px]">magic_button</span> Generate for All
        </button>
    </div>

    <!-- Bento Layout: Gallery & Preview -->
    <div x-data="{ template: 'modern' }" class="grid grid-cols-1 lg:grid-cols-12 gap-lg h-full pb-2xl">
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
