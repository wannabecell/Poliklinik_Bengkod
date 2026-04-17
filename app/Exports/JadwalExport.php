<?php

namespace App\Exports;

use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JadwalExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return JadwalPeriksa::where('id_dokter', Auth::id())->get();
    }

    public function headings(): array
    {
        return ['Hari', 'Jam Mulai', 'Jam Selesai'];
    }

    public function map($jadwal): array
    {
        return [
            $jadwal->hari,
            $jadwal->jam_mulai,
            $jadwal->jam_selesai,
        ];
    }
}
