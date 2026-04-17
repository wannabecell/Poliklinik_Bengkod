<x-layouts.app title="Pendaftaran Poli">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('daftar.index') }}"
            class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-xs"></i>
        </a>
        <h2 class="text-xl font-bold text-slate-800">
            Pendaftaran Poli
        </h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Profil Pasien Singkat --}}
        <div class="lg:col-span-1">
            <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
                <div class="card-body p-6">
                    <p class="text-xs font-bold uppercase tracking-widest text-[#2d4499] mb-4">
                        Informasi Pasien
                    </p>
                    
                    <div class="mb-4">
                        <p class="text-xs text-slate-400 font-semibold mb-1">Nomor Rekam Medis</p>
                        <p class="font-mono bg-slate-100 px-3 py-2 rounded-lg font-bold text-slate-700">
                            {{ auth()->user()->no_rm ?? 'Belum ter-generate' }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <p class="text-xs text-slate-400 font-semibold mb-1">Nama Pasien</p>
                        <p class="font-semibold text-slate-800">{{ auth()->user()->nama }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Formulir Pendaftaran --}}
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
                <div class="card-body p-7">

                    {{-- Alert jika error validasi atau sistem --}}
                    @if(session('error'))
                    <div class="alert alert-error mb-5 rounded-xl shadow-sm text-sm">
                        <i class="fas fa-circle-xmark"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    @endif

                    <form action="{{ route('daftar.store') }}" method="POST">
                        @csrf

                        {{-- Pilih Poli --}}
                        <div class="form-control mb-5">
                            <label class="label pb-1">
                                <span class="text-sm font-semibold text-gray-700">Pilih Poliklinik <span class="text-red-500">*</span></span>
                            </label>
                            
                            <select id="pilih_poli" class="select select-bordered w-full rounded-lg text-sm" required>
                                <option value="" disabled selected>-- Pilih Poli --</option>
                                @foreach($polis as $poli)
                                    <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                                @endforeach
                            </select>
                            <label class="label pt-1 pb-0">
                                <span class="label-text-alt text-slate-400"><i class="fas fa-info-circle mr-1"></i>Pilih Poli terlebih dahulu guna melihat jadwal dokter yang tersedia.</span>
                            </label>
                        </div>

                        {{-- Pilih Jadwal (Disabled by default, filled via AJAX) --}}
                        <div class="form-control mb-5">
                            <label class="label pb-1">
                                <span class="text-sm font-semibold text-gray-700">Pilih Jadwal Dokter <span class="text-red-500">*</span></span>
                            </label>
                            
                            <select id="pilih_jadwal" name="id_jadwal" class="select select-bordered w-full rounded-lg text-sm @error('id_jadwal') select-error @enderror" required disabled>
                                <option value="" disabled selected>-- Pilih Poli terlebih dahulu --</option>
                            </select>
                            @error('id_jadwal')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                        </div>

                        {{-- Keluhan --}}
                        <div class="form-control mb-6">
                            <label class="label pb-1">
                                <span class="text-sm font-semibold text-gray-700">Keluhan yang dirasakan <span class="text-red-500">*</span></span>
                            </label>

                            <textarea name="keluhan" rows="4" placeholder="Jelaskan keluhan Anda sedetail mungkin..."
                                class="textarea textarea-bordered w-full rounded-lg text-sm resize-none @error('keluhan') textarea-error @enderror"
                                required>{{ old('keluhan') }}</textarea>
                            @error('keluhan')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                        </div>


                        {{-- Buttons --}}
                        <div class="flex gap-3">
                            <button type="submit" id="btn_submit" disabled
                                class="flex items-center gap-2 px-6 py-2.5 bg-amber-500 hover:bg-amber-600 disabled:bg-slate-300 disabled:text-slate-500 text-white rounded-lg text-sm font-semibold transition cursor-not-allowed">
                                <i class="fas fa-ticket-alt"></i>
                                Ambil Antrean
                            </button>

                            <a href="{{ route('daftar.index') }}"
                                class="flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg text-sm font-semibold transition">
                                Batal
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const poliSelect = document.getElementById('pilih_poli');
            const jadwalSelect = document.getElementById('pilih_jadwal');
            const btnSubmit = document.getElementById('btn_submit');

            poliSelect.addEventListener('change', function() {
                const poliId = this.value;

                // Reset Jadwal dropdown
                jadwalSelect.innerHTML = '<option value="" disabled selected>Sedang memuat jadwal...</option>';
                jadwalSelect.disabled = true;
                btnSubmit.disabled = true;
                btnSubmit.classList.add('cursor-not-allowed');

                if (poliId) {
                    fetch(`/pasien/get-jadwal/${poliId}`)
                        .then(response => response.json())
                        .then(data => {
                            jadwalSelect.innerHTML = '<option value="" disabled selected>-- Pilih Jadwal & Dokter --</option>';
                            
                            if(data.length > 0) {
                                data.forEach(jadwal => {
                                    // Parse jam mulai & selesai (format HH:mm:ss to HH:mm)
                                    const jamMulai = jadwal.jam_mulai.substring(0, 5);
                                    const jamSelesai = jadwal.jam_selesai.substring(0, 5);
                                    const namaDokter = jadwal.dokter.nama;

                                    const opt = document.createElement('option');
                                    opt.value = jadwal.id;
                                    opt.textContent = `${namaDokter} | ${jadwal.hari} (${jamMulai} - ${jamSelesai})`;
                                    jadwalSelect.appendChild(opt);
                                });
                                jadwalSelect.disabled = false;
                                // Enable button only when User actually selects a valid schedule
                                jadwalSelect.addEventListener('change', function() {
                                    if(this.value) {
                                        btnSubmit.disabled = false;
                                        btnSubmit.classList.remove('cursor-not-allowed');
                                    }
                                });
                            } else {
                                jadwalSelect.innerHTML = '<option value="" disabled selected>-- Tidak ada dokter yang tersedia di Poli ini --</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            jadwalSelect.innerHTML = '<option value="" disabled selected>-- Gagal memuat data --</option>';
                        });
                }
            });
        });
    </script>
    @endpush

</x-layouts.app>
