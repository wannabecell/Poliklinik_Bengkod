<x-layouts.app title="Tambah Pasien">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Tambah Pasien Baru</h2>
        <a href="{{ route('pasiens.index') }}" class="btn btn-ghost rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="card bg-base-100 shadow-sm border border-slate-200 rounded-2xl">
        <div class="card-body p-8">
            <form action="{{ route('pasiens.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold text-slate-700">Nama Pasien</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" class="input input-bordered w-full rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary @error('nama') input-error @enderror" required>
                        @error('nama')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold text-slate-700">Nomor Rekam Medis (RM)</span></label>
                        <input type="text" name="no_rm" value="{{ old('no_rm') }}" placeholder="Contoh: RM-20231001-001" class="input input-bordered w-full rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary font-mono text-sm @error('no_rm') input-error @enderror" required>
                        @error('no_rm')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold text-slate-700">Nomor KTP</span></label>
                        <input type="number" name="no_ktp" value="{{ old('no_ktp') }}" placeholder="Masukkan 16 digit NIK" class="input input-bordered w-full rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary @error('no_ktp') input-error @enderror" required>
                        @error('no_ktp')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold text-slate-700">Nomor HP (WhatsApp)</span></label>
                        <input type="number" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890" class="input input-bordered w-full rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary @error('no_hp') input-error @enderror" required>
                        @error('no_hp')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold text-slate-700">Email Akun</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email untuk login" class="input input-bordered w-full rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary @error('email') input-error @enderror" required>
                        @error('email')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control w-full md:col-span-2">
                        <label class="label"><span class="label-text font-semibold text-slate-700">Alamat Lengkap</span></label>
                        <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap" class="textarea textarea-bordered w-full rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-base @error('alamat') textarea-error @enderror" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="reset" class="btn btn-ghost rounded-xl">Reset</button>
                    <button type="submit" class="btn btn-primary rounded-xl px-8 shadow-sm">
                        <i class="fas fa-save mr-2"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
