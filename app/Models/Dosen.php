<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Dosen extends Authenticatable
{
    use Notifiable;

    protected $table = 'dosen';

    protected $fillable = [
        'nama_dosen',
        'nip',
        'email',
        'password',
        'no_hp',
    ];

    // Define relationships if needed
} 