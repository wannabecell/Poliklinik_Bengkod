<x-layouts.app title="Validasi Pembayaran">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Daftar Tagihan & Validasi</h2>
        <p class="text-sm text-slate-400 mt-1">Kelola dan verifikasi pembayaran pasien dari pemeriksaan medis.</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success mb-4 rounded-xl shadow-sm text-white">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="card bg-base-100 shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Pasien</th>
                        <th class="px-6 py-4">Layanan</th>
                        <th class="px-6 py-4 text-right">Total Biaya</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayats as $riwayat)
                    @php $p = $riwayat->periksas->first(); @endphp
                    <tr class="hover:bg-slate-50 border-b border-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-800">{{ $riwayat->pasien->nama }}</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase">RM: {{ $riwayat->pasien->no_rm }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-bold text-slate-600">{{ $riwayat->jadwalPeriksa->dokter->poli->nama_poli }}</div>
                            <div class="text-[10px] text-slate-400">{{ $riwayat->jadwalPeriksa->dokter->nama }}</div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-black text-slate-700">
                            Rp {{ number_format($p->biaya_periksa, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($p->bukti_bayar)
                                <div class="flex flex-col items-center gap-1">
                                    <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Sudah Upload</span>
                                    <a href="{{ asset('storage/' . $p->bukti_bayar) }}" target="_blank" class="text-[10px] text-indigo-500 underline font-bold">Lihat Bukti</a>
                                </div>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Belum Bayar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($p->bukti_bayar)
                                <form action="{{ route('admin.payment.validate', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm bg-emerald-500 hover:bg-emerald-600 text-white border-none rounded-lg font-bold uppercase text-[10px] shadow-sm">
                                        Konfirmasi Lunas
                                    </button>
                                </form>
                            @else
                                <span class="text-slate-300 italic text-[10px]">Menunggu Pasien</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic font-medium">Bagus! Semua tagihan sudah terverifikasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
