<x-admin-layout title="Management > User">
    <x-slot name="head">
        <title>Manajemen User</title>
    </x-slot>

    <!-- Passing data $users dari Laravel ke Alpine via directive -->
    <div x-data='userIndex(@json($users))' :class="{ 'dark': isDark }"
        class="h-full flex flex-col transition-colors duration-500 overflow-hidden">

        <!-- Background Glow (Dark Mode) -->
        <template x-if="isDark">
            <div class="fixed inset-0 pointer-events-none opacity-20">
                <div class="absolute top-0 left-0 w-125 h-125 bg-indigo-900 rounded-full blur-[120px] mix-blend-screen">
                </div>
                <div
                    class="absolute bottom-0 right-0 w-125 h-125 bg-amber-900 rounded-full blur-[120px] mix-blend-screen">
                </div>
            </div>
        </template>

        <!-- Header / Navigation -->
        <div
            class="sticky top-0 z-40 w-full px-8 py-4 backdrop-blur-xl border-b transition-all duration-300 bg-white/70 border-gray-200/50 dark:bg-slate-900/70 dark:border-white/5">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 mx-auto w-full">
                <div class="flex items-center gap-3">
                    <div
                        class="p-2.5 rounded-xl bg-orange-100 text-orange-600 shadow-sm dark:bg-slate-800 dark:text-amber-500 dark:shadow-lg dark:shadow-black/20">
                        <x-lucide-user-cog class="w-6 h-6" />
                    </div>
                    <div>
                        <h1 class="font-bold text-lg leading-none tracking-tight dark:text-white">User Management</h1>
                        <p
                            class="text-[10px] mt-1 uppercase font-bold tracking-widest text-gray-500 dark:text-gray-400">
                            Control access and permissions
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4 w-full md:w-auto">
                    <div
                        class="flex items-center gap-3 flex-1 p-3 px-5 border rounded-full transition-all shadow-sm bg-white border-gray-200 focus-within:border-orange-500 dark:bg-slate-900 dark:border-white/10 dark:focus-within:border-amber-500/50">
                        <x-lucide-search class="w-[18px] h-[18px] text-gray-400 dark:text-slate-500" />
                        <input type="text" placeholder="Cari nama atau email..." x-model="searchQuery"
                            class="bg-transparent border-none outline-none w-full text-sm font-medium dark:text-white focus:ring-0" />

                        <button @click="toggleTheme()"
                            class="rounded-full transition-transform active:rotate-90 hover:scale-110 text-slate-600 hover:bg-white shadow-sm dark:text-amber-400 dark:hover:bg-slate-800 dark:shadow-none">
                            <template x-if="isDark"><x-lucide-sun class="w-[18px] h-[18px]" /></template>
                            <template x-if="!isDark"><x-lucide-moon class="w-[18px] h-[18px]" /></template>
                        </button>
                    </div>

                    <a href="{{ route('users.create') }}"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all active:scale-95 flex items-center gap-2 shadow-lg bg-slate-900 text-white hover:bg-slate-800 shadow-slate-200 dark:bg-white dark:text-slate-950 dark:hover:bg-gray-200 dark:shadow-none">
                        <x-lucide-user-plus class="w-4 h-4" /> User Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Grid Content -->
        <div
            class="p-8 mx-auto w-full flex-1 overflow-y-auto pb-32 bg-gray-50/30 dark:bg-transparent [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <template x-for="u in filteredUsers" :key="u.id">
                    <div
                        class="group relative p-6 rounded-3xl border transition-all duration-300 hover:-translate-y-1 bg-white border-gray-100 hover:shadow-xl shadow-gray-200/50 dark:bg-slate-900/50 dark:border-white/5 dark:hover:bg-slate-800 dark:shadow-2xl dark:shadow-black/20">

                        <!-- Status Badge -->
                        <div class="absolute top-6 right-6 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest"
                            :class="u.status === 'active' ?
                                'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' :
                                'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400'"
                            x-text="u.status">
                        </div>

                        <div class="flex flex-col items-center text-center">
                            <div class="relative mb-4">
                                <!-- Dynamic Avatar -->
                                <img :src="u.avatar_url ??
                                    `https://ui-avatars.com/api/?name=${encodeURIComponent(u.name)}&background=${isDark ? '1e293b' : 'f1f5f9'}&color=${isDark ? 'f59e0b' : 'ea580c'}&bold=true`"
                                    class="w-20 h-20 rounded-3xl object-cover border-2 shadow-inner border-gray-100 dark:border-white/5"
                                    :alt="u.name" />
                                <div
                                    class="absolute -bottom-2 -right-2 p-2 rounded-xl shadow-lg bg-white text-orange-600 dark:bg-slate-950 dark:text-indigo-400">
                                    <template x-if="u.role === 'admin'"><x-lucide-shield-check
                                            class="w-3.5 h-3.5" /></template>
                                    <template x-if="u.role !== 'admin'"><x-lucide-user class="w-3.5 h-3.5" /></template>
                                </div>
                            </div>

                            <h3 class="text-sm font-black uppercase tracking-tight mb-1 text-slate-800 dark:text-white"
                                x-text="u.name"></h3>

                            <div
                                class="flex items-center gap-1.5 text-[10px] font-bold opacity-60 mb-4 text-gray-500 dark:text-gray-400">
                                <x-lucide-mail class="w-3 h-3" />
                                <span x-text="u.email"></span>
                            </div>

                            <div class="w-full h-px mb-4 bg-gray-50 dark:bg-white/5"></div>

                            <div class="flex items-center justify-between w-full mb-6">
                                <div class="text-left">
                                    <p
                                        class="text-[8px] font-black uppercase opacity-40 mb-0.5 text-slate-900 dark:text-white">
                                        Member Since</p>
                                    <div
                                        class="flex items-center gap-1 text-[10px] font-bold text-slate-600 dark:text-gray-300">
                                        <x-lucide-clock class="w-2.5 h-2.5" />
                                        <!-- Format created_at dari database -->
                                        <span x-text="formatDate(u.created_at)"></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p
                                        class="text-[8px] font-black uppercase opacity-40 mb-0.5 text-slate-900 dark:text-white">
                                        Role</p>
                                    <span class="text-[10px] font-black uppercase text-orange-600 dark:text-amber-500"
                                        x-text="u.role || 'User'"></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 w-full">
                                <button :disabled="processingUserId === u.id" @click="toggleStatus(u.id)"
                                    class="flex items-center justify-center gap-2 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all"
                                    :class="u.status === 'active' ?
                                        'bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white' :
                                        'bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white'">
                                    <template x-if="processingUserId === u.id">
                                        <span>...</span>
                                    </template>
                                    <template x-if="processingUserId !== u.id">
                                        <span class="flex items-center gap-2">
                                            <x-lucide-power class="w-3 h-3" />
                                            <span x-text="u.status === 'active' ? 'Disable' : 'Enable'"></span>
                                        </span>
                                    </template>
                                </button>

                                <a :href="`/users/${u.id}/edit`"
                                    class="flex items-center justify-center gap-2 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all bg-gray-100 text-slate-600 hover:bg-slate-900 hover:text-white dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10 dark:hover:text-white">
                                    <x-lucide-edit-3 class="w-3 h-3" /> Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <template x-if="filteredUsers.length === 0">
                <div class="flex flex-col items-center justify-center py-20 opacity-30">
                    <x-lucide-user-cog class="w-12 h-12 mb-4 dark:text-white" />
                    <p class="font-black uppercase tracking-widest text-xs dark:text-white">No users found</p>
                </div>
            </template>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            // Menerima parameter initialData dari controller
            Alpine.data('userIndex', (initialData) => ({
                isDark: false,
                searchQuery: '',
                processingUserId: null,
                users: initialData, // Mengisi data dari database

                get filteredUsers() {
                    return this.users.filter(u =>
                        u.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        u.email.toLowerCase().includes(this.searchQuery.toLowerCase())
                    );
                },

                toggleTheme() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                    this.updateRootTheme();
                },

                updateRootTheme() {
                    document.documentElement.classList.toggle('dark', this.isDark);
                    document.documentElement.dataset.theme = this.isDark ? 'dark' : 'light';
                },

                init() {
                    const storedTheme = localStorage.getItem('theme');
                    this.isDark = storedTheme === 'dark';
                    this.updateRootTheme();
                },

                toggleStatus(id) {
                    this.processingUserId = id;

                    // Contoh integrasi backend (opsional):
                    fetch(`/users/${id}/toggle-status`, { method: 'POST', headers: {'X-CSRF-TOKEN': '...'}})

                    setTimeout(() => {
                        let user = this.users.find(u => u.id === id);
                        if (user) user.status = user.status === 'active' ? 'disabled' :
                        'active';
                        this.processingUserId = null;
                    }, 400);
                },

                formatDate(dateString) {
                    if (!dateString) return 'Unknown';
                    return new Date(dateString).toLocaleDateString('id-ID', {
                        month: 'short',
                        year: 'numeric'
                    });
                }
            }));
        });
    </script>
</x-admin-layout>
