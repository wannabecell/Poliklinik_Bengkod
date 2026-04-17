<x-layouts.app title="Riwayat Pendaftaran">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Riwayat Pendaftaran Saya</h2>
    </div>

    @if(session('success'))
    <div class="alert alert-success mb-4 rounded-xl shadow-sm text-white">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 gap-4">
        @forelse($riwayats as $riwayat)
        <div class="card bg-base-100 shadow-sm border border-slate-200 rounded-2xl overflow-hidden hover:shadow-md transition">
            <div class="card-body p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 shrink-0">
                            <i class="fas fa-hospital text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">{{ $riwayat->jadwalPeriksa->dokter->poli->nama_poli }}</h3>
                            <p class="text-sm text-slate-500">{{ $riwayat->jadwalPeriksa->dokter->nama }}</p>
                            <div class="mt-2 flex items-center gap-4 text-xs font-medium text-slate-400">
                                <span><i class="far fa-calendar-alt mr-1"></i> {{ $riwayat->created_at->format('d M Y') }}</span>
                                <span class="badge badge-outline border-slate-200 font-bold">#{{ $riwayat->no_antrian }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-3">
                        @if($riwayat->periksas->count() > 0)
                            @php $p = $riwayat->periksas->first(); @endphp
                            <div class="flex flex-col items-end">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-2">SELESAI</span>
                                <div class="flex gap-2">
                                    <a href="{{ route('pasien.riwayat.show', $riwayat->id) }}" class="btn btn-sm bg-slate-100 hover:bg-slate-200 border-none text-slate-700 rounded-lg">
                                        Detail Pemeriksaan
                                    </a>
                                    @if($p->status_bayar != 'Lunas')
                                    <a href="{{ route('pasien.pembayaran') }}" class="btn btn-sm bg-indigo-600 hover:bg-indigo-700 text-white border-none rounded-lg">
                                        Bayar Sekarang
                                    </a>
                                    @endif
                                </div>
                            </div>
                        @else
                            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">MENUNGGU</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card bg-base-100 border border-slate-200 border-dashed rounded-2xl">
            <div class="card-body p-12 text-center text-slate-400">
                <i class="fas fa-notes-medical text-4xl mb-4 opacity-30"></i>
                <p>Belum ada riwayat pendaftaran.</p>
                <a href="{{ route('daftar.create') }}" class="mt-4 text-indigo-600 font-bold hover:underline">Daftar sekarang &rarr;</a>
            </div>
        </div>
        @endforelse
    </div>

</x-layouts.app>
