<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Field yang boleh diisi mass assignment.
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'opd_id',
    ];

    /**
     * Field yang disembunyikan saat model di-serialize (JSON/array).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast otomatis.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed', // Laravel akan hash otomatis saat set password
        ];
    }

    /**
     * Relasi ke OPD (kalau role = opd).
     */
    public function opd()
    {
        return $this->belongsTo(Opd::class, 'opd_id');
    }

    /**
     * Helper kecil (opsional) biar enak dipakai.
     */
    public function isPublik(): bool
    {
        return $this->role === 'publik';
    }

    public function isOpd(): bool
    {
        return $this->role === 'opd';
    }
}