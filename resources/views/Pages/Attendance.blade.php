@extends('layouts.app')

@section('title', 'Attendance Check-in')

@section('content')
<div x-data="attendanceState({{ $eventId }})" x-init="init()" class="max-w-container-max mx-auto flex flex-col gap-lg min-h-full pb-32 md:pb-2xl">
    <!-- Event Header -->
    <x-event-header eventId="{{ $eventId }}" activeTab="attendance" />

    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-sm mt-sm">
        <div>
            <h3 class="font-headline-sm text-headline-sm text-on-surface">Live Check-in</h3>
        </div>

        <div x-show="feedbackMessage" :class="feedbackType === 'success' ? 'bg-primary/20 text-primary border-primary/50' : 'bg-error/20 text-error border-error/50'" class="border px-md py-sm rounded-lg font-body-sm text-body-sm w-full md:w-auto text-center md:text-left">
            <span x-text="feedbackMessage"></span>
        </div>
        
        <div class="flex items-center gap-md" style="display: none;" x-show="canManage">
            <button @click="showManualCheckinModal = true" class="flex items-center gap-xs px-md py-2 rounded-lg border border-outline-variant/50 text-on-surface-variant hover:bg-surface-variant transition-colors font-label-caps text-label-caps">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                Manual Check-in
            </button>
            <div class="flex items-center gap-sm bg-surface-container-low px-md py-2 rounded-lg border border-outline-variant/30">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                </span>
                <span class="font-label-caps text-label-caps text-primary">SCANNER ACTIVE</span>
            </div>
        </div>
    </div>

    <!-- Main Grid: Scanner (Left) & Feed/Stats (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg h-[600px]">
        
        <!-- Left Column: QR Scanner -->
        <div class="lg:col-span-7 flex flex-col gap-md h-full">
            <div class="bg-surface-container-low border border-outline-variant/30 rounded-xl relative overflow-hidden flex-1 flex flex-col items-center justify-center group">
                <!-- Atmospheric Blur Elements -->
                <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-primary/10 rounded-full blur-3xl mix-blend-screen pointer-events-none"></div>
                <div class="absolute bottom-1/4 right-1/4 w-48 h-48 bg-secondary/10 rounded-full blur-2xl mix-blend-screen pointer-events-none"></div>
                
                <!-- Scanner Viewfinder -->
                <div class="relative w-64 h-64 md:w-80 md:h-80 border-2 border-dashed border-outline-variant rounded-lg flex items-center justify-center bg-black/40 backdrop-blur-sm z-10 overflow-hidden" id="reader-container">
                    
                    <div id="reader" class="w-full h-full" x-show="isScannerActive"></div>

                    <!-- Dummy visual when inactive -->
                    <template x-if="!isScannerActive">
                        <div class="flex flex-col items-center justify-center w-full h-full cursor-pointer" @click="startScanner">
                            <span class="material-symbols-outlined text-outline-variant text-[48px] opacity-50 group-hover:scale-110 transition-transform duration-500">qr_code_scanner</span>
                            <span class="text-outline-variant mt-2 font-label-sm text-label-sm">Click to Start Scanner</span>
                        </div>
                    </template>
                </div>
                
                <div class="absolute bottom-lg z-10 flex flex-col gap-sm items-center">
                    <div class="flex items-center gap-sm bg-surface-container/80 backdrop-blur-md px-md py-sm rounded-full border border-outline-variant/30">
                        <span class="material-symbols-outlined text-primary text-[20px]">videocam</span>
                        <span class="font-body-sm text-body-sm text-on-surface">Camera 01: Main Entrance</span>
                    </div>
                    <button x-show="isScannerActive" @click="stopScanner" class="bg-error/20 text-error hover:bg-error/30 transition-colors border border-error/50 px-4 py-1.5 rounded-full font-label-sm text-label-sm flex items-center gap-2 shadow-lg">
                        <span class="material-symbols-outlined text-[16px]">stop_circle</span> Stop Scanner
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Column: Stats & Feed -->
        <div class="lg:col-span-5 flex flex-col gap-lg h-full">
            <!-- Stats Bento -->
            <div class="grid grid-cols-2 gap-sm">
                <div class="bg-surface-container-low border border-outline-variant/30 rounded-xl p-md flex flex-col justify-between relative overflow-hidden">
                    <div class="flex justify-between items-start mb-lg">
                        <span class="font-label-caps text-label-caps text-on-surface-variant">Present</span>
                        <span class="material-symbols-outlined text-primary">how_to_reg</span>
                    </div>
                    <div>
                        <div class="font-headline-sm text-headline-sm text-on-surface" x-text="stats.presentCount"></div>
                        <div class="font-body-sm text-body-sm text-primary mt-xs flex items-center gap-xs">
                            <span class="material-symbols-outlined text-[16px]">trending_up</span> Live
                        </div>
                    </div>
                </div>
                <div class="bg-surface-container-low border border-outline-variant/30 rounded-xl p-md flex flex-col justify-between relative overflow-hidden">
                    <div class="flex justify-between items-start mb-lg">
                        <span class="font-label-caps text-label-caps text-on-surface-variant">Expected</span>
                        <span class="material-symbols-outlined text-outline-variant">person_off</span>
                    </div>
                    <div>
                        <div class="font-headline-sm text-headline-sm text-on-surface" x-text="stats.expectedCount"></div>
                        <div class="font-body-sm text-body-sm text-outline-variant mt-xs" x-text="stats.expectedCount > 0 ? ((stats.presentCount / stats.expectedCount) * 100).toFixed(1) + '% Capacity' : '0% Capacity'"></div>
                    </div>
                    <div class="absolute bottom-0 left-0 h-1 bg-surface-variant w-full">
                        <div class="h-full bg-outline-variant" :style="`width: ${stats.expectedCount > 0 ? (stats.presentCount / stats.expectedCount) * 100 : 0}%;`"></div>
                    </div>
                </div>
            </div>

            <!-- Live Feed -->
            <div class="bg-surface-container-low border border-outline-variant/30 rounded-xl flex flex-col flex-1 overflow-hidden relative">
                <div class="p-md border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-low z-10">
                    <h3 class="font-label-caps text-label-caps text-on-surface">Live Feed</h3>
                </div>
                <div class="flex-1 overflow-y-auto p-md flex flex-col gap-unit custom-scrollbar">
                    <template x-for="log in recentLogs.slice(0, 5)" :key="log.id">
                        <div class="bg-surface/50 border border-primary/20 rounded-lg p-sm flex items-center gap-md hover:bg-surface-variant/30 transition-colors">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0 border border-primary/20">
                                <span class="material-symbols-outlined text-primary text-[20px]">check_circle</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-body-base text-body-base text-on-surface truncate" x-text="log.participant?.user?.name || 'Unknown'"></div>
                                <div class="font-body-sm text-body-sm text-on-surface-variant truncate" x-text="`${log.participant?.status} • ${new Date(log.check_in_time).toLocaleTimeString()}`"></div>
                            </div>
                        </div>
                    </template>
                    <div x-show="recentLogs.length === 0" class="text-center text-on-surface-variant p-md">No logs available.</div>
                </div>
                <div class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-surface-container-low to-transparent pointer-events-none"></div>
            </div>
        </div>
    </div>

    <!-- Bottom Section: History Table -->
    <div class="bg-surface-container-low border border-outline-variant/30 rounded-xl overflow-hidden mt-xl">
        <div class="p-md border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-low">
            <h3 class="font-label-caps text-label-caps text-on-surface">Recent Check-in Logs</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant/30 bg-surface-container-lowest/50">
                        <th class="py-sm px-md font-label-caps text-label-caps text-on-surface-variant">Timestamp</th>
                        <th class="py-sm px-md font-label-caps text-label-caps text-on-surface-variant">Name</th>
                        <th class="py-sm px-md font-label-caps text-label-caps text-on-surface-variant">Ticket Type</th>
                        <th class="py-sm px-md font-label-caps text-label-caps text-on-surface-variant">Status</th>
                        <th class="py-sm px-md font-label-caps text-label-caps text-on-surface-variant text-right">Gate</th>
                    </tr>
                </thead>
                <tbody class="font-mono-code text-mono-code text-on-surface">
                    <template x-for="log in recentLogs.slice(0, 10)" :key="'table-'+log.id">
                        <tr class="border-b border-outline-variant/10 hover:bg-white/5 transition-colors">
                            <td class="py-sm px-md text-outline-variant" x-text="new Date(log.check_in_time).toLocaleTimeString()"></td>
                            <td class="py-sm px-md font-body-sm text-body-sm text-on-surface" x-text="log.participant?.user?.name || 'Unknown'"></td>
                            <td class="py-sm px-md">
                                <span class="px-2 py-1 rounded bg-surface-variant text-on-surface-variant text-xs">Standard</span>
                            </td>
                            <td class="py-sm px-md">
                                <span class="text-primary flex items-center gap-xs">
                                    <span class="material-symbols-outlined text-[16px]">check</span> Granted
                                </span>
                            </td>
                            <td class="py-sm px-md text-right text-outline-variant">G-01</td>
                        </tr>
                    </template>
                    <tr x-show="recentLogs.length === 0">
                        <td colspan="5" class="text-center p-md text-on-surface-variant">No check-in logs.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Manual Check-in Modal -->
    <div x-show="showManualCheckinModal" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showManualCheckinModal = false"></div>
        <div class="relative bg-surface-container-low border border-outline-variant/30 rounded-2xl w-full max-w-md shadow-2xl p-lg z-10 mx-4" x-transition>
            <div class="flex justify-between items-center mb-md">
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Manual Check-in</h3>
                <button @click="showManualCheckinModal = false" class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form @submit.prevent="submitManualCheckin">
                <div class="flex flex-col gap-md mb-lg">
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface-variant mb-xs">Email Address</label>
                        <input type="email" x-model="checkinEmail" required placeholder="example@email.com" class="w-full bg-surface-container border border-outline-variant/30 rounded-lg px-4 py-2 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                        <p class="font-body-sm text-body-sm text-outline-variant mt-xs">Enter the participant's registered email to check them in manually.</p>
                    </div>
                </div>
                
                <div class="flex justify-end gap-sm">
                    <button type="button" @click="showManualCheckinModal = false" class="px-4 py-2 rounded-lg font-label-lg text-label-lg text-on-surface-variant hover:bg-surface-variant transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-lg font-label-lg text-label-lg bg-primary text-on-primary hover:bg-primary/90 transition-colors flex items-center gap-2" :disabled="isSubmitting">
                        <span x-show="isSubmitting" class="animate-spin rounded-full h-4 w-4 border-2 border-on-primary border-t-transparent"></span>
                        <span x-text="isSubmitting ? 'Checking in...' : 'Check-in'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('attendanceState', (eventId) => ({
        eventId: eventId,
        recentLogs: [],
        stats: {
            presentCount: 0,
            expectedCount: 0
        },
        showManualCheckinModal: false,
        checkinEmail: '',
        isSubmitting: false,
        canManage: false,
        feedbackMessage: '',
        feedbackType: '', // 'success' or 'error'
        
        // Scanner State
        isScannerActive: false,
        html5QrCode: null,
        lastScannedCode: null,
        lastScanTime: 0,

        init() {
            this.checkPermissions();
            this.fetchInitialData();
            
            // Setup Laravel Echo listener
            if (window.Echo) {
                window.Echo.channel(`attendance.${this.eventId}`)
                    .listen('.LiveAttendanceUpdated', (e) => {
                        this.stats.presentCount = e.presentCount;
                        this.stats.expectedCount = e.expectedCount;
                        this.fetchInitialData(); // Optionally refetch logs to show the latest check-in
                    });
            }
        },

        async checkPermissions() {
            try {
                const eventRes = await fetchApi('/api/events/' + this.eventId);
                if (eventRes.success) {
                    this.canManage = true; 
                }
            } catch (e) { console.error(e); }
        },

        async fetchInitialData() {
            try {
                const res = await fetchApi('/api/events/' + this.eventId + '/participants?status=Attended');
                if (res.success) {
                    this.recentLogs = res.data.data.map(p => ({
                        id: p.id,
                        participant: { user: p.user_detail, status: p.status },
                        check_in_time: p.updated_at
                    }));
                    this.stats.presentCount = res.data.total;
                    
                    const allRes = await fetchApi('/api/events/' + this.eventId + '/participants');
                    if (allRes.success) {
                        this.stats.expectedCount = allRes.data.total;
                    }
                }
            } catch (e) { console.error(e); }
        },

        startScanner() {
            if (!this.canManage) return;
            this.isScannerActive = true;
            this.$nextTick(() => {
                this.html5QrCode = new Html5Qrcode("reader");
                const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                this.html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    (decodedText, decodedResult) => {
                        this.onScanSuccess(decodedText);
                    },
                    (errorMessage) => {
                        // ignore background errors
                    }
                ).catch(err => {
                    console.error("Error starting scanner", err);
                    this.feedbackMessage = "Failed to start camera. Please check permissions.";
                    this.feedbackType = 'error';
                    this.isScannerActive = false;
                });
            });
        },

        stopScanner() {
            if (this.html5QrCode) {
                this.html5QrCode.stop().then(() => {
                    this.html5QrCode.clear();
                    this.isScannerActive = false;
                }).catch(err => {
                    console.error("Failed to stop scanner", err);
                });
            } else {
                this.isScannerActive = false;
            }
        },

        async onScanSuccess(decodedText) {
            const now = Date.now();
            // Prevent duplicate scans within 3 seconds
            if (this.lastScannedCode === decodedText && (now - this.lastScanTime) < 3000) {
                return;
            }
            
            this.lastScannedCode = decodedText;
            this.lastScanTime = now;
            
            // Optional: Play a beep sound
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(800, audioCtx.currentTime); // 800Hz beep
                oscillator.connect(audioCtx.destination);
                oscillator.start();
                oscillator.stop(audioCtx.currentTime + 0.1);
            } catch(e) {}

            await this.submitScanData(decodedText);
        },

        async submitScanData(ticketNumber) {
            this.feedbackMessage = 'Processing scan...';
            this.feedbackType = 'success'; // neutral
            try {
                const res = await fetchApi('/api/events/' + this.eventId + '/attendance/check-in', 'POST', { ticket_number: ticketNumber });
                if (res.success) {
                    this.feedbackMessage = 'Scan successful: ' + ticketNumber;
                    this.feedbackType = 'success';
                    this.fetchInitialData();
                } else {
                    this.feedbackMessage = res.message || 'Error checking in';
                    this.feedbackType = 'error';
                }
            } catch (err) {
                this.feedbackMessage = 'Connection error during scan';
                this.feedbackType = 'error';
            } finally {
                setTimeout(() => { this.feedbackMessage = ''; }, 3000);
            }
        },

        async submitManualCheckin() {
            this.isSubmitting = true;
            this.feedbackMessage = '';
            try {
                const res = await fetchApi('/api/events/' + this.eventId + '/attendance/check-in', 'POST', { email: this.checkinEmail });
                if (res.success) {
                    this.feedbackMessage = 'Checked in successfully!';
                    this.feedbackType = 'success';
                    this.showManualCheckinModal = false;
                    this.checkinEmail = '';
                    this.fetchInitialData(); // reload
                } else {
                    this.feedbackMessage = res.message || 'Error checkin';
                    this.feedbackType = 'error';
                }
            } catch (err) {
                this.feedbackMessage = 'Connection error';
                this.feedbackType = 'error';
            } finally {
                this.isSubmitting = false;
                setTimeout(() => { this.feedbackMessage = ''; }, 5000);
            }
        }
    }));
});
</script>
@endsection
