<?php

namespace Database\Factories;

use App\Models\Ormawa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ormawa>
 */
class OrmawaFactory extends Factory
{
    protected $model = Ormawa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_mahasiswa' => $this->faker->name,
            'nama_ormawa' => $this->faker->company,
            'nim' => $this->faker->unique()->numerify('##########'),
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'no_hp' => $this->faker->phoneNumber,
        ];
    }
}
