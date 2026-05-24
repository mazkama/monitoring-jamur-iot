<x-app-layout>
    <x-slot name="header">
        Profil Pengguna
    </x-slot>

    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-primary font-headline tracking-tight flex items-center gap-3">
                <span class="material-symbols-outlined text-4xl">account_circle</span>
                Pengaturan Profil Pribadi
            </h2>
            <p class="text-secondary font-medium mt-1">Kelola data personal, preferensi keamanan, dan kredensial akun Anda di sistem IoT.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Porsi Kiri (Informasi Profil & Update) -->
            <div class="space-y-8">
                <div class="bg-surface-container-low rounded-3xl p-6 md:p-8 shadow-sm border border-outline-variant/10">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Porsi Kanan (Password & Delete) -->
            <div class="space-y-8">
                <div class="bg-surface-container-low rounded-3xl p-6 md:p-8 shadow-sm border border-outline-variant/10">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="bg-error-container/10 rounded-3xl p-6 md:p-8 shadow-sm border border-error/20">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
