<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriksaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get daftar antrean untuk jadwal milik dokter yang login
        $daftarPolis = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($q) {
                $q->where('id_dokter', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dokter.periksa.index', compact('daftarPolis'));
    }

    /**
     * Show the form for creating a new resource (Pemeriksaan).
     * Menerima parameter $id_daftar_poli.
     */
    public function createDaftar($id_daftar_poli)
    {
        $daftarPoli = DaftarPoli::with('pasien')->findOrFail($id_daftar_poli);

        // Security check: ensure this daftar poli belongs to a schedule of the logged-in doctor
        if ($daftarPoli->jadwalPeriksa->id_dokter !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Cek jika pasien sudah diperiksa, arahkan ke edit
        if ($daftarPoli->periksas->count() > 0) {
            return redirect()->route('periksa.edit', $daftarPoli->periksas->first()->id);
        }

        $obats = Obat::all();

        return view('dokter.periksa.create', compact('daftarPoli', 'obats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required|exists:daftar_poli,id',
            'tgl_periksa' => 'required|date',
            'catatan' => 'required|string',
            'obats' => 'nullable|array',
            'obats.*' => 'exists:obat,id',
        ]);
    $biayaJasa = 150000;
    $totalBiayaObat = 0;

    // Validasi stok obat
    if ($request->filled('obats')) {

        $obats = Obat::whereIn('id', $request->obats)->get();

        foreach ($obats as $obat) {
            if ($obat->stok <= 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Stok obat '{$obat->nama_obat}' habis. Silakan pilih obat lain.");
            }
        }

        $totalBiayaObat = $obats->sum('harga');
    }

    \DB::beginTransaction();

    try {

        $biaya_periksa = $biayaJasa + $totalBiayaObat;

        $periksa = Periksa::create([
            'id_daftar_poli' => $request->id_daftar_poli,
            'tgl_periksa' => $request->tgl_periksa,
            'catatan' => $request->catatan,
            'biaya_periksa' => $biaya_periksa,
        ]);

        if ($request->filled('obats')) {

            foreach ($request->obats as $id_obat) {

                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $id_obat,
                ]);

                Obat::find($id_obat)->decrement('stok');
            }
        }

        // Broadcast antrean
        $currentQueue = $periksa->daftarPoli->no_antrian;
        $idJadwal = $periksa->daftarPoli->id_jadwal;

        event(new \App\Events\QueueUpdated($idJadwal, $currentQueue));

        \DB::commit();

        return redirect()->route('periksa.index')
            ->with('success', 'Pemeriksaan selesai. Biaya Rp ' . number_format($biaya_periksa, 0, ',', '.'));

    } catch (\Exception $e) {

        \DB::rollBack();

        return redirect()->back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $periksa = Periksa::with(['daftarPoli.pasien', 'detailPeriksas'])->findOrFail($id);

        if ($periksa->daftarPoli->jadwalPeriksa->id_dokter !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $obats = Obat::all();
        $selectedObatIds = $periksa->detailPeriksas->pluck('id_obat')->toArray();

        return view('dokter.periksa.edit', compact('periksa', 'obats', 'selectedObatIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $periksa = Periksa::findOrFail($id);

        if ($periksa->daftarPoli->jadwalPeriksa->id_dokter !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'tgl_periksa' => 'required|date',
            'catatan' => 'required|string',
            'obats' => 'nullable|array',
            'obats.*' => 'exists:obat,id',
        ]);

        $biayaJasa = 150000;
        $totalBiayaObat = 0;

        if ($request->has('obats')) {
            $obats = Obat::whereIn('id', $request->obats)->get();
            $totalBiayaObat = $obats->sum('harga');
        }

        $biaya_periksa = $biayaJasa + $totalBiayaObat;

        $periksa->update([
            'tgl_periksa' => $request->tgl_periksa,
            'catatan' => $request->catatan,
            'biaya_periksa' => $biaya_periksa,
        ]);

        // Restore stock for old prescribed medicines first
        $oldDetails = DetailPeriksa::where('id_periksa', $periksa->id)->get();
        foreach ($oldDetails as $detail) {
            $obat = Obat::find($detail->id_obat);
            if ($obat) {
                $obat->increment('stok');
            }
        }

        // Drop existing details and recreate
        DetailPeriksa::where('id_periksa', $periksa->id)->delete();

        if ($request->has('obats')) {
            foreach ($request->obats as $id_obat) {
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $id_obat,
                ]);

                // Reduce new stock
                $obat = Obat::find($id_obat);
                if ($obat) {
                    $obat->decrement('stok');
                }
            }
        }

        return redirect()->route('periksa.index')->with('success', 'Data Pemeriksaan diperbarui. Biaya: Rp ' . number_format($biaya_periksa, 0, ',', '.'));
    }
}
