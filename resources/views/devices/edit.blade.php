<x-app-layout>
    <x-slot name="header">
        Edit Perangkat: {{ $device->name }}
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-primary font-headline tracking-tight flex items-center gap-3">
                <span class="material-symbols-outlined text-4xl">edit_square</span>
                Ubah Data Perangkat
            </h2>
            <p class="text-secondary font-medium mt-1">Perbarui konfigurasi sensor dan gateway {{ $device->name }}.</p>
        </div>

        <div class="bg-surface-container-low rounded-3xl p-6 md:p-8 shadow-sm">
            <form action="{{ route('devices.update', $device) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-primary ml-1">ID Perangkat (Permanen)</label>
                        <div class="relative">
                            <input class="w-full bg-surface-container-highest border-none rounded-2xl py-2.5 px-5 text-secondary ring-1 ring-outline-variant/30 outline-none font-mono opacity-80 cursor-not-allowed" 
                                   type="text" value="{{ $device->id }}" readonly title="ID Perangkat tidak dapat diubah">
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-secondary opacity-50">lock</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-primary ml-1" for="name">Nama Perangkat</label>
                        <div class="relative">
                            <input class="w-full bg-white border-none rounded-2xl py-2.5 px-5 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none" 
                                   id="name" name="name" type="text" value="{{ old('name', $device->name) }}" required>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-primary ml-1" for="location">Lokasi (Kumbung)</label>
                        <div class="relative">
                            <input class="w-full bg-white border-none rounded-2xl py-2.5 px-5 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none" 
                                   id="location" name="location" type="text" value="{{ old('location', $device->location) }}" required>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-secondary">location_on</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-primary ml-1" for="status">Ubah Status</label>
                        <select class="w-full bg-white border-none rounded-2xl py-2.5 px-5 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none appearance-none cursor-pointer" 
                                id="status" name="status">
                            <option value="active" {{ $device->status === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ $device->status === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="w-full sm:w-auto flex-1 bg-gradient-to-r from-primary to-primary-container text-white font-bold py-2.5 rounded-full shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">update</span>
                        Perbarui Data
                    </button>
                    <a href="{{ route('devices.index') }}" class="w-full sm:w-auto px-6 bg-surface-container-highest text-secondary hover:text-primary font-bold py-2.5 rounded-full transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
