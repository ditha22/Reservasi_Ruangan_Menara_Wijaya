<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Opd;

class OpdSeeder extends Seeder
{
    public function run(): void
    {
        $gedung = 'Gedung Menara Wijaya Perkantoran Terpadu';

        $opds = [
            // Lantai 1
            ['nama' => 'PPID', 'lantai' => 1, 'gedung' => $gedung, 'is_active' => true],
            ['nama' => 'JDIH', 'lantai' => 1, 'gedung' => $gedung, 'is_active' => true],

            // Lantai 2
            ['nama' => 'Bappelbangda', 'lantai' => 2, 'gedung' => $gedung, 'is_active' => true],
            ['nama' => 'Disdagkop dan UKM', 'lantai' => 2, 'gedung' => $gedung, 'is_active' => true],

            // Lantai 3
            ['nama' => 'Dinas Pemberdayaan Masyarakat Desa (DPMD)', 'lantai' => 3, 'gedung' => $gedung, 'is_active' => true],
            ['nama' => 'Dinas Kearsipan dan Perpustakaan', 'lantai' => 3, 'gedung' => $gedung, 'is_active' => true],
            ['nama' => 'DPPKBP3A', 'lantai' => 3, 'gedung' => $gedung, 'is_active' => true],

            // Lantai 4
            ['nama' => 'Dinas Lingkungan Hidup (DLH)', 'lantai' => 4, 'gedung' => $gedung, 'is_active' => true],
            ['nama' => 'Disperinaker', 'lantai' => 4, 'gedung' => $gedung, 'is_active' => true],

            // Lantai 5
            ['nama' => 'Kesbangpol', 'lantai' => 5, 'gedung' => $gedung, 'is_active' => true],
            ['nama' => 'Dinas Pangan', 'lantai' => 5, 'gedung' => $gedung, 'is_active' => true],
            ['nama' => 'Dinas Kominfo', 'lantai' => 5, 'gedung' => $gedung, 'is_active' => true],

            // Lantai 6
            ['nama' => 'Dinas Perumahan dan Permukiman', 'lantai' => 6, 'gedung' => $gedung, 'is_active' => true],

            // Lantai 7
            ['nama' => 'Dinas Pemuda dan Olahraga', 'lantai' => 7, 'gedung' => $gedung, 'is_active' => true],
            ['nama' => 'BKPP', 'lantai' => 7, 'gedung' => $gedung, 'is_active' => true],

            // Lantai 8
            ['nama' => 'Bagian Umum (Setda)', 'lantai' => 8, 'gedung' => $gedung, 'is_active' => true],
            ['nama' => 'Bagian Hukum (Setda)', 'lantai' => 8, 'gedung' => $gedung, 'is_active' => true],
            ['nama' => 'Bagian Perekonomian (Setda)', 'lantai' => 8, 'gedung' => $gedung, 'is_active' => true],
            ['nama' => 'Bagian Pemerintahan (Setda)', 'lantai' => 8, 'gedung' => $gedung, 'is_active' => true],
            ['nama' => 'Bagian Kesra (Setda)', 'lantai' => 8, 'gedung' => $gedung, 'is_active' => true],
            ['nama' => 'Bagian Protokol & Komunikasi Pimpinan (Setda)', 'lantai' => 8, 'gedung' => $gedung, 'is_active' => true],

            // Lantai 9
            ['nama' => 'Sekda', 'lantai' => 9, 'gedung' => $gedung, 'is_active' => true],
            ['nama' => 'Asisten Sekda', 'lantai' => 9, 'gedung' => $gedung, 'is_active' => true],
            ['nama' => 'Staf Ahli', 'lantai' => 9, 'gedung' => $gedung, 'is_active' => true],
        ];

        foreach ($opds as $o) {
            Opd::updateOrCreate(
                ['nama' => $o['nama']],
                $o
            );
        }
    }
}