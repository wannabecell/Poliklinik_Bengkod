<x-layouts.app>
    <x-slot:title>
        Dashboard Pasien
    </x-slot:title>

    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body">
            <h2 class="card-title text-amber-700">Selamat datang, {{ auth()->user()->nama }}!</h2>
            <p>Ini adalah halaman utama untuk Pasien. Anda dapat mendaftar periksa dan melihat riwayat pemeriksaan Anda.</p>
        </div>
    </div>
</x-layouts.app>
