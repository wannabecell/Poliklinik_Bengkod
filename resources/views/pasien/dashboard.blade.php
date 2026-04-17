<x-layouts.app title="Dashboard Pasien">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        {{-- Welcome Card --}}
        <div class="col-span-full">
            <div class="card bg-gradient-to-r from-[#2d4499] to-[#1e2d6b] text-white shadow-xl rounded-2xl">
                <div class="card-body p-8">
                    <h2 class="text-3xl font-bold mb-2">Selamat Datang, {{ auth()->user()->nama }}!</h2>
                    <p class="text-indigo-100 opacity-90">Pantau status antrean dan riwayat pemeriksaan Anda secara real-time di sini.</p>
                </div>
            </div>
        </div>

        @if($latestDaftar)
            @php $isSelesai = $latestDaftar->periksas->count() > 0; @endphp
            {{-- Queue Status Card --}}
            <div class="card bg-base-100 shadow-md border border-slate-200 rounded-2xl overflow-hidden">
                <div class="p-5 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Status Antrean Hari Ini</span>
                    @if($isSelesai)
                        <span class="badge badge-success badge-sm text-white font-bold tracking-tighter">SELESAI</span>
                    @else
                        <span class="badge badge-primary badge-sm">Aktif</span>
                    @endif
                </div>
                <div class="card-body p-6 text-center">
                    <p class="text-sm text-slate-400 mb-1">Nomor Antrean Anda</p>
                    <h3 class="text-5xl font-black {{ $isSelesai ? 'text-slate-300' : 'text-[#2d4499]' }} mb-4">{{ $latestDaftar->no_antrian }}</h3>
                    
                    @if($isSelesai)
                        <div class="bg-green-50 rounded-xl p-4 mb-2 border border-green-100">
                             <p class="text-xs text-green-600 font-bold mb-1"><i class="fas fa-circle-check mr-1"></i> Pemeriksaan Selesai</p>
                             <p class="text-xs text-green-800 leading-relaxed px-2">Anda sudah selesai diperiksa oleh dokter. Sila cek riwayat untuk detail tagihan.</p>
                        </div>
                    @else
                        <div class="bg-indigo-50 rounded-xl p-4 mb-2">
                            <p class="text-xs text-indigo-500 font-bold mb-1">Sedang Dipanggil</p>
                            <h4 id="current_queue" class="text-2xl font-bold text-indigo-800">--</h4>
                        </div>
                        
                        <div class="flex items-center justify-center gap-2 text-xs text-slate-400">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            Live Update (Setiap 5 detik)
                        </div>
                    @endif
                </div>
            </div>

            {{-- Info Poli Card --}}
            <div class="card bg-base-100 shadow-md border border-slate-200 rounded-2xl">
                <div class="p-5 bg-slate-50 border-b border-slate-100 italic">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Detail Pendaftaran</span>
                </div>
                <div class="card-body p-6">
                    <div class="mb-4">
                        <label class="text-xs text-slate-400 block mb-1">Poliklinik</label>
                        <p class="font-bold text-slate-800">{{ $latestDaftar->jadwalPeriksa->dokter->poli->nama_poli }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="text-xs text-slate-400 block mb-1">Dokter</label>
                        <p class="font-bold text-slate-800">{{ $latestDaftar->jadwalPeriksa->dokter->nama }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-slate-400 block mb-1">Jadwal</label>
                        <p class="text-sm font-semibold text-slate-600">
                            {{ $latestDaftar->jadwalPeriksa->hari }}, 
                            {{ \Carbon\Carbon::parse($latestDaftar->jadwalPeriksa->jam_mulai)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($latestDaftar->jadwalPeriksa->jam_selesai)->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        @else
            {{-- Empty State --}}
            <div class="col-span-1 lg:col-span-2 card bg-base-100 shadow-md border border-slate-200 rounded-2xl border-dashed">
                <div class="card-body p-10 text-center flex flex-col items-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-calendar-plus text-2xl text-slate-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Antrean</h3>
                    <p class="text-sm text-slate-400 mb-6">Anda belum mendaftar poliklinik. Silakan pilih poli untuk mendapatkan nomor antrean.</p>
                    <a href="{{ route('daftar.create') }}" class="btn btn-primary rounded-xl px-8">Daftar Sekarang</a>
                </div>
            </div>
        @endif

        {{-- Statistics / Quick Access --}}
        <div class="card bg-indigo-50 shadow-sm border border-indigo-100 rounded-2xl overflow-hidden">
             <div class="card-body p-6">
                <p class="text-indigo-600 font-bold text-sm mb-4">Akses Cepat</p>
                <div class="flex flex-col gap-3">
                    <a href="{{ route('daftar.index') }}" class="flex items-center justify-between p-3 bg-white rounded-xl shadow-sm hover:shadow-md transition">
                        <span class="text-sm font-semibold text-slate-700">Riwayat Periksa</span>
                        <i class="fas fa-arrow-right text-xs text-slate-300"></i>
                    </a>
                </div>
             </div>
        </div>

    </div>

    @if($latestDaftar)
    @push('scripts')
    @vite(['resources/js/app.js']) {{-- Ensure Echo is loaded --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentQueueEl = document.getElementById('current_queue');
            const idJadwal = "{{ $latestDaftar->id_jadwal }}";
            const myQueueNumber = "{{ $latestDaftar->no_antrian }}";

            // 1. DENGAN LARAVEL ECHO (REAL-TIME WEBSOCKET)
            if (typeof window.Echo !== 'undefined') {
                console.log('Echo connected. Listening for queue updates on channel queue.' + idJadwal);
                window.Echo.channel('queue.' + idJadwal)
                    .listen('.queue.updated', (e) => {
                        console.log('Real-time update received:', e);
                        updateUI(e.current_queue);
                    });
            } else {
                // FALLBACK: POLLING (Jika WebSocket Server belum aktif)
                console.warn('Echo not found. Falling back to 5s polling.');
                setInterval(() => {
                    fetch(`/pasien/queue-status/${idJadwal}`)
                        .then(res => res.json())
                        .then(data => updateUI(data.current));
                }, 5000);
            }

            function updateUI(current) {
                if (!currentQueueEl) return;
                
                currentQueueEl.innerText = current === 0 ? "Belum Dimulai" : "#" + current;
                
                // Jika nomor antrean sekarang sudah menyentuh atau melampaui nomor pasien, 
                // Segarkan halaman untuk menampilkan status 'Selesai' (Banner Hijau & Riwayat)
                if(current >= myQueueNumber) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            }
        });
    </script>
    @endpush
    @endif

</x-layouts.app>
