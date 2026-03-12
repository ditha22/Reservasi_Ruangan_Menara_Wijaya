<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->string('kode')->unique();
            $table->string('kegiatan');

            // OPD (FK dipasang belakangan karena table opds dibuat setelah bookings)
            $table->unsignedBigInteger('opd_id')->nullable();

            // PAKAI ruang_id (sesuai Controller + Model)
            $table->unsignedBigInteger('ruang_id')->nullable();

            $table->date('tanggal')->nullable();
            $table->string('sesi')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();

            // legacy / teks tampilan
            $table->string('opd')->nullable();

            // kolom yang dipakai form & detail
            $table->string('pj')->nullable();
            $table->string('telp')->nullable();
            $table->unsignedInteger('peserta')->nullable();

            // legacy lama (biar aman kalau ada view/kode lama)
            $table->string('pemohon')->nullable();
            $table->string('kontak')->nullable();

            $table->string('status')->default('MENUNGGU');

            $table->text('catatan')->nullable();
            $table->text('rejection_reason')->nullable();

            // legacy alasan tolak lama
            $table->text('alasan_tolak')->nullable();

            $table->timestamps();

            // FK rooms boleh langsung karena rooms dibuat duluan
            $table->foreign('ruang_id')->references('id')->on('rooms')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};