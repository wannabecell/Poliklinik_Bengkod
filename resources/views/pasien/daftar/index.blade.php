<x-layouts.app title="Riwayat Pendaftaran Poli">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Riwayat Pendaftaran
        </h2>

        <a href="{{ route('daftar.create') }}" class="btn bg-amber-500 hover:bg-amber-600 
                  text-white border-none rounded-lg px-5">
            <i class="fas fa-stethoscope"></i>
            Daftar Poli
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
                            <th class="px-6 py-4">Poli & Dokter</th>
                            <th class="px-6 py-4">Jadwal Periksa</th>
                            <th class="px-6 py-4">Nomor Antrean</th>
                            <th class="px-6 py-4">Status Layanan</th>
                            <th class="px-6 py-4">Tagihan & Pembayaran</th>
                        </tr>
                    </thead>

                    {{-- Body --}}
                    <tbody>
                        @forelse($riwayats as $riwayat)
                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $riwayat->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</div>
                                <div class="text-sm text-slate-500 mt-1">{{ $riwayat->jadwalPeriksa->dokter->nama ?? '-' }}</div>
                            </td>

                            <td class="px-6 py-4 text-slate-600 font-medium text-sm">
                                <div class="mb-1"><i class="far fa-calendar-alt w-4 text-slate-400"></i> {{ $riwayat->jadwalPeriksa->hari ?? '-' }}</div>
                                <div>
                                    <i class="far fa-clock w-4 text-slate-400"></i> 
                                    {{ \Carbon\Carbon::parse($riwayat->jadwalPeriksa->jam_mulai)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($riwayat->jadwalPeriksa->jam_selesai)->format('H:i') }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="w-10 h-10 bg-indigo-100 text-indigo-700 rounded-lg flex items-center justify-center font-black text-lg">
                                    {{ $riwayat->no_antrian }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @if($riwayat->periksas->count() > 0)
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Selesai Periksa</span>
                                @else
                                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-semibold">Menunggu</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if($riwayat->periksas->count() > 0)
                                    @php $periksa = $riwayat->periksas->first(); @endphp
                                    <div class="mb-2">
                                        <span class="text-indigo-600 font-bold">Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    @if($periksa->status_bayar == 'Lunas')
                                        <span class="badge badge-success badge-sm font-bold text-white">LUNAS</span>
                                    @elseif($periksa->bukti_bayar)
                                        <span class="badge badge-warning badge-sm font-bold">MENUNGGU VALIDASI</span>
                                    @else
                                        <form action="{{ route('pasien.payment.upload', $periksa->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-2">
                                            @csrf
                                            <input type="file" name="bukti_bayar" class="file-input file-input-bordered file-input-xs w-full max-w-xs" required />
                                            <button type="submit" class="btn btn-xs btn-primary rounded">Upload Bukti</button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-slate-300 italic text-xs">Belum ada tagihan</span>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-14 text-slate-400">
                                <i class="fas fa-notes-medical text-3xl mb-3 block"></i>
                                Anda belum pernah mendaftar periksa.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-layouts.app>
