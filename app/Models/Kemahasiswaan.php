<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Kemahasiswaan extends Authenticatable
{
    use Notifiable;

    protected $table = 'kemahasiswaan';
    protected $guard = 'kemahasiswaan';

    protected $fillable = [
        'nama',
        'email',
        'nama_kemahasiswaan',
        'nip',
        'password',
        'no_hp',
        'prodi',
        'profile'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class, 'id_kemahasiswaan');
    }
}