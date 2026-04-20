<section class="space-y-6">
    <header class="flex items-center gap-3">
        <div class="w-10 h-10 bg-error/10 rounded-full flex items-center justify-center text-error">
            <span class="material-symbols-outlined">delete_forever</span>
        </div>
        <div>
            <h2 class="text-lg font-bold text-error font-headline">
                Hapus Akun
            </h2>
            <p class="mt-1 text-xs text-error/80 leading-tight">
                Data akan terhapus secara permanen. Mohon berhati-hati.
            </p>
        </div>
    </header>

    <div class="bg-white/50 rounded-2xl p-4 border border-error/20">
        <p class="text-sm text-on-surface/80">
            Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi yang ingin Anda simpan.
        </p>
        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="mt-4 bg-error text-white font-bold py-3 px-6 rounded-full shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center gap-2"
        >
            <span class="material-symbols-outlined text-sm">warning</span>
            Hapus Akun Permanen
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-surface-container-low rounded-3xl">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-error/10 text-error flex items-center justify-center rounded-full">
                    <span class="material-symbols-outlined">warning</span>
                </div>
                <h2 class="text-xl font-bold text-on-surface font-headline">
                    Apakah Anda yakin ingin menghapus akun?
                </h2>
            </div>

            <p class="mt-1 text-sm text-secondary">
                Setelah akun Anda dihapus, seluruh akses, riwayat notifikasi, serta datanya akan hilang selamanya. Masukkan sandi Anda untuk mengkonfirmasi bahwa Anda benar-benar meyakini tindakan ini.
            </p>

            <div class="mt-6 space-y-2">
                <label class="block text-sm font-bold text-error ml-1" for="password">Kata Sandi</label>
                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="w-full bg-white border-2 border-error/50 rounded-2xl py-2.5 px-4 text-on-surface ring-0 focus:border-error focus:ring-4 focus:ring-error/20 transition-all outline-none"
                        placeholder="Masukkan sandi..."
                    />
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-error/50">key</span>
                </div>

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-error text-xs font-bold bg-error/10 px-3 py-1.5 rounded-lg w-fit" />
            </div>

            <div class="mt-8 flex justify-end gap-3 border-t border-surface-container pt-4">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-surface-container-highest text-secondary hover:text-on-surface font-bold rounded-full transition-colors">
                    Batalkan
                </button>

                <button type="submit" class="px-5 py-2.5 bg-error text-white shadow-lg shadow-error/20 font-bold rounded-full hover:bg-error-container hover:text-on-error-container transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">delete</span>
                    Hapus Selamanya
                </button>
            </div>
        </form>
    </x-modal>
</section>
