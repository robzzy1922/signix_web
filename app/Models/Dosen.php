<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Dosen extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    use HasApiTokens;

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

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Define relationships if needed
}