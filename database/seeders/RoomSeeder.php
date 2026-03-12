<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'nama' => 'Ruangan GSP',
                'kapasitas' => 200,
                'icon' => '🏛️',
                'gedung' => 'Gedung Menara Wijaya Perkantoran Terpadu',
                'lantai' => 'Lt. 1',
            ],
            [
                'nama' => 'Auditorium',
                'kapasitas' => 300,
                'icon' => '🎤',
                'gedung' => 'Gedung Sekretariat Daerah',
                'lantai' => 'Lt. 2',
            ],
            [
                'nama' => 'Wijaya 1',
                'kapasitas' => 40,
                'icon' => '🏢',
                'gedung' => 'Gedung Menara Wijaya Perkantoran Terpadu',
                'lantai' => 'Lt. 3',
            ],
            [
                'nama' => 'Wijaya 2',
                'kapasitas' => 40,
                'icon' => '🏢',
                'gedung' => 'Gedung Menara Wijaya Perkantoran Terpadu',
                'lantai' => 'Lt. 4',
            ],
            [
                'nama' => 'Wijaya 3',
                'kapasitas' => 40,
                'icon' => '🏢',
                'gedung' => 'Gedung Menara Wijaya Perkantoran Terpadu',
                'lantai' => 'Lt. 5',
            ],
            [
                'nama' => 'Wijaya 4',
                'kapasitas' => 40,
                'icon' => '🏢',
                'gedung' => 'Gedung Menara Wijaya Perkantoran Terpadu',
                'lantai' => 'Lt. 6',
            ],
            [
                'nama' => 'Ruang Rapat Asisten I',
                'kapasitas' => 20,
                'icon' => '📦',
                'gedung' => 'Gedung Sekretariat Daerah',
                'lantai' => 'Lt. 2',
            ],
            [
                'nama' => 'Ruang Rapat Asisten II',
                'kapasitas' => 20,
                'icon' => '📦',
                'gedung' => 'Gedung Sekretariat Daerah',
                'lantai' => 'Lt. 2',
            ],
        ];

        foreach ($rooms as $r) {
            Room::updateOrCreate(
                ['nama' => $r['nama']],
                $r
            );
        }
    }
}