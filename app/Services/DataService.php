<?php

namespace App\Services;

class DataService
{
    public static function getRuangan(): array
    {
        return [
            ['id' => 1, 'nama' => 'Ruangan GSP', 'kapasitas' => 200, 'icon' => '🏟️', 'lantai' => 'Lt. 1', 'gedung' => 'Gedung Sekretariat Daerah'],
            ['id' => 2, 'nama' => 'Auditorium', 'kapasitas' => 300, 'icon' => '🎭', 'lantai' => 'Lt. 2', 'gedung' => 'Gedung Sekretariat Daerah'],
            ['id' => 3, 'nama' => 'Wijaya 1', 'kapasitas' => 40, 'icon' => '🏛️', 'lantai' => 'Lt. 3', 'gedung' => 'Gedung Menara Wijaya'],
            ['id' => 4, 'nama' => 'Wijaya 2', 'kapasitas' => 40, 'icon' => '🏛️', 'lantai' => 'Lt. 4', 'gedung' => 'Gedung Menara Wijaya'],
            ['id' => 5, 'nama' => 'Wijaya 3', 'kapasitas' => 40, 'icon' => '🏛️', 'lantai' => 'Lt. 5', 'gedung' => 'Gedung Menara Wijaya'],
            ['id' => 6, 'nama' => 'Wijaya 4', 'kapasitas' => 40, 'icon' => '🏛️', 'lantai' => 'Lt. 6', 'gedung' => 'Gedung Menara Wijaya'],
            ['id' => 7, 'nama' => 'Ruang Rapat Asisten I', 'kapasitas' => 20, 'icon' => '💼', 'lantai' => 'Lt. 7', 'gedung' => 'Gedung Sekretariat Daerah'],
            ['id' => 8, 'nama' => 'Ruang Rapat Asisten II', 'kapasitas' => 20, 'icon' => '💼', 'lantai' => 'Lt. 8', 'gedung' => 'Gedung Sekretariat Daerah'],
            ['id' => 9, 'nama' => 'Ruang Rapat Asisten III', 'kapasitas' => 20, 'icon' => '💼', 'lantai' => 'Lt. 9', 'gedung' => 'Gedung Sekretariat Daerah'],
        ];
    }

    public static function getSesi(): array
    {
        // Konsisten dengan DB enum: pagi, siang, sore, full
        return [
            ['id' => 'pagi', 'label' => 'Pagi', 'waktu' => '07:00–12:00', 'start' => '07:00', 'end' => '12:00', 'wrap' => false],
            ['id' => 'siang', 'label' => 'Siang', 'waktu' => '13:00–17:00', 'start' => '13:00', 'end' => '17:00', 'wrap' => false],
            ['id' => 'sore', 'label' => 'Sore', 'waktu' => '18:00–00:00', 'start' => '18:00', 'end' => '00:00', 'wrap' => true],
            ['id' => 'full', 'label' => '1 Hari Penuh', 'waktu' => '07:00–00:00', 'start' => '07:00', 'end' => '00:00', 'wrap' => true],
        ];
    }

    public static function getSesiById(string $id): ?array
    {
        foreach (self::getSesi() as $s) {
            if ($s['id'] === $id) return $s;
        }
        return null;
    }

    public static function getBookings(): array
    {
        if (session()->has('bookings')) {
            return session('bookings');
        }

        $bookings = [
            [
                'id' => 'BK001',
                'kegiatan' => 'Rapat Koordinasi Dinas Pendidikan',
                'opd' => 'Dinas Pendidikan',
                'pj' => 'Budi Santoso',
                'telp' => '081234567890',
                'peserta' => 20,
                'ruang_id' => 3,
                'tanggal' => '2025-05-21',
                'sesi' => 'pagi',
                'jam_mulai' => '08:00',
                'jam_selesai' => '12:00',
                'status' => 'DISETUJUI',
                'catatan' => 'Mohon sediakan proyektor dan whiteboard.',
                'created_at' => '2025-05-18',
                'rejection_reason' => ''
            ],
            [
                'id' => 'BK002',
                'kegiatan' => 'Seminar Teknologi Informasi',
                'opd' => 'Dinas Kominfo',
                'pj' => 'Rina Wijayanti',
                'telp' => '082345678901',
                'peserta' => 75,
                'ruang_id' => 2,
                'tanggal' => '2025-05-21',
                'sesi' => 'siang',
                'jam_mulai' => '13:00',
                'jam_selesai' => '16:00',
                'status' => 'MENUNGGU',
                'catatan' => 'Butuh sound system lengkap.',
                'created_at' => '2025-05-19',
                'rejection_reason' => ''
            ],
            [
                'id' => 'BK003',
                'kegiatan' => 'Rapat Internal Keuangan',
                'opd' => 'Bapenda',
                'pj' => 'Siti Rahayu',
                'telp' => '083456789012',
                'peserta' => 10,
                'ruang_id' => 7,
                'tanggal' => '2025-05-22',
                'sesi' => 'siang',
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:00',
                'status' => 'MENUNGGU',
                'catatan' => '',
                'created_at' => '2025-05-19',
                'rejection_reason' => ''
            ],
            [
                'id' => 'BK004',
                'kegiatan' => 'Presentasi Proyek Smart City',
                'opd' => 'Dinas PU',
                'pj' => 'Ahmad Fauzi',
                'telp' => '084567890123',
                'peserta' => 15,
                'ruang_id' => 4,
                'tanggal' => '2025-05-23',
                'sesi' => 'pagi',
                'jam_mulai' => '09:00',
                'jam_selesai' => '12:00',
                'status' => 'DISETUJUI',
                'catatan' => '',
                'created_at' => '2025-05-20',
                'rejection_reason' => ''
            ],
        ];

        session(['bookings' => $bookings]);
        return $bookings;
    }

    public static function saveBookings(array $bookings): void
    {
        session(['bookings' => $bookings]);
    }

    public static function getBlackouts(): array
    {
        if (session()->has('blackouts')) {
            return session('blackouts');
        }

        $blackouts = [
            [
                'id' => 1,
                'tanggal' => '2025-05-28',
                'ruangan' => 'Semua Ruangan',
                'alasan' => 'Pemeliharaan Tahunan Gedung'
            ],
            [
                'id' => 2,
                'tanggal' => '2025-06-01',
                'ruangan' => 'Aula Serbaguna',
                'alasan' => 'Kegiatan Resmi Pimpinan'
            ],
        ];

        session(['blackouts' => $blackouts]);
        return $blackouts;
    }

    public static function saveBlackouts(array $blackouts): void
    {
        session(['blackouts' => $blackouts]);
    }

    public static function getRuanganById(int $id): ?array
    {
        foreach (self::getRuangan() as $r) {
            if ($r['id'] === $id) return $r;
        }
        return null;
    }

    public static function formatDate(string $date): string
    {
        if (!$date) return '—';

        $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

        $ts = strtotime($date);

        return $days[date('w', $ts)] . ', ' . date('j', $ts) . ' ' . $months[date('n', $ts) - 1] . ' ' . date('Y', $ts);
    }

    public static function formatDateShort(string $date): string
    {
        if (!$date) return '—';

        $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        $ts = strtotime($date);

        return date('j', $ts) . ' ' . $months[date('n', $ts) - 1] . ' ' . date('Y', $ts);
    }
}