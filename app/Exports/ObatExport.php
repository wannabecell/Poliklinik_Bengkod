<?php

namespace App\Exports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ObatExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Obat::all();
    }

    public function headings(): array
    {
        return ['Nama Obat', 'Kemasan', 'Harga', 'Stok'];
    }

    public function map($obat): array
    {
        return [
            $obat->nama_obat,
            $obat->kemasan,
            $obat->harga,
            $obat->stok,
        ];
    }
}
