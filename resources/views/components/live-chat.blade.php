<!-- Live Chat Floating Widget -->
<div x-data="liveChatWidget()"
     x-show="activeCommunityId"
     class="fixed bottom-6 right-6 z-50 flex flex-col items-end"
     style="position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999;"
     x-cloak>
     
    <!-- Chat Window -->
    <div x-show="isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 scale-95"
         class="mb-4 w-80 sm:w-96 h-[500px] max-h-[70vh] bg-surface-container/90 backdrop-blur-2xl border border-outline-variant/30 rounded-2xl shadow-2xl flex flex-col overflow-hidden origin-bottom-right"
         @click.outside="isOpen = false"
         style="display: none;">
         
        <!-- Chat Header -->
        <div class="px-md py-sm bg-primary/10 border-b border-primary/20 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-xs">
                <span class="material-symbols-outlined text-primary text-[20px]">forum</span>
                <div class="flex flex-col">
                    <span class="font-body-base text-body-base text-on-surface font-bold leading-tight" x-text="'Community Chat'"></span>
                    <span class="font-label-caps text-[10px] text-primary" x-text="activeCommunity ? activeCommunity.name : ''"></span>
                </div>
            </div>
            <button @click="isOpen = false" class="text-on-surface-variant hover:text-on-surface p-1 rounded-full hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto p-md flex flex-col gap-md custom-scrollbar" id="chat-messages-container">
            <template x-if="isLoading">
                <div class="flex-1 flex items-center justify-center text-on-surface-variant text-sm gap-2">
                    <span class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span> Loading...
                </div>
            </template>
            
            <template x-if="!isLoading && messages.length === 0">
                <div class="flex-1 flex flex-col items-center justify-center text-on-surface-variant text-center opacity-70">
                    <span class="material-symbols-outlined text-[48px] mb-2">chat_bubble_outline</span>
                    <p class="font-body-sm text-body-sm">No messages yet.<br>Be the first to say hello!</p>
                </div>
            </template>
            
            <template x-for="msg in messages" :key="msg.id">
                <div class="flex flex-col w-full" :class="msg.user_id == (user ? user.id : 0) ? 'items-end' : 'items-start'">
                    <!-- Sender Name & Badges -->
                    <div class="flex items-center gap-1 mb-1 mx-1" :class="msg.user_id == (user ? user.id : 0) ? 'justify-end flex-row-reverse' : 'justify-start'">
                        <span class="text-[10px] font-bold text-on-surface-variant" x-text="msg.user_detail?.name || 'Unknown User'"></span>
                        
                        <!-- Super Admin Badge -->
                        <template x-if="msg.user_detail?.roles?.some(r => r.name === 'Super Admin')">
                            <span class="bg-error/20 text-error px-1 py-[2px] rounded text-[8px] font-bold uppercase">Admin</span>
                        </template>
                        
                        <!-- Owner Badge -->
                        <template x-if="msg.role === 'Owner'">
                            <span class="bg-pink-500/20 text-pink-500 px-1 py-[2px] rounded text-[8px] font-bold uppercase">Owner</span>
                        </template>
                    </div>
                    
                    <!-- Chat Bubble -->
                    <div class="px-md py-sm rounded-2xl max-w-[85%] break-words relative group"
                         :class="msg.user_id == (user ? user.id : 0) ? 'bg-primary text-on-primary rounded-br-sm' : 'bg-surface-variant text-on-surface rounded-bl-sm'">
                        <p class="font-body-sm text-body-sm whitespace-pre-wrap leading-relaxed" x-text="msg.content"></p>
                        
                        <!-- Timestamp -->
                        <span class="text-[9px] mt-1 block opacity-70" 
                              :class="msg.user_id == (user ? user.id : 0) ? 'text-right' : 'text-left'"
                              x-text="formatTime(msg.created_at)"></span>
                    </div>
                </div>
            </template>
        </div>

        <!-- Input Area -->
        <div class="p-sm bg-surface-container shrink-0 border-t border-outline-variant/30">
            <form @submit.prevent="sendMessage" class="flex items-end gap-2 relative">
                <textarea x-model="newMessage"
                          @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                          placeholder="Type a message..."
                          class="w-full bg-surface-container-highest border border-outline-variant/50 rounded-xl px-sm py-2 text-on-surface font-body-sm text-body-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary resize-none custom-scrollbar min-h-[40px] max-h-[120px]"
                          rows="1"></textarea>
                <button type="submit" 
                        :disabled="!newMessage.trim() || isSending"
                        class="w-10 h-10 rounded-xl bg-primary text-on-primary flex items-center justify-center shrink-0 hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="material-symbols-outlined text-[18px]" :class="isSending ? 'animate-spin' : ''" x-text="isSending ? 'progress_activity' : 'send'"></span>
                </button>
            </form>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button @click="isOpen = !isOpen; if(isOpen) { unreadCount = 0; scrollToBottom(); }"
            class="w-14 h-14 bg-primary text-on-primary rounded-full shadow-2xl flex items-center justify-center hover:scale-105 active:scale-95 transition-transform relative group">
        <span class="material-symbols-outlined text-[24px] transition-transform duration-300" 
              :class="isOpen ? 'rotate-90 scale-0 opacity-0' : 'rotate-0 scale-100 opacity-100'">chat</span>
        <span class="material-symbols-outlined text-[24px] absolute transition-transform duration-300"
              :class="isOpen ? 'rotate-0 scale-100 opacity-100' : '-rotate-90 scale-0 opacity-0'">close</span>
              
        <!-- Unread Badge -->
        <div x-show="unreadCount > 0 && !isOpen"
             x-transition.scale
             class="absolute -top-1 -right-1 bg-error text-on-error text-[10px] font-bold px-2 py-0.5 rounded-full shadow-md border-2 border-background"
             x-text="unreadCount > 99 ? '99+' : unreadCount"
             style="display: none;"></div>
             
        <!-- Tooltip -->
        <div class="absolute right-full mr-4 bg-surface-container-high text-on-surface px-3 py-1.5 rounded-lg text-xs font-bold font-label-caps whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity">
            Community Chat
        </div>
    </button>
