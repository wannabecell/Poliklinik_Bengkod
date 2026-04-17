<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::resource('polis', \App\Http\Controllers\Admin\PoliController::class);
    Route::resource('obat', \App\Http\Controllers\Admin\ObatController::class);
    Route::resource('dokter', \App\Http\Controllers\Admin\DokterController::class);
    Route::resource('pasiens', \App\Http\Controllers\Admin\PasienController::class);
    Route::get('/riwayat', [\App\Http\Controllers\Admin\RiwayatController::class, 'index'])->name('admin.riwayat.index');
    Route::get('/riwayat/export', [\App\Http\Controllers\Admin\RiwayatController::class, 'exportExcel'])->name('admin.riwayat.export');

    // New Master Data Exports
    Route::get('/dokter-export', function() {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\DokterExport, 'data-dokter.xlsx');
    })->name('admin.dokter.export');
    
    Route::get('/pasien-export', function() {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PasienExport, 'data-pasien.xlsx');
    })->name('admin.pasien.export');
    
    Route::get('/obat-export', function() {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ObatExport, 'data-obat.xlsx');
    })->name('admin.obat.export');
});

Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->group(function () {
    Route::get('/dashboard', function () {
        return view('dokter.dashboard');
    })->name('dokter.dashboard');
    Route::resource('jadwal', \App\Http\Controllers\Dokter\JadwalPeriksaController::class);
    
    Route::get('/periksa/create/{id_daftar_poli}', [\App\Http\Controllers\Dokter\PeriksaController::class, 'createDaftar'])->name('periksa.createDaftar');
    Route::resource('periksa', \App\Http\Controllers\Dokter\PeriksaController::class)->except(['create', 'show']);

    // New Doctor Exports
    Route::get('/jadwal-export', function() {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\JadwalExport, 'jadwal-saya.xlsx');
    })->name('dokter.jadwal.export');

    Route::get('/riwayat-export', function() {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\RiwayatPasienExport, 'riwayat-pasien.xlsx');
    })->name('dokter.riwayat.export');
});

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Pasien\DashboardController::class, 'index'])->name('pasien.dashboard');
    Route::get('/queue-status/{id_jadwal}', [\App\Http\Controllers\Pasien\DashboardController::class, 'queueStatus'])->name('pasien.queueStatus');

    Route::resource('daftar', \App\Http\Controllers\Pasien\DaftarPoliController::class);
    Route::get('/get-jadwal/{id_poli}', [\App\Http\Controllers\Pasien\DaftarPoliController::class, 'getJadwal'])->name('daftar.getJadwal');
    
    // New Dedicated Menus per Requirements
    Route::get('/riwayat-pendaftaran', [\App\Http\Controllers\Pasien\DaftarPoliController::class, 'riwayat'])->name('pasien.riwayat');
    Route::get('/riwayat-pendaftaran/{id}', [\App\Http\Controllers\Pasien\DaftarPoliController::class, 'show'])->name('pasien.riwayat.show');
    Route::get('/pembayaran', [\App\Http\Controllers\Pasien\DaftarPoliController::class, 'pembayaran'])->name('pasien.pembayaran');
    Route::post('/payment/upload/{id_periksa}', [\App\Http\Controllers\PaymentController::class, 'upload'])->name('pasien.payment.upload');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/pembayaran', [\App\Http\Controllers\Admin\RiwayatController::class, 'pembayaran'])->name('admin.pembayaran');
    Route::post('/payment/validate/{id_periksa}', [\App\Http\Controllers\PaymentController::class, 'validatePayment'])->name('admin.payment.validate');
});
