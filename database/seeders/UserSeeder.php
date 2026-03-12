<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Opd;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Admin Publik (1 akun)
        User::updateOrCreate(
            ['username' => 'adminpublik'],
            [
                'name' => 'Admin Publik',
                'password' => Hash::make('AdminPublik123'),
                'role' => 'publik',
                'opd_id' => null,
            ]
        );

        /**
         * ✅ Alias demo agar username pendek & mudah diingat
         * key = nama OPD persis seperti di OpdSeeder
         * value = username demo yang kamu mau
         */
        $aliases = [
            'PPID' => 'ppid',
            'JDIH' => 'jdih',
            'Bappelbangda' => 'bappelbangda',
            'Disdagkop dan UKM' => 'disdagkop',
            'Dinas Pemberdayaan Masyarakat Desa (DPMD)' => 'dpmd',
            'Dinas Kearsipan dan Perpustakaan' => 'arsip',
            'DPPKBP3A' => 'dppkbp3a',
            'Dinas Lingkungan Hidup (DLH)' => 'dlh',
            'Disperinaker' => 'disperinaker',
            'Kesbangpol' => 'kesbangpol',
            'Dinas Pangan' => 'dinaspangan',
            'Dinas Kominfo' => 'kominfo',
            'Dinas Perumahan dan Permukiman' => 'perkim',
            'Dinas Pemuda dan Olahraga' => 'dispora',
            'BKPP' => 'bkpp',
            'Bagian Umum (Setda)' => 'bagianumum',
            'Bagian Hukum (Setda)' => 'bagianhukum',
            'Bagian Perekonomian (Setda)' => 'bagianeko',
            'Bagian Pemerintahan (Setda)' => 'bagianpem',
            'Bagian Kesra (Setda)' => 'bagiankesra',
            'Bagian Protokol & Komunikasi Pimpinan (Setda)' => 'prokopim',
            'Sekda' => 'sekda',
            'Asisten Sekda' => 'asistensekda',
            'Staf Ahli' => 'stafahli',
        ];

        // 2) Buat 1 akun untuk setiap OPD aktif
        $opds = Opd::where('is_active', 1)->orderBy('id')->get();

        foreach ($opds as $opd) {

            // ✅ kalau ada alias, pakai alias. kalau tidak ada, pakai generator lama kamu
            if (isset($aliases[$opd->nama]) && $aliases[$opd->nama] !== '') {
                $username = $aliases[$opd->nama];
            } else {
                $username = strtolower($opd->nama);
                $username = preg_replace('/[^a-z0-9]+/i', '', $username);

                if (strlen($username) < 4) {
                    $username = 'opd' . $opd->id;
                }
            }

            User::updateOrCreate(
                ['username' => $username],
                [
                    'name' => 'Admin OPD ' . $opd->nama,
                    'password' => Hash::make('Opd12345'),
                    'role' => 'opd',
                    'opd_id' => $opd->id,
                ]
            );
        }
    }
}