<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Blackout;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanBookingsExport;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistik ambil dari DB
        $total = Booking::count();
        $pending = Booking::where('status', 'MENUNGGU')->count();
        $approved = Booking::where('status', 'DISETUJUI')->count();
        $rejected = Booking::where('status', 'DITOLAK')->count();

        // ✅ NEW: dibatalkan
        $canceled = Booking::where('status', 'DIBATALKAN')->count();

        // Aktivitas terbaru (5 terakhir) dari DB
        $recent = Booking::with('room')
            ->orderByDesc('id')
            ->limit(5)
            ->get()
            ->map(function ($b) {
                $b->tanggal_formatted = $b->tanggal
                    ? Carbon::parse($b->tanggal)->translatedFormat('d M Y')
                    : '-';
                return $b;
            });

        return view('admin.dashboard', compact('total', 'pending', 'approved', 'rejected', 'canceled', 'recent'));
    }

    public function bookings(Request $request)
    {
        $filter = $request->get('filter', 'semua');
        $search = (string) $request->get('search', '');

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

        return view('admin.bookings', [
            'bookings' => $bookings,
            'filter' => $filter,
            'search' => $search,
        ]);
    }

    public function showBooking(string $id)
    {
        $booking = Booking::with('room')->where('kode', $id)->firstOrFail();
        return view('admin.booking-detail', compact('booking'));
    }

    public function approve(string $id)
    {
        $booking = Booking::where('kode', $id)->firstOrFail();

        if ($booking->status !== 'MENUNGGU') {
            return redirect()->route('admin.bookings')->with('error', 'Booking ini tidak bisa disetujui.');
        }

        $booking->update([
            'status' => 'DISETUJUI',
            'rejection_reason' => null,
        ]);

        return redirect()->route('admin.bookings')->with('success', "Booking {$id} berhasil DISETUJUI!");
    }

    public function reject(Request $request, string $id)
    {
        $request->validate(['alasan' => 'required|string|min:5']);

        $booking = Booking::where('kode', $id)->firstOrFail();

        if ($booking->status !== 'MENUNGGU') {
            return redirect()->route('admin.bookings')->with('error', 'Booking ini tidak bisa ditolak.');
        }

        $booking->update([
            'status' => 'DITOLAK',
            'rejection_reason' => $request->alasan,
        ]);

        return redirect()->route('admin.bookings')->with('success', "Booking {$id} telah DITOLAK.");
    }

    /**
     * ✅ Laporan (BULAN + TAHUN + RUANGAN)
     */
    public function laporan(Request $request)
    {
        $month  = (int) $request->get('month', now()->month);
        $year   = (int) $request->get('year', now()->year);

        $roomId = $request->get('room_id');
        $roomId = ($roomId === null || $roomId === '' || $roomId === 'semua') ? null : (int)$roomId;

        $rooms = Room::orderBy('nama')->get();
        $roomSelected = $roomId ? $rooms->firstWhere('id', $roomId) : null;

        $base = Booking::with('room')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year);

        if ($roomId) {
            $base->where('ruang_id', $roomId);
        }

        $filteredBookings = $base->get();
        $total = $filteredBookings->count();

        $prev = Carbon::create($year, $month, 1)->subMonth();
        $prevQ = Booking::query()
            ->whereMonth('tanggal', $prev->month)
            ->whereYear('tanggal', $prev->year);

        if ($roomId) {
            $prevQ->where('ruang_id', $roomId);
        }

        $prevCount = $prevQ->count();

        $percent = null;
        if ($prevCount > 0) {
            $percent = round((($total - $prevCount) / $prevCount) * 100);
        }

        $roomUsage = $filteredBookings
            ->map(function ($b) {
                return $b->room->nama ?? '-';
            })
            ->countBy()
            ->sortDesc()
            ->take(5);

        $rows = $filteredBookings
            ->sortByDesc('tanggal')
            ->take(30)
            ->values()
            ->map(function ($b) {
                return [
                    'tanggal' => $b->tanggal ? Carbon::parse($b->tanggal)->format('d M') : '-',
                    'room' => $b->room->nama ?? '-',
                    'opd' => $b->opd ?? '-',
                    'status' => strtoupper($b->status ?? 'MENUNGGU'),
                ];
            });

        return view('admin.laporan', [
            'month' => $month,
            'year' => $year,
            'room_id' => $roomId,
            'rooms' => $rooms,
            'roomSelected' => $roomSelected,
            'total' => $total,
            'percent' => $percent,
            'roomUsage' => $roomUsage,
            'rows' => $rows,
        ]);
    }

    public function exportLaporanPdf(Request $request)
    {
        $month  = (int) $request->get('month', now()->month);
        $year   = (int) $request->get('year', now()->year);

        $roomId = $request->get('room_id');
        $roomId = ($roomId === null || $roomId === '' || $roomId === 'semua') ? null : (int)$roomId;

        $payload = $this->buildLaporanPayload($month, $year, $roomId);

        $pdf = Pdf::loadView('admin.exports.laporan_pdf', $payload)->setPaper('A4', 'portrait');

        $suffixRoom = $roomId ? ('-room-' . $roomId) : '';
        $filename = "laporan-menara-wijaya-{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "{$suffixRoom}.pdf";

        return $pdf->download($filename);
    }

    public function exportLaporanExcel(Request $request)
    {
        $month  = (int) $request->get('month', now()->month);
        $year   = (int) $request->get('year', now()->year);

        $roomId = $request->get('room_id');
        $roomId = ($roomId === null || $roomId === '' || $roomId === 'semua') ? null : (int)$roomId;

        $suffixRoom = $roomId ? ('-room-' . $roomId) : '';
        $filename = "laporan-menara-wijaya-{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "{$suffixRoom}.xlsx";

        return Excel::download(new LaporanBookingsExport($month, $year, $roomId), $filename);
    }

    private function buildLaporanPayload(int $month, int $year, ?int $roomId = null): array
    {
        $rooms = Room::orderBy('nama')->get();
        $roomSelected = $roomId ? $rooms->firstWhere('id', $roomId) : null;

        $q = Booking::with('room')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year);

        if ($roomId) {
            $q->where('ruang_id', $roomId);
        }

        $filteredBookings = $q->get();
        $total = $filteredBookings->count();

        $roomUsage = $filteredBookings
            ->map(fn($b) => $b->room->nama ?? '-')
            ->countBy()
            ->sortDesc();

        $rows = $filteredBookings
            ->sortByDesc('tanggal')
            ->values()
            ->map(function ($b) {
                return [
                    'tanggal' => $b->tanggal ? Carbon::parse($b->tanggal)->format('d M Y') : '-',
                    'room' => $b->room->nama ?? '-',
                    'opd' => $b->opd ?? '-',
                    'status' => strtoupper($b->status ?? 'MENUNGGU'),
                ];
            });

        return [
            'month' => $month,
            'year' => $year,
            'room_id' => $roomId,
            'rooms' => $rooms,
            'roomSelected' => $roomSelected,
            'total' => $total,
            'roomUsage' => $roomUsage,
            'rows' => $rows,
        ];
    }

    /**
     * ✅ Blackout (aman walau kolom belum sinkron)
     */
    public function blackout()
    {
        $query = Blackout::query();

        if (Schema::hasColumn('blackouts', 'tanggal')) {
            $query->orderByDesc('tanggal');
        }

        $blackouts = $query->orderByDesc('id')->get();
        $ruangan = Room::orderBy('id')->get();

        return view('admin.blackout', compact('blackouts', 'ruangan'));
    }

    public function storeBlackout(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'ruangan' => 'required|string|max:255',
            'alasan' => 'required|string|min:3|max:1000',
        ]);

        // ===== TAMBAHAN (tidak menghapus kode lama) =====
        // memastikan tabel blackouts ada
        if (!Schema::hasTable('blackouts')) {
            Schema::create('blackouts', function ($table) {
                $table->id();
                $table->date('tanggal');
                $table->string('ruangan');
                $table->text('alasan');
                $table->timestamps();
            });
        }

        // memastikan kolom ada
        if (!Schema::hasColumn('blackouts', 'tanggal')) {
            Schema::table('blackouts', function ($table) {
                $table->date('tanggal')->nullable();
            });
        }

        if (!Schema::hasColumn('blackouts', 'ruangan')) {
            Schema::table('blackouts', function ($table) {
                $table->string('ruangan')->nullable();
            });
        }

        if (!Schema::hasColumn('blackouts', 'alasan')) {
            Schema::table('blackouts', function ($table) {
                $table->text('alasan')->nullable();
            });
        }
        // ===== END TAMBAHAN =====

        // kalau struktur tabel belum sinkron, jangan crash
        if (
            !Schema::hasColumn('blackouts', 'tanggal') ||
            !Schema::hasColumn('blackouts', 'ruangan') ||
            !Schema::hasColumn('blackouts', 'alasan')
        ) {
            return redirect()->route('admin.blackout')
                ->with('error', 'Struktur tabel blackout belum sinkron. Jalankan migrate:fresh --seed terlebih dahulu.');
        }

        Blackout::create([
            'tanggal' => $request->tanggal,
            'ruangan' => $request->ruangan,
            'alasan' => $request->alasan,
        ]);

        return redirect()->route('admin.blackout')->with('success', 'Blackout berhasil ditambahkan!');
    }

    public function deleteBlackout(int $id)
    {
        $blackout = Blackout::findOrFail($id);
        $blackout->delete();

        return redirect()->route('admin.blackout')->with('success', 'Blackout berhasil dihapus.');
    }
}