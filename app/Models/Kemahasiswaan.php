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
        'profile',
        'is_email_verified',
        'email_verification_code',
        'email_verification_expires_at',
        'email_verified_at',
        'verification_email'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_verification_expires_at' => 'datetime',
        'is_email_verified' => 'boolean',
    ];

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class, 'id_kemahasiswaan');
    }
}