<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $riwayats = DaftarPoli::with(['pasien', 'jadwalPeriksa.dokter.poli', 'periksas'])->get();
        return view('admin.riwayat.index', compact('riwayats'));
    }

    public function pembayaran()
    {
        // Only show those with medical records (checked) but either unpaid or needing validation
        $riwayats = DaftarPoli::whereHas('periksas', function($q) {
            $q->where('status_bayar', 'Belum Lunas');
        })->with(['pasien', 'jadwalPeriksa.dokter.poli', 'periksas'])->get();

        return view('admin.pembayaran.index', compact('riwayats'));
    }

    public function exportExcel()
    {
        $riwayats = DaftarPoli::with(['pasien', 'jadwalPeriksa.dokter.poli', 'periksas'])->get();
        
        $filename = "Laporan_Pendaftaran_Poli_" . date('Y-m-d_H-i-s') . ".xls";

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "<table border='1'>";
        echo "<thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pasien</th>
                    <th>Poliklinik</th>
                    <th>Dokter</th>
                    <th>Hari/Jadwal</th>
                    <th>Antrean</th>
                    <th>Biaya</th>
                    <th>Status Bayar</th>
                </tr>
              </thead>";
        echo "<tbody>";
        
        $no = 1;
        foreach ($riwayats as $riwayat) {
            $biaya = $riwayat->periksas->first() ? $riwayat->periksas->first()->biaya_periksa : 0;
            $status = $riwayat->periksas->first() ? $riwayat->periksas->first()->status_bayar : 'N/A';
            
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . ($riwayat->pasien->nama ?? '-') . "</td>";
            echo "<td>" . ($riwayat->jadwalPeriksa->dokter->poli->nama_poli ?? '-') . "</td>";
            echo "<td>" . ($riwayat->jadwalPeriksa->dokter->nama ?? '-') . "</td>";
            echo "<td>" . ($riwayat->jadwalPeriksa->hari ?? '-') . "</td>";
            echo "<td>" . $riwayat->no_antrian . "</td>";
            echo "<td>" . number_format($biaya, 0, ',', '.') . "</td>";
            echo "<td>" . $status . "</td>";
            echo "</tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
        exit;
    }
}
