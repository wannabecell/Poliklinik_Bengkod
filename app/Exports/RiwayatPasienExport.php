<?php

namespace App\Exports;

use App\Models\DaftarPoli;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RiwayatPasienExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return DaftarPoli::with(['pasien', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($q) {
                $q->where('id_dokter', Auth::id());
            })
            ->whereHas('periksas')
            ->get();
    }

    public function headings(): array
    {
        return ['Tanggal Periksa', 'Nama Pasien', 'No. RM', 'Keluhan', 'Catatan Dokter', 'Total Biaya'];
    }

    public function map($daftar): array
    {
        $periksa = $daftar->periksas->first();
        return [
            $periksa->tgl_periksa,
            $daftar->pasien->nama,
            $daftar->pasien->no_rm,
            $daftar->keluhan,
            $periksa->catatan,
            $periksa->biaya_periksa,
        ];
    }
}
