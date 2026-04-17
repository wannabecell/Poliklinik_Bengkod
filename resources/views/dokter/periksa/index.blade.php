<x-layouts.app title="Daftar Pasien">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Daftar Pasien
        </h2>
        <a href="{{ route('dokter.riwayat.export') }}" class="btn bg-green-600 hover:bg-green-700 text-white border-none rounded-lg px-5 shadow-sm">
            <i class="fas fa-file-excel"></i>
            Export Histori Pasien
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="alert alert-success mb-4 rounded-xl shadow-sm">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-error mb-4 rounded-xl shadow-sm">
        <i class="fas fa-circle-xmark"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Card --}}
    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">

                    {{-- Head --}}
                    <thead class="bg-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Status & Waktu Daftar</th>
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">No. Antrean & Jadwal</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Body --}}
                    <tbody>
                        @forelse($daftarPolis as $daftar)
                        <tr class="hover:bg-slate-50 transition">

                            {{-- Status Flag --}}
                            <td class="px-6 py-4">
                                @if($daftar->periksas->count() > 0)
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold inline-block mb-2">
                                        <i class="fas fa-check mr-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold inline-block mb-2">
                                        <i class="fas fa-clock mr-1"></i> Menunggu
                                    </span>
                                @endif
                                <div class="text-xs text-slate-400 font-medium whitespace-nowrap">
                                    {{ $daftar->created_at->diffForHumans() }}
                                </div>
                            </td>

                            {{-- Info Pasien --}}
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $daftar->pasien->nama ?? '-' }}</div>
                                <div class="text-xs font-mono text-slate-500 mt-1">RM: {{ $daftar->pasien->no_rm ?? '-' }}</div>
                            </td>

                            {{-- Jadwal & Antrean --}}
                            <td class="px-6 py-4 text-sm">
                                <div class="font-bold text-[#2d4499] text-xl mb-1">
                                    Antrean {{ $daftar->no_antrian }}
                                </div>
                                <div class="text-slate-500">
                                    {{ $daftar->jadwalPeriksa->hari }} ({{ \Carbon\Carbon::parse($daftar->jadwalPeriksa->jam_mulai)->format('H:i') }})
                                </div>
                            </td>

                            {{-- Action --}}
                            <td class="px-6 py-4 text-center">
                                @if($daftar->periksas->count() > 0)
                                    <a href="{{ route('periksa.edit', $daftar->periksas->first()->id) }}" class="btn btn-sm bg-indigo-100 hover:bg-indigo-200 
                                                  text-indigo-700 border-none rounded-lg px-4 font-semibold">
                                        <i class="fas fa-edit"></i>
                                        Edit Pemeriksaan
                                    </a>
                                @else
                                    <a href="{{ route('periksa.createDaftar', $daftar->id) }}" class="btn btn-sm bg-[#2d4499] hover:bg-[#1e2d6b] 
                                                  text-white border-none rounded-lg px-4 btn-block shadow-sm font-semibold">
                                        <i class="fas fa-stethoscope"></i>
                                        Periksa Sekarang
                                    </a>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-14 text-slate-400">
                                <i class="fas fa-users-slash text-3xl mb-3 block"></i>
                                Belum ada pasien yang mendaftar ke jadwal Anda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-layouts.app>
