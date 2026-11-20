<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@ecopoint.test'],
            [
                'name' => 'Administrator EcoPoint',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'total_points' => 0,
            ]
        );

        // Peserta Demo 1
        User::updateOrCreate(
            ['email' => 'peserta@ecopoint.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('peserta123'),
                'role' => 'peserta',
                'total_points' => 35,
            ]
        );

        // Peserta Demo 2
        User::updateOrCreate(
            ['email' => 'siti@ecopoint.test'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('peserta123'),
                'role' => 'peserta',
                'total_points' => 60,
            ]
        );
    }
}
