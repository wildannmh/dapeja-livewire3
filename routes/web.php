<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Dashboard;
use App\Livewire\Jabatan;
use App\Livewire\UnitKerja;
use App\Livewire\Pegawai;
use App\Livewire\Cuti;
use App\Livewire\Laporan\RekapAbsensi;
use App\Livewire\Cuti\AjukanCuti;
use App\Livewire\Cuti\RiwayatCuti;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/jabatan', Jabatan\Index::class)->name('jabatan.index');
    Route::get('/unitkerja', UnitKerja\Index::class)->name('unitkerja.index');
    Route::get('/pegawai', Pegawai\Index::class)->name('pegawai.index');
    Route::get('/laporan/absensi', RekapAbsensi::class)->name('laporan.absensi');
    Route::get('/cuti/ajukan', AjukanCuti::class)->name('cuti.ajukan');
    Route::get('/cuti/riwayat', RiwayatCuti::class)->name('cuti.riwayat');
});

require __DIR__.'/auth.php';
