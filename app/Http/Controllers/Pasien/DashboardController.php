<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the latest registration for the logged-in patient
        $latestDaftar = DaftarPoli::with(['jadwalPeriksa.dokter.poli', 'periksas'])
            ->where('id_pasien', Auth::id())
            ->latest()
            ->first();

        return view('pasien.dashboard', compact('latestDaftar'));
    }

    public function queueStatus($id_jadwal)
    {
        // Get the current queue number being served for this specific schedule
        // The current number is the highest no_antrian that has a corresponding entry in the 'periksa' table
        $currentQueue = DaftarPoli::where('id_jadwal', $id_jadwal)
            ->whereHas('periksas')
            ->max('no_antrian') ?? 0;

        // Total waiting
        $totalQueue = DaftarPoli::where('id_jadwal', $id_jadwal)->count();

        return response()->json([
            'current' => $currentQueue,
            'total' => $totalQueue
        ]);
    }
}
