<x-layouts.app title="Riwayat Pendaftaran">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Riwayat Pendaftaran Pasien
        </h2>

        <a href="{{ route('admin.riwayat.export') }}" class="btn bg-green-600 hover:bg-green-700 
                  text-white border-none rounded-lg px-5 shadow-lg shadow-green-900/10 transition">
            <i class="fas fa-file-excel"></i>
            Export ke Excel
        </a>
    </div>

    {{-- Card --}}
    <div class="card bg-base-100 shadow-md rounded-2 border border-slate-200">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">

                    {{-- Head --}}
                    <thead class="bg-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">Poli & Dokter</th>
                            <th class="px-6 py-4">Waktu Daftar</th>
                            <th class="px-6 py-4">Antrean</th>
                            <th class="px-6 py-4">Biaya</th>
                            <th class="px-6 py-4 text-center">Status Bayar</th>
                        </tr>
                    </thead>

                    {{-- Body --}}
                    <tbody>
                        @forelse($riwayats as $riwayat)
                        @php
                            $pemeriksaan = $riwayat->periksas->first();
                        @endphp
                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $riwayat->pasien->nama ?? '-' }}</div>
                                <div class="text-xs font-mono text-slate-400 mt-0.5">RM: {{ $riwayat->pasien->no_rm ?? '-' }}</div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-slate-700">{{ $riwayat->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</div>
                                <div class="text-xs text-slate-500 mt-1">{{ $riwayat->jadwalPeriksa->dokter->nama ?? '-' }}</div>
                            </td>

                            <td class="px-6 py-4 text-xs text-slate-500">
                                <div>{{ $riwayat->created_at->format('d M Y') }}</div>
                                <div class="opacity-70 mt-1">{{ $riwayat->created_at->format('H:i') }} WIB</div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="badge badge-outline border-slate-300 font-bold text-[#2d4499]">
                                    #{{ $riwayat->no_antrian }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @if($pemeriksaan)
                                    <span class="text-indigo-600 font-bold text-sm">Rp {{ number_format($pemeriksaan->biaya_periksa, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-slate-300 italic text-xs">Belum Diperiksa</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($pemeriksaan)
                                    <div class="flex flex-col items-center gap-2">
                                        @if($pemeriksaan->status_bayar == 'Lunas')
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                                                Lunas
                                            </span>
                                        @else
                                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                                                Belum Lunas
                                            </span>
                                            
                                            @if($pemeriksaan->bukti_bayar)
                                                <div class="mt-1 flex flex-col gap-2">
                                                    <a href="{{ asset('storage/' . $pemeriksaan->bukti_bayar) }}" target="_blank" class="text-[10px] text-blue-600 underline font-bold">
                                                        Lihat Bukti
                                                    </a>
                                                    <form action="{{ route('admin.payment.validate', $pemeriksaan->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs bg-[#2d4499] hover:bg-[#1e2d6b] text-white border-none rounded font-bold shadow-sm px-3 uppercase text-[10px]">
                                                            Validasi
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-14 text-slate-400">
                                <i class="fas fa-history text-3xl mb-3 block"></i>
                                Belum ada riwayat pendaftaran di dalam sistem.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-layouts.app>
