<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['nama', 'kapasitas', 'icon', 'lantai', 'gedung'];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'ruang_id');
    }
}