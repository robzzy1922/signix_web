<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ormawa;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\DosenSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'unique_test@example.com',
        ]);

        $this->call([
            OrmawaSeeder::class,
            DosenSeeder::class,
            AdminSeeder::class
        ]);
    }
}