<x-app-layout>
    <x-slot name="header">
        Laporan Panen
    </x-slot>

    <!-- Top Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-primary font-headline tracking-tight">Laporan Panen</h2>
        <p class="text-sm text-secondary font-medium">Analisis akumulasi pertumbuhan dan hasil produksi</p>
    </div>

    <!-- Stats & Filters -->
    <section class="space-y-8 mb-8">
        <!-- Filter Form -->
        <div class="bg-surface-container-low p-6 rounded-3xl mb-6">
            <form action="{{ route('harvests.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4">
                <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl ring-1 ring-outline-variant/30 w-full md:w-auto">
                    <span class="material-symbols-outlined text-secondary text-sm">calendar_month</span>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="border-none focus:ring-0 p-0 text-sm w-full">
                </div>
                <span class="text-secondary font-bold text-sm">Hingga</span>
                <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl ring-1 ring-outline-variant/30 w-full md:w-auto">
                    <span class="material-symbols-outlined text-secondary text-sm">calendar_month</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="border-none focus:ring-0 p-0 text-sm w-full">
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto ml-auto">
                    <button type="submit" class="flex-1 md:flex-none bg-primary text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow hover:bg-primary-container transition-colors">
                        Filter Data
                    </button>
                    <a href="{{ route('harvests.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-bold text-secondary hover:bg-surface-container-highest transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        @php
            $totalBerat = App\Models\Harvest::sum('amount');
            $gradeA = App\Models\Harvest::where('quality', 'like', '%Grade A%')->count();
            $totalCount = App\Models\Harvest::count();
            $percentageA = $totalCount > 0 ? round(($gradeA / $totalCount) * 100) : 0;
            $activeDevice = App\Models\Device::whereHas('harvests')->withCount('harvests')->orderBy('harvests_count', 'desc')->first();
        @endphp

        <!-- Bento Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-2 bg-gradient-to-br from-primary to-primary-container p-6 rounded-3xl text-white shadow-xl flex flex-col justify-between">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-white/80 font-medium text-sm">Total Akumulasi Panen</p>
                        <h3 class="text-4xl font-black mt-1 font-headline">{{ number_format($totalBerat, 1) }} <span class="text-xl font-normal opacity-80">Kg</span></h3>
                    </div>
                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-md">
                        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">eco</span>
                    </div>
                </div>
                <div class="mt-8 flex items-center gap-2 text-sm bg-white/10 w-fit px-3 py-1 rounded-full">
                    <span class="material-symbols-outlined text-xs">trending_up</span>
                    <span>Berdasarkan total rekaman: {{ $totalCount }} data</span>
                </div>
            </div>

            <div class="bg-surface-container-lowest p-6 rounded-3xl shadow-sm flex flex-col justify-between border border-primary/5">
                <div>
                    <p class="text-secondary font-medium text-sm">Persentase Grade A</p>
                    <h3 class="text-3xl font-bold text-primary mt-1 font-headline">{{ $percentageA }}%</h3>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <div class="w-full bg-surface-container rounded-full h-2">
                        <div class="bg-primary h-2 rounded-full" style="width: {{ $percentageA }}%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-surface-container-highest p-6 rounded-3xl shadow-sm border border-outline-variant/10 flex flex-col justify-between">
                <div>
                    <p class="text-secondary font-medium text-sm">Kumbung Teraktif</p>
                    <h3 class="text-3xl font-bold text-on-surface mt-1 font-headline">{{ $activeDevice ? substr($activeDevice->id, 0, 8) : 'N/A' }}</h3>
                </div>
                <p class="text-xs mt-2 text-secondary font-medium">{{ $activeDevice ? $activeDevice->name : '-' }}</p>
            </div>
        </div>
    </section>

    <!-- Table Section -->
    <div class="bg-surface-container-lowest rounded-3xl overflow-hidden shadow-sm border border-outline-variant/10">
        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-surface-container">
            <div>
                <h4 class="text-xl font-bold text-on-surface font-headline">Riwayat Panen Lengkap</h4>
                <p class="text-sm text-secondary">Data historis setiap catatan hasil produksi</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('harvests.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-full font-bold text-sm hover:shadow-lg transition-all active:scale-95">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Tambah Panen
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-left">
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider">Perangkat Sumber</th>
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider">Total Berat</th>
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider">Kualitas</th>
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider">Petugas</th>
                        <th class="px-6 py-4 text-xs font-bold text-secondary uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container text-sm">
                    @forelse($harvests as $harvest)
                    <tr class="hover:bg-background transition-colors">
                        <td class="px-6 py-4 font-medium text-on-surface">
                            {{ \Carbon\Carbon::parse($harvest->date)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-secondary">
                            {{ $harvest->device->name ?? 'Gabungan' }}
                        </td>
                        <td class="px-6 py-4 font-bold text-primary">
                            {{ number_format($harvest->amount, 2) }} Kg
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5">
                                @if(str_contains(strtolower($harvest->quality), 'grade a'))
                                    <div class="w-2 h-2 rounded-full bg-primary"></div>
                                @elseif(str_contains(strtolower($harvest->quality), 'grade b'))
                                    <div class="w-2 h-2 rounded-full bg-tertiary"></div>
                                @else
                                    <div class="w-2 h-2 rounded-full bg-error"></div>
                                @endif
                                <span class="font-bold">{{ $harvest->quality }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-secondary">
                            {{ $harvest->user->name ?? 'Sistem' }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('harvests.edit', $harvest) }}" class="inline-block p-2 text-secondary hover:bg-secondary/10 hover:text-primary transition-colors rounded-lg" title="Edit">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form action="{{ route('harvests.destroy', $harvest) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus catatan panen ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-error hover:bg-error/10 transition-colors rounded-lg" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-secondary">
                            Belum ada data riwayat panen untuk rentang waktu ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-surface-container-low/50 border-t border-surface-container">
            {{ $harvests->links() }}
        </div>
    </div>
</x-app-layout>
