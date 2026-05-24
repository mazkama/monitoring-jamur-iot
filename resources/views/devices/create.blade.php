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

                {{-- Identitas Device --}}
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

                {{-- Konfigurasi Threshold Default --}}
                <div class="border-t border-outline-variant/20 pt-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-primary">tune</span>
                        <h3 class="text-base font-bold text-primary">Konfigurasi Threshold Default</h3>
                        <span class="text-xs text-secondary bg-surface-container px-2 py-0.5 rounded-full">akan dikirim ke device via MQTT</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        {{-- Suhu --}}
                        <div class="bg-orange-50 rounded-2xl p-4 space-y-3 ring-1 ring-orange-200/50">
                            <div class="flex items-center gap-2 text-orange-600 font-bold text-sm">
                                <span class="material-symbols-outlined text-base">thermostat</span>
                                Suhu (°C)
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-[10px] text-orange-500 font-semibold ml-1">Min</label>
                                    <input type="number" name="temp_min" step="0.1"
                                           value="{{ old('temp_min', $defaults['temperature']['min_value']) }}"
                                           class="w-full bg-white rounded-xl py-2 px-3 text-sm ring-1 ring-orange-200 focus:ring-2 focus:ring-orange-400 outline-none text-center font-mono" required>
                                </div>
                                <div>
                                    <label class="text-[10px] text-orange-500 font-semibold ml-1">Max</label>
                                    <input type="number" name="temp_max" step="0.1"
                                           value="{{ old('temp_max', $defaults['temperature']['max_value']) }}"
                                           class="w-full bg-white rounded-xl py-2 px-3 text-sm ring-1 ring-orange-200 focus:ring-2 focus:ring-orange-400 outline-none text-center font-mono" required>
                                </div>
                            </div>
                        </div>

                        {{-- Kelembaban --}}
                        <div class="bg-blue-50 rounded-2xl p-4 space-y-3 ring-1 ring-blue-200/50">
                            <div class="flex items-center gap-2 text-blue-600 font-bold text-sm">
                                <span class="material-symbols-outlined text-base">water_drop</span>
                                Kelembaban (%)
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-[10px] text-blue-500 font-semibold ml-1">Min</label>
                                    <input type="number" name="hum_min" step="0.1"
                                           value="{{ old('hum_min', $defaults['humidity']['min_value']) }}"
                                           class="w-full bg-white rounded-xl py-2 px-3 text-sm ring-1 ring-blue-200 focus:ring-2 focus:ring-blue-400 outline-none text-center font-mono" required>
                                </div>
                                <div>
                                    <label class="text-[10px] text-blue-500 font-semibold ml-1">Max</label>
                                    <input type="number" name="hum_max" step="0.1"
                                           value="{{ old('hum_max', $defaults['humidity']['max_value']) }}"
                                           class="w-full bg-white rounded-xl py-2 px-3 text-sm ring-1 ring-blue-200 focus:ring-2 focus:ring-blue-400 outline-none text-center font-mono" required>
                                </div>
                            </div>
                        </div>

                        {{-- CO2 --}}
                        <div class="bg-green-50 rounded-2xl p-4 space-y-3 ring-1 ring-green-200/50">
                            <div class="flex items-center gap-2 text-green-600 font-bold text-sm">
                                <span class="material-symbols-outlined text-base">air</span>
                                CO₂ (ppm)
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-[10px] text-green-500 font-semibold ml-1">Min</label>
                                    <input type="number" name="co2_min" step="1"
                                           value="{{ old('co2_min', $defaults['co2']['min_value']) }}"
                                           class="w-full bg-white rounded-xl py-2 px-3 text-sm ring-1 ring-green-200 focus:ring-2 focus:ring-green-400 outline-none text-center font-mono" required>
                                </div>
                                <div>
                                    <label class="text-[10px] text-green-500 font-semibold ml-1">Max</label>
                                    <input type="number" name="co2_max" step="1"
                                           value="{{ old('co2_max', $defaults['co2']['max_value']) }}"
                                           class="w-full bg-white rounded-xl py-2 px-3 text-sm ring-1 ring-green-200 focus:ring-2 focus:ring-green-400 outline-none text-center font-mono" required>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="md:col-span-2 pt-4 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="w-full sm:w-auto flex-1 bg-gradient-to-r from-primary to-primary-container text-white font-bold py-2.5 rounded-full shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">save</span>
                        Simpan & Kirim Config ke Device
                    </button>
                    <a href="{{ route('devices.index') }}" class="w-full sm:w-auto px-6 bg-surface-container-highest text-secondary hover:text-primary font-bold py-2.5 rounded-full transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

