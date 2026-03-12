<?php

namespace App\Http\Controllers\OPD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Opd;
use App\Models\User;

class BookingController extends Controller
{
    /**
     * Helper: ambil user login dari session username
     * (karena AuthController pakai session manual)
     */
    private function currentUser(): ?User
    {
        $username = session('username');
        if (!$username) return null;

        return User::where('username', $username)->first();
    }

    public function index(Request $request)
    {
        $filter = (string) $request->get('filter', 'semua');

        $user = $this->currentUser();
        if (!$user) {
            return redirect()->route('login')->withErrors(['login' => 'Silakan login terlebih dahulu.']);
        }

        $q = Booking::with(['room', 'opdRef'])->orderByDesc('id');

        // booking milik OPD user yang login
        if ($user->opd_id) {
            $q->where('opd_id', $user->opd_id);
        } else {
            // fallback legacy (kalau masih ada)
            $userOpd = session('user_opd');
            if ($userOpd) $q->where('opd', $userOpd);
        }

        if ($filter !== 'semua') {
            $q->where('status', $filter);
        }

        $bookings = $q->get();

        return view('opd.bookings', compact('bookings', 'filter'));
    }

    public function create(Request $request)
    {
        $ruangId  = $request->get('ruang_id');
        $sesi     = $request->get('sesi');
        $tanggal  = $request->get('tanggal');

        $ruangan = Room::orderBy('id')->get();
        $selectedRoom = $ruangId ? Room::find((int) $ruangId) : null;

        $opds = Opd::where('is_active', true)->orderBy('nama')->get();

        return view('opd.booking-create', [
            'ruangan'      => $ruangan,
            'selectedRoom' => $selectedRoom,
            'selectedSesi' => $sesi ?: null,
            'selectedDate' => $tanggal,
            'opds'         => $opds,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan'    => 'required|string|max:255',
            'opd_id'      => 'required|exists:opds,id',
            'ruang_id'    => 'required|integer|exists:rooms,id',
            'tanggal'     => 'required|date|after_or_equal:today',
            'sesi'        => 'required|in:pagi,siang,sore,full',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
            'pj'          => 'required|string|max:100',
            'telp'        => 'required|string|max:20',
            'peserta'     => 'required|integer|min:1',
        ]);

        $ruangId = (int) $request->ruang_id;
        $tanggal = (string) $request->tanggal;
        $sesiReq = (string) $request->sesi;

        // conflict check
        $baseQuery = Booking::where('ruang_id', $ruangId)
            ->where('tanggal', $tanggal)
            ->whereIn('status', ['MENUNGGU', 'DISETUJUI']);

        if ($sesiReq === 'full') {
            $conflict = $baseQuery->exists();
        } else {
            $conflict = $baseQuery
                ->where(function ($q) use ($sesiReq) {
                    $q->where('sesi', 'full')
                      ->orWhere('sesi', $sesiReq);
                })
                ->exists();
        }

        if ($conflict) {
            return back()->withErrors(['conflict' => 'Ruangan sudah dibooking pada sesi tersebut.'])->withInput();
        }

        // kode BKxxx
        $last = Booking::selectRaw("MAX(CAST(SUBSTRING(kode, 3) AS UNSIGNED)) as maxnum")->value('maxnum');
        $next = ((int) $last) + 1;
        $kode = 'BK' . str_pad($next, 3, '0', STR_PAD_LEFT);

        $opd = Opd::find((int) $request->opd_id);

        Booking::create([
            'kode'     => $kode,
            'kegiatan' => $request->kegiatan,

            'opd_id' => (int) $request->opd_id,
            'opd'    => $opd?->nama,

            'pj'      => $request->pj,
            'telp'    => $request->telp,
            'peserta' => (int) $request->peserta,

            'ruang_id'    => $ruangId,
            'tanggal'     => $tanggal,
            'sesi'        => $sesiReq,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,

            'status'           => 'MENUNGGU',
            'catatan'          => $request->catatan ? (string) $request->catatan : null,
            'rejection_reason' => null,
        ]);

        return redirect()->route('opd.bookings')
            ->with('success', 'Pengajuan berhasil dikirim! Menunggu verifikasi Admin.');
    }

    public function show(string $kode)
    {
        $user = $this->currentUser();
        if (!$user) return redirect()->route('login');

        $q = Booking::with(['room', 'opdRef'])->where('kode', $kode);

        // hanya boleh lihat booking OPD sendiri
        if ($user->opd_id) {
            $q->where('opd_id', $user->opd_id);
        }

        $booking = $q->firstOrFail();

        return view('opd.booking-detail', compact('booking'));
    }

    public function cancel(string $kode)
    {
        $user = $this->currentUser();
        if (!$user) return redirect()->route('login');

        $q = Booking::where('kode', $kode);

        // hanya boleh batalkan booking OPD sendiri
        if ($user->opd_id) {
            $q->where('opd_id', $user->opd_id);
        }

        $booking = $q->first();
        if (!$booking) {
            return redirect()->route('opd.bookings')->with('error', 'Booking tidak ditemukan.');
        }

        // tidak boleh cancel kalau sudah lewat waktu mulai
        $startAt = Carbon::parse($booking->tanggal . ' ' . $booking->jam_mulai);
        if ($startAt->isPast()) {
            return redirect()->route('opd.bookings')->with('error', 'Booking yang sudah lewat tidak bisa dibatalkan.');
        }

        // status yang boleh dibatalkan
        if (!in_array($booking->status, ['MENUNGGU', 'DISETUJUI'], true)) {
            return redirect()->route('opd.bookings')->with('error', 'Tidak dapat membatalkan booking ini.');
        }

        $booking->update(['status' => 'DIBATALKAN']);

        return redirect()->route('opd.bookings')->with('success', 'Booking berhasil dibatalkan.');
    }
}