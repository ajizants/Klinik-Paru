<?php

use App\Http\Controllers\DataAnalisController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\EkinController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasienKominfoController;
use App\Http\Controllers\SuratController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::get('RO/Hasil/{id}', [HomeController::class, 'rontgenHasil'])->name('rontgenHasil');

Route::get('RO/Hasil', [HomeController::class, 'roHasil'])->name('roHasil');

Route::get('dispenser', [HomeController::class, 'dispenser'])->name('dispenser');

Route::get('verif/{id}', [HomeController::class, 'verif'])->name('verif');

Route::get('displayAntrian', [HomeController::class, 'displayAntrian'])->name('displayAntrian');
Route::get('display/loket', [DisplayController::class, 'loket'])->name('loket');
Route::get('display/farmasi', [DisplayController::class, 'farmasi'])->name('farmasi');
Route::get('display/tensi', [DisplayController::class, 'tensi'])->name('tensi');
Route::get('display/lab', [DisplayController::class, 'lab'])->name('lab');
Route::get('display/poli/{id}', [DisplayController::class, 'poli'])->name('poli');
Route::get('grafik/dokter', [DisplayController::class, 'rme'])->name('rme');
//menu
Route::middleware('auth')->group(function () {
    Route::get('surat/medis', [SuratController::class, 'index'])->name('suratMedis');
    //pendaftaran
    Route::get('report', [HomeController::class, 'report'])->name('report');
    Route::get('Laporan/Pendaftaran', [HomeController::class, 'laporanPendaftaran'])->name('laporanPendaftaran');
    Route::get('pendaftaran', [HomeController::class, 'pendaftaran'])->name('pendaftaran');
    //farmasi
    Route::get('farmasi', [HomeController::class, 'farmasi'])->name('farmasi')->middleware('role:farmasi');
    Route::get('gudangFarmasi', [HomeController::class, 'gudangFarmasi'])->name('gudangFarmasi')->middleware('role:farmasi');
    //dots
    Route::get('dots', [HomeController::class, 'dots'])->name('dots')->middleware('role:dots');
    //igd
    Route::get('askep', [HomeController::class, 'askep'])->name('askep')->middleware('role:igd');
    Route::get('igd', [HomeController::class, 'igd'])->name('igd')->middleware('role:igd');
    Route::get('gudangIGD', [HomeController::class, 'gudangIGD'])->name('gudangIGD')->middleware('role:igd');
    //kinerja pegawai
    Route::get('E-kinerja', [EkinController::class, 'index'])->name('kinerja');
    //Kasir
    Route::get('kasir', [HomeController::class, 'kasir'])->name('kasir')->middleware('role:kasir');
    Route::get('kasir/master', [HomeController::class, 'masterKasir'])->name('kasir')->middleware('role:kasir');
    Route::get('kasir/report', [HomeController::class, 'rekapKasir'])->name('rekapKasir')->middleware('role:kasir');
    Route::get('kasir/pendapatan', [HomeController::class, 'pendapatan'])->name('rekapKasir')->middleware('role:kasir');
    Route::get('kasir/pendapatan/lain', [HomeController::class, 'pendapatanLain'])->name('rekapKasir')->middleware('role:kasir');
    Route::get('lte', [HomeController::class, 'lte'])->name('lte')->middleware('role:kasir');
    //Laborat
    Route::get('lab', [HomeController::class, 'lab'])->name('lab')->middleware('role:lab');
    Route::get('hasilLab', [HomeController::class, 'hasilLab'])->name('hasilLab')->middleware('role:lab');
    Route::get('riwayatLab', [HomeController::class, 'riwayatLab'])->name('riwayatLab')->middleware('role:lab');
    Route::get('Laporan/Lab', [HomeController::class, 'riwayatLab'])->name('riwayatLab')->middleware('role:lab');
    Route::get('masterLab', [HomeController::class, 'masterLab'])->name('masterLab')->middleware('role:lab');
    //RO
    Route::get('ro', [HomeController::class, 'ro'])->name('ro')->middleware('role:ro');
    Route::get('ro2', [HomeController::class, 'ro2'])->name('ro')->middleware('role:ro');
    Route::get('masterRo', [HomeController::class, 'masterRo'])->name('masterRo')->middleware('role:ro');
    Route::get('riwayatRo', [HomeController::class, 'riwayatRo'])->name('hasilRo')->middleware('role:ro');
    Route::get('Laporan/Lab', [HomeController::class, 'riwayatRo'])->name('hasilRo')->middleware('role:ro');
    Route::post('waktu_layanan', [PasienKominfoController::class, 'waktuLayanan']);
    //gizi
    Route::get('gizi', [HomeController::class, 'gizi'])->name('gizi')->middleware('role:gizi');
    Route::get('masterGizi', [HomeController::class, 'masterGizi'])->name('masterGizi')->middleware('role:gizi');
    Route::get('riwayatGizi', [HomeController::class, 'riwayatGizi'])->name('riwayatGizi')->middleware('role:gizi');
    //riwayat diagnosa
    Route::get('/Riwayat/Pasien', [HomeController::class, 'riwayatKunjungan'])->name('riwayatKunjungan')->middleware('role:dokter,perawat,dots,igd');
    Route::get('/Diagnosa/Mapping', [HomeController::class, 'mappingDx'])->name('mappingDx')->middleware('role:dokter,perawat');

    //analisis data
    Route::get('Pusat-Data', [DataAnalisController::class, 'index'])->name('pusatData')->middleware('role:analitik');
    Route::get('analisis/pendaftaran', [DataAnalisController::class, 'analisisPendaftaran'])->name('analisisPendaftaran')->middleware('role:analitik');
    Route::get('analisis/riwayat', [DataAnalisController::class, 'analisisRiwayat'])->name('analisisRiwayat')->middleware('role:analitik');

    Route::get('jadwal', [JadwalController::class, 'index'])->name('viewJadwal');
    Route::get('/download-template', function () {
        $filePath = 'public/templates/format_jadwal_karyawan.xlsx';

        if (Storage::exists($filePath)) {
            return Storage::download($filePath, 'format_jadwal_karyawan.xlsx');
        }

        return response()->json(['error' => 'File tidak ditemukan'], 404);
    })->name('download.template');
});
