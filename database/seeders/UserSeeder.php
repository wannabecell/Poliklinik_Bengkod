<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'nama' => 'Dewa Ivan',
            'alamat' => 'YNKTS',
            'no_ktp' => '1234567890123456',
            'no_hp' => '081234567890',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'no_rm' => Carbon::now()->format('Ym') . '-001'
        ]);

        $dokter = User::create([
            'nama' => 'Dokter Ivan',
            'alamat' => 'YNKTS',
            'no_ktp' => '1234567890123457',
            'no_hp' => '081234567891',
            'email' => 'dokter@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter',
            'no_rm' => Carbon::now()->format('Ym') . '-002'
        ]);

        $pasien = User::create([
            'nama' => 'Pasien',
            'alamat' => 'YNKTS',
            'no_ktp' => '1234567890123458',
            'no_hp' => '081234567892',
            'email' => 'pasien@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'pasien',
            'no_rm' => Carbon::now()->format('Ym') . '-003'
        ]);
    }
}
