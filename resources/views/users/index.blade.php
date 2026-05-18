<x-admin-layout title="Management User" page_title="Management > User">

    <!-- Passing data $users dari Laravel ke Alpine via directive -->
    <div x-data='userIndex(@json($users))' :class="{ 'dark': isDark }"
        class="flex h-full flex-col overflow-hidden transition-colors duration-500">

        <!-- Background Glow (Dark Mode) -->
        <template x-if="isDark">
            <div class="pointer-events-none fixed inset-0 opacity-20">
                <div class="w-125 h-125 absolute left-0 top-0 rounded-full bg-indigo-900 mix-blend-screen blur-[120px]">
                </div>
                <div
                    class="w-125 h-125 absolute bottom-0 right-0 rounded-full bg-amber-900 mix-blend-screen blur-[120px]">
                </div>
            </div>
        </template>

        <!-- Header / Navigation -->
        <div
            class="sticky top-0 z-40 w-full border-b border-gray-200/50 bg-white/70 px-8 py-4 backdrop-blur-xl transition-all duration-300 dark:border-white/5 dark:bg-slate-900/70">
            <div class="mx-auto flex w-full flex-col items-center justify-between gap-6 md:flex-row">
                <div class="flex items-center gap-3">
                    <div
                        class="rounded-xl bg-orange-100 p-2.5 text-orange-600 shadow-sm dark:bg-slate-800 dark:text-amber-500 dark:shadow-lg dark:shadow-black/20">
                        <x-lucide-user-cog class="h-6 w-6" />
                    </div>
                    <div>
                        <h1 class="text-lg font-bold leading-none tracking-tight dark:text-white">User Management</h1>
                        <p
                            class="mt-1 text-[10px] font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">
                            Control access and permissions
                        </p>
                    </div>
                </div>

                <div class="flex w-full items-center gap-4 md:w-auto">
                    <div
                        class="flex flex-1 items-center gap-3 rounded-full border border-gray-200 bg-white p-3 px-5 shadow-sm transition-all focus-within:border-orange-500 dark:border-white/10 dark:bg-slate-900 dark:focus-within:border-amber-500/50">
                        <x-lucide-search class="h-[18px] w-[18px] text-gray-400 dark:text-slate-500" />
                        <input type="text" placeholder="Cari nama atau email..." x-model="searchQuery"
                            class="w-full border-none bg-transparent text-sm font-medium outline-none focus:ring-0 dark:text-white" />

                        <button @click="$dispatch('toggle-theme')"
                            class="rounded-full text-slate-600 shadow-sm transition-transform hover:scale-110 hover:bg-white active:rotate-90 dark:text-amber-400 dark:shadow-none dark:hover:bg-slate-800">
                            <span x-show="isDark"><x-lucide-sun class="h-[18px] w-[18px]" /></span>
                            <span x-show="!isDark"><x-lucide-moon class="h-[18px] w-[18px]" /></span>
                        </button>
                    </div>

                    <a href="{{ route('users.create') }}"
                        class="flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-xs font-bold text-white shadow-lg shadow-slate-200 transition-all hover:bg-slate-800 active:scale-95 dark:bg-white dark:text-slate-950 dark:shadow-none dark:hover:bg-gray-200">
                        <x-lucide-user-plus class="h-4 w-4" /> User Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Grid Content -->
        <div
            class="mx-auto w-full flex-1 overflow-y-auto bg-gray-50/30 p-8 pb-32 [-ms-overflow-style:none] [scrollbar-width:none] dark:bg-transparent [&::-webkit-scrollbar]:hidden">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <template x-for="u in filteredUsers" :key="u.id">
                    <div
                        class="group relative rounded-3xl border border-gray-100 bg-white p-6 shadow-gray-200/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl dark:border-white/5 dark:bg-slate-900/50 dark:shadow-2xl dark:shadow-black/20 dark:hover:bg-slate-800">

                        <!-- Status Badge -->
                        <div class="absolute right-6 top-6 rounded-full px-3 py-1 text-[8px] font-black uppercase tracking-widest"
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
                                    class="h-20 w-20 rounded-3xl border-2 border-gray-100 object-cover shadow-inner dark:border-white/5"
                                    :alt="u.name" />
                                <div
                                    class="absolute -bottom-2 -right-2 rounded-xl bg-white p-2 text-orange-600 shadow-lg dark:bg-slate-950 dark:text-indigo-400">
                                    <template x-if="u.role === 'admin'"><x-lucide-shield-check
                                            class="h-3.5 w-3.5" /></template>
                                    <template x-if="u.role !== 'admin'"><x-lucide-user class="h-3.5 w-3.5" /></template>
                                </div>
                            </div>

                            <h3 class="mb-1 text-sm font-black uppercase tracking-tight text-slate-800 dark:text-white"
                                x-text="u.name"></h3>

                            <div
                                class="mb-4 flex items-center gap-1.5 text-[10px] font-bold text-gray-500 opacity-60 dark:text-gray-400">
                                <x-lucide-mail class="h-3 w-3" />
                                <span x-text="u.email"></span>
                            </div>

                            <div class="mb-4 h-px w-full bg-gray-50 dark:bg-white/5"></div>

                            <div class="mb-6 flex w-full items-center justify-between">
                                <div class="text-left">
                                    <p
                                        class="mb-0.5 text-[8px] font-black uppercase text-slate-900 opacity-40 dark:text-white">
                                        Member Since</p>
                                    <div
                                        class="flex items-center gap-1 text-[10px] font-bold text-slate-600 dark:text-gray-300">
                                        <x-lucide-clock class="h-2.5 w-2.5" />
                                        <!-- Format created_at dari database -->
                                        <span x-text="formatDate(u.created_at)"></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p
                                        class="mb-0.5 text-[8px] font-black uppercase text-slate-900 opacity-40 dark:text-white">
                                        Role</p>
                                    <span class="text-[10px] font-black uppercase text-orange-600 dark:text-amber-500"
                                        x-text="u.role || 'User'"></span>
                                </div>
                            </div>

                            <div class="grid w-full grid-cols-2 gap-3">
                                <button :disabled="processingUserId === u.id" @click="toggleStatus(u.id)"
                                    class="flex items-center justify-center gap-2 rounded-xl py-2.5 text-[9px] font-black uppercase tracking-widest transition-all"
                                    :class="u.status === 'active' ?
                                        'bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white' :
                                        'bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white'">
                                    <template x-if="processingUserId === u.id">
                                        <span>...</span>
                                    </template>
                                    <template x-if="processingUserId !== u.id">
                                        <span class="flex items-center gap-2">
                                            <x-lucide-power class="h-3 w-3" />
                                            <span x-text="u.status === 'active' ? 'Disable' : 'Enable'"></span>
                                        </span>
                                    </template>
                                </button>

                                <a :href="`/users/${u.id}/edit`"
                                    class="flex items-center justify-center gap-2 rounded-xl bg-gray-100 py-2.5 text-[9px] font-black uppercase tracking-widest text-slate-600 transition-all hover:bg-slate-900 hover:text-white dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10 dark:hover:text-white">
                                    <x-lucide-edit-3 class="h-3 w-3" /> Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <template x-if="filteredUsers.length === 0">
                <div class="flex flex-col items-center justify-center py-20 opacity-30">
                    <x-lucide-user-cog class="mb-4 h-12 w-12 dark:text-white" />
                    <p class="text-xs font-black uppercase tracking-widest dark:text-white">No users found</p>
                </div>
            </template>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            // Menerima parameter initialData dari controller
            Alpine.data('userIndex', (initialData) => ({
                searchQuery: '',
                processingUserId: null,
                users: initialData, // Mengisi data dari database

                get filteredUsers() {
                    return this.users.filter(u =>
                        u.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        u.email.toLowerCase().includes(this.searchQuery.toLowerCase())
                    );
                },

                toggleStatus(id) {
                    this.processingUserId = id;

                    // Contoh integrasi backend (opsional):
                    fetch(`/users/${id}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '...'
                        }
                    })

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
