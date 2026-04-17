<x-layouts.app title="Edit Dokter">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('dokter.index') }}" class="inline-flex items-center justify-center w-9 h-9 
                  rounded-lg bg-slate-100 text-slate-500 
                  hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>

        <h2 class="text-2xl font-bold text-slate-800">
            Edit Dokter
        </h2>
    </div>

    {{-- Card --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">

        <div class="card-body p-8">

            <form action="{{ route('dokter.update', $dokter->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Nama --}}
                    <div class="mb-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" value="{{ old('nama', $dokter->nama) }}" placeholder="Contoh: Dr. Budi Setiawan"
                            class="w-full px-4 py-2 text-sm rounded-lg border-2 border-slate-300 focus:border-[#2d4499] focus:outline-none @error('nama') border-red-500 @enderror" required>
                        @error('nama')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Poliklinik --}}
                    <div class="mb-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Pilih Poliklinik <span class="text-red-500">*</span>
                        </label>
                        <select name="id_poli" class="w-full px-4 py-2 text-sm rounded-lg border-2 border-slate-300 focus:border-[#2d4499] focus:outline-none @error('id_poli') border-red-500 @enderror" required>
                            <option value="" disabled>-- Pilih Poli --</option>
                            @foreach($polis as $poli)
                                <option value="{{ $poli->id }}" {{ old('id_poli', $dokter->id_poli) == $poli->id ? 'selected' : '' }}>
                                    {{ $poli->nama_poli }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_poli')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    {{-- HP --}}
                    <div class="mb-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">No. HP/Telepon <span class="text-red-500">*</span></label>
                        <input type="number" name="no_hp" value="{{ old('no_hp', $dokter->no_hp) }}" placeholder="08..."
                            class="w-full px-4 py-2 text-sm rounded-lg border-2 border-slate-300 focus:border-[#2d4499] focus:outline-none @error('no_hp') border-red-500 @enderror" required>
                        @error('no_hp')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- KTP --}}
                    <div class="mb-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">No. KTP</label>
                        <input type="number" name="no_ktp" value="{{ old('no_ktp', $dokter->no_ktp) }}" placeholder="16 digit NIK"
                            class="w-full px-4 py-2 text-sm rounded-lg border-2 border-slate-300 focus:border-[#2d4499] focus:outline-none @error('no_ktp') border-red-500 @enderror">
                        @error('no_ktp')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Tinggal <span class="text-red-500">*</span></label>
                        <input type="text" name="alamat" value="{{ old('alamat', $dokter->alamat) }}" placeholder="Jl. Raya..."
                            class="w-full px-4 py-2 text-sm rounded-lg border-2 border-slate-300 focus:border-[#2d4499] focus:outline-none @error('alamat') border-red-500 @enderror" required>
                        @error('alamat')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <hr class="mb-6 border-slate-200">
                <p class="text-sm font-bold text-slate-800 mb-4">Informasi Akun (Kosongkan password jika tidak diubah)</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    {{-- Email --}}
                    <div class="mb-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Email Utama <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $dokter->email) }}" placeholder="dokter@gmail.com"
                            class="w-full px-4 py-2 text-sm rounded-lg border-2 border-slate-300 focus:border-[#2d4499] focus:outline-none @error('email') border-red-500 @enderror" required>
                        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Password Baru</label>
                        <input type="password" name="password" placeholder="Biarkan kosong jika tetap"
                            class="w-full px-4 py-2 text-sm rounded-lg border-2 border-slate-300 focus:border-[#2d4499] focus:outline-none @error('password') border-red-500 @enderror">
                        @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                            class="w-full px-4 py-2 text-sm rounded-lg border-2 border-slate-300 focus:border-[#2d4499] focus:outline-none">
                    </div>
                </div>

                {{-- Button --}}
                <div class="flex gap-3">
                    <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-[#2d4499] hover:bg-[#1e2d6b] 
                               text-white font-semibold text-sm transition">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>

                    <a href="{{ route('dokter.index') }}"
                        class="px-6 py-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 
                               text-slate-600 font-semibold text-sm transition">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</x-layouts.app>