</div>

@push('scripts')
<script>
function liveChatWidget() {
    return {
        isOpen: false,
        isLoading: true,
        isSending: false,
        messages: [],
        newMessage: '',
        unreadCount: 0,
        currentChannel: null,
        
        init() {
            try {
                // Watch for changes to activeCommunityId from the parent globalAppState
                this.$watch('activeCommunityId', (newId) => {
                    if (newId) {
                        this.loadChat(newId);
                    } else {
                        this.messages = [];
                        this.leaveChannel();
                    }
                });

                // Initial load if already set
                if (this.activeCommunityId) {
                    this.loadChat(this.activeCommunityId);
                }
            } catch (e) {
                console.error("LiveChat Init Error:", e);
                // Fallback if $watch fails
                if (window.localStorage.getItem('active_community_id')) {
                    this.loadChat(window.localStorage.getItem('active_community_id'));
                }
            }
        },

        async loadChat(communityId) {
            this.isLoading = true;
            this.messages = [];
            this.leaveChannel();
            
            try {
                const res = await window.apiFetch(`/api/communities/${communityId}/feed`);
                if (res.ok) {
                    const data = await res.json();
                    this.messages = data.data || [];
                    this.scrollToBottom();
                }
            } catch (e) {
                console.error("Failed to load chat history", e);
            } finally {
                this.isLoading = false;
            }
            
            this.listenToChannel(communityId);
        },
        
        listenToChannel(communityId) {
            if (!window.Echo) {
                console.warn("Laravel Echo not initialized!");
                return;
            }
            
            // Create a dedicated Echo instance for the chat to guarantee correct authEndpoint
            if (!this.chatEcho) {
                // Disconnect any existing global instance to prevent connection reuse conflicts
                try { window.Echo.disconnect(); } catch(e) {}
                
                this.chatEcho = new window.Echo.constructor({
                    broadcaster: 'reverb',
                    key: 'peta_reverb_key',
                    wsHost: window.location.hostname,
                    wsPort: 8080,
                    wssPort: 8080,
                    forceTLS: false,
                    enabledTransports: ['ws', 'wss'],
                    authEndpoint: 'http://127.0.0.1:8002/api/broadcasting/auth',
                    auth: {
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('jwt_token')
                        }
                    }
                });
                
                // Add debug logging for Pusher connection
                if (this.chatEcho.connector && this.chatEcho.connector.pusher) {
                    this.chatEcho.connector.pusher.connection.bind('error', function (err) {
                        console.error('Reverb Connection Error:', err);
                    });
                }
            }
            
            const channelName = `community.${communityId}.feed`;
            this.currentChannel = this.chatEcho.private(channelName);
            
            this.currentChannel.error((error) => {
                console.error("Reverb Channel Auth Error:", error);
            });
            
            this.currentChannel.listen('.new-feed', (e) => {
                const newMsg = e.feedData || e.data || e; 
                
                // Avoid duplicating our own messages if we optimistically added it
                if (!this.messages.find(m => m.id === newMsg.id)) {
                    this.messages.push(newMsg);
                    
                    if (!this.isOpen) {
                        this.unreadCount++;
                    }
                    this.scrollToBottom();
                }
            });
        },
        
        leaveChannel() {
            if (this.currentChannel && this.chatEcho) {
                this.chatEcho.leave(this.currentChannel.name);
                this.currentChannel = null;
            }
        },
        
        async sendMessage() {
            if (!this.newMessage.trim() || this.isSending || !this.activeCommunityId) return;
            
            const content = this.newMessage.trim();
            this.newMessage = ''; // clear immediately for UX
            this.isSending = true;
            
            try {
                const res = await window.apiFetch(`/api/communities/${this.activeCommunityId}/feed`, 'POST', {
                    content: content
                });
                
                if (res.ok) {
                    const resData = await res.json();
                    const newMsg = resData.data;
                    
                    // Optimistically add the message to the UI immediately
                    if (!this.messages.find(m => m.id === newMsg.id)) {
                        this.messages.push(newMsg);
                        this.scrollToBottom();
                    }
                } else {
                    console.error("Failed to send message");
                    this.newMessage = content; // restore on failure
                }
            } catch (e) {
                console.error("Error sending message", e);
                this.newMessage = content; // restore
            } finally {
                this.isSending = false;
            }
        },
        
        formatTime(dateString) {
            if (!dateString) return '';
            const d = new Date(dateString);
            return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        },
        
        scrollToBottom() {
            this.$nextTick(() => {
                const container = document.getElementById('chat-messages-container');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        }
    }
}
</script>
@endpush
