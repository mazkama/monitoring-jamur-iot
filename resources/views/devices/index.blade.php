<x-app-layout>
    <x-slot name="header">
        Perangkat IoT
    </x-slot>

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-primary font-headline">Manajemen Aset</h2>
            <p class="text-secondary font-medium">Kelola dan pantau seluruh sensor IoT di ekosistem kumbung Anda.</p>
        </div>
        <a href="{{ route('devices.create') }}" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary to-primary-container text-white rounded-full shadow-lg hover:shadow-xl hover:opacity-90 transition-all active:scale-95 group">
            <span class="material-symbols-outlined group-hover:rotate-90 transition-transform" data-icon="add">add</span>
            <span class="font-semibold">Tambah Perangkat Baru</span>
        </a>
    </div>

    @php
        $total = App\Models\Device::count();
        $active = App\Models\Device::where('status', 'active')->count();
        $inactive = $total - $active;
    @endphp

    <!-- Stats Overview (Bento Style) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-surface-container-low p-6 rounded-2xl flex flex-col gap-2 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <span class="material-symbols-outlined text-6xl" data-icon="sensors">sensors</span>
            </div>
            <span class="text-secondary text-sm font-bold uppercase tracking-wider">Total Perangkat</span>
            <span class="text-4xl font-headline font-bold text-primary">{{ $total }}</span>
        </div>
        
        <div class="bg-surface-container-low p-6 rounded-2xl flex flex-col gap-2 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <span class="material-symbols-outlined text-6xl text-primary" data-icon="check_circle">check_circle</span>
            </div>
            <span class="text-secondary text-sm font-bold uppercase tracking-wider">Aktif (Online)</span>
            <span class="text-4xl font-headline font-bold text-primary">{{ $active }}</span>
            <div class="mt-2 text-xs text-[#005762] bg-[#a9f1ff] self-start px-2 py-1 rounded-full">
                {{ $total > 0 ? round(($active/$total)*100) : 0 }}% Uptime
            </div>
        </div>

        <div class="bg-surface-container-low p-6 rounded-2xl flex flex-col gap-2 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <span class="material-symbols-outlined text-6xl text-error" data-icon="warning">warning</span>
            </div>
            <span class="text-secondary text-sm font-bold uppercase tracking-wider">Perlu Perhatian</span>
            <span class="text-4xl font-headline font-bold text-error">{{ $inactive }}</span>
            <div class="mt-2 text-xs text-error bg-error-container self-start px-2 py-1 rounded-full">Device Mati</div>
        </div>
    </div>

    <!-- Main Table Section -->
    <div class="bg-surface-container-lowest rounded-3xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low">
                        <th class="px-6 py-3 text-secondary font-bold text-xs md:text-sm uppercase tracking-widest">ID Perangkat</th>
                        <th class="px-6 py-3 text-secondary font-bold text-xs md:text-sm uppercase tracking-widest">Nama Lokasi</th>
                        <th class="px-6 py-3 text-secondary font-bold text-xs md:text-sm uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-3 text-secondary font-bold text-xs md:text-sm uppercase tracking-widest text-center">Transmisi Data</th>
                        <th class="px-6 py-3 text-secondary font-bold text-xs md:text-sm uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container text-sm">
                    @forelse($devices as $device)
                    <tr class="hover:bg-surface-container/30 transition-colors">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-secondary/10 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-secondary text-lg" data-icon="router">router</span>
                                </div>
                                <span class="font-mono font-semibold text-primary">{{ $device->id }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex flex-col">
                                <span class="font-bold text-on-surface">{{ $device->name }}</span>
                                <span class="text-[11px] md:text-xs text-secondary">{{ $device->location }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if($device->status == 'active')
                                <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-error/10 text-error px-3 py-1 rounded-full text-xs font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                                    Mati
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm text-center">
                            <form action="{{ route('devices.toggle', $device) }}" method="POST">
                                @csrf
                                <button type="submit" class="{{ $device->status == 'active' ? 'text-primary border-primary' : 'text-secondary border-secondary' }} border px-3 py-1 rounded-full text-xs font-bold hover:bg-surface-container transition-colors">
                                    Ganti Status
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-3 text-right space-x-1">
                            <a href="{{ route('devices.edit', $device) }}" title="Edit" class="inline-block p-1.5 text-secondary hover:bg-secondary/10 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[20px]" data-icon="edit">edit</span>
                            </a>
                            <form action="{{ route('devices.destroy', $device) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus perangkat ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete" class="p-1.5 text-error hover:bg-error/10 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[20px]" data-icon="delete">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-secondary">
                            Belum ada perangkat yang terdaftar di ekosistem ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="px-6 py-3 border-t border-surface-container bg-surface-container-low/50">
            {{ $devices->links() }}
        </div>
    </div>

</x-app-layout>
