<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// Public controller
use App\Http\Controllers\Public\HomeController;

// Booking controller
use App\Http\Controllers\OPD\BookingController as OPDBookingController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

// Kelola OPD
use App\Http\Controllers\Admin\OpdController as AdminOpdController;

// Kelola Ruang (Admin Publik)
use App\Http\Controllers\Admin\RoomController as AdminRoomController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/agenda', [HomeController::class, 'agenda'])->name('agenda');
Route::get('/kalender', [HomeController::class, 'kalender'])->name('kalender');

Route::get('/kalender/ruangan/{date}', [HomeController::class, 'getRoomSlots'])
    ->name('kalender.slots');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/redirect', [AuthController::class, 'redirectAfterLogin'])->name('redirect');

/*
|--------------------------------------------------------------------------
| OPD Routes (role: opd)
|--------------------------------------------------------------------------
| FIX: parameter route harus {kode} karena controller show/cancel cari by kode
*/
Route::middleware('auth.role:opd')
    ->prefix('opd')
    ->name('opd.')
    ->group(function () {

        Route::get('/booking', [OPDBookingController::class, 'index'])
            ->name('bookings');

        Route::get('/booking/buat', [OPDBookingController::class, 'create'])
            ->name('booking.create');

        Route::post('/booking/kirim', [OPDBookingController::class, 'store'])
            ->name('booking.store');

        // ✅ pakai KODE (BKxxx)
        Route::get('/booking/{kode}', [OPDBookingController::class, 'show'])
            ->name('booking.show');

        // ✅ pakai KODE (BKxxx)
        Route::delete('/booking/{kode}/batal', [OPDBookingController::class, 'cancel'])
            ->name('booking.cancel');
    });

/*
|--------------------------------------------------------------------------
| Admin Publik Routes (role: publik)
|--------------------------------------------------------------------------
| Konsisten: admin juga pakai {kode} karena show/approve/reject/cancel pakai kode
*/
Route::middleware('auth.role:publik')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // Booking (Admin Publik)
        Route::get('/booking', [AdminBookingController::class, 'index'])
            ->name('bookings');

        // ✅ pakai KODE
        Route::get('/booking/{kode}', [AdminBookingController::class, 'show'])
            ->name('booking.show');

        // ✅ pakai KODE
        Route::post('/booking/{kode}/setujui', [AdminBookingController::class, 'approve'])
            ->name('booking.approve');

        // ✅ pakai KODE
        Route::post('/booking/{kode}/tolak', [AdminBookingController::class, 'reject'])
            ->name('booking.reject');

        /**
         * ✅ BATALKAN dari ADMIN
         * Route name ini yang dipanggil blade kamu: admin.booking.cancelApproved
         */
        Route::post('/booking/{kode}/batalkan', [AdminBookingController::class, 'cancelApproved'])
            ->name('booking.cancelApproved');

        // (Opsional) kompatibel dengan kode lama:
        Route::post('/booking/{kode}/batal', [AdminBookingController::class, 'cancelApproved'])
            ->name('booking.cancel');

        // Laporan (✅ sekarang mendukung query: month, year, room_id)
        Route::get('/laporan', [AdminController::class, 'laporan'])
            ->name('laporan');

        Route::get('/laporan/pdf', [AdminController::class, 'exportLaporanPdf'])
            ->name('laporan.pdf');

        Route::get('/laporan/excel', [AdminController::class, 'exportLaporanExcel'])
            ->name('laporan.excel');

        // Blackout
        Route::get('/blackout', [AdminController::class, 'blackout'])
            ->name('blackout');

        Route::post('/blackout', [AdminController::class, 'storeBlackout'])
            ->name('blackout.store');

        Route::delete('/blackout/{id}', [AdminController::class, 'deleteBlackout'])
            ->name('blackout.delete');

        // Kelola OPD
        Route::get('/opd', [AdminOpdController::class, 'index'])
            ->name('opd.index');

        Route::get('/opd/create', [AdminOpdController::class, 'create'])
            ->name('opd.create');

        Route::post('/opd', [AdminOpdController::class, 'store'])
            ->name('opd.store');

        Route::get('/opd/{id}/edit', [AdminOpdController::class, 'edit'])
            ->name('opd.edit');

        Route::put('/opd/{id}', [AdminOpdController::class, 'update'])
            ->name('opd.update');

        Route::patch('/opd/{id}/toggle', [AdminOpdController::class, 'toggle'])
            ->name('opd.toggle');

        // Kelola Ruang (Admin Publik) — CRUD
        Route::get('/ruang', [AdminRoomController::class, 'index'])
            ->name('ruang.index');

        Route::get('/ruang/create', [AdminRoomController::class, 'create'])
            ->name('ruang.create');

        Route::post('/ruang', [AdminRoomController::class, 'store'])
            ->name('ruang.store');

        Route::get('/ruang/{id}/edit', [AdminRoomController::class, 'edit'])
            ->name('ruang.edit');

        Route::put('/ruang/{id}', [AdminRoomController::class, 'update'])
            ->name('ruang.update');

        Route::delete('/ruang/{id}', [AdminRoomController::class, 'destroy'])
            ->name('ruang.destroy');
    });

/*
|--------------------------------------------------------------------------
| DEBUG ROUTES (sementara)
|--------------------------------------------------------------------------
| Hapus nanti jika sistem sudah stabil
*/
Route::get('/debug-db', function () {
    return [
        'rooms' => DB::table('rooms')->count(),
        'bookings' => DB::table('bookings')->count(),
        'opds_table_exists' => Schema::hasTable('opds'),
        'opds' => Schema::hasTable('opds') ? DB::table('opds')->count() : null,
        'opds_active' => Schema::hasTable('opds') ? DB::table('opds')->where('is_active', 1)->count() : null,
        'opds_sample' => Schema::hasTable('opds') ? DB::table('opds')->limit(5)->get() : null,
    ];
});

Route::get('/debug-bookings-cols', function () {
    return Schema::getColumnListing('bookings');
});

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    abort(404);
});