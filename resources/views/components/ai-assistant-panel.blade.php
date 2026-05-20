{{-- components/ai-modal.blade.php --}}
@props([
    'module' => 'general',
    'payloadData' => null,
])

<div x-data="aiModal()" x-show="open" x-transition.opacity.duration.300ms
    class="fixed inset-0 z-[100] flex select-none items-center justify-center bg-slate-950/80 p-4 backdrop-blur-md lg:p-6"
    style="display: none;">

    <div class="flex w-full max-w-3xl flex-col overflow-hidden rounded-[2.5rem] border shadow-2xl transition-all duration-500"
        :class="isDark ? 'bg-slate-950 border-white/10 text-white' : 'bg-white border-gray-200 text-slate-900'"
        x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-95" @click.stop>

        {{-- HEADER --}}
        <div class="flex items-center justify-between border-b px-8 py-6"
            :class="isDark ? 'border-white/5' : 'border-gray-200'">
            <div class="flex items-center gap-4">
                <div class="rounded-2xl p-3"
                    :class="isDark ? 'bg-amber-500 text-slate-950' : 'bg-orange-600 text-white'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xs font-black uppercase leading-none tracking-widest">AnomaliPOS AI</h2>
                    <p class="mt-1 text-[9px] font-bold uppercase tracking-tighter opacity-30"
                        x-text="'Context: ' + module.toUpperCase()"></p>
                </div>
            </div>

            {{-- Close Button --}}
            <button @click="close()" class="rounded-2xl border p-3 transition-all active:scale-95"
                :class="isDark ?
                    'bg-rose-500/10 text-rose-500 border-rose-500/20 hover:bg-rose-500 hover:text-white' :
                    'bg-rose-50 text-rose-600 border-rose-100 hover:bg-rose-600 hover:text-white'">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        {{-- BODY --}}
        <div class="scrollbar-hide max-h-[65vh] overflow-y-auto p-8 lg:p-10">

            {{-- EMPTY STATE --}}
            <div x-show="!loading && !aiResponse" class="flex flex-col items-center justify-center text-center">
                <div class="w-full rounded-[3rem] border-2 border-dashed p-10 transition-all lg:p-12"
                    :class="isDark ? 'bg-white/5 border-white/10' : 'bg-gray-50 border-gray-200'">

                    <span class="mb-4 block text-[10px] font-black uppercase tracking-[0.2em] opacity-40">Asisten AI
                        Siap</span>
                    <p class="mx-auto mb-8 max-w-sm text-xs font-bold leading-relaxed opacity-50">Sistem siap memproses
                        data terkait modul ini untuk memberikan analisis atau saran.</p>

                    <button @click="analyze()"
                        class="mx-auto flex items-center justify-center gap-3 rounded-2xl px-8 py-5 text-xs font-black uppercase tracking-[0.15em] shadow-xl transition-all active:scale-95"
                        :class="isDark ? 'bg-amber-500 text-slate-950 hover:bg-amber-400' :
                            'bg-orange-600 text-white hover:bg-orange-500'">
                        Mulai Analisis
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" fill="currentColor">
                            <polygon points="5 3 19 12 5 21 5 3" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- LOADING STATE --}}
            <div x-show="loading" class="flex h-full w-full flex-col justify-center">
                <div class="w-full space-y-4 rounded-[3rem] border-2 border-dashed p-10 transition-all"
                    :class="isDark ? 'bg-white/5 border-white/10' : 'bg-gray-50 border-gray-200'">
                    <div class="mb-8 flex items-center gap-4">
                        <div class="h-8 w-8 animate-spin rounded-full border-4 border-t-transparent"
                            :class="isDark ? 'border-amber-500' : 'border-orange-600'"></div>
                        <div>
                            <span
                                class="block text-[10px] font-black uppercase tracking-[0.2em] opacity-40">Memproses...</span>
                            <span class="text-xs font-bold opacity-70">AI sedang menganalisis data Anda</span>
                        </div>
                    </div>

                    <div class="animate-pulse space-y-3">
                        <div class="h-3 w-3/4 rounded bg-current opacity-10"></div>
                        <div class="h-3 w-full rounded bg-current opacity-10"></div>
                        <div class="h-3 w-5/6 rounded bg-current opacity-10"></div>
                    </div>
                </div>
            </div>

            {{-- RESULT STATE --}}
            <div x-show="aiResponse && !loading" class="flex flex-col space-y-8">

                <div class="prose prose-sm max-w-none text-[13px] leading-relaxed"
                    :class="isDark ? 'prose-invert text-slate-300' : 'text-slate-700'" x-html="aiResponse"></div>

                <div x-show="quickActions.length" class="space-y-4 border-t pt-8"
                    :class="isDark ? 'border-white/5' : 'border-gray-100'">
                    <h4 class="text-[10px] font-black uppercase tracking-widest opacity-60">Rekomendasi Aksi</h4>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <template x-for="action in quickActions">
                            <button @click="doAction(action)"
                                class="rounded-[1rem] border px-5 py-3 font-bold transition-all active:scale-95"
                                :class="isDark ?
                                    'bg-white/5 hover:bg-white/10 text-white border-white/10' :
                                    'bg-gray-50 hover:bg-gray-100 text-slate-800 border-gray-200'">
                                <span x-text="action"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="flex justify-center pt-4">
                    <button @click="analyze()"
                        class="flex items-center gap-2 rounded-2xl border px-6 py-3 text-[10px] font-black uppercase tracking-widest transition-all active:scale-95"
                        :class="isDark ?
                            'text-amber-500 border-amber-500/20 hover:bg-amber-500/10' :
                            'text-orange-600 border-orange-200 hover:bg-orange-50'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                            <path d="M3 3v5h5" />
                        </svg>
                        Analisis Ulang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tambahkan script marked.js sebelum script Alpine --}}
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('aiModal', (config = {}) => ({
            open: false,
            module: 'general',
            payload: null,
            loading: false,
            aiResponse: '',
            quickActions: [],
            isDark: config.isDark ?? true,

            init() {
                window.addEventListener('open-ai-modal', (e) => {
                    this.module = e.detail.module || 'general';
                    this.payload = e.detail.payload || {};
                    this.open = true;
                    this.aiResponse = '';
                    window.dispatchEvent(new CustomEvent('open-ai-modal-opened', {
                        detail: false
                    }));
                });
            },

            close() {
                window.dispatchEvent(new CustomEvent('open-ai-modal-closed', {
                    detail: true
                }));
                this.open = false;
            },

            async analyze() {
                if (!this.payload) {
                    alert('Tidak ada data untuk dianalisis');
                    return;
                }

                this.loading = true;
                this.aiResponse = '';

                console.log(this.module)

                try {
                    const res = await fetch('/ai-engine/process', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify({
                            context_module: this.module,
                            payload: this.payload
                        })
                    });

                    const data = await res.json();


                    // Ambil raw markdown dari API
                    const rawMarkdown = data.ai_response_markdown ||
                        'Tidak ada respons dari AI.';

                    // Render Markdown ke HTML menggunakan marked.js
                    this.aiResponse = typeof marked !== 'undefined' ?
                        marked.parse(rawMarkdown) :
                        rawMarkdown;

                    this.quickActions = data.quick_actions || [];

                } catch (err) {
                    console.error(err);
                    document.body.innerHTML == err;
                } finally {
                    this.loading = false;
                }
            },

            doAction(action) {
                // alert('Menjalankan: ' + action);
            }
        }));
    });
</script>
