<x-layouts.app>
    <x-slot:title>
        Dashboard Admin
    </x-slot:title>

    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body">
            <h2 class="card-title text-indigo-700">Selamat datang, {{ auth()->user()->nama }}!</h2>
            <p>Ini adalah halaman utama untuk Admin. Anda memiliki akses penuh ke seluruh data dan manajemen sistem.</p>
        </div>
    </div>
</x-layouts.app>
