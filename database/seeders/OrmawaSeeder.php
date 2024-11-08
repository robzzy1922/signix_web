<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ormawa;
use Illuminate\Support\Facades\Hash;

class OrmawaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_mahasiswa' => 'Robi Permana',
                'nama_ormawa' => 'DPM',
                'nim' => '2305050',
                'email' => 'robi.permana@example.com',
                'password' => Hash::make('12345'),
                'no_hp' => '081234567890',
            ],
            // Data lainnya...
        ];
    
        foreach ($data as $ormawa) {
            Ormawa::firstOrCreate(['nim' => $ormawa['nim']], $ormawa);
        }
    }
}
