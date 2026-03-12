<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'kode',
        'kegiatan',
        'opd_id',
        'opd',
        'pj',
        'telp',
        'peserta',
        'ruang_id',
        'tanggal',
        'sesi',
        'jam_mulai',
        'jam_selesai',
        'status',
        'catatan',
        'rejection_reason',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'ruang_id');
    }

    public function opdRef()
    {
        return $this->belongsTo(Opd::class, 'opd_id');
    }
}