<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasienKominfoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

//login
Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');

Route::get('home', [HomeController::class, 'home'])->name('home')->middleware('auth');
Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');

Route::get('forbidden', [HomeController::class, 'forbidden'])->name('forbidden')->middleware('auth');

Route::get('logFarmasi', [HomeController::class, 'logFarmasi'])->name('logFarmasi')->middleware('auth');
//menu
Route::middleware('auth')->group(function () {
    Route::get('report', [HomeController::class, 'report'])->name('report');
    Route::get('Laporan/Pendaftaran', [HomeController::class, 'laporanPendaftaran'])->name('laporanPendaftaran');
    //farmasi
    Route::get('farmasi', [HomeController::class, 'farmasi'])->name('farmasi')->middleware('role:farmasi');
    Route::get('gudangFarmasi', [HomeController::class, 'gudangFarmasi'])->name('gudangFarmasi')->middleware('role:farmasi');
    //dots
    Route::get('dots', [HomeController::class, 'dots'])->name('dots')->middleware('role:dots');
    //igd
    Route::get('askep', [HomeController::class, 'askep'])->name('askep')->middleware('role:igd');
    Route::get('igd', [HomeController::class, 'igd'])->name('igd')->middleware('role:igd');
    Route::get('gudangIGD', [HomeController::class, 'gudangIGD'])->name('gudangIGD')->middleware('role:igd');
    //Kasir
    Route::get('kasir', [HomeController::class, 'kasir'])->name('kasir')->middleware('role:kasir');
    Route::get('lte', [HomeController::class, 'lte'])->name('lte')->middleware('role:kasir');
    //Laborat
    Route::get('lab', [HomeController::class, 'lab'])->name('lab')->middleware('role:lab');
    Route::get('hasilLab', [HomeController::class, 'hasilLab'])->name('hasilLab')->middleware('role:lab');
    Route::get('riwayatLab', [HomeController::class, 'riwayatLab'])->name('riwayatLab')->middleware('role:lab');
    Route::get('masterLab', [HomeController::class, 'masterLab'])->name('masterLab')->middleware('role:lab');
    //RO
    Route::get('ro', [HomeController::class, 'ro'])->name('ro')->middleware('role:ro');
    Route::get('masterRo', [HomeController::class, 'masterRo'])->name('masterRo')->middleware('role:ro');
    Route::get('riwayatRo', [HomeController::class, 'riwayatRo'])->name('hasilRo')->middleware('role:ro');
    Route::post('waktu_layanan', [PasienKominfoController::class, 'waktuLayanan']);
    //gizi
    Route::get('gizi', [HomeController::class, 'gizi'])->name('gizi')->middleware('role:gizi');
    Route::get('masterGizi', [HomeController::class, 'masterGizi'])->name('masterGizi')->middleware('role:gizi');
    Route::get('riwayatGizi', [HomeController::class, 'riwayatGizi'])->name('riwayatGizi')->middleware('role:gizi');
});
Route::get('dispenser', [HomeController::class, 'dispenser'])->name('dispenser');
Route::get('verif', [HomeController::class, 'verif'])->name('verif');
Route::get('displayAntrian', [HomeController::class, 'displayAntrian'])->name('displayAntrian');
