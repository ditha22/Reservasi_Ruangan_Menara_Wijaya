<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoomSeeder::class,
            OpdSeeder::class,   // buat data OPD dulu
            UserSeeder::class,  // baru buat user OPD
        ]);
    }
}