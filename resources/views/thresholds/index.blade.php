<x-app-layout>
    <x-slot name="header">
        Pengaturan Threshold
    </x-slot>

    @php
        $devicesRaw = \App\Models\Device::all();
        $thresholds = \App\Models\Threshold::all()->groupBy('device_id');

        $deviceData = $devicesRaw->map(function($device) use ($thresholds) {
            $dt = $thresholds->get($device->id, collect())->keyBy('sensor_type');
            return [
                'id'       => $device->id,
                'name'     => $device->name,
                'location' => $device->location,
                'status'   => $device->status,
                'tMin'     => (float)($dt->get('temperature')->min_value ?? 20.0),
                'tMax'     => (float)($dt->get('temperature')->max_value ?? 35.0),
                'hMin'     => (float)($dt->get('humidity')->min_value ?? 60),
                'hMax'     => (float)($dt->get('humidity')->max_value ?? 90),
                'cMin'     => (float)($dt->get('co2')->min_value ?? 400),
                'cMax'     => (float)($dt->get('co2')->max_value ?? 1000),
            ];
        });
    @endphp

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-10">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-10">
            <div>
                <h1 class="text-4xl font-extrabold tracking-[-0.03em] text-primary mb-2 font-headline">Pengaturan Ambang Batas (Thresholds)</h1>
                <p class="text-on-surface-variant max-w-xl text-sm leading-relaxed">Konfigurasikan batas parameter lingkungan untuk setiap chamber. Sistem akan memicu notifikasi jika sensor mendeteksi nilai di luar jangkauan ini.</p>
            </div>
            <div class="flex gap-3 shrink-0">
                <button onclick="window.location.reload()" class="px-6 py-3 bg-surface-container-high text-primary font-semibold rounded-xl hover:bg-surface-container-highest transition-all duration-300">Reset Semua</button>
                <button type="button" onclick="document.getElementById('master-submit-all')?.click()" class="px-6 py-3 bg-gradient-to-br from-[#2e5227] to-[#456b3d] text-white font-bold rounded-xl shadow-lg shadow-primary/20 active:scale-95 transition-all">Simpan Semua</button>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-surface-container-low p-4 rounded-2xl mb-8 flex items-center justify-between border border-outline-variant/5">
            <div class="flex gap-4">
                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg text-sm font-medium text-on-surface-variant shadow-sm cursor-pointer hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-sm">filter_list</span>
                    Semua Chamber
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg text-sm font-medium text-on-surface-variant shadow-sm cursor-pointer hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-sm">sort</span>
                    Urutkan: Nama
                </div>
            </div>
            <p class="text-sm font-medium text-on-surface-variant/60">{{ count($deviceData) }} Perangkat Aktif Terdeteksi</p>
        </div>

        <!-- Configuration Bento Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            @foreach($deviceData as $device)
                <div class="bg-white rounded-3xl p-8 shadow-[0_4px_20px_rgba(46,82,39,0.03)] border border-outline-variant/5 transition-all hover:shadow-[0_8px_40px_rgba(46,82,39,0.06)] group">
                    <div class="flex justify-between items-start mb-8">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-primary/5 rounded-2xl text-primary group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined">{{ $loop->iteration % 2 == 0 ? 'cloud' : 'egg_alt' }}</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold tracking-tight font-headline">{{ $device['name'] }}</h3>
                                <p class="text-xs font-medium text-on-surface-variant/60 uppercase tracking-widest">{{ $device['location'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 bg-tertiary-container/10 text-tertiary px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full {{ $device['status'] === 'active' ? 'bg-tertiary animate-pulse' : 'bg-slate-400' }}"></span>
                            {{ $device['status'] === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </div>
                    </div>

                    <form action="{{ route('thresholds.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="device_id" value="{{ $device['id'] }}">

                        <!-- Suhu Control -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-[18px]">thermostat</span>
                                <span class="font-bold text-on-surface">Suhu (°C)</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-surface-container-low rounded-2xl p-4">
                                    <span class="text-[10px] font-bold text-on-surface-variant/60 uppercase tracking-widest block mb-1">Min Ambang</span>
                                    <input name="thresholds[temperature][min_value]" class="w-full bg-transparent border-none p-0 text-2xl font-bold font-headline focus:ring-0" step="0.1" type="number" value="{{ $device['tMin'] }}"/>
                                </div>
                                <div class="bg-surface-container-low rounded-2xl p-4">
                                    <span class="text-[10px] font-bold text-on-surface-variant/60 uppercase tracking-widest block mb-1">Max Ambang</span>
                                    <input name="thresholds[temperature][max_value]" class="w-full bg-transparent border-none p-0 text-2xl font-bold font-headline focus:ring-0" step="0.1" type="number" value="{{ $device['tMax'] }}"/>
                                </div>
                            </div>
                            <input type="hidden" name="thresholds[temperature][is_active]" value="1">
                        </div>

                        <!-- Kelembapan Control -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-[18px]">humidity_percentage</span>
                                <span class="font-bold text-on-surface">Kelembapan (%)</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-surface-container-low rounded-2xl p-4">
                                    <span class="text-[10px] font-bold text-on-surface-variant/60 uppercase tracking-widest block mb-1">Min Ambang</span>
                                    <input name="thresholds[humidity][min_value]" class="w-full bg-transparent border-none p-0 text-2xl font-bold font-headline focus:ring-0" type="number" value="{{ $device['hMin'] }}"/>
                                </div>
                                <div class="bg-surface-container-low rounded-2xl p-4">
                                    <span class="text-[10px] font-bold text-on-surface-variant/60 uppercase tracking-widest block mb-1">Max Ambang</span>
                                    <input name="thresholds[humidity][max_value]" class="w-full bg-transparent border-none p-0 text-2xl font-bold font-headline focus:ring-0" type="number" value="{{ $device['hMax'] }}"/>
                                </div>
                            </div>
                            <input type="hidden" name="thresholds[humidity][is_active]" value="1">
                        </div>

                        <!-- CO2 Control -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-[18px]">co2</span>
                                <span class="font-bold text-on-surface">CO2 (ppm)</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-surface-container-low rounded-2xl p-4">
                                    <span class="text-[10px] font-bold text-on-surface-variant/60 uppercase tracking-widest block mb-1">Min Ambang</span>
                                    <input name="thresholds[co2][min_value]" class="w-full bg-transparent border-none p-0 text-2xl font-bold font-headline focus:ring-0" type="number" value="{{ $device['cMin'] }}"/>
                                </div>
                                <div class="bg-surface-container-low rounded-2xl p-4">
                                    <span class="text-[10px] font-bold text-on-surface-variant/60 uppercase tracking-widest block mb-1">Max Ambang</span>
                                    <input name="thresholds[co2][max_value]" class="w-full bg-transparent border-none p-0 text-2xl font-bold font-headline focus:ring-0" type="number" value="{{ $device['cMax'] }}"/>
                                </div>
                            </div>
                            <input type="hidden" name="thresholds[co2][is_active]" value="1">
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" onclick="window.location.reload()" class="flex-1 py-3 text-sm font-bold text-primary bg-surface-container-low rounded-xl hover:bg-surface-container-high transition-colors">Reset</button>
                            <button type="submit" class="flex-[2] py-3 text-sm font-bold text-white bg-primary rounded-xl hover:shadow-lg hover:shadow-primary/10 transition-all active:scale-95">Update Unit</button>
                        </div>
                    </form>
                </div>
            @endforeach

            <!-- Visual Asset Card - Asymmetric Layout Element -->
            <div class="xl:col-span-2 relative overflow-hidden rounded-3xl h-64 bg-primary-container flex items-center p-12">
                <div class="relative z-10 max-w-md">
                    <h2 class="text-3xl font-black text-on-primary-container leading-tight mb-4 tracking-tighter font-headline">Optimalkan Pertumbuhan Mycelium Anda</h2>
                    <p class="text-on-primary-container/80 text-sm font-medium mb-6">Pastikan ambang batas sesuai dengan spesies jamur yang sedang dibudidayakan. Perubahan akan segera diterapkan pada sensor IoT.</p>
                    <button class="flex items-center gap-2 text-on-primary-container font-bold group">
                        Lihat Panduan Spesies 
                        <span class="material-symbols-outlined transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </button>
                </div>
                <div class="absolute right-0 top-0 h-full w-1/2 overflow-hidden pointer-events-none opacity-40 mix-blend-overlay">
                    <img alt="Mushroom cultivation" class="h-full w-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC_cXbZ0rfKdTe47UKT6Jv3iaWQ7AXYBD3MpedDrmFwSvnJW9h1lF8yF6AIWb243HbtV2n-lIv_ED3V9tzob_BDhsy3QdtCssHb--yfKE2vspnpXnnjnaZXxzZbXEbuHnZN-kNX0iVUf0tI1lG8-kmQJNycMLTZIMnEBUZ76zW5MWnY01aq-XT07JwKZ3MbbNeKydgSjMFD6BLYpfGQy7TGJdeBGnhrSXi31Ab8uCSP76SuCyMRKKteM23hfcydJev-Hsyq-HjDAuA"/>
                </div>
                <div class="absolute inset-0 bg-gradient-to-r from-primary-container via-primary-container/90 to-transparent"></div>
            </div>
        </div>
    </div>

    <!-- FAB Action Button -->
    <a href="{{ route('devices.index') }}" class="fixed bottom-10 right-10 w-16 h-16 bg-gradient-to-br from-[#2e5227] to-[#456b3d] text-white rounded-2xl shadow-[0_10px_30px_rgba(46,82,39,0.3)] flex items-center justify-center hover:scale-105 active:scale-95 transition-all z-50 group">
        <span class="material-symbols-outlined text-3xl group-hover:rotate-90 transition-all duration-500">add</span>
    </a>
</x-app-layout>
