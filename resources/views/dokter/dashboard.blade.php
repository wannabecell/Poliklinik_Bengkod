<x-layouts.app>
    <x-slot:title>
        Dashboard Dokter
    </x-slot:title>

    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body">
            <h2 class="card-title text-purple-700">Selamat datang, {{ auth()->user()->nama }}!</h2>
            <p>Ini adalah halaman utama untuk Dokter. Anda dapat melihat jadwal periksa dan memeriksa pasien.</p>
        </div>
    </div>
</x-layouts.app>
