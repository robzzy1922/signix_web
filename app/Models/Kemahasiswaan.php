<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kemahasiswaan extends Model
{

    use Notifiable;
    use HasFactory;
    protected $table = 'kemahasiswaan';

    protected $fillable = [
        'nama_kemahasiswaan',
        'nip',
        'email',
        'password',
        'no_hp',
        'prodi',
        'profile'
    ];
}