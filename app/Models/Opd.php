<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opd extends Model
{
    protected $fillable = ['nama', 'lantai', 'gedung', 'is_active'];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'opd_id');
    }
}