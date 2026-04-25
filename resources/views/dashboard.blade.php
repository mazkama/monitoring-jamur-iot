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
        <!-- Chart Area -->
        <div class="lg:col-span-2 bg-surface-container-lowest p-5 md:p-8 rounded-[1.5rem] md:rounded-[2rem] shadow-sm md:shadow-[0_8px_32px_rgba(43,88,37,0.04)] border border-outline-variant/5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h4 class="text-base md:text-lg font-bold text-primary font-headline">Tren Lingkungan 24 Jam</h4>
                    <p class="text-[10px] md:text-sm text-secondary">Visualisasi fluktuasi suhu, kelembapan, dan CO2</p>
                </div>
                <!-- Custom HTML Legend mirroring the exact mockup -->
                <div class="flex flex-wrap gap-2">
                    <div class="flex items-center gap-2 text-xs font-bold px-3 py-1.5 bg-surface-container-low rounded-full border border-surface-container-highest">
                        <span class="w-2.5 h-2.5 rounded-full bg-primary-fixed-dim"></span> Suhu
                    </div>
                    <div class="flex items-center gap-2 text-xs font-bold px-3 py-1.5 bg-surface-container-low rounded-full border border-surface-container-highest">
                        <span class="w-2.5 h-2.5 rounded-full bg-tertiary-fixed-dim"></span> Kelembapan
                    </div>
                    <div class="flex items-center gap-2 text-xs font-bold px-3 py-1.5 bg-surface-container-low rounded-full border border-surface-container-highest">
                        <span class="w-2.5 h-2.5 rounded-full bg-secondary-fixed-dim"></span> CO2
                    </div>
                </div>
            </div>
            
            <div class="relative w-full h-[250px] md:h-[300px]">
                <canvas id="sensorChart"></canvas>
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
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('sensorChart').getContext('2d');
            const chartData = @json($chartData);
            
            const labels = chartData.map(data => new Date(data.hour_time).getHours() + ':00');
            const tempData = chartData.map(data => data.avg_temperature);
            const humData = chartData.map(data => data.avg_humidity);
            const co2Data = chartData.map(data => data.avg_co2);

            // Tailwind specific colors mimicking mockup
            const colorPrimary = '#a1d494'; // primary-fixed-dim
            const colorPrimaryHover = '#2b5825'; // primary
            const colorTertiary = '#55d7ed'; // tertiary-fixed-dim
            const colorTertiaryHover = '#005762'; // tertiary
            const colorSecondary = '#adcbda'; // secondary-fixed-dim
            const colorSecondaryHover = '#466270'; // secondary

            window.sensorChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Suhu (°C)',
                        data: tempData,
                        backgroundColor: colorPrimary,
                        hoverBackgroundColor: colorPrimaryHover,
                        borderRadius: {topLeft: 6, topRight: 6},
                        barPercentage: 0.7,
                        categoryPercentage: 0.8
                    }, {
                        label: 'Kelembapan (%)',
                        data: humData,
                        backgroundColor: colorTertiary,
                        hoverBackgroundColor: colorTertiaryHover,
                        borderRadius: {topLeft: 6, topRight: 6},
                        barPercentage: 0.7,
                        categoryPercentage: 0.8
                    }, {
                        label: 'CO2 (ppm)',
                        data: co2Data,
                        backgroundColor: colorSecondary,
                        hoverBackgroundColor: colorSecondaryHover,
                        borderRadius: {topLeft: 6, topRight: 6},
                        barPercentage: 0.7,
                        categoryPercentage: 0.8,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { display: false }, // Using custom HTML legend instead
                        tooltip: {
                            backgroundColor: 'rgba(23, 29, 20, 0.9)',
                            titleFont: { family: 'Plus Jakarta Sans', size: 14 },
                            bodyFont: { family: 'Inter', size: 13 },
                            padding: 12,
                            cornerRadius: 12,
                            boxPadding: 8
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { 
                                maxTicksLimit: window.innerWidth < 768 ? 6 : 12,
                                font: { family: 'Inter', size: 11 },
                                color: '#466270'
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: { color: '#dee5d6', drawBorder: false },
                            ticks: { font: { size: window.innerWidth < 768 ? 9 : 11 }, color: '#466270' }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: { font: { size: window.innerWidth < 768 ? 9 : 11 }, color: '#466270' }
                        }
                    }
                }
            });

            // Real-time Polling Logic
            const statsEndpoint = "{{ route('dashboard.api') }}";
            const tableBody = document.getElementById('log-table-body');
            const isFirstPage = {{ $latestLogs->onFirstPage() ? 'true' : 'false' }};

            async function updateDashboard() {
                try {
                    const response = await fetch(statsEndpoint);
                    const data = await response.json();

                    // Update stats
                    document.getElementById('stat-temperature').textContent = data.stats.temperature;
                    document.getElementById('stat-humidity').textContent = data.stats.humidity;
                    document.getElementById('stat-co2').textContent = data.stats.co2;
                    document.getElementById('stat-device-name').textContent = data.stats.device_name;
                    document.getElementById('stat-last-seen').textContent = data.stats.last_seen;
                    
                    const alertCount = document.getElementById('stat-alert-count');
                    const alertStatus = document.getElementById('stat-alert-status');
                    alertCount.textContent = `${data.stats.unresolved_alerts} Notifikasi`;
                    
                    if (data.stats.unresolved_alerts > 0) {
                        alertStatus.textContent = 'Alert!';
                        alertStatus.classList.remove('bg-secondary');
                        alertStatus.classList.add('bg-error');
                    } else {
                        alertStatus.textContent = 'Stabil';
                        alertStatus.classList.remove('bg-error');
                        alertStatus.classList.add('bg-secondary');
                    }

                    // Update table only if on first page
                    if (isFirstPage && data.logs.length > 0) {
                        let html = '';
                        data.logs.forEach(log => {
                            html += `
                                <tr class="hover:bg-background transition-colors group">
                                    <td class="px-4 md:px-6 py-3 md:py-4">
                                        <span class="font-mono font-bold text-secondary">${log.device_name}</span>
                                    </td>
                                    <td class="px-4 md:px-6 py-3 md:py-4 text-secondary/80 whitespace-nowrap">${log.time_human}</td>
                                    <td class="px-4 md:px-6 py-3 md:py-4 text-center font-black text-error drop-shadow-sm">${log.temperature}</td>
                                    <td class="px-4 md:px-6 py-3 md:py-4 text-center font-black text-tertiary-container drop-shadow-sm">${log.humidity}</td>
                                    <td class="px-4 md:px-6 py-3 md:py-4 text-center font-black text-primary drop-shadow-sm">${log.co2}</td>
                                </tr>
                            `;
                        });
                        tableBody.innerHTML = html;
                    }

                    // Update chart
                    if (data.chartData && window.sensorChart) {
                        window.sensorChart.data.labels = data.chartData.map(d => {
                            const date = new Date(d.hour_time);
                            return date.getHours() + ':00';
                        });
                        window.sensorChart.data.datasets[0].data = data.chartData.map(d => d.avg_temperature);
                        window.sensorChart.data.datasets[1].data = data.chartData.map(d => d.avg_humidity);
                        window.sensorChart.data.datasets[2].data = data.chartData.map(d => d.avg_co2);
                        window.sensorChart.update();
                    }

                    // Update devices list
                    if (data.devices) {
                        const deviceList = document.getElementById('device-status-list');
                        if (data.devices.length > 0) {
                            let deviceHtml = '';
                            data.devices.forEach(device => {
                                const isActive = device.status === 'active';
                                const bgClass = isActive ? 'bg-primary-container' : 'bg-error';
                                const badgeHtml = isActive 
                                    ? '<span class="px-2 py-1 bg-primary/10 text-primary text-[9px] md:text-[10px] font-bold rounded-lg shrink-0">AKTIF</span>'
                                    : '<span class="px-2 py-1 bg-error/10 text-error text-[9px] md:text-[10px] font-bold rounded-lg shrink-0">MATI</span>';

                                deviceHtml += `
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
                                    ${badgeHtml}
                                </div>
                                `;
                            });
                            deviceList.innerHTML = deviceHtml;
                        } else {
                            deviceList.innerHTML = '<p class="text-[10px] md:text-xs text-secondary text-center py-4">Belum ada perangkat terdaftar.</p>';
                        }
                    }

                    // Update device count
                    if (data.stats.device_count !== undefined) {
                        const deviceLink = document.getElementById('device-list-link');
                        if (deviceLink) {
                            deviceLink.textContent = `Lihat Semua (${data.stats.device_count})`;
                        }
                        }
                } catch (error) {
                    console.error('Error fetching real-time data:', error);
                }
            }

            // Poll every 5 seconds
            setInterval(updateDashboard, 5000);
        });
    </script>
</x-app-layout>
