<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Mycology IoT') }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2e5227",
                        "on-secondary-fixed": "#001f2a", "surface-container": "#ecefea", "tertiary": "#185066",
                        "on-primary-fixed": "#002201", "outline-variant": "#c2c8c0", "tertiary-container": "#35687f",
                        "surface-dim": "#d5dcce", "primary-fixed-dim": "#a1d494", "secondary": "#506447",
                        "surface-container-lowest": "#ffffff", "secondary-fixed-dim": "#b6cdaa",
                        "surface-container-low": "#f2f4ef", "background": "#f8faf5", "primary-container": "#456b3d",
                        "secondary-fixed": "#d2eac5", "error": "#ba1a1a", "surface-container-highest": "#dee5d6",
                        "on-secondary": "#ffffff", "on-tertiary-container": "#a9f1ff", "on-error-container": "#93000a",
                        "inverse-on-surface": "#eff1ec", "secondary-container": "#d2eac5", "on-secondary-fixed-variant": "#2e4b57",
                        "surface-bright": "#f5fced", "tertiary-fixed": "#9eefff", "inverse-primary": "#a1d494",
                        "on-primary": "#ffffff", "on-error": "#ffffff", "on-background": "#171d14",
                        "error-container": "#ffdad6", "on-secondary-container": "#4a6774", "surface-tint": "#3b6934",
                        "inverse-surface": "#2c3228", "primary-fixed": "#bcf0ae", "on-tertiary-fixed": "#001f24",
                        "surface-variant": "#dee5d6", "surface": "#f5fced", "on-surface": "#171d14",
                        "outline": "#737971", "on-tertiary": "#ffffff", "on-surface-variant": "#424842",
                        "on-primary-container": "#bef3b0", "on-primary-fixed-variant": "#23501e",
                        "tertiary-fixed-dim": "#55d7ed", "on-tertiary-fixed-variant": "#004e59", "surface-container-high": "#e3ebdc"
                    },
                    fontFamily: {
                        headline: ["Plus Jakarta Sans"], body: ["Inter"], label: ["Inter"]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-headline { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-panel { background: rgba(245, 252, 237, 0.7); backdrop-filter: blur(16px); }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-background text-on-background min-h-screen flex selection:bg-primary-fixed selection:text-on-primary-fixed">

    <!-- Desktop Sidebar -->
    <aside class="hidden md:flex fixed left-0 top-0 h-full w-64 flex-col p-4 z-40 bg-[#f5fced] dark:bg-emerald-950 text-sm font-medium shadow-[4px_0_24px_rgba(43,88,37,0.06)]">
        <div class="flex items-center gap-3 px-4 py-6 mb-4">
            <div class="w-10 h-10 bg-primary-container rounded-xl flex items-center justify-center text-white">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">eco</span>
            </div>
            <div>
                <h2 class="text-[#2b5825] font-black text-lg leading-none">Mycology IoT</h2>
                <p class="text-[10px] text-secondary/70 uppercase tracking-widest mt-1">Sistem Monitoring Jamur</p>
            </div>
        </div>
        
        <nav class="flex-1 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:translate-x-1 transition-transform duration-200 cursor-pointer active:scale-98 {{ request()->routeIs('dashboard') ? 'bg-[#43713b] text-white shadow-sm' : 'text-[#466270] hover:bg-[#43713b]/5' }}">
                <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('devices.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:translate-x-1 transition-transform duration-200 cursor-pointer active:scale-98 {{ request()->routeIs('devices.*') ? 'bg-[#43713b] text-white shadow-sm' : 'text-[#466270] hover:bg-[#43713b]/5' }}">
                <span class="material-symbols-outlined" data-icon="settings_input_component">router</span>
                <span>Perangkat</span>
            </a>
            @if(auth()->user() && auth()->user()->role === 'admin')
            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:translate-x-1 transition-transform duration-200 cursor-pointer active:scale-98 {{ request()->routeIs('users.*') ? 'bg-[#43713b] text-white shadow-sm' : 'text-[#466270] hover:bg-[#43713b]/5' }}">
                <span class="material-symbols-outlined" data-icon="group">group</span>
                <span>Kelola User</span>
            </a>
            @endif
            <a href="{{ route('harvests.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:translate-x-1 transition-transform duration-200 cursor-pointer active:scale-98 {{ request()->routeIs('harvests.create') ? 'bg-[#43713b] text-white shadow-sm' : 'text-[#466270] hover:bg-[#43713b]/5' }}">
                <span class="material-symbols-outlined" data-icon="add_circle">add_circle</span>
                <span>Input Panen</span>
            </a>
            <a href="{{ route('harvests.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:translate-x-1 transition-transform duration-200 cursor-pointer active:scale-98 {{ request()->routeIs('harvests.index') ? 'bg-[#43713b] text-white shadow-sm' : 'text-[#466270] hover:bg-[#43713b]/5' }}">
                <span class="material-symbols-outlined" data-icon="analytics">analytics</span>
                <span>Laporan Panen</span>
            </a>
            <a href="{{ route('thresholds.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:translate-x-1 transition-transform duration-200 cursor-pointer active:scale-98 {{ request()->routeIs('thresholds.*') ? 'bg-[#43713b] text-white shadow-sm' : 'text-[#466270] hover:bg-[#43713b]/5' }}">
                <span class="material-symbols-outlined" data-icon="tune">tune</span>
                <span>Thresholds</span>
            </a>
            <a href="{{ route('alerts.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:translate-x-1 transition-transform duration-200 cursor-pointer active:scale-98 {{ request()->routeIs('alerts.*') ? 'bg-[#43713b] text-white shadow-sm' : 'text-[#466270] hover:bg-[#43713b]/5' }}">
                <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                <span>Notifikasi</span>
                @if(App\Models\Alert::where('status', 'unresolved')->count() > 0)
                    <span class="ml-auto w-2 h-2 bg-error rounded-full"></span>
                @endif
            </a>
        </nav>
        
        <div class="mt-auto pt-6 border-t border-surface-container">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 text-[#466270] px-4 py-3 hover:bg-error/5 hover:text-error rounded-lg transition-colors">
                    <span class="material-symbols-outlined" data-icon="logout">logout</span>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="md:ml-64 w-full min-h-screen flex flex-col relative z-0">
        <!-- Top Navigation Bar -->
        <header class="flex justify-between items-center px-6 py-4 w-full bg-[#f5fced] dark:bg-emerald-950/20 bg-surface-container-low shadow-none z-30 sticky top-0 border-b border-surface-container/50">
            <div class="flex items-center gap-4">
                <button class="md:hidden p-2 hover:bg-[#43713b]/10 rounded-full transition-colors">
                    <span class="material-symbols-outlined text-[#2b5825]" data-icon="menu">menu</span>
                </button>
                <!-- Title removed as per user request to keep it clean and empty -->
            </div>
            
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center bg-surface-container rounded-full px-4 py-2">
                    <span class="material-symbols-outlined text-outline text-sm mr-2" data-icon="search">search</span>
                    <input class="bg-transparent border-none focus:ring-0 text-sm text-on-surface p-0 w-48" placeholder="Cari..." type="text"/>
                </div>
                <div class="flex items-center gap-2 border-l border-outline-variant/30 pl-4 ml-2">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-primary">{{ auth()->user()->name ?? 'Guest' }}</p>
                        <p class="text-xs text-secondary">{{ auth()->check() ? ucfirst(auth()->user()->role) : '' }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-full overflow-hidden border-2 border-primary-fixed ml-2 bg-surface-container-high flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">person</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 md:px-8 pt-4">
            @if(session('success'))
            <div class="bg-primary/10 border border-primary text-primary px-4 py-3 rounded-xl relative mb-4 flex gap-3 items-center" role="alert">
                <span class="material-symbols-outlined text-sm">check_circle</span>
                <span class="block sm:inline text-sm font-bold">{{ session('success') }}</span>
            </div>
            @endif
            @if(session('error'))
            <div class="bg-error/10 border border-error text-error px-4 py-3 rounded-xl relative mb-4 flex gap-3 items-center" role="alert">
                <span class="material-symbols-outlined text-sm">error</span>
                <span class="block sm:inline text-sm font-bold">{{ session('error') }}</span>
            </div>
            @endif
            @if($errors->any())
            <div class="bg-error/10 border border-error text-error px-4 py-3 rounded-xl relative mb-4" role="alert">
                <ul class="list-disc list-inside text-sm font-medium pl-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <!-- Page Content -->
        <div class="px-4 md:px-8 pb-20 md:pb-8 flex-1">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <footer class="mt-auto p-6 md:p-8 border-t border-surface-container text-center">
            <p class="text-secondary text-sm">© {{ date('Y') }} Mycology IoT Dashboard. Teknologi Presisi untuk Budidaya Jamur.</p>
        </footer>

        <!-- Mobile Bottom Navigation -->
        <nav class="md:hidden fixed bottom-0 left-0 w-full bg-white shadow-[0_-4px_24px_rgba(0,0,0,0.08)] flex justify-around items-center px-4 py-3 z-50">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-primary font-bold' : 'text-secondary' }}">
                <span class="material-symbols-outlined text-xl" data-icon="dashboard">dashboard</span>
                <span class="text-[10px]">Home</span>
            </a>
            <a href="{{ route('devices.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('devices.*') ? 'text-primary font-bold' : 'text-secondary' }}">
                <span class="material-symbols-outlined text-xl" data-icon="settings_input_component">settings_input_component</span>
                <span class="text-[10px]">Alat</span>
            </a>
            <a href="{{ route('harvests.create') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('harvests.create') ? 'text-primary font-bold' : 'text-secondary' }}">
                <span class="material-symbols-outlined text-xl" data-icon="add_circle">add_circle</span>
                <span class="text-[10px]">Input</span>
            </a>
            <a href="{{ route('harvests.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('harvests.index') ? 'text-primary font-bold' : 'text-secondary' }}">
                <span class="material-symbols-outlined text-xl" data-icon="analytics">analytics</span>
                <span class="text-[10px]">Data</span>
            </a>
        </nav>
    </main>
</body>
</html>
