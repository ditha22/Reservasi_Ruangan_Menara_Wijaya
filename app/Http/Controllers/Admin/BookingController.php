<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $filter = (string) $request->query('filter', 'semua');
        $search = trim((string) $request->query('search', ''));

        $q = Booking::with('room')->orderByDesc('id');

        if ($filter !== 'semua') {
            $q->where('status', $filter);
        }

        if ($search !== '') {
            $q->where(function ($qq) use ($search) {
                $qq->where('kegiatan', 'like', "%{$search}%")
                    ->orWhere('opd', 'like', "%{$search}%")
                    ->orWhere('pj', 'like', "%{$search}%")
                    ->orWhere('telp', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        $bookings = $q->get();

        return view('admin.bookings', compact('bookings', 'filter', 'search'));
    }

    public function show(string $kode)
    {
        $booking = Booking::with('room')->where('kode', $kode)->firstOrFail();
        return view('admin.booking-detail', compact('booking'));
    }

    public function approve(string $kode)
    {
        $booking = Booking::where('kode', $kode)->firstOrFail();

        if ($booking->status !== 'MENUNGGU') {
            return redirect()->route('admin.booking.show', $kode)
                ->with('error', 'Booking ini tidak bisa disetujui.');
        }

        $booking->update([
            'status' => 'DISETUJUI',
            'rejection_reason' => null,
        ]);

        return redirect()->route('admin.booking.show', $kode)
            ->with('success', 'Booking berhasil disetujui.');
    }

    public function reject(Request $request, string $kode)
    {
        $booking = Booking::where('kode', $kode)->firstOrFail();

        if ($booking->status !== 'MENUNGGU') {
            return redirect()->route('admin.booking.show', $kode)
                ->with('error', 'Booking ini tidak bisa ditolak.');
        }

        $reason = trim((string) $request->input('alasan', ''));

        if ($reason === '') {
            return redirect()->route('admin.booking.show', $kode)
                ->with('error', 'Alasan penolakan wajib diisi.');
        }

        $booking->update([
            'status' => 'DITOLAK',
            'rejection_reason' => $reason,
        ]);

        return redirect()->route('admin.booking.show', $kode)
            ->with('success', 'Booking berhasil ditolak.');
    }

    /**
     * ✅ Admin publik batalkan booking MENUNGGU/DISETUJUI
     * (yang belum lewat waktu mulai).
     * Route: admin.booking.cancelApproved
     */
    public function cancelApproved(string $kode)
    {
        $booking = Booking::where('kode', $kode)->firstOrFail();

        if (!in_array($booking->status, ['MENUNGGU', 'DISETUJUI'], true)) {
            return redirect()->route('admin.booking.show', $kode)
                ->with('error', 'Booking ini tidak bisa dibatalkan.');
        }

        $startAt = Carbon::parse($booking->tanggal . ' ' . $booking->jam_mulai);
        if ($startAt->isPast()) {
            return redirect()->route('admin.booking.show', $kode)
                ->with('error', 'Booking yang sudah lewat tidak bisa dibatalkan.');
        }

        $booking->update([
            'status' => 'DIBATALKAN',
        ]);

        // ✅ balik ke list biar langsung kelihatan di filter "DIBATALKAN"
        return redirect()->route('admin.bookings', ['filter' => 'DIBATALKAN'])
            ->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * (Opsional) kompatibilitas kalau ada kode lama yang manggil cancel()
     */
    public function cancel(string $kode)
    {
        return $this->cancelApproved($kode);
    }
}