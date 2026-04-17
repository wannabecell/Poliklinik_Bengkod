<x-layouts.app title="Tagihan Pembayaran">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Pembayaran Tagihan</h2>
        <p class="text-sm text-slate-400 mt-1">Silakan upload bukti pembayaran untuk tagihan pemeriksaan Anda.</p>
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
                        <th class="px-6 py-4">Tgl Periksa</th>
                        <th class="px-6 py-4">Poli & Dokter</th>
                        <th class="px-6 py-4">Total Biaya</th>
                        <th class="px-6 py-4">Status & Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayats as $riwayat)
                    @php $p = $riwayat->periksas->first(); @endphp
                    <tr class="hover:bg-slate-50 border-b border-slate-50 transition">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($p->tgl_periksa)->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-800">{{ $riwayat->jadwalPeriksa->dokter->poli->nama_poli }}</div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $riwayat->jadwalPeriksa->dokter->nama }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm font-black text-indigo-600">
                            Rp {{ number_format($p->biaya_periksa, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($p->status_bayar == 'Lunas')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Lunas</span>
                            @elseif($p->bukti_bayar)
                                <div class="flex flex-col gap-1">
                                    <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-center">Menunggu Verifikasi</span>
                                    <a href="{{ asset('storage/' . $p->bukti_bayar) }}" target="_blank" class="text-[10px] text-indigo-500 underline text-center font-bold">Lihat Bukti</a>
                                </div>
                            @else
                                <form action="{{ route('pasien.payment.upload', $p->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-2">
                                    @csrf
                                    <input type="file" name="bukti_bayar" class="file-input file-input-bordered file-input-xs w-full max-w-xs" required />
                                    <button type="submit" class="btn btn-xs bg-indigo-600 hover:bg-indigo-700 text-white rounded border-none font-bold uppercase">Upload Bukti</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">Tidak ada tagihan aktif.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
