# Implementasi Dashboard Real-time

Dokumen ini menjelaskan bagaimana fitur real-time pada dashboard (peringatan, log sensor baru, metrik terkini, status perangkat, dan grafik 24 jam) diimplementasikan tanpa memerlukan reload atau penyegaran halaman (page refresh).

## Mekanisme Kerja
Dashboard menggunakan teknik **AJAX Short Polling** menggunakan JavaScript murni (Vanilla JS) dengan metode `fetch()`. Polling dikonfigurasi untuk dieksekusi secara periodik.

### 1. Interval Polling
Script JavaScript (`resources/views/dashboard.blade.php`) secara otomatis memanggil fungsi `updateDashboard()` setiap **5 detik** menggunakan:
```javascript
setInterval(updateDashboard, 5000);
```

### 2. Endpoint Data
Data terbaru ditarik dari rute backend internal:
- **Endpoint**: `/dashboard/api` (Didaftarkan sebagai rute `dashboard.api` di `routes/web.php`)
- **Controller**: `DashboardController@apiData`

Controller akan menyiapkan dan memformat respons berformat JSON yang berisi:
- `stats`: Data suhu, kelembapan, CO2 terakhir, jumlah perangkat aktif, dan total _unresolved alerts_.
- `logs`: 10 data riwayat log sensor terbaru untuk merender ulang tabel "Log Sensor Terbaru".
- `chartData`: Data komposit rata-rata metrik lingkungan dalam 24 jam terakhir yang dikelompokkan per jam (`SensorLogHourly`) untuk merender ulang grafik.
- `devices`: 3 status perangkat IoT terbaru yang mengatur indikator hidup/mati perangkat.

### 3. Pembaruan DOM (Frontend)
Setelah JSON diterima, JavaScript memanipulasi elemen DOM (Document Object Model) terkait berdasarkan `id` nya. Tidak ada pengiriman data antar tab atau socket yang kompleks; semuanya mengandalkan DOM traversal sederhana:

- **Metrik Utama**: `document.getElementById('stat-temperature').textContent = ...`
- **Grafik Utama (`Chart.js`)**: Modifikasi referensi global instans `window.sensorChart` lalu pemanggilan ulang metode `update()`.
- **Daftar Perangkat**: Konten komponen diperbarui memakai sintaks literal templat (Template Literals) menjadi HTML yang baru disisipkan via `innerHTML` ke penampung `#device-status-list`.
- **Tabel Logs**: Sama seperti daftar perangkat, diperbarui via `innerHTML` hanya jika pengguna sedang berada di halaman pertama paginasi.

## Keuntungan Sistem Ini
- Membebaskan memori server sebab tidak ada koneksi TCP menetap layaknya protokol WebSockets (seperti Pusher/Socket.io).
- Cukup responsif dengan beban _server_ yang minim untuk skala IoT sederhana.

## Hal yang Harus Diperhatikan
- Logika pembaruan riwayat (Tabel Logs) dilengkapi dengan variabel status (`isFirstPage`). Hal ini bertujuan agar data tabel tidak menimpa otomatis manakala Admin sedang mengakses halaman tabel log selanjutnya (halaman 2, 3, dst).
