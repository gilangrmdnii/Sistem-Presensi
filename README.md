# Sistem Presensi PT MAZ Nusantara Cakti

Rancang Bangun Sistem Presensi Berbasis Web Menggunakan Framework Laravel.

> Tugas Akhir — Ahmad Nur Aziz (0110222003)
> Program Studi Teknik Informatika, Sekolah Tinggi Teknologi Terpadu Nurul Fikri

## Deskripsi

Sistem presensi karyawan berbasis web yang menggantikan proses presensi manual di PT MAZ Nusantara Cakti. Karyawan melakukan presensi masuk & pulang menggunakan **QR Code** unik, mengajukan izin/cuti secara online, dan data kehadiran dapat dipantau secara real-time oleh HRD.

## Fitur Utama

- **Autentikasi** berbasis role (Karyawan, Atasan Divisi, HRD)
- **Presensi QR Code** — scan QR Code untuk mencatat jam masuk & pulang secara otomatis
- **Pengajuan Izin & Cuti** — form online untuk karyawan mengajukan izin
- **Persetujuan Izin** — atasan divisi dapat approve/reject pengajuan karyawan
- **Manajemen Karyawan (HRD)** — CRUD data karyawan, divisi, jabatan, pendidikan
- **Laporan Presensi** — rekap harian/mingguan/bulanan dengan export Excel
- **Validasi Lokasi GPS** — nilai tambah untuk memastikan karyawan presensi di area kantor

## Teknologi

- [Laravel 11](https://laravel.com/) — framework utama (MVC)
- [Bootstrap 5](https://getbootstrap.com/) — UI framework
- [Livewire 3](https://livewire.laravel.com/) — komponen reaktif
- [Endroid QR Code](https://github.com/endroid/qr-code) — generator QR Code
- [Leaflet.js](https://leafletjs.com/) + [OpenStreetMap](https://www.openstreetmap.org/) — validasi lokasi
- MySQL / MariaDB

## Instalasi

### Prasyarat

- PHP 8.3
- [Composer](https://getcomposer.org)
- [NPM & Node.js](https://nodejs.org)
- MySQL / MariaDB

### Langkah

```bash
# 1. Copy environment file
cp .env.example .env

# 2. Install dependencies
composer install
npm install

# 3. Generate app key
php artisan key:generate

# 4. Konfigurasi database di .env, lalu migrate + seed
php artisan migrate --seed

# 5. Build assets
npm run build

# 6. Jalankan server
php artisan serve
```

### Seeder

- `php artisan db:seed --class=DatabaseSeeder` — data awal (HRD, Atasan Divisi, Karyawan contoh)
- `php artisan db:seed --class=FakeDataSeeder` — data dummy lengkap untuk testing

## Akun Default

| Role           | Email                      | Password  |
| -------------- | -------------------------- | --------- |
| HRD            | hrd@maznusantara.co.id     | password  |
| Atasan Divisi  | atasan@maznusantara.co.id  | password  |
| Karyawan       | karyawan@maznusantara.co.id| password  |

## Struktur Role

- **Karyawan** — presensi masuk/pulang, ajukan izin/cuti, lihat riwayat
- **Atasan Divisi** — approve/reject pengajuan izin bawahan divisinya
- **HRD** — kelola data karyawan, monitor kehadiran, export laporan

## Lisensi

Proprietary — hak milik Ahmad Nur Aziz & PT MAZ Nusantara Cakti.
