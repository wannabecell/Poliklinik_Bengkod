<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get users where role is 'dokter'
        $dokters = User::with('poli')->where('role', 'dokter')->get();
        return view('admin.dokter.index', compact('dokters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $polis = Poli::all();
        return view('admin.dokter.create', compact('polis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_poli' => 'required|exists:poli,id',
        ]);

        User::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'no_ktp' => $request->no_ktp ?? null,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dokter',
            'id_poli' => $request->id_poli,
        ]);

        return redirect()->route('dokter.index')->with('success', 'Data Dokter berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dokter = User::where('role', 'dokter')->findOrFail($id);
        $polis = Poli::all();
        return view('admin.dokter.edit', compact('dokter', 'polis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dokter = User::where('role', 'dokter')->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($dokter->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'id_poli' => 'required|exists:poli,id',
        ]);

        $data = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'id_poli' => $request->id_poli,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $dokter->update($data);

        return redirect()->route('dokter.index')->with('success', 'Data Dokter berhasil di-update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dokter = User::where('role', 'dokter')->findOrFail($id);
        $dokter->delete();

        return redirect()->route('dokter.index')->with('success', 'Data Dokter berhasil dihapus');
    }
}
