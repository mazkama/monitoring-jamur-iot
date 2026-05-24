<section>
    <header class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center text-primary">
            <span class="material-symbols-outlined">person</span>
        </div>
        <div>
            <h2 class="text-lg font-bold text-primary font-headline">
                Informasi Profil
            </h2>
            <p class="mt-1 text-xs text-secondary">
                Perbarui nama lengkap dan alamat email akun Anda.
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-2">
            <label class="block text-sm font-bold text-primary ml-1" for="name">Nama Lengkap</label>
            <div class="relative">
                <input class="w-full bg-white border-none rounded-2xl py-2.5 px-4 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none" 
                       id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-secondary">badge</span>
            </div>
            <x-input-error class="mt-2 text-error text-xs" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-bold text-primary ml-1" for="email">Alamat Email</label>
            <div class="relative">
                <input class="w-full bg-white border-none rounded-2xl py-2.5 px-4 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none" 
                       id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-secondary">email</span>
            </div>
            <x-input-error class="mt-2 text-error text-xs" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-tertiary-container/10 rounded-xl border border-tertiary-container/30">
                    <p class="text-sm font-medium text-tertiary-container flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">warning</span>
                        {{ __('Alamat email Anda belum terverifikasi.') }}
                    </p>
                    <button form="send-verification" class="mt-2 w-full text-xs font-bold bg-white px-3 py-2 border border-outline-variant/30 rounded-lg text-secondary hover:text-primary transition-colors">
                        {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-xs text-primary flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">check_circle</span>
                            {{ __('Tautan verifikasi baru telah dikirim ke email.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-surface-container">
            <button type="submit" class="bg-primary text-white font-bold py-2.5 px-5 rounded-full shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm text-white">save</span>
                Simpan
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-bold text-primary flex items-center gap-1 bg-primary/10 px-3 py-1 rounded-full"
                >
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                    Tersimpan
                </p>
            @endif
        </div>
    </form>
</section>
