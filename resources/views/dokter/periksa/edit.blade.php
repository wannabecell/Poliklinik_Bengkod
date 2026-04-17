<x-layouts.app title="Edit Pemeriksaan Pasien">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('periksa.index') }}"
            class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-xs"></i>
        </a>
        <h2 class="text-xl font-bold text-slate-800">
            Edit Rekam Medis
        </h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Profil Pasien Singkat --}}
        <div class="lg:col-span-1">
            <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200 sticky top-24">
                <div class="card-body p-6">
                    <p class="text-xs font-bold uppercase tracking-widest text-[#2d4499] mb-4">
                        Data Pasien
                    </p>
                    
                    <div class="font-bold text-lg text-slate-800 mb-1">{{ $periksa->daftarPoli->pasien->nama }}</div>
                    <div class="font-mono bg-slate-100 italic px-3 py-1 inline-block rounded-md text-xs font-bold text-slate-600 mb-4">
                        RM: {{ $periksa->daftarPoli->pasien->no_rm ?? '-' }}
                    </div>

                    <div class="bg-amber-50 p-4 rounded-xl border border-amber-100 mt-2">
                        <p class="text-xs text-amber-600 font-bold mb-2"><i class="fas fa-comment-medical mr-1"></i> Keluhan Awal:</p>
                        <p class="text-sm font-medium text-amber-900 leading-relaxed">{{ $periksa->daftarPoli->keluhan }}</p>
                    </div>

                    <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 mt-4">
                        <p class="text-xs text-indigo-600 font-bold mb-1">Total Penagihan:</p>
                        <p class="text-xl font-black text-indigo-800">Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Formulir Pemeriksaan Edit --}}
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
                <div class="card-body p-7">

                    <form action="{{ route('periksa.update', $periksa->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        

                        {{-- Tanggal Pemeriksaan --}}
                        <div class="form-control mb-5">
                            <label class="label pb-1">
                                <span class="text-sm font-semibold text-gray-700">Tanggal Pemeriksaan <span class="text-red-500">*</span></span>
                            </label>
                            
                            <input type="datetime-local" name="tgl_periksa" value="{{ old('tgl_periksa', \Carbon\Carbon::parse($periksa->tgl_periksa)->format('Y-m-d\TH:i')) }}"
                                class="input input-bordered w-full rounded-lg text-sm @error('tgl_periksa') input-error @enderror" required>
                                
                            @error('tgl_periksa')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                        </div>

                        {{-- Catatan Medis --}}
                        <div class="form-control mb-6">
                            <label class="label pb-1">
                                <span class="text-sm font-semibold text-gray-700">Catatan & Diagnosa <span class="text-red-500">*</span></span>
                            </label>

                            <textarea name="catatan" rows="5" placeholder="Masukkan diagnosa atau catatan medis untuk pasien..."
                                class="textarea textarea-bordered w-full rounded-lg text-sm resize-none @error('catatan') textarea-error @enderror"
                                required>{{ old('catatan', $periksa->catatan) }}</textarea>
                            @error('catatan')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                        </div>

                        <hr class="mb-6 border-slate-200 border-dashed">
                        
                        <p class="text-sm font-bold text-slate-800 mb-4 tracking-wide"><i class="fas fa-pills text-[#2d4499] mr-1"></i> Resep Obat (Opsional)</p>
                        
                        {{-- Checklist Obat --}}
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-8 h-64 overflow-y-auto">
                            @if($obats->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($obats as $obat)
                                    <label class="cursor-pointer label justify-start gap-4 p-3 rounded-lg border border-transparent hover:border-slate-300 hover:bg-white transition bg-white shadow-sm">
                                        <input type="checkbox" name="obats[]" value="{{ $obat->id }}" 
                                            class="checkbox checkbox-primary checkbox-sm border-2 rounded" 
                                            {{ in_array($obat->id, old('obats', $selectedObatIds)) ? 'checked' : '' }} />
                                        <div class="flex-1">
                                            <span class="label-text font-bold block text-slate-700 text-sm mb-0.5">{{ $obat->nama_obat }}</span>
                                            <span class="text-xs text-indigo-600 font-semibold flex items-center gap-1">
                                                Rp {{ number_format($obat->harga, 0, ',', '.') }}
                                                @if($obat->kemasan) <span class="text-slate-400 font-normal">/ {{ $obat->kemasan }}</span> @endif
                                            </span>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-slate-500 italic text-center my-4">Database obat kosong.</p>
                            @endif
                        </div>

                        {{-- Buttons --}}
                        <div class="flex gap-3">
                            <button type="submit"
                                class="flex items-center gap-2 px-8 py-3 bg-[#2d4499] hover:bg-[#1e2d6b] text-white rounded-xl text-sm font-bold transition shadow-lg shadow-indigo-900/20">
                                <i class="fas fa-save"></i>
                                Update Rekam Medis
                            </button>

                            <a href="{{ route('periksa.index') }}"
                                class="flex items-center gap-2 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition">
                                Batal
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>

</x-layouts.app>
