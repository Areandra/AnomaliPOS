<x-admin-layout :title="isset($user) ? 'Management > User > ' . $user->name . ' > Update' : 'Management > User > Create New User'">
    <x-slot name="head">
        <title>Manajemen User</title>
    </x-slot>

    <div x-data="userForm({
            name: '{{ old('name', $user->name ?? '') }}',
            email: '{{ old('email', $user->email ?? '') }}',
            role: '{{ old('role', $user->role ?? 'cashier') }}',
            avatarUrl: '{{ old('avatarUrl', $user->avatarUrl ?? '') }}'
        })" 
        :class="{ 'dark': isDark }" 
        class="h-full">
        
        <div class="p-8 h-full max-w-5xl mx-auto pb-32">
            <div class="rounded-[2.5rem] shadow-2xl transition-all duration-500 border overflow-hidden bg-white border-gray-100 dark:bg-slate-900 dark:border-white/5">
                
                <div class="px-10 py-8 flex justify-between items-center border-b border-gray-50 bg-gray-50/50 dark:border-white/5 dark:bg-white/5">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-2xl bg-orange-100 text-orange-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                            <x-lucide-user-plus class="w-6 h-6" />
                        </div>
                        <div>
                            <h2 class="text-xl font-black uppercase tracking-tight text-slate-800 dark:text-white">
                                {{ isset($user) ? 'Update Profile' : 'Add New User' }}
                            </h2>
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-50 text-gray-500 dark:text-gray-400">
                                Configure system access & permissions
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('users.index') }}" class="p-2 rounded-full transition-all text-gray-600 hover:bg-gray-200 dark:hover:bg-white/10 dark:text-gray-400">
                        <x-lucide-x class="w-6 h-6" />
                    </a>
                </div>

                <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST" class="p-10">
                    @csrf
                    @if(isset($user))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div class="space-y-8">
                            
                            <div class="relative group">
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 transition-colors text-gray-400 group-focus-within:text-orange-600 dark:text-gray-500 dark:group-focus-within:text-indigo-400">
                                    Full Name
                                </label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30 dark:text-white">
                                        <x-lucide-user-circle class="w-[18px] h-[18px]" />
                                    </div>
                                    <input
                                        type="text"
                                        name="name"
                                        x-model="form.name"
                                        class="w-full pl-12 pr-4 py-4 rounded-2xl border text-sm font-bold transition-all outline-none bg-gray-50 border-gray-200 text-slate-800 focus:bg-white focus:border-orange-500/50 dark:bg-slate-950 dark:border-white/5 dark:text-white dark:focus:border-indigo-500/50 dark:focus:ring-4 dark:focus:ring-indigo-500/10"
                                        placeholder="e.g. John Doe"
                                        required
                                    />
                                </div>
                                @error('name')
                                    <p class="text-red-500 text-[9px] font-black uppercase mt-2 tracking-widest">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="relative group">
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 transition-colors text-gray-400 group-focus-within:text-orange-600 dark:text-gray-500 dark:group-focus-within:text-indigo-400">
                                    Email Address
                                </label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30 dark:text-white">
                                        <x-lucide-mail class="w-[18px] h-[18px]" />
                                    </div>
                                    <input
                                        type="email"
                                        name="email"
                                        x-model="form.email"
                                        class="w-full pl-12 pr-4 py-4 rounded-2xl border text-sm font-bold transition-all outline-none bg-gray-50 border-gray-200 text-slate-800 focus:bg-white dark:bg-slate-950 dark:border-white/5 dark:text-white dark:focus:border-indigo-500/50"
                                        placeholder="john@siposqir.com"
                                        required
                                    />
                                </div>
                                @error('email')
                                    <p class="text-red-500 text-[9px] font-black uppercase mt-2 tracking-widest">{{ $message }}</p>
                                @enderror
                            </div>

                            @if(!isset($user))
                            <div class="relative group">
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 transition-colors text-gray-400 group-focus-within:text-orange-600 dark:text-gray-500 dark:group-focus-within:text-indigo-400">
                                    Access Password
                                </label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30 dark:text-white">
                                        <x-lucide-lock class="w-[18px] h-[18px]" />
                                    </div>
                                    <input
                                        type="password"
                                        name="password"
                                        class="w-full pl-12 pr-4 py-4 rounded-2xl border text-sm font-bold transition-all outline-none bg-gray-50 border-gray-200 text-slate-800 focus:bg-white dark:bg-slate-950 dark:border-white/5 dark:text-white dark:focus:border-indigo-500/50"
                                        placeholder="••••••••"
                                        required
                                    />
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="space-y-8">
                            
                            <div class="relative group">
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 transition-colors text-gray-400 group-focus-within:text-orange-600 dark:text-gray-500 dark:group-focus-within:text-indigo-400">
                                    System Role
                                </label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30 dark:text-white">
                                        <x-lucide-shield class="w-[18px] h-[18px]" />
                                    </div>
                                    <select
                                        name="role"
                                        x-model="form.role"
                                        class="w-full pl-12 pr-10 py-4 rounded-2xl border text-sm font-bold transition-all outline-none appearance-none bg-gray-50 border-gray-200 text-slate-800 focus:bg-white dark:bg-slate-950 dark:border-white/5 dark:text-white dark:focus:border-indigo-500/50"
                                    >
                                        <option value="cashier" class="bg-white dark:bg-slate-900">CASHIER</option>
                                        <option value="waiter" class="bg-white dark:bg-slate-900">WAITER</option>
                                        <option value="kitchen" class="bg-white dark:bg-slate-900">KITCHEN</option>
                                    </select>
                                </div>
                            </div>

                            <div class="relative group">
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] mb-3 transition-colors text-gray-400 group-focus-within:text-orange-600 dark:text-gray-500 dark:group-focus-within:text-indigo-400">
                                    Avatar Image URL
                                </label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30 dark:text-white">
                                        <x-lucide-image class="w-[18px] h-[18px]" />
                                    </div>
                                    <input
                                        type="url"
                                        name="avatarUrl"
                                        x-model="form.avatarUrl"
                                        class="w-full pl-12 pr-4 py-4 rounded-2xl border text-sm font-bold transition-all outline-none bg-gray-50 border-gray-200 text-slate-800 focus:bg-white dark:bg-slate-950 dark:border-white/5 dark:text-white dark:focus:border-indigo-500/50"
                                        placeholder="https://images.com/user.jpg"
                                    />
                                </div>
                            </div>

                            <div class="p-6 rounded-3xl border border-dashed flex items-center gap-6 bg-gray-50 border-gray-200 dark:bg-white/5 dark:border-white/10">
                                <img
                                    :src="form.avatarUrl || `https://ui-avatars.com/api/?name=${encodeURIComponent(form.name || 'U')}&background=6366f1&color=fff&bold=true`"
                                    class="w-16 h-16 rounded-2xl object-cover shadow-lg"
                                    alt="Preview"
                                />
                                <div>
                                    <h4 class="text-[10px] font-black uppercase tracking-widest mb-1 text-slate-800 dark:text-white">
                                        Avatar Preview
                                    </h4>
                                    <p class="text-[9px] font-bold opacity-50 uppercase tracking-tighter dark:text-gray-400">
                                        Identity image for the board
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 pt-8 border-t flex justify-end items-center gap-4 border-gray-100 dark:border-white/5">
                        <a href="{{ route('users.index') }}" class="px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="flex items-center gap-2 px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl transition-all active:scale-95 disabled:opacity-50 bg-slate-900 text-white hover:bg-slate-800 shadow-slate-200 dark:bg-white dark:text-slate-950 dark:hover:bg-gray-200 dark:shadow-none"
                        >
                            <x-lucide-save class="w-3.5 h-3.5" />
                            {{ isset($user) ? 'Update User' : 'Save System User' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('userForm', (initialData) => ({
                isDark: false, // Sesuaikan config tema default-mu di sini
                form: {
                    name: initialData.name,
                    email: initialData.email,
                    role: initialData.role,
                    avatarUrl: initialData.avatarUrl
                }
            }));
        });
    </script>
</x-admin-layout>