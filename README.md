# Jamur IoT - Sistem Monitoring & Otomatisasi Budidaya Jamur

Sistem monitoring cerdas berbasis IoT yang dirancang khusus untuk membantu pembudidaya jamur dalam memantau kondisi lingkungan secara real-time dan memastikan kondisi optimal untuk pertumbuhan jamur.

## 🚀 Fitur Utama

- **Monitoring Real-Time**: Pantau suhu, kelembaban, dan kadar CO2 di area budidaya melalui dashboard web modern.
- **Manajemen Threshold**: Atur batas minimum dan maksimum untuk setiap sensor guna menjaga stabilitas lingkungan.
- **Sistem Peringatan (Alerts)**: Notifikasi otomatis jika kondisi lingkungan berada di luar batas aman yang telah ditentukan.
- **Logging Data**: Penyimpanan riwayat data sensor secara berkala untuk keperluan analisis pertumbuhan.
- **Visualisasi Data**: Grafik interaktif yang menampilkan tren kondisi lingkungan dalam 24 jam terakhir.
- **Manajemen Perangkat**: Kelola perangkat IoT yang terhubung ke sistem (Aktif/Nonaktif).
- **Pencatatan Panen**: Fitur untuk mencatat jumlah hasil panen guna memantau produktivitas.

## 🛠️ Teknologi yang Digunakan

- **Backend**: [Laravel 10](https://laravel.com)
- **Frontend**: Blade Templates, Tailwind CSS, Vite
- **Database**: MySQL
- **IoT Interface**: REST API (untuk pengiriman data dari sensor)

## 📋 Prasyarat Sistem

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL Database

## ⚙️ Instalasi

1. **Clone Repository**
   ```bash
   git clone [url-repo]
   cd jamur-iot
   ```

2. **Instal Dependensi PHP**
   ```bash
   composer install
   ```

3. **Instal Dependensi Frontend**
   ```bash
   npm install
   ```

4. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env` dan sesuaikan pengaturan database Anda.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Migrasi Database & Seeding**
   ```bash
   php artisan migrate --seed
   ```

6. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   npm run dev
   ```

## 🔌 API Endpoint (IoT Device)

| Method | Endpoint | Deskripsi |
| --- | --- | --- |
| **POST** | `/api/sensor-log` | Mengirimkan data sensor (Temp, Hum, CO2) |

**Payload Contoh:**
```json
{
  "device_id": "DEV-01",
  "temperature": 25.5,
  "humidity": 85.0,
  "co2": 450
}
```

## 📂 Struktur Folder
- `app/`: Logika inti aplikasi (Controller, Model, Service).
- `doc/`: Dokumentasi tambahan sistem.
- `database/`: Migrasi dan seeder database.
- `resources/`: View (Blade) dan aset CSS/JS.
- `routes/`: Definisi rute antarmuka dan API.

---
*Dibuat untuk mempermudah monitoring budidaya jamur secara digital.*
