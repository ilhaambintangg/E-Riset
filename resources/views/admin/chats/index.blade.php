@extends('layouts.admin')

@section('title', 'Live Chat')
@section('header_title', 'Live Chat Pelanggan')

@section('content')
<div x-data="{
    sessions: [],
    activeSessionId: null,
    activeSession: null,
    messages: [],
    replyText: '',
    search: '',
    isLoadingSessions: true,
    pollInterval: null,
    
    init() {
        this.loadSessions();
        this.startPolling();
        
        // Refresh session list every 5 seconds
        setInterval(() => this.loadSessions(false), 5000);
    },
    
    loadSessions(showLoader = true) {
        if (showLoader && this.sessions.length === 0) this.isLoadingSessions = true;
        
        fetch('{{ route('admin.chats.sessions') }}')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    this.sessions = data.sessions;
                    
                    // Update active session details dynamically
                    if (this.activeSessionId) {
                        const active = this.sessions.find(s => s.id === this.activeSessionId);
                        if (active) {
                            this.activeSession = active;
                        }
                    } else {
                        // Check if session_id query param exists to auto-select session
                        const urlParams = new URLSearchParams(window.location.search);
                        const sessionId = parseInt(urlParams.get('session_id'));
                        if (sessionId) {
                            const session = this.sessions.find(s => s.id === sessionId);
                            if (session) {
                                this.selectSession(session);
                            }
                        }
                    }
                }
            })
            .catch(err => console.error('Gagal memuat sesi chat:', err))
            .finally(() => {
                this.isLoadingSessions = false;
            });
    },
    
    selectSession(session) {
        this.activeSessionId = session.id;
        this.activeSession = session;
        this.messages = [];
        this.replyText = '';
        this.loadMessages();
        
        // Set active session unread count to 0 locally
        session.unread_count = 0;
    },
    
    loadMessages() {
        if (!this.activeSessionId) return;
        
        fetch(`/admin/chats/${this.activeSessionId}/messages`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    this.messages = data.messages;
                    this.scrollBottom();
                }
            })
            .catch(err => console.error('Gagal memuat pesan:', err));
    },
    
    sendReply() {
        if (!this.replyText.trim() || !this.activeSessionId) return;
        
        const tempText = this.replyText;
        this.replyText = '';
        
        fetch(`/admin/chats/${this.activeSessionId}/reply`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: tempText })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.loadMessages();
                this.loadSessions(false);
            }
        })
        .catch(err => console.error('Gagal mengirim balasan:', err));
    },
    
    closeChat() {
        if (!this.activeSessionId) return;
        if (!confirm('Apakah Anda yakin ingin menyelesaikan sesi percakapan ini? Pengunjung tidak akan bisa mengirim pesan lagi sebelum memulai sesi baru.')) return;
        
        fetch(`/admin/chats/${this.activeSessionId}/close`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.loadMessages();
                this.loadSessions(false);
            }
        })
        .catch(err => console.error('Gagal menutup sesi:', err));
    },
    
    startPolling() {
        if (this.pollInterval) clearInterval(this.pollInterval);
        this.pollInterval = setInterval(() => {
            if (this.activeSessionId && this.activeSession && this.activeSession.is_active) {
                this.loadMessages();
            }
        }, 3000);
    },
    
    scrollBottom() {
        this.$nextTick(() => {
            const container = this.$refs.messagesContainer;
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    },
    
    get filteredSessions() {
        if (!this.search.trim()) return this.sessions;
        return this.sessions.filter(s => 
            s.name.toLowerCase().includes(this.search.toLowerCase()) || 
            (s.email && s.email.toLowerCase().includes(this.search.toLowerCase()))
        );
    }
}" class="flex h-[calc(100vh-170px)] bg-white border border-border-default rounded-base overflow-hidden shadow-xs animate-fade-in">

    <!-- Left Column: Sessions List -->
    <div class="w-80 border-r border-border-default flex flex-col shrink-0 bg-white">
        <!-- Search bar -->
        <div class="p-4 border-b border-border-default shrink-0">
            <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-fg-body-subtle"></i>
                <input type="text" x-model="search" placeholder="Cari nama / email..." 
                       class="w-full pl-9 pr-4 py-2.5 bg-neutral-primary-soft border border-border-default-medium rounded-default text-xs text-fg-heading focus:outline-none focus:border-brand-alt transition-all">
            </div>
        </div>

        <!-- Sessions Scroll List -->
        <div class="flex-1 overflow-y-auto divide-y divide-border-default-subtle custom-scrollbar">
            <!-- Loading state -->
            <div x-show="isLoadingSessions" class="p-6 text-center text-xs text-fg-body-subtle">
                <div class="w-6 h-6 border-2 border-brand-alt border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
                Memuat percakapan...
            </div>

            <!-- Empty Sessions List state -->
            <div x-show="!isLoadingSessions && filteredSessions.length === 0" class="p-6 text-center text-xs text-fg-body-subtle">
                <i data-lucide="message-circle-off" class="w-8 h-8 mx-auto mb-2 text-neutral-tertiary"></i>
                Tidak ada percakapan aktif.
            </div>

            <!-- Session list items -->
            <template x-for="session in filteredSessions" :key="session.id">
                <button @click="selectSession(session)" 
                        class="w-full text-left p-4 flex items-start gap-3 transition-all duration-200 border-l-4 cursor-pointer hover:bg-neutral-primary-medium"
                        :class="activeSessionId === session.id 
                            ? 'bg-brand-alt-soft/30 border-brand-alt' 
                            : 'border-transparent bg-white'">
                    <!-- Avatar icon or Initials -->
                    <div class="w-10 h-10 rounded-full shrink-0 flex items-center justify-center font-bold text-xs"
                         :class="session.is_active ? 'bg-brand-alt-soft text-fg-warning-strong' : 'bg-neutral-primary-strong text-fg-body-subtle'">
                        <span x-text="session.name.substring(0, 2).toUpperCase()"></span>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-fg-heading truncate m-0" x-text="session.name"></h4>
                            <span class="text-[9px] text-fg-body-subtle shrink-0" x-text="session.last_message_time"></span>
                        </div>
                        <p class="text-[10px] text-fg-body-subtle truncate mt-1 m-0" 
                           x-text="session.last_message || 'Belum ada pesan'"></p>
                        
                        <div class="flex items-center gap-1.5 mt-2">
                            <!-- Active Status -->
                            <span class="text-[8px] font-bold px-1.5 py-0.5 rounded-full uppercase"
                                  :class="session.is_active ? 'bg-success-soft text-fg-success-strong' : 'bg-neutral-primary-strong text-fg-body-subtle'"
                                  x-text="session.is_active ? 'Aktif' : 'Selesai'"></span>
                            
                            <!-- Email if exists -->
                            <template x-if="session.email">
                                <span class="text-[9px] text-fg-body-subtle truncate" x-text="session.email"></span>
                            </template>
                        </div>
                    </div>

                    <!-- Unread Count Pill -->
                    <template x-if="session.unread_count > 0">
                        <span class="bg-danger text-white rounded-full px-2 py-0.5 text-[9px] font-bold shrink-0 animate-pulse" 
                              x-text="session.unread_count"></span>
                    </template>
                </button>
            </template>
        </div>
    </div>

    <!-- Right Column: Conversation Area -->
    <div class="flex-1 flex flex-col bg-neutral-primary-soft">
        
        <!-- Empty State (No Chat Selected) -->
        <div x-show="!activeSessionId" class="flex-1 flex flex-col items-center justify-center p-8 text-center my-auto animate-fade-in">
            <div class="w-16 h-16 bg-brand-alt-soft rounded-base flex items-center justify-center shadow-xs border border-brand-alt/25 mb-4">
                <i data-lucide="message-square" class="w-8 h-8 text-brand-alt"></i>
            </div>
            <h3 class="text-sm font-bold text-fg-heading m-0">Pilih Percakapan</h3>
            <p class="text-xs text-fg-body-subtle max-w-sm mt-2 leading-relaxed">
                Silakan pilih salah satu percakapan di kolom kiri untuk mulai berinteraksi langsung dengan pengunjung website.
            </p>
        </div>

        <!-- Chat room selected state -->
        <template x-if="activeSessionId">
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Chat Header -->
                <div class="bg-white border-b border-border-default px-6 py-4 flex items-center justify-between shrink-0">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-extrabold text-fg-heading m-0" x-text="activeSession.name"></h3>
                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full uppercase"
                                  :class="activeSession.is_active ? 'bg-success-soft text-fg-success-strong border border-border-success/30' : 'bg-neutral-primary-strong text-fg-body-subtle'"
                                  x-text="activeSession.is_active ? 'Aktif' : 'Selesai'"></span>
                        </div>
                        <p class="text-[10px] text-fg-body-subtle mt-1 m-0">
                            <span x-text="activeSession.email ? 'Email: ' + activeSession.email : 'Pengunjung tidak mendaftarkan email.'"></span>
                        </p>
                    </div>

                    <!-- Actions -->
                    <template x-if="activeSession.is_active">
                        <button @click="closeChat()" class="btn-danger btn-sm text-[11px] font-bold py-2 px-4 shadow-2xs hover:scale-102 flex items-center gap-1.5">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                            Selesaikan Chat
                        </button>
                    </template>
                </div>

                <!-- Messages Log -->
                <div x-ref="messagesContainer" class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">
                    <template x-for="msg in messages" :key="msg.id">
                        <div>
                            <!-- System/Auto-close notification messages -->
                            <template x-if="msg.sender === 'admin' && msg.message.includes('diakhiri')">
                                <div class="flex justify-center my-4">
                                    <div class="bg-neutral-primary-strong text-fg-body-subtle text-[10px] font-medium px-4 py-1.5 border border-border-default rounded-full shadow-2xs" x-text="msg.message"></div>
                                </div>
                            </template>
                            
                            <!-- Normal messages -->
                            <template x-if="!(msg.sender === 'admin' && msg.message.includes('diakhiri'))">
                                <div class="flex flex-col" :class="msg.sender === 'admin' ? 'items-end' : 'items-start'">
                                    <!-- Sender label (Visitor) -->
                                    <template x-if="msg.sender === 'visitor'">
                                        <span class="text-[9px] font-bold text-fg-body-subtle mb-1 px-1" x-text="activeSession.name"></span>
                                    </template>
                                    
                                    <div class="max-w-[70%] rounded-default px-4 py-3 text-xs shadow-2xs"
                                         :class="msg.sender === 'admin' 
                                             ? 'bg-brand text-white rounded-br-none' 
                                             : 'bg-white border border-border-default text-fg-body rounded-bl-none'">
                                        <p class="m-0 leading-relaxed break-words whitespace-pre-line" x-text="msg.message"></p>
                                    </div>
                                    <span class="text-[9px] text-fg-body-subtle mt-1.5 px-1" x-text="new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Input Reply Footer -->
                <div class="bg-white border-t border-border-default p-4 shrink-0">
                    <!-- Session Active Input -->
                    <template x-if="activeSession.is_active">
                        <form @submit.prevent="sendReply()" class="flex items-end gap-3">
                            <div class="flex-1">
                                <textarea x-model="replyText" 
                                          @keydown.enter.prevent="if(!event.shiftKey) sendReply()"
                                          placeholder="Tulis balasan Anda di sini... (Tekan Enter untuk kirim, Shift+Enter untuk baris baru)" 
                                          rows="2"
                                          class="w-full px-4 py-3 bg-neutral-primary-soft border border-border-default-medium rounded-default text-xs text-fg-heading focus:outline-none focus:border-brand-alt transition-all resize-none"></textarea>
                            </div>
                            <button type="submit" :disabled="!replyText.trim()" 
                                    class="bg-brand hover:bg-brand-medium text-white font-bold py-3 px-5 rounded-default shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 text-xs shrink-0 cursor-pointer disabled:opacity-50 disabled:scale-100">
                                Kirim
                                <i data-lucide="send" class="w-3.5 h-3.5"></i>
                            </button>
                        </form>
                    </template>

                    <!-- Session Closed indicator -->
                    <template x-if="!activeSession.is_active">
                        <div class="p-3 bg-neutral-primary-medium border border-border-default rounded-default text-center text-xs text-fg-body-subtle font-medium">
                            Percakapan telah diselesaikan dan berstatus read-only.
                        </div>
                    </template>
                </div>
            </div>
        </template>

    </div>
</div>

<script>
    // Initialize Lucide Icons for dynamic content templates
    document.addEventListener('alpine:init', () => {
        Alpine.effect(() => {
            // Re-create icons when activeSessionId updates
            setTimeout(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }, 100);
        });
    });
</script>
@endsection
