<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Ormawas extends Model implements Authenticatable
{
    use HasFactory, HasApiTokens, AuthenticatableTrait;

    protected $table = 'ormawas';
    protected $fillable = [
        'namaMahasiswa',
        'namaOrmawa',
        'nim',
        'email',
        'noHp',
        'password',
        'profile'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}