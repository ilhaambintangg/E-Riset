<div x-data="{
    isOpen: false,
    name: '',
    email: '',
    token: localStorage.getItem('chat_token') || '',
    messages: [],
    newMessage: '',
    isActive: true,
    isRegistering: false,
    pollInterval: null,
    
    init() {
        if (this.token) {
            this.fetchMessages();
            this.startPolling();
        }
        
        // Listen to custom window scroll bottom event
        window.addEventListener('chat-scroll-bottom', () => this.scrollBottom());
    },
    
    startChat() {
        if (!this.name.trim()) return;
        this.isRegistering = true;
        
        fetch('/api/public/livechat/start', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: this.name,
                email: this.email
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.token = data.token;
                localStorage.setItem('chat_token', data.token);
                this.fetchMessages();
                this.startPolling();
            }
        })
        .catch(err => console.error('Gagal memulai sesi chat:', err))
        .finally(() => {
            this.isRegistering = false;
        });
    },
    
    fetchMessages() {
        if (!this.token) return;
        
        fetch(`/api/public/livechat/messages?token=${this.token}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.messages = data.messages;
                this.isActive = data.is_active;
                this.scrollBottom();
            }
        })
        .catch(err => console.error('Gagal mengambil pesan:', err));
    },
    
    sendMessage() {
        if (!this.newMessage.trim() || !this.token || !this.isActive) return;
        
        const tempMsg = this.newMessage;
        this.newMessage = '';
        
        fetch('/api/public/livechat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Chat-Token': this.token
            },
            body: JSON.stringify({
                message: tempMsg
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.fetchMessages();
            }
        })
        .catch(err => console.error('Gagal mengirim pesan:', err));
    },
    
    sendSuggestion(text) {
        this.newMessage = text;
        this.sendMessage();
    },
    
    startPolling() {
        if (this.pollInterval) clearInterval(this.pollInterval);
        this.pollInterval = setInterval(() => {
            if (this.isOpen && this.token && this.isActive) {
                this.fetchMessages();
            }
        }, 3000);
    },
    
    toggleChat() {
        this.isOpen = !this.isOpen;
        if (this.isOpen) {
            this.scrollBottom();
            this.fetchMessages();
            this.startPolling();
        } else {
            if (this.pollInterval) {
                clearInterval(this.pollInterval);
                this.pollInterval = null;
            }
        }
    },
    
    scrollBottom() {
        this.$nextTick(() => {
            const container = this.$refs.messagesContainer;
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    },
    
    resetSession() {
        if (confirm('Apakah Anda ingin menghapus riwayat chat dan memulai percakapan baru?')) {
            localStorage.removeItem('chat_token');
            this.token = '';
            this.messages = [];
            this.name = '';
            this.email = '';
            this.isActive = true;
            if (this.pollInterval) {
                clearInterval(this.pollInterval);
                this.pollInterval = null;
            }
        }
    }
}" class="relative">

    <!-- Floating Chat Button -->
    <button @click="toggleChat()" 
            class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-gradient-to-r from-brand-alt to-amber-500 hover:from-brand-alt-strong hover:to-orange-600 text-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 active:scale-95 transition-all duration-300 cursor-pointer group"
            title="Tanya Si Risi (Live Chat)">
        <!-- Glowing Pulse -->
        <span class="absolute inline-flex h-full w-full rounded-full bg-brand-alt opacity-75 animate-ping group-hover:hidden"></span>
        <span class="relative">
            <!-- Chat Icon -->
            <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.3.025-.602.048-.905.069m0 0c-.056.14-.15.263-.275.354l-3.235 2.36c-.457.333-1.11-.004-1.11-.57v-2.147m4.62-1.004a48.205 48.205 0 0 1-9.28 0m9.28 0a48.66 48.66 0 0 0-9.28 0m0 0c-1.133-.093-1.98-.957-1.98-2.093V10.6c0-.969.616-1.813 1.5-2.097a48.11 48.11 0 0 1 12.02 0z" />
            </svg>
            <!-- Close Icon -->
            <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6" x-cloak>
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </span>
    </button>

    <!-- Chat Panel Widget -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 scale-95"
         class="fixed bottom-24 right-6 w-96 max-w-[calc(100vw-3rem)] h-[520px] max-h-[calc(100vh-8rem)] z-50 bg-white/95 backdrop-blur-xl border border-border-default shadow-xl rounded-base flex flex-col overflow-hidden animate-fade-in"
         x-cloak>
         
        <!-- Header -->
        <div class="bg-brand-strong text-white px-5 py-4 flex items-center justify-between shadow-sm shrink-0">
            <div class="flex items-center gap-3">
                <!-- Mascot Mini Avatar -->
                <div class="w-9 h-9 bg-brand-alt rounded-full flex items-center justify-center shadow-md">
                    <i data-lucide="scale" class="w-5 h-5 text-brand-strong"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold m-0 tracking-tight leading-none text-white">Layanan Live Chat</h3>
                    <p class="text-[10px] text-brand-alt font-medium mt-1 uppercase tracking-wider flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-success rounded-full animate-pulse inline-block"></span>
                        Admin Online
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <!-- Reset Button if token exists -->
                <template x-if="token">
                    <button @click="resetSession()" class="p-1 hover:bg-white/10 rounded-default text-white/70 hover:text-brand-alt transition-colors" title="Chat Baru">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                </template>
                <button @click="toggleChat()" class="p-1 hover:bg-white/10 rounded-default text-white/70 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="flex-1 overflow-hidden flex flex-col bg-neutral-primary-soft">
            
            <!-- Registration Form (Before Chat session started) -->
            <template x-if="!token">
                <div class="p-6 my-auto flex flex-col gap-5">
                    <div class="text-center">
                        <span class="text-4xl">👋</span>
                        <h4 class="text-sm font-bold text-fg-heading mt-3 mb-1">Ada yang bisa kami bantu?</h4>
                        <p class="text-xs text-fg-body-subtle">Silakan isi nama Anda untuk memulai percakapan langsung dengan administrator kami.</p>
                    </div>

                    <form @submit.prevent="startChat()" class="space-y-4">
                        <div>
                            <label class="input-label text-xs">Nama Anda <span class="text-danger">*</span></label>
                            <input type="text" x-model="name" required placeholder="Masukkan nama lengkap" 
                                   class="w-full px-4 py-2.5 bg-white border border-border-default rounded-default text-xs text-fg-heading focus:outline-none focus:border-brand-alt focus:ring-2 focus:ring-brand-alt-soft transition-all">
                        </div>
                        <div>
                            <label class="input-label text-xs">Email (Opsional)</label>
                            <input type="email" x-model="email" placeholder="contoh@email.com" 
                                   class="w-full px-4 py-2.5 bg-white border border-border-default rounded-default text-xs text-fg-heading focus:outline-none focus:border-brand-alt focus:ring-2 focus:ring-brand-alt-soft transition-all">
                        </div>
                        
                        <button type="submit" :disabled="isRegistering || !name.trim()" 
                                class="w-full bg-brand hover:bg-brand-medium text-white font-bold py-3 rounded-default transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer text-xs disabled:opacity-50">
                            <span x-show="!isRegistering">Mulai Chat</span>
                            <span x-show="isRegistering" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        </button>
                    </form>
                </div>
            </template>

            <!-- Chat Room Area -->
            <template x-if="token">
                <div class="flex-1 flex flex-col overflow-hidden">
                    <!-- Message Log -->
                    <div x-ref="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
                        <template x-for="msg in messages" :key="msg.id">
                            <div class="flex flex-col" :class="msg.sender === 'visitor' ? 'items-end' : 'items-start'">
                                <div class="max-w-[80%] rounded-default px-4 py-2.5 text-xs shadow-2xs" 
                                     :class="msg.sender === 'visitor' ? 'bg-gradient-to-br from-brand-alt to-amber-500 text-white rounded-br-none' : 'bg-white border border-border-default text-fg-body rounded-bl-none'">
                                    <p class="m-0 leading-relaxed break-words whitespace-pre-line" x-text="msg.message"></p>
                                </div>
                                <span class="text-[9px] text-fg-body-subtle mt-1 px-1" x-text="new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                            </div>
                        </template>
                        
                        <!-- Empty Chat State -->
                        <template x-if="messages.length === 0">
                            <div class="h-full flex flex-col items-center justify-center text-center p-6 text-fg-body-subtle my-auto">
                                <i data-lucide="message-square" class="w-8 h-8 text-neutral-tertiary mb-2"></i>
                                <p class="text-xs font-semibold">Memuat riwayat percakapan...</p>
                            </div>
                        </template>
                    </div>

                    <!-- Suggestions Chips (Only if session is active and user has sent few messages) -->
                    <template x-if="isActive && messages.length <= 2">
                        <div class="px-4 py-2 flex flex-wrap gap-2 shrink-0 border-t border-border-default-subtle bg-white/50">
                            <button @click="sendSuggestion('Bagaimana cara daftar izin penelitian?')" class="text-[10px] bg-white border border-border-default hover:border-brand-alt text-fg-body hover:text-brand-alt px-2.5 py-1 rounded-full shadow-2xs transition-all cursor-pointer">
                                Cara daftar?
                            </button>
                            <button @click="sendSuggestion('Apa saja syarat dokumen yang diperlukan?')" class="text-[10px] bg-white border border-border-default hover:border-brand-alt text-fg-body hover:text-brand-alt px-2.5 py-1 rounded-full shadow-2xs transition-all cursor-pointer">
                                Syarat dokumen?
                            </button>
                            <button @click="sendSuggestion('Bagaimana cara melacak permohonan saya?')" class="text-[10px] bg-white border border-border-default hover:border-brand-alt text-fg-body hover:text-brand-alt px-2.5 py-1 rounded-full shadow-2xs transition-all cursor-pointer">
                                Lacak permohonan?
                            </button>
                        </div>
                    </template>

                    <!-- Closed Chat Indicator -->
                    <template x-if="!isActive">
                        <div class="px-4 py-3 bg-neutral-primary-strong text-center text-xs text-fg-body-subtle border-t border-border-default">
                            Sesi percakapan ini telah diakhiri.
                        </div>
                    </template>

                    <!-- Input Reply Form -->
                    <template x-if="isActive">
                        <form @submit.prevent="sendMessage()" class="p-3 bg-white border-t border-border-default flex items-center gap-2 shrink-0">
                            <input type="text" x-model="newMessage" placeholder="Tulis pesan Anda..." 
                                   class="flex-1 px-4 py-2 bg-neutral-primary-soft border border-border-default-medium rounded-full text-xs text-fg-heading focus:outline-none focus:border-brand-alt transition-all">
                            <button type="submit" :disabled="!newMessage.trim()" 
                                    class="w-8 h-8 rounded-full bg-brand text-white flex items-center justify-center shadow-xs hover:scale-105 active:scale-95 transition-all cursor-pointer disabled:opacity-50 disabled:scale-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0 1 21.485 12 59.77 59.77 0 0 1 3.27 20.876L5.999 12zm0 0h7.5" />
                                </svg>
                            </button>
                        </form>
                    </template>
                </div>
            </template>

        </div>
    </div>
</div>
