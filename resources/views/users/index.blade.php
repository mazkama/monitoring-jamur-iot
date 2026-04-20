<x-app-layout>
    <x-slot name="header">
        Kelola User
    </x-slot>

    <!-- Header Section with Actions -->
    <section class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div class="space-y-1">
            <h2 class="text-3xl font-extrabold text-primary tracking-tight font-headline">Manajemen Pengguna</h2>
            <p class="text-secondary font-body">Atur hak akses dan profil petugas sistem IoT Mycology.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex bg-surface-container p-1 rounded-xl">
                <a href="{{ route('users.index') }}" class="px-4 py-2 text-sm font-semibold bg-surface-container-lowest text-primary rounded-lg shadow-sm">Semua</a>
                <a href="{{ route('users.index', ['role' => 'admin']) }}" class="px-4 py-2 text-sm font-medium text-secondary hover:text-primary transition-colors">Admin</a>
                <a href="{{ route('users.index', ['role' => 'staff']) }}" class="px-4 py-2 text-sm font-medium text-secondary hover:text-primary transition-colors">Petugas</a>
            </div>
            <a href="{{ route('users.create') }}" class="bg-gradient-to-br from-primary to-primary-container text-white px-5 py-2.5 rounded-full flex items-center gap-2 font-semibold shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-95 transition-all">
                <span class="material-symbols-outlined" data-icon="person_add">person_add</span>
                Tambah User
            </a>
        </div>
    </section>

        @php
        $totalUsers = App\Models\User::count();
        $adminUsers = App\Models\User::where('role', 'admin')->count();
    @endphp

    <!-- Bento Stats Section -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-surface-container-low p-6 rounded-3xl flex items-center gap-5 border border-primary/5">
            <div class="w-14 h-14 rounded-2xl bg-primary-fixed text-on-primary-fixed flex items-center justify-center shadow-inner">
                <span class="material-symbols-outlined text-3xl" data-icon="group">group</span>
            </div>
            <div>
                <p class="text-sm font-medium text-secondary">Total User</p>
                <h3 class="text-2xl font-black text-primary">{{ $totalUsers }} <span class="text-sm font-medium opacity-60">Terdaftar</span></h3>
            </div>
        </div>
        
        <div class="bg-surface-container-low p-6 rounded-3xl flex items-center gap-5 border border-primary/5">
            <div class="w-14 h-14 rounded-2xl bg-secondary-fixed text-on-secondary-fixed flex items-center justify-center shadow-inner">
                <span class="material-symbols-outlined text-3xl" data-icon="shield_person">shield_person</span>
            </div>
            <div>
                <p class="text-sm font-medium text-secondary">Administrator</p>
                <h3 class="text-2xl font-black text-secondary">{{ $adminUsers }} <span class="text-sm font-medium opacity-60">Level</span></h3>
            </div>
        </div>

        <div class="bg-primary/5 p-6 rounded-3xl flex flex-col justify-center gap-2 relative overflow-hidden group cursor-pointer border border-primary/10">
            <div class="absolute -right-4 -bottom-4 text-primary/10 group-hover:scale-110 transition-transform duration-500">
                <span class="material-symbols-outlined text-8xl" data-icon="security">security</span>
            </div>
            <p class="text-sm font-bold text-primary tracking-wide">KEAMANAN SISTEM</p>
            <p class="text-xs text-secondary/80 max-w-[180px]">Semua aktivitas pengguna dicatat dalam log sistem otomatis.</p>
        </div>
    </section>

    <!-- Table Container -->
    <div class="bg-surface-container-lowest rounded-3xl p-2 shadow-[0_8px_30px_rgb(43,88,37,0.04)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low/50">
                        <th class="px-6 py-5 text-sm font-bold text-primary uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-5 text-sm font-bold text-primary uppercase tracking-wider">Peran</th>
                        <th class="px-6 py-5 text-sm font-bold text-primary uppercase tracking-wider">Kontak</th>
                        <th class="px-6 py-5 text-sm font-bold text-primary uppercase tracking-wider">Status</th>
                        <th class="px-6 py-5 text-sm font-bold text-primary uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-low">
                    @forelse($users as $user)
                    <tr class="hover:bg-surface-container-low/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl overflow-hidden bg-primary-container/10 flex items-center justify-center text-primary-container">
                                    <span class="material-symbols-outlined font-bold">person</span>
                                </div>
                                <div>
                                    <p class="font-bold text-on-background">{{ $user->name }}</p>
                                    <p class="text-xs text-secondary/60">Bergabung {{ $user->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role == 'admin')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-tertiary-container/10 text-tertiary text-xs font-bold">
                                    <span class="material-symbols-outlined text-[14px]" data-icon="shield">shield</span>
                                    ADMIN
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-secondary-container text-on-secondary-container text-xs font-bold">
                                    <span class="material-symbols-outlined text-[14px]" data-icon="badge">badge</span>
                                    PETUGAS
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-secondary">{{ $user->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-[10px] font-black tracking-widest uppercase bg-primary-container text-white">AKTIF</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-50 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('users.edit', $user) }}" class="p-2 text-secondary hover:text-primary hover:bg-primary/5 rounded-full transition-all" title="Edit">
                                    <span class="material-symbols-outlined" data-icon="edit">edit</span>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-secondary hover:text-error hover:bg-error/5 rounded-full transition-all" title="Hapus">
                                        <span class="material-symbols-outlined" data-icon="delete">delete</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-secondary">Belum ada data pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination / Footer -->
        <div class="px-6 py-4 border-t border-surface-container bg-surface-container-low/50">
            {{ $users->links() }}
        </div>
    </div>



</x-app-layout>
