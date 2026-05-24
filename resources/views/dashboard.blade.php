<x-app-layout>
    <x-slot name="header">
        Ringkasan Dashboard
    </x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-extrabold text-primary font-headline">Ringkasan Dashboard</h2>
            <p class="text-secondary font-medium">Pantau kondisi ekosistem jamur tiram Anda secara real-time.</p>
        </div>
    </div>

    <!-- Stats Overview -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
        @php 
            $latestEnv = count($latestLogs) > 0 ? $latestLogs->first() : null;
        @endphp
        
        <!-- Suhu Card -->
        <div class="bg-surface-container-highest p-5 md:p-6 rounded-[1.5rem] md:rounded-[2rem] relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <span class="material-symbols-outlined text-7xl md:text-8xl" style="font-variation-settings: 'FILL' 1;">thermostat</span>
            </div>
            <div class="flex items-center gap-2 mb-4">
                <div class="bg-primary-container/10 p-2 rounded-lg">
                    <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">thermostat</span>
                </div>
                <span class="text-xs md:text-sm font-semibold text-secondary">Suhu Terakhir</span>
            </div>
            <div class="flex items-end gap-1">
                <h3 id="stat-temperature" class="text-4xl md:text-5xl font-headline font-medium text-primary">{{ $latestEnv ? number_format($latestEnv->temperature, 1) : '--' }}</h3>
                <span class="text-xl md:text-2xl font-headline font-medium text-primary mb-1">°C</span>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] md:text-xs font-bold bg-primary-container text-white">
                    Live
                </span>
                <span id="stat-device-name" class="text-[10px] md:text-xs text-secondary truncate">{{ count($latestLogs) ? $latestEnv->device->name : 'N/A' }}</span>
            </div>
        </div>

        <!-- Kelembapan Card -->
        <div class="bg-surface-container-highest p-5 md:p-6 rounded-[1.5rem] md:rounded-[2rem] relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <span class="material-symbols-outlined text-7xl md:text-8xl" style="font-variation-settings: 'FILL' 1;">humidity_mid</span>
            </div>
            <div class="flex items-center gap-2 mb-4">
                <div class="bg-tertiary/10 p-2 rounded-lg">
                    <span class="material-symbols-outlined text-tertiary" style="font-variation-settings: 'FILL' 1;">humidity_mid</span>
                </div>
                <span class="text-xs md:text-sm font-semibold text-secondary">Kelembapan</span>
            </div>
            <div class="flex items-end gap-1">
                <h3 id="stat-humidity" class="text-4xl md:text-5xl font-headline font-medium text-primary">{{ $latestEnv ? number_format($latestEnv->humidity, 1) : '--' }}</h3>
                <span class="text-xl md:text-2xl font-headline font-medium text-primary mb-1">%</span>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] md:text-xs font-bold bg-primary-container text-white">
                    Live
                </span>
                <span id="stat-last-seen" class="text-[10px] md:text-xs text-secondary">{{ count($latestLogs) ? $latestEnv->created_at->diffForHumans() : 'N/A' }}</span>
            </div>
        </div>

        <!-- CO2 / Methane Card (Full width on small screens if odd numbers) -->
        <div class="bg-surface-container-low p-5 md:p-6 rounded-[1.5rem] md:rounded-[2rem] relative overflow-hidden group border border-outline-variant/10 sm:col-span-2 lg:col-span-1">
            <div class="absolute top-0 right-0 p-4 opacity-5">
                <span class="material-symbols-outlined text-7xl md:text-8xl" style="font-variation-settings: 'FILL' 1;">co2</span>
            </div>
            <div class="flex items-center gap-2 mb-4">
                <div class="bg-secondary/10 p-2 rounded-lg">
                    <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">co2</span>
                </div>
                <span class="text-xs md:text-sm font-semibold text-secondary">Karbon Dioksida</span>
            </div>
            <div class="flex items-end gap-1">
                <h3 id="stat-co2" class="text-4xl md:text-5xl font-headline font-medium text-primary">{{ $latestEnv ? number_format($latestEnv->co2, 0) : '--' }}</h3>
                <span class="text-sm md:text-lg font-headline font-medium text-primary mb-1">ppm</span>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span id="stat-alert-status" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] md:text-xs font-bold {{ $unresolvedAlerts > 0 ? 'bg-error' : 'bg-secondary' }} text-white">
                    {{ $unresolvedAlerts > 0 ? 'Alert!' : 'Stabil' }}
                </span>
                <span id="stat-alert-count" class="text-[10px] md:text-xs text-secondary">{{ $unresolvedAlerts }} Notifikasi</span>
            </div>
        </div>
    </section>

    <!-- Main Chart & Devices Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">

        <!-- ═══ Professional Chart Card ═══ -->
        <div class="lg:col-span-2 bg-surface-container-lowest rounded-[1.5rem] md:rounded-[2rem] shadow-sm md:shadow-[0_8px_32px_rgba(43,88,37,0.06)] border border-outline-variant/5 overflow-hidden">

            <!-- Card Header -->
            <div class="px-5 md:px-8 pt-5 md:pt-7 pb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex flex-col gap-1">
                    <div class="flex items-center gap-2">
                        <h4 id="chart-title" class="text-base md:text-lg font-bold text-primary font-headline">Tren Lingkungan</h4>
                        <!-- Animated Live Badge -->
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-primary/10 border border-primary/20 text-primary text-[10px] font-bold">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                            </span>
                            LIVE
                        </span>
                    </div>
                    <p id="chart-subtitle" class="text-[10px] md:text-xs text-secondary">Data per menit dalam 1 jam terakhir</p>
                </div>

                <!-- Timeframe Selector -->
                <div class="flex items-center gap-1 bg-surface-container-low rounded-xl p-1 border border-outline-variant/10" role="group" aria-label="Pilih rentang waktu">
                    @foreach(['1H' => '1h', '6H' => '6h', '12H' => '12h', '24H' => '24h', '7D' => '7d'] as $label => $value)
                    <button
                        data-tf="{{ $value }}"
                        class="tf-btn px-2.5 py-1 text-xs font-bold rounded-lg transition-all duration-200 {{ $value === '1h' ? 'bg-primary text-white shadow-sm' : 'text-secondary hover:text-primary' }}"
                        aria-pressed="{{ $value === '1h' ? 'true' : 'false' }}"
                    >{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            <!-- Sensor Toggle Pills -->
            <div class="px-5 md:px-8 pb-4 flex flex-wrap gap-2">
                <button id="toggle-temp" data-ds="0" class="sensor-toggle active-toggle flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border transition-all duration-200 border-transparent bg-[rgba(43,88,37,0.12)] text-[#2b5825]" aria-pressed="true">
                    <span class="w-2 h-2 rounded-full bg-[#2b5825] inline-block"></span> Suhu
                </button>
                <button id="toggle-hum" data-ds="1" class="sensor-toggle active-toggle flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border transition-all duration-200 border-transparent bg-[rgba(24,80,102,0.12)] text-[#185066]" aria-pressed="true">
                    <span class="w-2 h-2 rounded-full bg-[#185066] inline-block"></span> Kelembapan
                </button>
                <button id="toggle-co2" data-ds="2" class="sensor-toggle active-toggle flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border transition-all duration-200 border-transparent bg-[rgba(70,98,112,0.12)] text-[#466270]" aria-pressed="true">
                    <span class="w-2 h-2 rounded-full bg-[#466270] inline-block"></span> CO₂
                </button>
                <!-- Last updated indicator -->
                <span class="ml-auto text-[10px] text-secondary self-center" id="chart-updated">Memuat...</span>
            </div>

            <!-- Chart Canvas with Loading Overlay -->
            <div class="relative px-4 md:px-6 pb-6">
                <div id="chart-loading" class="absolute inset-0 flex items-center justify-center bg-surface-container-lowest/80 rounded-xl z-10 transition-opacity duration-300 opacity-0 pointer-events-none">
                    <div class="flex flex-col items-center gap-2">
                        <svg class="animate-spin h-6 w-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span class="text-xs text-secondary font-medium">Memuat data...</span>
                    </div>
                </div>
                <div class="relative w-full h-[260px] md:h-[300px]">
                    <canvas id="sensorChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Side Details -->
        <div class="space-y-6">
            <!-- Latest Devices Status -->
            <div class="bg-surface-container-lowest p-5 md:p-6 rounded-[1.5rem] md:rounded-[2rem] shadow-sm md:shadow-[0_8px_32px_rgba(43,88,37,0.04)] border border-outline-variant/5">
                <h4 class="text-base md:text-md font-bold text-primary font-headline mb-4">Status Perangkat</h4>
                <div class="space-y-3 md:space-y-4" id="device-status-list">
                    @forelse(App\Models\Device::latest()->take(3)->get() as $device)
                    <div class="flex items-center justify-between p-3 bg-surface-container-low rounded-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full {{ $device->status == 'active' ? 'bg-primary-container' : 'bg-error' }} flex items-center justify-center text-white shrink-0">
                                <span class="material-symbols-outlined text-[16px]">router</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs md:text-sm font-semibold truncate">{{ $device->name }}</p>
                                <p class="text-[9px] md:text-[10px] text-secondary truncate">{{ substr($device->id, 0, 15) }}</p>
                            </div>
                        </div>
                        @if($device->status == 'active')
                            <span class="px-2 py-1 bg-primary/10 text-primary text-[9px] md:text-[10px] font-bold rounded-lg shrink-0">AKTIF</span>
                        @else
                            <span class="px-2 py-1 bg-error/10 text-error text-[9px] md:text-[10px] font-bold rounded-lg shrink-0">MATI</span>
                        @endif
                    </div>
                    @empty
                    <p class="text-[10px] md:text-xs text-secondary text-center py-4">Belum ada perangkat terdaftar.</p>
                    @endforelse
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('devices.index') }}" id="device-list-link" class="text-[10px] md:text-xs font-bold text-primary hover:underline">Lihat Semua ({{ $deviceCount }})</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Table of Latest Raw Data -->
    <div class="mt-6 md:mt-8 bg-surface-container-lowest p-5 md:p-8 rounded-[1.5rem] md:rounded-[2rem] shadow-sm md:shadow-[0_8px_32px_rgba(43,88,37,0.04)] mb-20 md:mb-0 border border-outline-variant/5">
        <h4 class="text-base md:text-lg font-bold text-primary font-headline mb-4 md:mb-6">Log Sensor Terbaru</h4>
        <div class="overflow-x-auto rounded-xl">
            <table class="w-full text-left border-collapse min-w-[500px]">
                <thead>
                    <tr class="bg-surface-container-low/50">
                        <th class="px-4 md:px-6 py-3 md:py-4 text-[10px] md:text-xs font-bold text-primary uppercase tracking-wider">Perangkat</th>
                        <th class="px-4 md:px-6 py-3 md:py-4 text-[10px] md:text-xs font-bold text-primary uppercase tracking-wider">Waktu</th>
                        <th class="px-4 md:px-6 py-3 md:py-4 text-[10px] md:text-xs font-bold text-primary uppercase tracking-wider text-center">Suhu (°C)</th>
                        <th class="px-4 md:px-6 py-3 md:py-4 text-[10px] md:text-xs font-bold text-primary uppercase tracking-wider text-center">Hum (%)</th>
                        <th class="px-4 md:px-6 py-3 md:py-4 text-[10px] md:text-xs font-bold text-primary uppercase tracking-wider text-center">CO2 (ppm)</th>
                    </tr>
                </thead>
                <tbody id="log-table-body" class="divide-y divide-surface-container text-xs md:text-sm">
                    @forelse($latestLogs as $log)
                    <tr class="hover:bg-background transition-colors group">
                        <td class="px-4 md:px-6 py-3 md:py-4">
                            <span class="font-mono font-bold text-secondary">{{ $log->device->name }}</span>
                        </td>
                        <td class="px-4 md:px-6 py-3 md:py-4 text-secondary/80 whitespace-nowrap">{{ $log->created_at->diffForHumans() }}</td>
                        <td class="px-4 md:px-6 py-3 md:py-4 text-center font-black text-error drop-shadow-sm">{{ $log->temperature }}</td>
                        <td class="px-4 md:px-6 py-3 md:py-4 text-center font-black text-tertiary-container drop-shadow-sm">{{ $log->humidity }}</td>
                        <td class="px-4 md:px-6 py-3 md:py-4 text-center font-black text-primary drop-shadow-sm">{{ $log->co2 }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 md:px-6 py-8 text-center text-secondary/70">Belum ada log data sensor yang tersimpan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $latestLogs->links() }}
        </div>
    </div>

    <script>
    <!-- Application Scripts -->
        document.addEventListener('DOMContentLoaded', function () {

            // ── Gradient helper ──────────────────────────────────────────
            function makeGradient(ctx, colorTop, colorBot) {
                const grad = ctx.createLinearGradient(0, 0, 0, 320);
                grad.addColorStop(0, colorTop);
                grad.addColorStop(1, colorBot);
                return grad;
            }

            // ── Label formatter depending on timeframe ───────────────────
            function formatLabel(rawTime, tf) {
                // rawTime is "YYYY-MM-DD HH:MM:00" (server format)
                const d = new Date(rawTime.replace(' ', 'T') + '+07:00');
                if (tf === '7d') {
                    const days = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
                    return days[d.getDay()] + ' ' + d.getHours().toString().padStart(2,'0') + ':00';
                }
                if (tf === '1h') {
                    return d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0');
                }
                if (tf === '6h') {
                    return d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0');
                }
                // 12h / 24h → just HH:00
                return d.getHours().toString().padStart(2,'0') + ':00';
            }

            // ── Subtitle text map ─────────────────────────────────────────
            const subtitleMap = {
                '1h':  'Data per menit dalam 1 jam terakhir',
                '6h':  'Data per 5 menit dalam 6 jam terakhir',
                '12h': 'Data per jam dalam 12 jam terakhir',
                '24h': 'Data per jam dalam 24 jam terakhir',
                '7d':  'Data per 6 jam dalam 7 hari terakhir',
            };
            const titleSuffix = {
                '1h':'1 Jam', '6h':'6 Jam', '12h':'12 Jam', '24h':'24 Jam', '7d':'7 Hari'
            };

            // ── Initial chart build ───────────────────────────────────────
            const canvasEl = document.getElementById('sensorChart');
            const ctx = canvasEl.getContext('2d');

            // Default timeframe: langsung fetch dari API saat load
            let activeTf = '1h';

            function buildDatasets(chartData, tf) {
                const labels   = chartData.map(d => formatLabel(d.hour_time, tf));
                const tempData = chartData.map(d => parseFloat(d.avg_temperature).toFixed(1));
                const humData  = chartData.map(d => parseFloat(d.avg_humidity).toFixed(1));
                const co2Data  = chartData.map(d => parseFloat(d.avg_co2).toFixed(0));
                return { labels, tempData, humData, co2Data };
            }

            // Init chart kosong — data akan diisi lewat loadTimeframe() di bawah
            window.sensorChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Suhu (°C)',
                            data: [],
                            borderColor: '#2b5825',
                            backgroundColor: makeGradient(ctx, 'rgba(43,88,37,0.18)', 'rgba(43,88,37,0.02)'),
                            borderWidth: 2.5,
                            pointRadius: 3,
                            pointBackgroundColor: '#2b5825',
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Kelembapan (%)',
                            data: [],
                            borderColor: '#185066',
                            backgroundColor: makeGradient(ctx, 'rgba(24,80,102,0.14)', 'rgba(24,80,102,0.01)'),
                            borderWidth: 2.5,
                            pointRadius: 3,
                            pointBackgroundColor: '#185066',
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y'
                        },
                        {
                            label: 'CO₂ (ppm)',
                            data: [],
                            borderColor: '#466270',
                            backgroundColor: makeGradient(ctx, 'rgba(70,98,112,0.12)', 'rgba(70,98,112,0.01)'),
                            borderWidth: 2.5,
                            pointRadius: 3,
                            pointBackgroundColor: '#466270',
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 700, easing: 'easeInOutQuart' },
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(23,29,20,0.92)',
                            titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: 'bold' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 14,
                            cornerRadius: 14,
                            boxPadding: 8,
                            callbacks: {
                                label: function(context) {
                                    const units = ['°C', '%', ' ppm'];
                                    return ` ${context.dataset.label.split(' ')[0]}: ${context.parsed.y}${units[context.datasetIndex]}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: {
                                maxTicksLimit: window.innerWidth < 640 ? 5 : window.innerWidth < 1024 ? 8 : 12,
                                font: { family: 'Inter', size: 11 },
                                color: '#6b8a7a',
                                maxRotation: 0
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: { color: 'rgba(43,88,37,0.08)' },
                            border: { display: false, dash: [4, 4] },
                            ticks: { font: { family: 'Inter', size: 11 }, color: '#6b8a7a', maxTicksLimit: 6 }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            border: { display: false },
                            ticks: { font: { family: 'Inter', size: 11 }, color: '#6b8a7a', maxTicksLimit: 6 }
                        }
                    }
                }
            });

            // ── Sensor toggle pill logic ──────────────────────────────────
            document.querySelectorAll('.sensor-toggle').forEach(btn => {
                btn.addEventListener('click', () => {
                    const idx = parseInt(btn.dataset.ds);
                    const ds  = window.sensorChart.data.datasets[idx];
                    const hidden = !ds.hidden;
                    ds.hidden = hidden;
                    btn.setAttribute('aria-pressed', !hidden);
                    btn.style.opacity = hidden ? '0.4' : '1';
                    window.sensorChart.update();
                });
            });

            // ── Timeframe selector ────────────────────────────────────────
            const loadingOverlay = document.getElementById('chart-loading');
            const chartUpdatedEl = document.getElementById('chart-updated');
            const chartSubtitle  = document.getElementById('chart-subtitle');

            async function loadTimeframe(tf) {
                // Show spinner
                loadingOverlay.style.opacity = '1';
                loadingOverlay.style.pointerEvents = 'auto';

                try {
                    const url = `{{ secure_url(route('dashboard.api', [], false)) }}?timeframe=${tf}`;
                    const res  = await fetch(url);
                    const data = await res.json();

                    if (data.chartData) {
                        const { labels, tempData, humData, co2Data } = buildDatasets(data.chartData, tf);
                        window.sensorChart.data.labels = labels;
                        window.sensorChart.data.datasets[0].data = tempData;
                        window.sensorChart.data.datasets[1].data = humData;
                        window.sensorChart.data.datasets[2].data = co2Data;
                        window.sensorChart.update('active');
                    }

                    chartSubtitle.textContent = subtitleMap[tf] || '';
                    chartUpdatedEl.textContent = 'Diperbarui baru saja';
                } catch (e) {
                    console.error('Gagal memuat data timeframe:', e);
                    chartUpdatedEl.textContent = 'Gagal memperbarui';
                } finally {
                    loadingOverlay.style.opacity = '0';
                    loadingOverlay.style.pointerEvents = 'none';
                }
            }

            document.querySelectorAll('.tf-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    activeTf = btn.dataset.tf;

                    // Update active styling
                    document.querySelectorAll('.tf-btn').forEach(b => {
                        const isActive = b === btn;
                        b.classList.toggle('bg-primary',   isActive);
                        b.classList.toggle('text-white',   isActive);
                        b.classList.toggle('shadow-sm',    isActive);
                        b.classList.toggle('text-secondary', !isActive);
                        b.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    });

                    loadTimeframe(activeTf);
                });
            });

            // Langsung muat data timeframe aktif saat halaman pertama dibuka
            loadTimeframe(activeTf);

            // ── Real-time Polling ─────────────────────────────────────────
            const statsEndpoint = "{{ secure_url(route('dashboard.api', [], false)) }}";
            const tableBody  = document.getElementById('log-table-body');
            const isFirstPage = {{ $latestLogs->onFirstPage() ? 'true' : 'false' }};
            let lastUpdateTime = Date.now();

            function updateUpdatedLabel() {
                const diff = Math.floor((Date.now() - lastUpdateTime) / 1000);
                if (diff < 60) {
                    chartUpdatedEl.textContent = diff <= 5 ? 'Diperbarui baru saja' : `Diperbarui ${diff}d lalu`;
                } else {
                    chartUpdatedEl.textContent = `Diperbarui ${Math.floor(diff/60)}m lalu`;
                }
            }
            setInterval(updateUpdatedLabel, 10000);

            async function updateDashboard() {
                try {
                    const url = `${statsEndpoint}?timeframe=${activeTf}`;
                    const response = await fetch(url);
                    const data = await response.json();

                    // Update stat cards
                    document.getElementById('stat-temperature').textContent = data.stats.temperature;
                    document.getElementById('stat-humidity').textContent    = data.stats.humidity;
                    document.getElementById('stat-co2').textContent         = data.stats.co2;
                    document.getElementById('stat-device-name').textContent = data.stats.device_name;
                    document.getElementById('stat-last-seen').textContent   = data.stats.last_seen;

                    const alertCount  = document.getElementById('stat-alert-count');
                    const alertStatus = document.getElementById('stat-alert-status');
                    alertCount.textContent = `${data.stats.unresolved_alerts} Notifikasi`;
                    if (data.stats.unresolved_alerts > 0) {
                        alertStatus.textContent = 'Alert!';
                        alertStatus.classList.replace('bg-secondary', 'bg-error');
                    } else {
                        alertStatus.textContent = 'Stabil';
                        alertStatus.classList.replace('bg-error', 'bg-secondary');
                    }

                    // Update table (first page only)
                    if (isFirstPage && data.logs.length > 0) {
                        let html = '';
                        data.logs.forEach(log => {
                            html += `
                                <tr class="hover:bg-background transition-colors group">
                                    <td class="px-4 md:px-6 py-3 md:py-4"><span class="font-mono font-bold text-secondary">${log.device_name}</span></td>
                                    <td class="px-4 md:px-6 py-3 md:py-4 text-secondary/80 whitespace-nowrap">${log.time_human}</td>
                                    <td class="px-4 md:px-6 py-3 md:py-4 text-center font-black text-error drop-shadow-sm">${log.temperature}</td>
                                    <td class="px-4 md:px-6 py-3 md:py-4 text-center font-black text-tertiary-container drop-shadow-sm">${log.humidity}</td>
                                    <td class="px-4 md:px-6 py-3 md:py-4 text-center font-black text-primary drop-shadow-sm">${log.co2}</td>
                                </tr>`;
                        });
                        tableBody.innerHTML = html;
                    }

                    // Update chart (smart incremental)
                    if (data.chartData && window.sensorChart) {
                        const newLabels = data.chartData.map(d => formatLabel(d.hour_time, activeTf));
                        const newTemp   = data.chartData.map(d => parseFloat(d.avg_temperature).toFixed(1));
                        const newHum    = data.chartData.map(d => parseFloat(d.avg_humidity).toFixed(1));
                        const newCo2    = data.chartData.map(d => parseFloat(d.avg_co2).toFixed(0));

                        const lastLabel     = window.sensorChart.data.labels.at(-1);
                        const incomingLast  = newLabels.at(-1);
                        const maxPoints     = activeTf === '7d' ? 28 : activeTf === '1h' ? 60 : 24;

                        if (lastLabel !== incomingLast) {
                            window.sensorChart.data.labels.push(incomingLast);
                            window.sensorChart.data.datasets[0].data.push(newTemp.at(-1));
                            window.sensorChart.data.datasets[1].data.push(newHum.at(-1));
                            window.sensorChart.data.datasets[2].data.push(newCo2.at(-1));
                            if (window.sensorChart.data.labels.length > maxPoints) {
                                window.sensorChart.data.labels.shift();
                                window.sensorChart.data.datasets.forEach(ds => ds.data.shift());
                            }
                        } else {
                            const lastIdx = window.sensorChart.data.labels.length - 1;
                            window.sensorChart.data.datasets[0].data[lastIdx] = newTemp.at(-1);
                            window.sensorChart.data.datasets[1].data[lastIdx] = newHum.at(-1);
                            window.sensorChart.data.datasets[2].data[lastIdx] = newCo2.at(-1);
                        }
                        window.sensorChart.update();
                    }

                    // Update devices list
                    if (data.devices) {
                        const deviceList = document.getElementById('device-status-list');
                        if (data.devices.length > 0) {
                            let dHtml = '';
                            data.devices.forEach(device => {
                                const isActive = device.status === 'active';
                                const bgClass  = isActive ? 'bg-primary-container' : 'bg-error';
                                const badge    = isActive
                                    ? '<span class="px-2 py-1 bg-primary/10 text-primary text-[9px] md:text-[10px] font-bold rounded-lg shrink-0">AKTIF</span>'
                                    : '<span class="px-2 py-1 bg-error/10 text-error text-[9px] md:text-[10px] font-bold rounded-lg shrink-0">MATI</span>';
                                dHtml += `
                                <div class="flex items-center justify-between p-3 bg-surface-container-low rounded-2xl">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full ${bgClass} flex items-center justify-center text-white shrink-0">
                                            <span class="material-symbols-outlined text-[16px]">router</span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs md:text-sm font-semibold truncate">${device.name}</p>
                                            <p class="text-[9px] md:text-[10px] text-secondary truncate">${device.short_id}</p>
                                        </div>
                                    </div>
                                    ${badge}
                                </div>`;
                            });
                            deviceList.innerHTML = dHtml;
                        } else {
                            deviceList.innerHTML = '<p class="text-[10px] md:text-xs text-secondary text-center py-4">Belum ada perangkat terdaftar.</p>';
                        }
                    }

                    if (data.stats.device_count !== undefined) {
                        const deviceLink = document.getElementById('device-list-link');
                        if (deviceLink) deviceLink.textContent = `Lihat Semua (${data.stats.device_count})`;
                    }

                    lastUpdateTime = Date.now();
                    chartUpdatedEl.textContent = 'Diperbarui baru saja';

                } catch (error) {
                    console.error('Error fetching real-time data:', error);
                }
            }

            // Poll every 5 seconds
            setInterval(updateDashboard, 5000);
        });
    </script>
</x-app-layout>
