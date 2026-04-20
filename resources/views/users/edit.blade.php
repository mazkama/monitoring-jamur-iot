<x-app-layout>
    <x-slot name="header">
        Edit Pengguna: {{ $user->name }}
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-primary font-headline tracking-tight flex items-center gap-3">
                <span class="material-symbols-outlined text-4xl">manage_accounts</span>
                Perbarui Profil Pengguna
            </h2>
            <p class="text-secondary font-medium mt-1">Ubah data personal, keamanan sandi, atau hak akses milik {{ $user->name }}.</p>
        </div>

        <div class="bg-surface-container-low rounded-3xl p-6 md:p-8 shadow-sm border border-outline-variant/10">
            <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-primary ml-1" for="name">Nama Lengkap</label>
                        <div class="relative">
                            <input class="w-full bg-white border-none rounded-2xl py-2.5 px-5 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none" 
                                   id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-secondary">badge</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-primary ml-1" for="email">Alamat Email</label>
                        <div class="relative">
                            <input class="w-full bg-white border-none rounded-2xl py-2.5 px-5 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none" 
                                   id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-secondary">email</span>
                        </div>
                    </div>
                </div>

                <div class="bg-surface-container-highest/20 rounded-2xl p-6 border border-outline-variant/30 space-y-4">
                    <div class="flex items-center gap-2 mb-2 text-secondary">
                        <span class="material-symbols-outlined text-xl">gpp_good</span>
                        <h4 class="font-bold text-sm">Pembaruan Kata Sandi</h4>
                    </div>
                    <p class="text-xs text-secondary/80 mb-4">*Kosongkan form kata sandi jika Anda tidak ingin merubahnya.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-secondary ml-1" for="password">Kata Sandi Baru</label>
                            <div class="relative">
                                <input class="w-full bg-white border-none rounded-xl py-2.5 px-4 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none" 
                                       id="password" name="password" type="password" placeholder="Biarkan kosong jika tidak diubah">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-secondary ml-1" for="password_confirmation">Konfirmasi Kata Sandi</label>
                            <div class="relative">
                                <input class="w-full bg-white border-none rounded-xl py-2.5 px-4 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none" 
                                       id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi sandi baru">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <label class="block text-sm font-bold text-primary ml-1">Hak Akses (Role)</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="role" value="admin" class="peer sr-only" {{ old('role', $user->role) == 'admin' ? 'checked' : '' }} required>
                            <div class="p-4 rounded-2xl bg-white border-2 border-transparent peer-checked:border-primary peer-checked:bg-primary-container/10 flex items-center gap-4 transition-all">
                                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center peer-checked:bg-primary peer-checked:text-white">
                                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">shield</span>
                                </div>
                                <div>
                                    <p class="font-bold text-primary">Administrator</p>
                                    <p class="text-[10px] text-secondary">Akses penuh keseluruhan sistem</p>
                                </div>
                            </div>
                        </label>
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="role" value="staff" class="peer sr-only" {{ old('role', $user->role) == 'staff' ? 'checked' : '' }} required>
                            <div class="p-4 rounded-2xl bg-white border-2 border-transparent peer-checked:border-secondary peer-checked:bg-secondary-container/20 flex items-center gap-4 transition-all">
                                <div class="w-10 h-10 rounded-full bg-secondary/10 text-secondary flex items-center justify-center peer-checked:bg-secondary peer-checked:text-white">
                                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">person</span>
                                </div>
                                <div>
                                    <p class="font-bold text-secondary">Petugas Luar</p>
                                    <p class="text-[10px] text-secondary">Input panen dan monitoring alat</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="pt-6 flex flex-col sm:flex-row gap-4 border-t border-surface-container">
                    <button type="submit" class="w-full sm:w-auto flex-1 bg-gradient-to-r from-primary to-primary-container text-white font-bold py-2.5 rounded-full shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">save_as</span>
                        Perbarui Pengguna
                    </button>
                    <a href="{{ route('users.index') }}" class="w-full sm:w-auto px-6 bg-surface-container-highest text-secondary hover:text-primary font-bold py-2.5 rounded-full transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
