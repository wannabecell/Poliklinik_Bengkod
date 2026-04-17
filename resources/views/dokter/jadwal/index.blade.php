<x-layouts.app title="Jadwal Praktik">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Jadwal Praktik Saya
        </h2>

        <div class="flex gap-3">
            <a href="{{ route('dokter.jadwal.export') }}" class="btn bg-green-600 hover:bg-green-700 text-white border-none rounded-lg px-5">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </a>
            <a href="{{ route('jadwal.create') }}" class="btn bg-purple-600 hover:bg-purple-700 
                      text-white border-none rounded-lg px-5">
                <i class="fas fa-plus"></i>
                Tambah Jadwal
            </a>
        </div>
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
                            <th class="px-6 py-4">Hari Praktik</th>
                            <th class="px-6 py-4">Jam Mulai</th>
                            <th class="px-6 py-4">Jam Selesai</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Body --}}
                    <tbody>
                        @forelse($jadwals as $jadwal)
                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $jadwal->hari }}
                            </td>

                            <td class="px-6 py-4 text-slate-600 font-medium">
                                <i class="far fa-clock mr-1 text-slate-400"></i>
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                            </td>

                            <td class="px-6 py-4 text-slate-600 font-medium">
                                <i class="far fa-clock mr-1 text-slate-400"></i>
                                {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('jadwal.edit', $jadwal->id) }}" class="btn btn-sm bg-amber-500 hover:bg-amber-600 
                                                  text-white border-none rounded-lg px-4">
                                        <i class="fas fa-pen-to-square"></i>
                                        Edit
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus jadwal ini?')" class="btn btn-sm bg-red-500 hover:bg-red-600 
                                                       text-white border-none rounded-lg px-4">
                                            <i class="fas fa-trash"></i>
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-14 text-slate-400">
                                <i class="far fa-calendar-times text-3xl mb-3 block"></i>
                                Anda belum memasukkan jadwal praktik apa pun.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-layouts.app>
