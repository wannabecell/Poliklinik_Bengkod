<?php

namespace App\Http\Controllers;

use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function upload(Request $request, $id_periksa)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $periksa = Periksa::findOrFail($id_periksa);

        if ($request->hasFile('bukti_bayar')) {
            // Delete old file if exists
            if ($periksa->bukti_bayar) {
                Storage::disk('public')->delete($periksa->bukti_bayar);
            }

            $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
            $periksa->update([
                'bukti_bayar' => $path,
            ]);
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu validasi Admin.');
    }

    public function validatePayment($id_periksa)
    {
        $periksa = Periksa::findOrFail($id_periksa);
        $periksa->update([
            'status_bayar' => 'Lunas',
        ]);

        return redirect()->back()->with('success', 'Pembayaran telah dikonfirmasi sebagai Lunas.');
    }
}
