<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ormawas;
use Illuminate\Support\Facades\Hash;

class OrmawaSeeder extends Seeder
{
    public function run()
    {
        Ormawas::create([
            'namaMahasiswa' => 'Robi Permana',
            'namaOrmawa' => 'HIMA',
            'nim' => '2305050',
            'email' => 'Robipermana@gmail.com',
            'noHp' => '081234567890',
            'password' => Hash::make('password123'),
            'profile' => null
        ]);

        Ormawas::create([
            'namaMahasiswa' => 'Gamma Estu Mahardika',
            'namaOrmawa' => 'BEM',
            'nim' => '2305036',
            'email' => 'Gammaestu@gmail.com',
            'noHp' => '081234567891',
            'password' => Hash::make('password123'),
            'profile' => null
        ]);
    }
}