<!DOCTYPE html>
<html lang="id" x-data="themeManager()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AnoPos')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    @yield('content')

    <script>
    function themeManager() {
        return {
            isDark: localStorage.getItem('theme') === 'dark',
            isFullscreen: false,
            toggleTheme() {
                this.isDark = !this.isDark;
                localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                this.updateRootTheme();
            },
            updateRootTheme() {
                document.documentElement.classList.toggle('dark', this.isDark);
                document.documentElement.dataset.theme = this.isDark ? 'dark' : 'light';
            },
            toggleFullscreen() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                    this.isFullscreen = true;
                } else {
                    document.exitFullscreen();
                    this.isFullscreen = false;
                }
            },
            init() {
                this.updateRootTheme();
                window.addEventListener('toggle-theme', () => this.toggleTheme());
                document.addEventListener('fullscreenchange', () => {
                    this.isFullscreen = !!document.fullscreenElement;
                });
            }
        }
    }
    </script>
</body>
</html>
