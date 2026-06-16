<tr class="group hover:bg-surface-container-lowest/50 transition-colors">
    <td class="px-md py-sm w-12">
        <div class="flex items-center justify-center">
            <input type="checkbox" :value="participant.id" x-model="selected" class="rounded border-outline-variant bg-surface-container-high focus:ring-primary text-primary w-4 h-4 cursor-pointer">
        </div>
    </td>
    <td class="px-md py-sm">
        <div class="flex items-center gap-sm">
            <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant/30 flex flex-shrink-0 items-center justify-center font-headline-sm text-headline-sm text-on-surface shadow-inner overflow-hidden">
                <span x-text="(participant.user_detail?.name || 'U').substring(0, 2).toUpperCase()"></span>
            </div>
            <div class="flex flex-col min-w-0">
                <span class="font-body-lg text-body-lg text-on-surface truncate" x-text="participant.user_detail?.name || 'Unknown'"></span>
                <!-- Here we'd map role if we have it in API, assuming generic 'Participant' for now -->
                <span class="font-body-sm text-body-sm text-on-surface-variant opacity-70 truncate" x-text="participant.user_detail?.roles?.[0]?.name || 'Participant'"></span>
            </div>
        </div>
    </td>
    <td class="px-md py-sm">
        <span class="font-body-md text-body-md text-on-surface-variant truncate block w-[200px]" x-text="participant.user_detail?.email || 'N/A'"></span>
    </td>
    <td class="px-md py-sm">
        <!-- Assuming institution isn't fully implemented in DB, defaulting to General -->
        <span class="font-body-md text-body-md text-on-surface-variant">General Participant</span>
    </td>
    <td class="px-md py-sm">
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.away="open = false" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-label-md text-label-md border transition-all cursor-pointer"
                :class="{
                    'bg-primary-container text-on-primary-container border-primary-container': participant.status === 'Registered',
                    'bg-green-500/20 text-green-400 border-green-500/30': participant.status === 'Attended',
                    'bg-amber-500/20 text-amber-400 border-amber-500/30': participant.status === 'Waitlisted'
                }">
                <div class="w-1.5 h-1.5 rounded-full"
                    :class="{
                        'bg-primary': participant.status === 'Registered',
                        'bg-green-500': participant.status === 'Attended',
                        'bg-amber-500': participant.status === 'Waitlisted'
                    }"></div>
                <span x-text="participant.status"></span>
                <span class="material-symbols-outlined text-[16px] opacity-70" x-show="canManage">expand_more</span>
            </button>
            
            <div x-show="open && canManage" class="absolute left-0 mt-1 w-32 bg-surface-container border border-outline-variant/30 rounded-lg shadow-lg overflow-hidden z-20">
                <button @click="updateStatus(participant.id, 'Registered'); open = false" class="w-full text-left px-3 py-2 text-sm hover:bg-surface-variant text-on-surface transition-colors">Registered</button>
                <button @click="updateStatus(participant.id, 'Attended'); open = false" class="w-full text-left px-3 py-2 text-sm hover:bg-surface-variant text-on-surface transition-colors">Attended</button>
                <button @click="updateStatus(participant.id, 'Waitlisted'); open = false" class="w-full text-left px-3 py-2 text-sm hover:bg-surface-variant text-on-surface transition-colors">Waitlisted</button>
            </div>
        </div>
    </td>
    <td class="px-md py-sm hidden md:table-cell">
        <span class="font-body-md text-body-md text-on-surface-variant" x-text="new Date(participant.created_at).toLocaleDateString()"></span>
    </td>
    <td class="px-md py-sm w-12 text-right">
        <!-- More options menu could go here -->
    </td>
</tr>
