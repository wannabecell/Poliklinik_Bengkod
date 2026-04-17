<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Poli;
use App\Models\Obat;
use App\Models\JadwalPeriksa;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use App\Models\DetailPeriksa;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PoliklinikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Poli
        $poliAnak = Poli::create([
            'nama_poli' => 'Poli Anak',
            'keterangan' => 'Pelayanan kesehatan anak-anak'
        ]);
        
        $poliGigi = Poli::create([
            'nama_poli' => 'Poli Gigi',
            'keterangan' => 'Pelayanan kesehatan gigi dan mulut'
        ]);

        $poliUmum = Poli::create([
            'nama_poli' => 'Poli Umum',
            'keterangan' => 'Pelayanan kesehatan umum'
        ]);

        // 2. Seed Obat
        $obats = [
            ['nama_obat' => 'Paracetamol', 'kemasan' => 'Tablet', 'harga' => 5000, 'stok' => 100],
            ['nama_obat' => 'Amoxicillin', 'kemasan' => 'Tablet', 'harga' => 12000, 'stok' => 50],
            ['nama_obat' => 'Ibuprofen', 'kemasan' => 'Tablet', 'harga' => 8000, 'stok' => 45],
            ['nama_obat' => 'Bodrexin', 'kemasan' => 'Sirup', 'harga' => 15000, 'stok' => 20],
            ['nama_obat' => 'OBH Tropica', 'kemasan' => 'Sirup', 'harga' => 25000, 'stok' => 15],
        ];

        foreach ($obats as $o) {
            Obat::create($o);
        }

        // 3. Seed Users (Admin, Dokter, Pasien)
        // Admin
        User::create([
            'nama' => 'Admin Sistem',
            'alamat' => 'Semarang',
            'no_ktp' => '3301010101010001',
            'no_hp' => '081122334455',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        // Dokter
        $dokter1 = User::create([
            'nama' => 'Dr. Bambang Setiawan',
            'alamat' => 'Semarang Tengah',
            'no_ktp' => '3301010101010002',
            'no_hp' => '082233445566',
            'email' => 'dokter@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter',
            'id_poli' => $poliAnak->id
        ]);

        $dokter2 = User::create([
            'nama' => 'Dr. Ani Rohani',
            'alamat' => 'Semarang Barat',
            'no_ktp' => '3301010101010003',
            'no_hp' => '083344556677',
            'email' => 'dokter2@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter',
            'id_poli' => $poliGigi->id
        ]);

        // Pasien
        $pasien = User::create([
            'nama' => 'Adi Pradana',
            'alamat' => 'Semarang Timur',
            'no_ktp' => '3301010101010004',
            'no_hp' => '084455667788',
            'email' => 'pasien@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'pasien',
            'no_rm' => Carbon::now()->format('Ym-d') . '-001'
        ]);

        // 4. Seed Jadwal Periksa
        $jadwal1 = JadwalPeriksa::create([
            'id_dokter' => $dokter1->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '12:00:00'
        ]);

        $jadwal2 = JadwalPeriksa::create([
            'id_dokter' => $dokter2->id,
            'hari' => 'Selasa',
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '17:00:00'
        ]);

        // 5. Seed Daftar Poli (History / Testing queue)
        $daftar1 = DaftarPoli::create([
            'id_pasien' => $pasien->id,
            'id_jadwal' => $jadwal1->id,
            'keluhan' => 'Demam dan pusing sejak kemarin malam.',
            'no_antrian' => 1
        ]);

        // 6. Seed Periksa (Selesai Periksa & Biaya)
        $periksa1 = Periksa::create([
            'id_daftar_poli' => $daftar1->id,
            'tgl_periksa' => Carbon::now()->subHours(2),
            'catatan' => 'Pasien mengalami flu ringan. Istirahat cukup.',
            'biaya_periksa' => 150000 + 5000, // Jasa + Paracetamol
            'status_bayar' => 'Belum Lunas'
        ]);

        // Detail Obat
        DetailPeriksa::create([
            'id_periksa' => $periksa1->id,
            'id_obat' => 1 // Paracetamol
        ]);
        
        // Update stock
        Obat::find(1)->decrement('stok');
    }
}
