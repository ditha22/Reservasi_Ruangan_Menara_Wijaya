# Menara Wijaya — Sistem Reservasi Ruangan (Laravel)

Sistem reservasi ruangan berbasis **Laravel 10** untuk Gedung **Menara Wijaya** yang memungkinkan OPD melakukan pemesanan ruangan secara online, melihat ketersediaan jadwal, serta mengelola data reservasi secara terstruktur.

---

# Fitur

### Publik

* Beranda informasi ruangan
* Agenda kegiatan hari ini
* Kalender reservasi interaktif

### Admin OPD

* Login OPD
* Membuat booking ruangan
* Melihat riwayat reservasi
* Detail reservasi
* Membatalkan reservasi

### Admin Publik

* Dashboard monitoring reservasi
* Verifikasi / kelola booking
* Laporan reservasi
* Grafik penggunaan ruangan
* Pengaturan blackout schedule

---

# Persyaratan Sistem

* PHP >= 8.1
* Composer
* MySQL
* Laravel 10

---

# Instalasi

```bash
# 1. Install dependencies
composer install

# 2. Salin file environment
cp .env.example .env

# 3. Generate application key
php artisan key:generate

# 4. Jalankan server
php artisan serve
```

Aplikasi dapat diakses melalui:

```
http://127.0.0.1:8000
```

---

# Akun Demo

## Admin Publik

| Username    | Password       |
| ----------- | -------------- |
| adminpublik | AdminPublik123 |

---

## Admin OPD (Pilih Salah Satu)

| Username     | Password | Instansi                         |
| ------------ | -------- | -------------------------------- |
| bkpp         | Opd12345 | BKPP                             |
| dinaspangan  | Opd12345 | Dinas Pangan                     |
| disdagkop    | Opd12345 | Disdagkop dan UKM                |
| disperinaker | Opd12345 | Disperinaker                     |

---

# Catatan

Data saat ini disimpan menggunakan **session PHP (bukan database)** sehingga data akan **reset ketika session berakhir**.

Untuk penggunaan produksi, sebaiknya **DataService diubah menggunakan database (Eloquent / MySQL)** agar data dapat tersimpan secara permanen.

---

# Struktur Proyek

```
app/
  Http/Controllers/
    AuthController.php
    PublicController.php
    BookingController.php
    AdminController.php

  Services/
    DataService.php

  Http/Middleware/
    RoleMiddleware.php

resources/views/
  layouts/app.blade.php
  pages/
  auth/
  opd/
  admin/

routes/web.php
```

---

# Teknologi yang Digunakan

* Laravel 10
* PHP
* Blade Template
* MySQL
* JavaScript

---

# Author

Ditha/
Pendidikan Teknik Informatika dan Komputer

