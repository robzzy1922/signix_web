<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Ormawa extends Authenticatable
{
    use Notifiable;

    protected $table = 'ormawa';

    protected $fillable = [
        'nama_mahasiswa',
        'nama_ormawa',
        'nim',
        'email',
        'password',
        'no_hp',
    ];

    // Define relationships if needed
} 