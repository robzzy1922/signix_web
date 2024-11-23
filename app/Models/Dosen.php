<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Dosen extends Authenticatable
{
    use Notifiable;
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'nama_dosen',
        'nip',
        'email',
        'password',
        'no_hp',
        'prodi',
        'profile'
    ];

    // Define relationships if needed
}