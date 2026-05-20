<x-admin-layout :title="isset($user) ? 'Management > User > ' . $user->name . ' > Update' : 'Management > User > Create New User'">
    <x-slot name="head">
        <title>Manajemen User</title>
    </x-slot>

    <div x-data="userForm({
        name: '{{ old('name', $user->name ?? '') }}',
        email: '{{ old('email', $user->email ?? '') }}',
        role: '{{ old('role', $user->role ?? 'cashier') }}',
        avatarUrl: '{{ old('avatarUrl', $user->avatarUrl ?? '') }}'
    })" :class="{ 'dark': isDark }" class="h-full">

        <div class="mx-auto h-full max-w-5xl p-8 pb-32">
            <div
                class="overflow-hidden rounded-[2.5rem] border border-gray-100 bg-white shadow-2xl transition-all duration-500 dark:border-white/5 dark:bg-slate-900">

                <div
                    class="flex items-center justify-between border-b border-gray-50 bg-gray-50/50 px-10 py-8 dark:border-white/5 dark:bg-white/5">
                    <div class="flex items-center gap-4">
                        <div
                            class="rounded-2xl bg-orange-100 p-3 text-orange-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                            <x-lucide-user-plus class="h-6 w-6" />
                        </div>
                        <div>
                            <h2 class="text-xl font-black uppercase tracking-tight text-slate-800 dark:text-white">
                                {{ isset($user) ? 'Update Profile' : 'Add New User' }}
                            </h2>
                            <p
                                class="text-[10px] font-black uppercase tracking-widest text-gray-500 opacity-50 dark:text-gray-400">
                                Configure system access & permissions
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('users.index') }}"
                        class="rounded-full p-2 text-gray-600 transition-all hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-white/10">
                        <x-lucide-x class="h-6 w-6" />
                    </a>
                </div>

                <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}"
                    method="POST" class="p-10">
                    @csrf
                    @if (isset($user))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 gap-12 md:grid-cols-2">
                        <div class="space-y-8">

                            <div class="group relative">
                                <label
                                    class="mb-3 block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 transition-colors group-focus-within:text-orange-600 dark:text-gray-500 dark:group-focus-within:text-indigo-400">
                                    Full Name
                                </label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30 dark:text-white">
                                        <x-lucide-user-circle class="h-[18px] w-[18px]" />
                                    </div>
                                    <input type="text" name="name" x-model="form.name"
                                        class="w-full rounded-2xl border border-gray-200 bg-gray-50 py-4 pl-12 pr-4 text-sm font-bold text-slate-800 outline-none transition-all focus:border-orange-500/50 focus:bg-white dark:border-white/5 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500/50 dark:focus:ring-4 dark:focus:ring-indigo-500/10"
                                        placeholder="e.g. John Doe" required />
                                </div>
                                @error('name')
                                    <p class="mt-2 text-[9px] font-black uppercase tracking-widest text-red-500">
                                        {{ $message }}</p>
                                @enderror
                            </div>

                            <div class="group relative">
                                <label
                                    class="mb-3 block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 transition-colors group-focus-within:text-orange-600 dark:text-gray-500 dark:group-focus-within:text-indigo-400">
                                    Email Address
                                </label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30 dark:text-white">
                                        <x-lucide-mail class="h-[18px] w-[18px]" />
                                    </div>
                                    <input type="email" name="email" x-model="form.email"
                                        class="w-full rounded-2xl border border-gray-200 bg-gray-50 py-4 pl-12 pr-4 text-sm font-bold text-slate-800 outline-none transition-all focus:bg-white dark:border-white/5 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500/50"
                                        placeholder="john@siposqir.com" required />
                                </div>
                                @error('email')
                                    <p class="mt-2 text-[9px] font-black uppercase tracking-widest text-red-500">
                                        {{ $message }}</p>
                                @enderror
                            </div>

                            @if (!isset($user))
                                <div class="group relative">
                                    <label
                                        class="mb-3 block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 transition-colors group-focus-within:text-orange-600 dark:text-gray-500 dark:group-focus-within:text-indigo-400">
                                        Access Password
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30 dark:text-white">
                                            <x-lucide-lock class="h-[18px] w-[18px]" />
                                        </div>
                                        <input type="password" name="password"
                                            class="w-full rounded-2xl border border-gray-200 bg-gray-50 py-4 pl-12 pr-4 text-sm font-bold text-slate-800 outline-none transition-all focus:bg-white dark:border-white/5 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500/50"
                                            placeholder="••••••••" required />
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-8">

                            <div class="group relative">
                                <label
                                    class="mb-3 block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 transition-colors group-focus-within:text-orange-600 dark:text-gray-500 dark:group-focus-within:text-indigo-400">
                                    System Role
                                </label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30 dark:text-white">
                                        <x-lucide-shield class="h-[18px] w-[18px]" />
                                    </div>
                                    <select name="role" x-model="form.role"
                                        class="w-full appearance-none rounded-2xl border border-gray-200 bg-gray-50 py-4 pl-12 pr-10 text-sm font-bold text-slate-800 outline-none transition-all focus:bg-white dark:border-white/5 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500/50">
                                        <option value="waiter" class="bg-white dark:bg-slate-900">WAITER</option>
                                        <option value="kitchen" class="bg-white dark:bg-slate-900">KITCHEN</option>
                                    </select>
                                </div>
                            </div>

                            <div class="group relative">
                                <label
                                    class="mb-3 block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 transition-colors group-focus-within:text-orange-600 dark:text-gray-500 dark:group-focus-within:text-indigo-400">
                                    Avatar Image URL
                                </label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30 dark:text-white">
                                        <x-lucide-image class="h-[18px] w-[18px]" />
                                    </div>
                                    <input type="url" name="avatarUrl" x-model="form.avatarUrl"
                                        class="w-full rounded-2xl border border-gray-200 bg-gray-50 py-4 pl-12 pr-4 text-sm font-bold text-slate-800 outline-none transition-all focus:bg-white dark:border-white/5 dark:bg-slate-950 dark:text-white dark:focus:border-indigo-500/50"
                                        placeholder="https://images.com/user.jpg" />
                                </div>
                            </div>

                            <div
                                class="flex items-center gap-6 rounded-3xl border border-dashed border-gray-200 bg-gray-50 p-6 dark:border-white/10 dark:bg-white/5">
                                <img :src="form.avatarUrl ||
                                    `https://ui-avatars.com/api/?name=${encodeURIComponent(form.name || 'U')}&background=6366f1&color=fff&bold=true`"
                                    class="h-16 w-16 rounded-2xl object-cover shadow-lg" alt="Preview" />
                                <div>
                                    <h4
                                        class="mb-1 text-[10px] font-black uppercase tracking-widest text-slate-800 dark:text-white">
                                        Avatar Preview
                                    </h4>
                                    <p
                                        class="text-[9px] font-bold uppercase tracking-tighter opacity-50 dark:text-gray-400">
                                        Identity image for the board
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-12 flex items-center justify-end gap-4 border-t border-gray-100 pt-8 dark:border-white/5">
                        <a href="{{ route('users.index') }}"
                            class="rounded-2xl px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-500 transition-all hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">
                            Cancel
                        </a>

                        <button type="submit"
                            class="flex items-center gap-2 rounded-2xl bg-slate-900 px-10 py-4 text-[10px] font-black uppercase tracking-widest text-white shadow-xl shadow-slate-200 transition-all hover:bg-slate-800 active:scale-95 disabled:opacity-50 dark:bg-white dark:text-slate-950 dark:shadow-none dark:hover:bg-gray-200">
                            <x-lucide-save class="h-3.5 w-3.5" />
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
