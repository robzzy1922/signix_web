<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dosen>
 */
class DosenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_dosen' => $this->faker->name,
            'nip' => $this->faker->unique()->numerify('##########'),
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'no_hp' => $this->faker->phoneNumber,
            'prodi' => $this->faker->randomElement(['Teknik Informatika', 'Teknik Elektro', 'Teknik Industri', 'Teknik Sipil', 'Teknik Mesin', 'Teknik Kimia', 'Teknik Geologi', 'Teknik Pertambangan', 'Teknik Geofisika', 'Teknik Geodesi', 'Teknik Geomatika', 'Teknik Geologi', 'Teknik Geofisika', 'Teknik Geodesi', 'Teknik Geomatika']),
        ];
    }
}
