<div x-show="showExportModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div x-show="showExportModal" x-transition.opacity class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity" @click="showExportModal = false"></div>

    <div x-show="showExportModal" x-transition.scale.origin.bottom class="relative transform overflow-hidden rounded-xl bg-surface-container-lowest border border-outline-variant/30 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg z-10">
        <form action="{{ route('analytics.export') }}" method="GET" target="_blank">
        <div class="px-lg py-md border-b border-outline-variant/20 flex justify-between items-center bg-surface-container/50">
            <div class="flex items-center gap-sm">
                <span class="material-symbols-outlined text-primary">download</span>
                <h3 class="font-headline-sm text-headline-sm text-on-surface" id="modal-title">Export Analytics Report</h3>
            </div>
            <button type="button" @click="showExportModal = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-md hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="p-lg space-y-lg">
            
            <div class="space-y-sm">
                <label class="block font-label-caps text-label-caps text-on-surface-variant mb-1">Report Format</label>
                <div class="grid grid-cols-3 gap-md">
                    <label class="flex flex-col items-center justify-center p-md rounded-lg border border-outline-variant/50 bg-surface-container-high cursor-pointer hover:border-primary transition-colors focus-within:ring-2 focus-within:ring-primary relative group">
                        <input type="radio" name="format" value="pdf" class="sr-only peer" checked>
                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-primary rounded-lg pointer-events-none"></div>
                        <span class="material-symbols-outlined text-error mb-2 text-[28px]">picture_as_pdf</span>
                        <span class="font-body-sm text-on-surface group-hover:text-primary">PDF</span>
                    </label>
                    <label class="flex flex-col items-center justify-center p-md rounded-lg border border-outline-variant/50 bg-surface-container-high cursor-pointer hover:border-primary transition-colors focus-within:ring-2 focus-within:ring-primary relative group">
                        <input type="radio" name="format" value="csv" class="sr-only peer">
                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-primary rounded-lg pointer-events-none"></div>
                        <span class="material-symbols-outlined text-emerald-500 mb-2 text-[28px]">csv</span>
                        <span class="font-body-sm text-on-surface group-hover:text-primary">CSV</span>
                    </label>
                    <label class="flex flex-col items-center justify-center p-md rounded-lg border border-outline-variant/50 bg-surface-container-high cursor-pointer hover:border-primary transition-colors focus-within:ring-2 focus-within:ring-primary relative group">
                        <input type="radio" name="format" value="excel" class="sr-only peer">
                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-primary rounded-lg pointer-events-none"></div>
                        <span class="material-symbols-outlined text-green-500 mb-2 text-[28px]">table_chart</span>
                        <span class="font-body-sm text-on-surface group-hover:text-primary">Excel</span>
                    </label>
                </div>
            </div>

            <div class="space-y-sm">
                <label class="block font-label-caps text-label-caps text-on-surface-variant mb-1">Included Data</label>
                <div class="space-y-2">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="data_points[]" value="growth" checked class="text-primary bg-surface-container-high border-outline-variant rounded focus:ring-primary h-4 w-4">
                        <span class="text-body-sm text-on-surface group-hover:text-primary transition-colors">Participation Growth Charts</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="data_points[]" value="certificates" checked class="text-primary bg-surface-container-high border-outline-variant rounded focus:ring-primary h-4 w-4">
                        <span class="text-body-sm text-on-surface group-hover:text-primary transition-colors">Certificate Status Metrics</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="data_points[]" value="events" checked class="text-primary bg-surface-container-high border-outline-variant rounded focus:ring-primary h-4 w-4">
                        <span class="text-body-sm text-on-surface group-hover:text-primary transition-colors">Recent Event Performance Table</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="px-lg py-md border-t border-outline-variant/20 flex justify-end gap-sm bg-surface-container-lowest/80">
            <button @click="showExportModal = false" type="button" class="px-4 py-2 rounded-lg border border-outline-variant/50 text-on-surface font-body-sm hover:bg-surface-variant/50 transition-colors">Cancel</button>
            <button @click="setTimeout(() => showExportModal = false, 100)" type="submit" class="flex items-center gap-xs px-4 py-2 rounded-lg bg-primary text-on-primary font-body-sm hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-[0_0_15px_rgba(77,142,255,0.2)]">
                <span class="material-symbols-outlined text-[18px]">download</span> Download Report
            </button>
        </div>
        </form>
    </div>
</div>
