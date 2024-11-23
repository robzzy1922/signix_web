<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ormawas extends Model
{
    use HasFactory;
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
}