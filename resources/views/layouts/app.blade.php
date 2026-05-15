<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AnomaliPOS')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>[x-cloak] { display: none !important; }</style>
</head>
<body>
<div x-data x-cloak>

    @include('components.notification')

    @yield('content')

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('notif', {
            open: false,
            type: 'success',
            title: '',
            message: '',
            set(type, title, message = '') {
                this.type    = type
                this.title   = title
                this.message = message
                this.open    = true
                setTimeout(() => this.open = false, 4000)
            }
        })

        Alpine.store('theme', {
            isDark: false,
            init() {
                const stored = localStorage.getItem('theme')
                if (stored === 'light' || stored === 'dark') {
                    this.isDark = stored === 'dark'
                } else {
                    this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches
                }
                document.documentElement.setAttribute('data-theme', this.isDark ? 'dark' : 'light')
                // watch perubahan
                this._watch()
            },
            _watch() {
                const observer = new MutationObserver(() => {})
                setInterval(() => {
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light')
                    document.documentElement.setAttribute('data-theme', this.isDark ? 'dark' : 'light')
                }, 300)
            },
            toggle() {
                this.isDark = !this.isDark
                localStorage.setItem('theme', this.isDark ? 'dark' : 'light')
                document.documentElement.setAttribute('data-theme', this.isDark ? 'dark' : 'light')
            }
        })
    })
</script>

<script>
    lucide.createIcons();
</script>

</body>
</html>