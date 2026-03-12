# Menara Wijaya — Sistem Reservasi Ruangan (Laravel)

Sistem reservasi ruangan berbasis Laravel 10 untuk Menara Wijaya.

## Fitur
- **Publik**: Beranda, Agenda Hari Ini, Kalender Reservasi interaktif
- **Admin OPD**: Login, Buat Booking, Riwayat Booking, Detail & Batalkan
- **Admin Publik**: Dashboard, Kelola & Verifikasi Booking, Laporan & Chart, Blackout

## Persyaratan
- PHP >= 8.1
- Composer
- SQLite (sudah termasuk dalam PHP)

## Instalasi

```bash
# 1. Install dependencies
composer install

# 2. Salin dan konfigurasi .env
cp .env.example .env
php artisan key:generate

# 3. Buat file database SQLite
touch database/database.sqlite

# 4. Jalankan server development
php artisan serve
```

Buka browser: **http://localhost:8000**

## Akun Demo
| Role         | Username      | Password |
|--------------|---------------|----------|
| Admin OPD    | admin.opd     | opd123   |
| Admin Publik | admin.publik  | pub123   |

## Catatan
Data tersimpan di session PHP (bukan database), sehingga data akan reset saat session berakhir.
Untuk produksi, ganti DataService agar menggunakan database/Eloquent.

## Struktur
```
app/
  Http/Controllers/
    AuthController.php      # Login/Logout
    PublicController.php    # Halaman publik
    BookingController.php   # OPD booking
    AdminController.php     # Admin Publik
  Services/
    DataService.php         # Data & helper
  Http/Middleware/
    RoleMiddleware.php      # Auth middleware
resources/views/
  layouts/app.blade.php     # Layout utama
  pages/                    # Halaman publik
  auth/                     # Login
  opd/                      # Halaman OPD
  admin/                    # Halaman Admin
routes/web.php              # Semua route
```
