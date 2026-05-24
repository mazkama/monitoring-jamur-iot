<x-app-layout>
    <x-slot name="header">
        Input Panen
    </x-slot>

    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="max-w-xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-container/10 text-primary text-xs font-bold mb-4">
                    <span class="material-symbols-outlined text-sm">inventory_2</span>
                    Log Aktivitas Harian
                </div>
                <h2 class="text-4xl font-extrabold text-primary tracking-tight mb-3 font-headline">Pencatatan Hasil Panen</h2>
                <p class="text-secondary leading-relaxed">Masukkan data hasil panen jamur tiram secara akurat untuk memantau performa setiap kumbung dan kualitas produksi harian Anda.</p>
            </div>
            
            <div class="hidden lg:block">
                <div class="bg-surface-container-highest p-4 rounded-xl flex items-center gap-4 border border-outline-variant/10">
                    <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center text-primary shadow-sm">
                        <span class="material-symbols-outlined text-3xl">calendar_today</span>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-secondary">Tanggal Hari Ini</p>
                        <p class="font-bold text-primary">{{ now()->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bento Layout Form -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Primary Inputs -->
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-surface-container-low rounded-3xl p-6 md:p-8 shadow-sm">
                    <form action="{{ route('harvests.store') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tanggal -->
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-primary ml-1" for="date">Tanggal Panen</label>
                                <div class="relative group">
                                    <input class="w-full bg-white border-none rounded-2xl py-2.5 px-4 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none" 
                                           type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required/>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                                        <span class="material-symbols-outlined">event</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pilih Kumbung -->
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-primary ml-1" for="device_id">Pilih Kumbung / Perangkat</label>
                                <select class="w-full bg-white border-none rounded-2xl py-2.5 px-4 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none appearance-none cursor-pointer" 
                                        id="device_id" name="device_id">
                                    <option value="">N/A (Panen Umum / Gabungan)</option>
                                    @foreach($devices as $device)
                                        <option value="{{ $device->id }}" {{ old('device_id') == $device->id ? 'selected' : '' }}>
                                            {{ $device->name }} ({{ $device->location }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Berat Total -->
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-primary ml-1" for="amount">Berat Total (kg)</label>
                                <div class="relative">
                                    <input class="w-full bg-white border-none rounded-2xl py-4 flex-1 px-5 text-on-surface ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-primary transition-all outline-none" 
                                           type="number" step="0.01" id="amount" name="amount" placeholder="0.00" value="{{ old('amount') }}" required/>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 font-bold text-secondary">
                                        kg
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Petugas -->
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-primary ml-1">Petugas Pencatat</label>
                                <div class="relative">
                                    <input class="w-full bg-surface-container-highest border-none rounded-2xl py-2.5 px-4 text-secondary ring-1 ring-outline-variant/30 outline-none" 
                                           type="text" value="{{ auth()->user()->name }}" readonly disabled title="Otomatis diisi sistem"/>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-primary">
                                        <span class="material-symbols-outlined">person</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quality Selection -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-primary ml-1">Kualitas (Grade)</label>
                            <div class="grid grid-cols-3 gap-4">
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="quality" value="Grade A" class="peer sr-only" {{ old('quality') == 'Grade A' ? 'checked' : '' }} required/>
                                    <div class="p-4 rounded-2xl bg-white border-2 border-transparent peer-checked:border-primary peer-checked:bg-primary-container/10 transition-all text-center">
                                        <p class="text-lg font-black text-primary">Grade A</p>
                                        <p class="text-[10px] text-secondary uppercase font-bold hidden sm:block">Premium Quality</p>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="quality" value="Grade B" class="peer sr-only" {{ old('quality', 'Grade B') == 'Grade B' ? 'checked' : '' }}/>
                                    <div class="p-4 rounded-2xl bg-white border-2 border-transparent peer-checked:border-primary peer-checked:bg-primary-container/10 transition-all text-center">
                                        <p class="text-lg font-black text-primary">Grade B</p>
                                        <p class="text-[10px] text-secondary uppercase font-bold hidden sm:block">Standard Quality</p>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="quality" value="Grade C" class="peer sr-only" {{ old('quality') == 'Grade C' ? 'checked' : '' }}/>
                                    <div class="p-4 rounded-2xl bg-white border-2 border-transparent peer-checked:border-primary peer-checked:bg-primary-container/10 transition-all text-center">
                                        <p class="text-lg font-black text-primary">Grade C</p>
                                        <p class="text-[10px] text-secondary uppercase font-bold hidden sm:block">Small / Broken</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="pt-4 flex flex-col sm:flex-row gap-4">
                            <button type="submit" class="w-full sm:w-auto flex-1 bg-gradient-to-r from-primary to-primary-container text-white font-bold py-5 rounded-full shadow-lg hover:shadow-xl transition-all transform active:scale-95 flex items-center justify-center gap-3">
                                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">save</span>
                                Simpan Data Panen
                            </button>
                            <a href="{{ route('harvests.index') }}" class="w-full sm:w-auto flex-1 bg-surface-container-highest text-secondary hover:text-primary font-bold py-5 rounded-full transition-all text-center">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Visual Summary & Tips -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Decorative Visual Card -->
                <div class="relative overflow-hidden rounded-3xl h-64 shadow-lg">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDAJsp7ekI_DX6am7apsWANtaDDSE9z8tCzYQDw_aQIwQs1K28AmBRP9ENnb5eSS9LhQOtSpeONuUWCNqCmBDrJoF8xkrUoU83NnVKyPfd7mkU3XnLu5K2HRGpwZCThPqz1XQy-bijgH3PTWkkmEEXzlt1QAoQ_5hqfN4-lyfiTMM3Mr493xulMyvIUvnAVi8zL-CyZ9cl7ESeFKtCZrz3Bx-YW35Qc8QaecF7MWPjzlu0zlsS9dB9chxrtWtm9NI06l249IBuzA-0f" 
                         alt="Fresh Oyster Mushrooms"
                         class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h3 class="text-xl font-bold font-headline">Hasil Panen Segar</h3>
                        <p class="text-sm opacity-90">Kualitas terjaga dengan IoT.</p>
                    </div>
                </div>

                <!-- Helpful Tip Card -->
                <div class="bg-primary-container text-on-primary-container p-6 rounded-3xl">
                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-on-primary-container text-3xl">lightbulb</span>
                        <div>
                            <p class="font-bold mb-1">Tips Akurasi</p>
                            <p class="text-xs leading-relaxed opacity-80">Pastikan timbangan telah dikalibrasi dan kondisi jamur dalam keadaan bersih sebelum ditimbang untuk mendapatkan data berat yang akurat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
