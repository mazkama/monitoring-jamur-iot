<x-app-layout>
    <x-slot name="header">
        Tambah Perangkat Baru
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-primary font-headline tracking-tight flex items-center gap-3">
                <span class="material-symbols-outlined text-4xl">add_box</span>
                Tambah Perangkat IoT
            </h2>
            <p class="text-secondary font-medium mt-1">Daftarkan sensor dan gateway baru ke sistem Mycology IoT secara unik.</p>
        </div>

        <div class="bg-surface-container-low rounded-3xl p-6 md:p-8 shadow-sm">
            <form action="{{ route('devices.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-primary ml-1" for="id">Custom ID Perangkat</label>
                        <div class="relative">
                            <input class="w-full bg-white border-none rounded-2xl py-2.5 px-5 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none font-mono" 
                                   id="id" name="id" type="text" value="{{ old('id') }}" placeholder="Contoh: KMB-01" required>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-secondary">qr_code</span>
                        </div>
                        <p class="text-[10px] text-secondary ml-2">Gunakan format unik untuk membedakan antar alat.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-primary ml-1" for="name">Nama Perangkat</label>
                        <div class="relative">
                            <input class="w-full bg-white border-none rounded-2xl py-2.5 px-5 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none" 
                                   id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Contoh: Sensor Node A" required>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-secondary">sensors</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-primary ml-1" for="location">Lokasi (Kumbung)</label>
                        <div class="relative">
                            <input class="w-full bg-white border-none rounded-2xl py-2.5 px-5 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none" 
                                   id="location" name="location" type="text" value="{{ old('location') }}" placeholder="Contoh: Kumbung Timur Rak 1" required>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-secondary">location_on</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-primary ml-1" for="status">Status Awal</label>
                        <select class="w-full bg-white border-none rounded-2xl py-2.5 px-5 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none appearance-none cursor-pointer" 
                                id="status" name="status">
                            <option value="active">Aktif (Monitoring Berjalan)</option>
                            <option value="inactive">Non-Aktif (Maintenance)</option>
                        </select>
                    </div>
                </div>

                <div class="md:col-span-2 pt-4 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="w-full sm:w-auto flex-1 bg-gradient-to-r from-primary to-primary-container text-white font-bold py-2.5 rounded-full shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">save</span>
                        Simpan Perangkat
                    </button>
                    <a href="{{ route('devices.index') }}" class="w-full sm:w-auto px-6 bg-surface-container-highest text-secondary hover:text-primary font-bold py-2.5 rounded-full transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
