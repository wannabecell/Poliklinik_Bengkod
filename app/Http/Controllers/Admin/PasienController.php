<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = User::where('role', 'pasien')->get();
        return view('admin.pasien.index', compact('pasiens'));
    }

    public function create()
    {
        return view('admin.pasien.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|unique:users,no_ktp',
            'no_hp' => 'required|string',
            'no_rm' => 'required|string|unique:users,no_rm',
        ]);

        User::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'no_rm' => $request->no_rm,
            'role' => 'pasien',
            'password' => bcrypt($request->no_hp), // default password
        ]);

        return redirect()->route('pasiens.index')->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function destroy(string $id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('pasiens.index')->with('success', 'Data pasien berhasil dihapus.');
    }
}
