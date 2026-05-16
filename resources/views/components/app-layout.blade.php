@props(['title' => 'AnomaliPOS'])

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body>

    <div x-cloak x-data x-init="$store.theme.init();
    $nextTick(() => lucide.createIcons())">

        <x-notification />

        {{ $slot }}

    </div>

    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.store('theme', {
                isDark: false,
                init() {
                    const stored = localStorage.getItem('theme')
                    this.isDark = stored === 'dark' ??
                        window.matchMedia('(prefers-color-scheme: dark)').matches
                },
                toggle() {
                    this.isDark = !this.isDark
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light')
                }
            })

            Alpine.store('notif', {
                open: false,
                type: 'success',
                title: '',
                message: '',
                set(type, title, message = '') {
                    this.type = type
                    this.title = title
                    this.message = message
                    this.open = true
                    setTimeout(() => {
                        this.open = false
                    }, 4000)
                }
            })

        })
    </script>

    <script>
        document.addEventListener('alpine:initialized', () => lucide.createIcons())
        document.addEventListener('alpine:mutated', () => lucide.createIcons())
    </script>

</body>

</html>
