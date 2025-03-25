<?php

namespace Database\Seeders;

use App\Models\Kemahasiswaan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class kemahasiswaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_kemahasiswaan' => 'Kemahasiswaan 1',
                'nip' => '12345',
                'email' => 'kemahasiswaan@gmail.com',
                'password' => Hash::make('12345'),
                'no_hp' => '081234567890',
                'prodi' => 'Teknik Informatika',
            ],
        ];

        foreach ($data as $kemahasiswaan) {
            Kemahasiswaan::firstOrCreate(['nip' => $kemahasiswaan['nip']], $kemahasiswaan);
        }
    }

}