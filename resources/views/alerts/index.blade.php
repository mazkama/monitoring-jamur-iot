<x-app-layout>
    <x-slot name="header">
        Notifikasi
    </x-slot>

    <section class="max-w-5xl mx-auto w-full mb-8">
        @php
            $kritis = App\Models\Alert::where('status', 'unresolved')->count();
            $hariIni = App\Models\Alert::whereDate('created_at', \Carbon\Carbon::today())->count();
            // Just for UI variety, we will use $kritis for "Belum Dibaca" as well since we don't have a read tick.
        @endphp

        <!-- Summary Bento Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border-l-4 border-error">
                <p class="text-secondary text-sm font-medium mb-1">Peringatan Kritis</p>
                <div class="flex items-center justify-between">
                    <h3 class="text-3xl font-bold text-on-surface">{{ $kritis }}</h3>
                    <span class="material-symbols-outlined text-error text-4xl" data-icon="error">error</span>
                </div>
            </div>
            
            <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border-l-4 border-primary">
                <p class="text-secondary text-sm font-medium mb-1">Belum Diselesaikan</p>
                <div class="flex items-center justify-between">
                    <h3 class="text-3xl font-bold text-on-surface">{{ $kritis }}</h3>
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary" data-icon="mark_email_unread">mark_email_unread</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border-l-4 border-tertiary">
                <p class="text-secondary text-sm font-medium mb-1">Peringatan Hari Ini</p>
                <div class="flex items-center justify-between">
                    <h3 class="text-3xl font-bold text-on-surface">{{ $hariIni }}</h3>
                    <span class="material-symbols-outlined text-tertiary text-4xl" data-icon="event_note">event_note</span>
                </div>
            </div>
        </div>

        <!-- Filters + Resolve All Button -->
        <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-2 scrollbar-hide">
            <button class="px-6 py-2 bg-primary text-white rounded-full text-sm font-medium shadow-sm transition-all whitespace-nowrap">Semua Notifikasi</button>
            <div class="ml-auto flex items-center gap-3">
                @if($kritis > 0)
                    <form action="{{ route('alerts.resolve-all') }}" method="POST"
                          onsubmit="return confirm('Selesaikan semua {{ $kritis }} alert yang belum diselesaikan?')">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-2 px-5 py-2 bg-error text-white rounded-full text-sm font-bold shadow-md hover:bg-error/80 active:scale-95 transition-all whitespace-nowrap">
                            <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">done_all</span>
                            Selesaikan Semua ({{ $kritis }})
                        </button>
                    </form>
                @endif
                <span class="text-primary text-sm font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm" data-icon="history">history</span>
                    Riwayat Sistem
                </span>
            </div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 mb-4 text-sm font-bold shadow-sm">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <!-- Notification Log List -->
        <div class="space-y-3">
            @forelse($alerts as $alert)
                @if($alert->status === 'unresolved')
                    <!-- Unread Critical Alert -->
                    <div class="group relative bg-surface-container-low p-5 rounded-2xl flex flex-col md:flex-row items-start gap-4 hover:bg-surface-container transition-all cursor-pointer border border-error/20">
                        <div class="absolute right-6 top-6 flex items-center gap-3 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                            <form action="{{ route('alerts.resolve', $alert) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-primary rounded-full text-white text-xs font-bold shadow-sm hover:bg-primary-container transition-colors flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm" data-icon="check">check</span>
                                    Selesaikan
                                </button>
                            </form>
                        </div>
                        
                        <div class="flex-shrink-0 w-12 h-12 bg-error-container text-error rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined" data-icon="warning">warning</span>
                        </div>
                        <div class="flex-1 md:pr-32">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-headline font-bold text-on-surface pr-4">Peringatan {{ ucfirst($alert->sensor_type) }}</h4>
                                <span class="bg-error text-white text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider whitespace-nowrap">Kritis</span>
                                <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
                            </div>
                            <p class="text-secondary text-sm leading-relaxed mb-2">
                                Perangkat <span class="font-bold">{{ $alert->device->name }}</span> melaporkan nilai <span class="uppercase font-bold text-error">{{ str_replace('_', ' ', $alert->condition) }}</span>. Nilai tercatat: <strong>{{ $alert->value }}</strong>.
                            </p>
                            <div class="flex items-center gap-3 text-xs text-secondary/70">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs" data-icon="schedule">schedule</span>
                                    {{ $alert->created_at->diffForHumans() }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs" data-icon="location_on">location_on</span>
                                    {{ $alert->device->location ?? 'Kumbung' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Resolved Alert / Info -->
                    <div class="group relative bg-surface-container-lowest/50 opacity-80 p-5 rounded-2xl flex flex-col md:flex-row items-start gap-4 hover:bg-surface-container-low transition-all cursor-pointer">
                        <div class="flex-shrink-0 w-12 h-12 bg-primary-container/20 text-primary rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined" data-icon="check_circle">check_circle</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-headline font-semibold text-on-surface">Peringatan {{ ucfirst($alert->sensor_type) }} (Diselesaikan)</h4>
                                <span class="text-xs text-secondary bg-surface-container-highest px-2 py-0.5 rounded font-bold">Aman</span>
                            </div>
                            <p class="text-secondary text-sm leading-relaxed mb-2">
                                Peringatan untuk perangkat <span class="font-bold">{{ $alert->device->name }}</span> ({{ str_replace('_', ' ', $alert->condition) }} dengan nilai {{ $alert->value }}) telah ditandai selasai.
                            </p>
                            <div class="flex items-center gap-3 text-xs text-secondary/70">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs" data-icon="schedule">schedule</span>
                                    {{ $alert->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center py-12 bg-surface-container-low rounded-2xl border border-dashed border-outline-variant/30">
                    <span class="material-symbols-outlined text-4xl text-outline-variant mb-2">notifications_paused</span>
                    <p class="text-secondary font-medium">Bagus! Tidak ada notifikasi atau peringatan aktif saat ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $alerts->links() }}
        </div>
    </section>

</x-app-layout>
