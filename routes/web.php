<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JasaController;
use App\Http\Controllers\PemesananJasaController;
use App\Http\Controllers\TimController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/pemesanan-jasa', [PemesananJasaController::class, 'index'])->name('dashboard');
    Route::post('/pemesanan-jasa', [PemesananJasaController::class, 'store'])->name('pemesanan.store');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pemesanan-jasa', [PemesananJasaController::class, 'adminIndex'])->name('dashboard');
    Route::patch('/pemesanan-jasa/{pemesananJasa}/persetujuan', [PemesananJasaController::class, 'approve'])->name('pemesanan.approve');
    Route::patch('/pemesanan-jasa/{pemesananJasa}/tim', [PemesananJasaController::class, 'assignTeam'])->name('pemesanan.assign');
    Route::patch('/pemesanan-jasa/{pemesananJasa}/status', [PemesananJasaController::class, 'updateProcess'])->name('pemesanan.status');
    Route::get('/jasa', [JasaController::class, 'index'])->name('jasa.index');
    Route::post('/jasa', [JasaController::class, 'store'])->name('jasa.store');
    Route::patch('/jasa/{jasa}', [JasaController::class, 'update'])->name('jasa.update');
    Route::get('/tim', [TimController::class, 'index'])->name('tim.index');
    Route::post('/tim', [TimController::class, 'store'])->name('tim.store');
    Route::patch('/tim/{tim}', [TimController::class, 'update'])->name('tim.update');
});

Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('superadmin.')->group(function () {
    Route::get('/pemesanan-jasa', [PemesananJasaController::class, 'superAdminIndex'])->name('dashboard');
    Route::patch('/pemesanan-jasa/{pemesananJasa}/persetujuan', [PemesananJasaController::class, 'approve'])->name('pemesanan.approve');
    Route::get('/jasa', [JasaController::class, 'index'])->name('jasa.index');
    Route::post('/jasa', [JasaController::class, 'store'])->name('jasa.store');
    Route::patch('/jasa/{jasa}', [JasaController::class, 'update'])->name('jasa.update');
    Route::get('/tim', [TimController::class, 'index'])->name('tim.index');
    Route::post('/tim', [TimController::class, 'store'])->name('tim.store');
    Route::patch('/tim/{tim}', [TimController::class, 'update'])->name('tim.update');
});
