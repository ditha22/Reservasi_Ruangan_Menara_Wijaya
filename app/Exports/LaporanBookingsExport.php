<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Models\Booking;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanBookingsExport implements FromCollection, WithHeadings
{
    public function __construct(
        private int $month,
        private int $year,
        private ?int $roomId = null // ✅ NEW
    ) {}

    public function headings(): array
    {
        return ['Tanggal', 'Ruang', 'OPD', 'Status'];
    }

    public function collection()
    {
        $q = Booking::with('room')
            ->whereMonth('tanggal', $this->month)
            ->whereYear('tanggal', $this->year);

        if (!is_null($this->roomId)) {
            $q->where('ruang_id', $this->roomId);
        }

        $rows = $q->orderBy('tanggal')->get()->map(function ($b) {
            $tgl = $b->tanggal ? Carbon::parse($b->tanggal)->format('d M Y') : '-';
            $room = $b->room->nama ?? '-';
            $opd = $b->opd ?? '-';
            $status = strtoupper($b->status ?? 'MENUNGGU');

            return [$tgl, $room, $opd, $status];
        });

        return new Collection($rows->values()->all());
    }
}