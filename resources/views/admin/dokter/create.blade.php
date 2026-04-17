<x-layouts.app title="Tambah Dokter">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('dokter.index') }}"
            class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-xs"></i>
        </a>
        <h2 class="text-xl font-bold text-slate-800">
            Tambah Dokter
        </h2>
    </div>

    {{-- Card --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-7">

            <form action="{{ route('dokter.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Nama --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></span>
                        </label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Dr. Budi Setiawan"
                            class="input input-bordered w-full rounded-lg text-sm @error('nama') input-error @enderror" required>
                        @error('nama')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>

                    {{-- Poliklinik --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-gray-700">Pilih Poliklinik <span class="text-red-500">*</span></span>
                        </label>
                        <select name="id_poli" class="select select-bordered w-full rounded-lg text-sm @error('id_poli') select-error @enderror" required>
                            <option value="" disabled selected>-- Pilih Poli --</option>
                            @foreach($polis as $poli)
                                <option value="{{ $poli->id }}" {{ old('id_poli') == $poli->id ? 'selected' : '' }}>
                                    {{ $poli->nama_poli }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_poli')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    {{-- HP --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-gray-700">No. HP/Telepon <span class="text-red-500">*</span></span>
                        </label>
                        <input type="number" name="no_hp" value="{{ old('no_hp') }}" placeholder="08..."
                            class="input input-bordered w-full rounded-lg text-sm @error('no_hp') input-error @enderror" required>
                        @error('no_hp')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>

                    {{-- KTP --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-gray-700">No. KTP</span>
                        </label>
                        <input type="number" name="no_ktp" value="{{ old('no_ktp') }}" placeholder="16 digit NIK"
                            class="input input-bordered w-full rounded-lg text-sm @error('no_ktp') input-error @enderror">
                        @error('no_ktp')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-gray-700">Alamat Tinggal <span class="text-red-500">*</span></span>
                        </label>
                        <input type="text" name="alamat" value="{{ old('alamat') }}" placeholder="Jl. Raya..."
                            class="input input-bordered w-full rounded-lg text-sm @error('alamat') input-error @enderror" required>
                        @error('alamat')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>
                </div>

                <hr class="mb-6 border-slate-200">
                <p class="text-sm font-bold text-slate-800 mb-4">Informasi Akun (Login)</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    {{-- Email --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-gray-700">Email Utama <span class="text-red-500">*</span></span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="dokter@gmail.com"
                            class="input input-bordered w-full rounded-lg text-sm @error('email') input-error @enderror" required>
                        @error('email')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-gray-700">Password Baru <span class="text-red-500">*</span></span>
                        </label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter"
                            class="input input-bordered w-full rounded-lg text-sm @error('password') input-error @enderror" required>
                        @error('password')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-gray-700">Konfirmasi Password <span class="text-red-500">*</span></span>
                        </label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password"
                            class="input input-bordered w-full rounded-lg text-sm" required>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex items-center gap-2 px-6 py-2.5 bg-[#2d4499] hover:bg-[#1e2d6b] text-white rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-save"></i>
                        Simpan Dokter
                    </button>

                    <a href="{{ route('dokter.index') }}"
                        class="flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg text-sm font-semibold transition">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

</x-layouts.app>
