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
            'nama' => 'Hafizh',
            'alamat' => 'BSB Blok C no.18',
            'no_ktp' => '123567890',
            'no_hp' => '087774339818',
            'email' => 'tamamhafiz18@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'no_rm' => Carbon::now()->format('Ym') . '-001'
        ]);

        $dokter = User::create([
            'nama' => 'Dokter Tirta',
            'alamat' => 'Jogja',
            'no_ktp' => '123567890',
            'no_hp' => '088214817627',
            'email' => 'Tirta@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'dokter',
            'no_rm' => Carbon::now()->format('Ym') . '-002'
        ]);

        $pasien = User::create([
            'nama' => 'Hafiz',
            'alamat' => 'BSB Blok C no.18',
            'no_ktp' => '123567890',
            'no_hp' => '087774339818',
            'email' => 'pasien@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'pasien',
            'no_rm' => Carbon::now()->format('Ym') . '-003'
        ]);
    }
}
