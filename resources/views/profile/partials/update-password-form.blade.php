<section>
    <header class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-on-surface/5 rounded-full flex items-center justify-center text-on-surface">
            <span class="material-symbols-outlined">password</span>
        </div>
        <div>
            <h2 class="text-lg font-bold text-on-surface font-headline">
                Ubah Kata Sandi
            </h2>
            <p class="mt-1 text-xs text-secondary leading-tight line-clamp-2">
                Pastikan akun Anda menggunakan kata sandi yang panjang dan acak demi keamanan maksimal.
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="space-y-2">
            <label class="block text-sm font-bold text-secondary ml-1" for="update_password_current_password">Kata Sandi Saat Ini</label>
            <div class="relative">
                <input class="w-full bg-white border-none rounded-2xl py-2.5 px-4 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none" 
                       id="update_password_current_password" name="current_password" type="password" autocomplete="current-password">
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-error text-xs" />
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-bold text-secondary ml-1" for="update_password_password">Kata Sandi Baru</label>
            <div class="relative">
                <input class="w-full bg-white border-none rounded-2xl py-2.5 px-4 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none" 
                       id="update_password_password" name="password" type="password" autocomplete="new-password">
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-error text-xs" />
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-bold text-secondary ml-1" for="update_password_password_confirmation">Konfirmasi Kata Sandi Baru</label>
            <div class="relative">
                <input class="w-full bg-white border-none rounded-2xl py-2.5 px-4 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none" 
                       id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-error text-xs" />
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-surface-container">
            <button type="submit" class="bg-surface-container-highest text-on-surface font-bold py-2.5 px-5 rounded-full shadow-sm hover:shadow hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm text-on-surface">lock_reset</span>
                Ganti Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-bold text-primary flex items-center gap-1 bg-primary/10 px-3 py-1 rounded-full"
                >
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                    Berhasil Diubah
                </p>
            @endif
        </div>
    </form>
</section>
