<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Kemahasiswaan extends Authenticatable
{
    use Notifiable;

    protected $table = 'kemahasiswaan';
    protected $guard = 'kemahasiswaan';

    protected $fillable = [
        'nama_kemahasiswaan',
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
}