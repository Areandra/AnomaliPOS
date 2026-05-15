{{-- Notification Stack Component --}}
{{-- Cara pakai: @include('components.notification') di dalam layout --}}

<div class="fixed top-5 right-5 z-[100] flex flex-col gap-2 pointer-events-none" style="min-width:280px">
    <template x-for="notif in $store.notifs.items" :key="notif.id">
        <div
            x-show="notif.visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-4"
            @click="$store.notifs.dismiss(notif.id)"
            :class="notif.type === 'success' ? 'bg-emerald-500 shadow-emerald-500/20' : 'bg-red-500 shadow-red-500/20'"
            class="px-5 py-4 rounded-2xl text-white shadow-xl cursor-pointer pointer-events-auto w-full"
        >
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest" x-text="notif.title"></p>
                    <p class="text-[10px] opacity-80 mt-0.5 normal-case" x-text="notif.message"></p>
                </div>
                <button class="opacity-60 hover:opacity-100 shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </template>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('notifs', {
            items: [],
            _counter: 0,

            set(type, title, message = '') {
                const id = ++this._counter
                this.items.push({ id, type, title, message, visible: true })
                setTimeout(() => this.dismiss(id), 4000)
            },

            dismiss(id) {
                const item = this.items.find(n => n.id === id)
                if (item) item.visible = false
                setTimeout(() => {
                    this.items = this.items.filter(n => n.id !== id)
                }, 300)
            }
        })

        // backward compat
        Alpine.store('notif', {
            set(type, title, message = '') {
                Alpine.store('notifs').set(type, title, message)
            }
        })
    })
</script>
