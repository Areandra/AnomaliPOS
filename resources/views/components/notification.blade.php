{{-- Pengganti: NotificationContext.tsx (Provider + useNotification hook) --}}
{{-- Cara pakai: @include('components.notification') di dalam layout --}}

<div
    x-data
    x-show="$store.notif.open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    @click="$store.notif.open = false"
    :class="$store.notif.type === 'success' ? 'bg-green-500 shadow-green-500/20' : 'bg-red-500 shadow-red-500/20'"
    class="fixed top-5 right-5 z-[100] px-5 py-4 rounded-2xl text-white shadow-xl cursor-pointer max-w-xs"
    x-cloak
>
    <p class="text-xs font-black uppercase tracking-widest" x-text="$store.notif.title"></p>
    <p class="text-[10px] opacity-80 mt-0.5" x-text="$store.notif.message"></p>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        // Pengganti: NotificationContext + useState<Notification>
        Alpine.store('notif', {
            open: false,
            type: 'success',   // 'success' | 'error'
            title: '',
            message: '',

            // Pengganti: setNotif({ open, type, title, message })
            set(type, title, message = '') {
                this.type    = type
                this.title   = title
                this.message = message
                this.open    = true
                setTimeout(() => this.open = false, 4000)
            }
        })
    })
</script>