<?php

namespace App\Http\Controllers\OPD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Opd;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $userOpd = session('user_opd'); // opsional (legacy)
        $filter = $request->get('filter', 'semua');

        $q = Booking::with(['room','opdRef'])->orderByDesc('id');

        if ($userOpd) {
            $q->where('opd', $userOpd);
        }

        if ($filter !== 'semua') {
            $q->where('status', $filter);
        }

        $bookings = $q->get();

        return view('opd.bookings', compact('bookings','filter'));
    }

    public function create(Request $request)
    {
        $ruangId  = $request->get('ruang_id');
        $sesi     = $request->get('sesi');
        $tanggal  = $request->get('tanggal');

        $ruangan = Room::orderBy('id')->get();
        $selectedRoom = $ruangId ? Room::find((int)$ruangId) : null;

        // ✅ OPD aktif untuk dropdown (urut berdasarkan NAMA, tanpa lantai)
        $opds = Opd::where('is_active', true)
            ->orderBy('nama')
            ->get();

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
            'sesi'        => 'required|in:pagi,siang,malam',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
            'pj'          => 'required|string|max:100',
            'telp'        => 'required|string|max:20',
            'peserta'     => 'required|integer|min:1',
        ]);

        // ✅ conflict check
        $conflict = Booking::where('ruang_id', (int)$request->ruang_id)
            ->where('tanggal', $request->tanggal)
            ->where('sesi', $request->sesi)
            ->whereIn('status', ['MENUNGGU', 'DISETUJUI'])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['conflict' => 'Ruangan sudah dibooking pada sesi tersebut.'])->withInput();
        }

        // ✅ kode BKxxx
        $last = Booking::selectRaw("MAX(CAST(SUBSTRING(kode, 3) AS UNSIGNED)) as maxnum")->value('maxnum');
        $next = ((int)$last) + 1;
        $kode = 'BK' . str_pad($next, 3, '0', STR_PAD_LEFT);

        $opd = Opd::find((int)$request->opd_id);

        Booking::create([
            'kode'     => $kode,
            'kegiatan' => $request->kegiatan,

            // simpan FK + legacy string
            'opd_id' => (int)$request->opd_id,
            'opd'    => $opd?->nama,

            'pj'      => $request->pj,
            'telp'    => $request->telp,
            'peserta' => (int)$request->peserta,

            'ruang_id'    => (int)$request->ruang_id,
            'tanggal'     => $request->tanggal,
            'sesi'        => $request->sesi,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,

            'status'           => 'MENUNGGU',
            'catatan'          => $request->catatan ? (string)$request->catatan : null,
            'rejection_reason' => null,
        ]);

        return redirect()->route('opd.bookings')->with('success', 'Pengajuan berhasil dikirim! Menunggu verifikasi Admin.');
    }

    public function show(string $id)
    {
        $booking = Booking::with(['room','opdRef'])->where('kode', $id)->first();
        if (!$booking) abort(404);
        return view('opd.booking-detail', compact('booking'));
    }

    public function cancel(string $id)
    {
        $booking = Booking::where('kode', $id)->first();
        if (!$booking) return redirect()->route('opd.bookings')->with('error', 'Booking tidak ditemukan.');

        if ($booking->status !== 'MENUNGGU') {
            return redirect()->route('opd.bookings')->with('error', 'Tidak dapat membatalkan booking ini.');
        }

        $booking->update(['status' => 'DIBATALKAN']);
        return redirect()->route('opd.bookings')->with('success', 'Booking berhasil dibatalkan.');
    }
}