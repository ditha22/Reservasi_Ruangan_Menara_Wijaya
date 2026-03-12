<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blackout extends Model
{
    protected $fillable = [
        'tanggal',
        'ruangan',
        'alasan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}