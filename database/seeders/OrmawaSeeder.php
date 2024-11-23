<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ormawas;
use Illuminate\Support\Facades\Hash;

class OrmawaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'namaMahasiswa' => 'Robi Permana',
                'namaOrmawa' => 'FORMADIKSI',
                'nim' => '2305050',
                'email' => 'robi.permana@example.com',
                'noHp' => '081234567890',
                'password' => Hash::make('12345'),
                'profile' => null,
            ],
        ];

        foreach ($data as $ormawa) {
            Ormawas::firstOrCreate(['nim' => $ormawa['nim']], $ormawa);
        }
    }
}