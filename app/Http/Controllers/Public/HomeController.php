<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Blackout; // TAMBAHAN
use App\Services\DataService;

class HomeController extends Controller
{
    private function normalizeBooking(Booking $b): array
    {
        $room = $b->room;

        return [
            'id' => $b->kode,
            'kode' => $b->kode,

            'kegiatan' => $b->kegiatan,
            'opd' => $b->opd,
            'pj' => $b->pj,
            'telp' => $b->telp,
            'peserta' => (int) $b->peserta,

            'ruang_id' => (int) $b->ruang_id,
            'tanggal' => $b->tanggal,
            'sesi' => $b->sesi,
            'jam_mulai' => $b->jam_mulai,
            'jam_selesai' => $b->jam_selesai,
            'status' => strtoupper($b->status),

            'catatan' => $b->catatan,
            'rejection_reason' => $b->rejection_reason,
            'created_at' => optional($b->created_at)->toDateTimeString(),

            'ruangan' => $room ? [
                'id' => $room->id,
                'nama' => $room->nama,
                'kapasitas' => $room->kapasitas,
                'gedung' => $room->gedung,
                'lantai' => $room->lantai,
                'icon' => $room->icon,
            ] : null,

            'sesi_data' => DataService::getSesiById($b->sesi),
            'tanggal_formatted' => DataService::formatDateShort($b->tanggal),
        ];
    }

    public function home()
    {
        $today = now()->toDateString();

        $ruangan = Room::orderBy('id')->get()->map(function ($r) {
            return [
                'id' => $r->id,
                'nama' => $r->nama,
                'kapasitas' => $r->kapasitas,
                'gedung' => $r->gedung,
                'lantai' => $r->lantai,
                'icon' => $r->icon,
            ];
        })->toArray();

        $bookings = Booking::with('room')
            ->orderByDesc('id')
            ->get()
            ->map(fn($b) => $this->normalizeBooking($b))
            ->toArray();

        $todayBookings = Booking::with('room')
            ->whereDate('tanggal', $today)
            ->where('status', 'DISETUJUI')
            ->orderBy('jam_mulai')
            ->get()
            ->map(fn($b) => $this->normalizeBooking($b))
            ->toArray();

        return view('pages.home', [
            'ruangan' => $ruangan,
            'bookings' => $bookings,
            'todayBookings' => $todayBookings,
            'totalRuangan' => count($ruangan),
            'totalToday' => count($todayBookings),
        ]);
    }

    public function agenda()
    {
        $today = now()->toDateString();
        $now = Carbon::now()->format('H:i');

        $todayBookings = Booking::with('room')
            ->whereDate('tanggal', $today)
            ->where('status', 'DISETUJUI')
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($b) use ($now) {

                $arr = $this->normalizeBooking($b);

                $status = 'akan';

                if ($now >= $arr['jam_mulai'] && $now <= $arr['jam_selesai']) {
                    $status = 'berlangsung';
                } elseif ($now > $arr['jam_selesai']) {
                    $status = 'selesai';
                }

                $arr['agenda_status'] = $status;

                return $arr;
            })
            ->toArray();

        return view('pages.agenda', [
            'agendaItems' => $todayBookings,
            'today' => DataService::formatDate($today),
        ]);
    }

    public function kalender()
    {
        $ruangan = Room::orderBy('id')->get()->map(function ($r) {
            return [
                'id' => $r->id,
                'nama' => $r->nama,
                'kapasitas' => $r->kapasitas,
                'gedung' => $r->gedung,
                'lantai' => $r->lantai,
                'icon' => $r->icon,
            ];
        })->toArray();

        return view('pages.kalender', [
            'ruangan' => $ruangan,
            'sesi' => DataService::getSesi(),
        ]);
    }

    public function getRoomSlots(Request $request, string $date)
    {
        $ruangan = Room::orderBy('id')->get();
        $sesi = DataService::getSesi();

        // TAMBAHAN: ambil blackout pada tanggal tersebut
        $blackouts = Blackout::whereDate('tanggal', $date)->get();

        $dayBookings = Booking::query()
            ->whereDate('tanggal', $date)
            ->where('status', 'DISETUJUI')
            ->get()
            ->groupBy('ruang_id');

        $slots = [];

        foreach ($ruangan as $r) {

            // TAMBAHAN: cek apakah ruangan sedang blackout
            $isBlackout = $blackouts->contains(function ($b) use ($r) {
                return $b->ruangan === $r->nama || $b->ruangan === 'Semua Ruangan';
            });

            $roomBookings = $dayBookings->get($r->id, collect());

            $bookedSesi = $roomBookings->pluck('sesi')->values()->all();

            $hasFullDay = in_array('full', $bookedSesi, true);

            $sesiStatus = [];

            foreach ($sesi as $s) {

                $booked = false;

                if ($isBlackout) {
                    $booked = true;
                }
                elseif ($hasFullDay) {
                    $booked = true;
                }
                else {
                    $booked = in_array($s['id'], $bookedSesi, true);
                }

                $sesiStatus[] = [
                    'id' => $s['id'],
                    'label' => $s['label'],
                    'waktu' => $s['waktu'],
                    'booked' => $booked,
                ];
            }

            $totalBooked = $isBlackout
                ? count($sesi)
                : ($hasFullDay ? count($sesi) : count($bookedSesi));

            $availability =
                $totalBooked === 0
                ? 'available'
                : ($totalBooked >= count($sesi) ? 'full' : 'partial');

            $slots[] = [
                'id' => $r->id,
                'nama' => $r->nama,
                'kapasitas' => $r->kapasitas,
                'gedung' => $r->gedung,
                'lantai' => $r->lantai,
                'icon' => $r->icon,
                'sesi_status' => $sesiStatus,
                'availability' => $availability,
            ];
        }

        return response()->json([
            'date' => $date,
            'dateFormatted' => DataService::formatDate($date),
            'slots' => $slots,
        ]);
    }
}