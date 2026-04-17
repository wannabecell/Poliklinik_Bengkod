<x-layouts.app title="Detail Pemeriksaan">

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('pasien.riwayat') }}" class="btn btn-sm btn-circle btn-ghost">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">Detail Pemeriksaan</h2>
    </div>

    @php
        $pemeriksaan = $riwayat->periksas->first();
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Kiri: Info Pendaftaran --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card bg-base-100 shadow-sm border border-slate-200 rounded-2xl">
                <div class="card-body p-8">
                    <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-100">
                        <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                            <i class="fas fa-stethoscope text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Hasil Konsultasi</p>
                            <h3 class="text-xl font-black text-slate-800">{{ $riwayat->jadwalPeriksa->dokter->poli->nama_poli }}</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-xs font-black uppercase text-slate-400 tracking-wider mb-3">Informasi Pasien</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-[10px] text-slate-400 uppercase font-bold block mb-1">Dokter Pemeriksa</label>
                                    <p class="font-bold text-slate-700">{{ $riwayat->jadwalPeriksa->dokter->nama }}</p>
                                </div>
                                <div>
                                    <label class="text-[10px] text-slate-400 uppercase font-bold block mb-1">Waktu Periksa</label>
                                    <p class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($pemeriksaan->tgl_periksa)->format('d F Y, H:i') }} WIB</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-black uppercase text-slate-400 tracking-wider mb-3">Catatan Dokter</h4>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 italic text-slate-600 text-sm leading-relaxed">
                                "{{ $pemeriksaan->catatan }}"
                            </div>
                        </div>
                    </div>

                    <div class="mt-10">
                        <h4 class="text-xs font-black uppercase text-slate-400 tracking-wider mb-4">Resep Obat</h4>
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px]">
                                    <tr>
                                        <th>Nama Obat</th>
                                        <th>Kemasan</th>
                                        <th class="text-right">Harga Satuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pemeriksaan->detailPeriksas as $detail)
                                    <tr class="text-sm font-medium text-slate-700 border-b border-slate-50">
                                        <td>{{ $detail->obat->nama_obat }}</td>
                                        <td><span class="badge badge-ghost badge-sm uppercase font-bold">{{ $detail->obat->kemasan }}</span></td>
                                        <td class="text-right">Rp {{ number_format($detail->obat->harga, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kanan: Billing Summary --}}
        <div>
            <div class="card bg-[#2d4499] text-white shadow-xl rounded-2xl overflow-hidden sticky top-6">
                <div class="card-body p-8">
                    <h3 class="text-lg font-bold mb-6 border-b border-white/10 pb-4">Ringkasan Biaya</h3>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center text-sm">
                            <span class="opacity-70">Jasa Dokter</span>
                            <span class="font-bold">Rp 150.000</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="opacity-70">Total Obat</span>
                            <span class="font-bold">Rp {{ number_format($pemeriksaan->biaya_periksa - 150000, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-white/20">
                            <span class="text-lg font-bold">Total Bayar</span>
                            <span class="text-2xl font-black">Rp {{ number_format($pemeriksaan->biaya_periksa, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        @if($pemeriksaan->status_bayar == 'Lunas')
                            <div class="bg-green-500/20 border border-green-500/30 rounded-xl p-4 text-center">
                                <i class="fas fa-check-circle text-green-400 mb-2 block text-xl"></i>
                                <span class="text-xs font-black uppercase tracking-widest text-green-300">Pembayaran Lunas</span>
                            </div>
                        @else
                            <a href="{{ route('pasien.pembayaran') }}" class="btn bg-white hover:bg-indigo-50 text-[#2d4499] border-none font-black uppercase rounded-xl">
                                Bayar Sekarang
                            </a>
                            <p class="text-[10px] text-center opacity-70 italic">Selesaikan pembayaran & upload bukti untuk validasi admin.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-layouts.app>
