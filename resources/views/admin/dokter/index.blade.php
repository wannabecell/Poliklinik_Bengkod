<x-layouts.app title="Manajemen Dokter">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Manajemen Dokter
        </h2>

        <div class="flex gap-3">
            <a href="{{ route('admin.dokter.export') }}" class="btn bg-green-600 hover:bg-green-700 text-white border-none rounded-lg px-5">
                <i class="fas fa-file-excel"></i>
                Export Excel
            </a>
            <a href="{{ route('dokter.create') }}" class="btn bg-[#2d4499] hover:bg-[#1e2d6b] 
                      text-white border-none rounded-lg px-5">
                <i class="fas fa-plus"></i>
                Tambah Dokter
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
                            <th class="px-6 py-4">Nama Dokter</th>
                            <th class="px-6 py-4">Kontak (HP/Email)</th>
                            <th class="px-6 py-4">Poliklinik</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Body --}}
                    <tbody>
                        @forelse($dokters as $dokter)
                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $dokter->nama }}
                                <div class="text-xs text-slate-400 font-normal mt-1">KTP: {{ $dokter->no_ktp ?? '-' }}</div>
                            </td>

                            <td class="px-6 py-4 text-slate-500 text-sm">
                                <div><i class="fas fa-phone mr-1 w-3"></i> {{ $dokter->no_hp }}</div>
                                <div><i class="fas fa-envelope mr-1 w-3"></i> {{ $dokter->email }}</div>
                            </td>

                            <td class="px-6 py-4">
                                @if($dokter->poli)
                                <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $dokter->poli->nama_poli }}
                                </span>
                                @else
                                <span class="text-slate-400 italic text-sm">Belum diatur</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('dokter.edit', $dokter->id) }}" class="btn btn-sm bg-amber-500 hover:bg-amber-600 
                                                  text-white border-none rounded-lg px-4">
                                        <i class="fas fa-pen-to-square"></i>
                                        Edit
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('dokter.destroy', $dokter->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus dokter ini?')" class="btn btn-sm bg-red-500 hover:bg-red-600 
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
                                <i class="fas fa-user-doctor text-3xl mb-3 block"></i>
                                Belum ada data dokter
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-layouts.app>
