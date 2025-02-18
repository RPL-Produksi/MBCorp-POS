<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'nama_lengkap' => 'Super Administrator',
            'username' => 'superadmin',
            'nomor_telp' => '08123123123',
            'password' => bcrypt('superadmin'),
            'role' => 'superadmin',
        ]);
    }
}
