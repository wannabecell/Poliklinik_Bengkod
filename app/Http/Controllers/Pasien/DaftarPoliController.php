<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DaftarPoliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display history of registrations.
     */
    public function riwayat()
    {
        $riwayats = DaftarPoli::with(['jadwalPeriksa.dokter.poli', 'periksas'])
                    ->where('id_pasien', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('pasien.riwayat.index', compact('riwayats'));
    }

    /**
     * Display specific registration details.
     */
    public function show($id)
    {
        $riwayat = DaftarPoli::with(['jadwalPeriksa.dokter.poli', 'periksas.detailPeriksas.obat'])
                        ->where('id_pasien', Auth::id())
                        ->findOrFail($id);
        
        return view('pasien.riwayat.show', compact('riwayat'));
    }

    /**
     * Dedicated payment view.
     */
    public function pembayaran()
    {
        $riwayats = DaftarPoli::with(['jadwalPeriksa.dokter.poli', 'periksas'])
                        ->where('id_pasien', Auth::id())
                        ->whereHas('periksas') // Only those already examined
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('pasien.pembayaran.index', compact('riwayats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Enforce constraint: One active registration at a time
        $activeRegistration = DaftarPoli::where('id_pasien', Auth::id())
            ->whereDoesntHave('periksas')
            ->exists();

        if ($activeRegistration) {
            return redirect()->route('pasien.dashboard')->with('error', 'Anda tidak dapat mendaftar poli baru sebelum pemeriksaan sebelumnya selesai.');
        }

        $polis = Poli::all();
        return view('pasien.daftar.create', compact('polis'));
    }

    /**
     * API Fetcher untuk daftar jadwal periksa berdasarkan id_poli
     */
    public function getJadwal($id_poli)
    {
        $jadwals = JadwalPeriksa::with('dokter')
            ->whereHas('dokter', function ($q) use ($id_poli) {
                $q->where('id_poli', $id_poli);
            })->get();

        return response()->json($jadwals);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'required|string',
        ]);

        // Enforce constraint: No multiple active registrations
        $activeRegistration = DaftarPoli::where('id_pasien', Auth::id())
            ->whereDoesntHave('periksas')
            ->exists();

        if ($activeRegistration) {
            return redirect()->back()->with('error', 'Anda masih memiliki antrean aktif. Selesaikan pemeriksaan sebelum mendaftar lagi.');
        }

        // Kalkulasi nomor antrian berdasarkan jadwal
        $antrianTerakhir = DaftarPoli::where('id_jadwal', $request->id_jadwal)->max('no_antrian');
        $no_antrian = $antrianTerakhir ? $antrianTerakhir + 1 : 1;

        DaftarPoli::create([
            'id_pasien' => Auth::id(),
            'id_jadwal' => $request->id_jadwal,
            'keluhan' => $request->keluhan,
            'no_antrian' => $no_antrian
        ]);

        return redirect()->route('pasien.riwayat')->with('success', 'Berhasil mendaftar antrean. Nomor antrean Anda: ' . $no_antrian);
    }
}
