<x-layouts.app title="Manajemen Pasien">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Manajemen Pasien</h2>
        <div class="flex gap-3">
            <a href="{{ route('admin.pasien.export') }}" class="btn bg-green-600 hover:bg-green-700 text-white border-none rounded-lg shadow-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ route('pasiens.create') }}" class="btn btn-primary rounded-lg">
                <i class="fas fa-plus"></i> Tambah Pasien
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success mb-4 text-white">
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="card bg-base-100 shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-widest border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">No. RM</th>
                        <th class="px-6 py-4">Nama Pasien</th>
                        <th class="px-6 py-4">No. KTP</th>
                        <th class="px-6 py-4">No. HP</th>
                        <th class="px-6 py-4">Alamat</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pasiens as $pasien)
                    <tr class="hover:bg-slate-50 border-b border-slate-50">
                        <td class="px-6 py-4 font-mono font-bold text-[#2d4499] text-xs">{{ $pasien->no_rm }}</td>
                        <td class="px-6 py-4 font-bold text-slate-700 text-sm">{{ $pasien->nama }}</td>
                        <td class="px-6 py-4 text-xs text-slate-500">{{ $pasien->no_ktp }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ $pasien->no_hp }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500 max-w-[200px] truncate">{{ $pasien->alamat }}</td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('pasiens.destroy', $pasien->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-xs text-red-500 hover:bg-red-50">
                                    <i class="fas fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada data pasien.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
