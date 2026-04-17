<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DokterExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::where('role', 'dokter')->with('poli')->get();
    }

    public function headings(): array
    {
        return ['Nama Dokter', 'Poliklinik', 'Email', 'No. HP', 'Alamat'];
    }

    public function map($dokter): array
    {
        return [
            $dokter->nama,
            $dokter->poli->nama_poli ?? '-',
            $dokter->email,
            $dokter->no_hp,
            $dokter->alamat,
        ];
    }
}
