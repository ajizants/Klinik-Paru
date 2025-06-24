<?php

use App\Http\Controllers\DataAnalisController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\DotsController;
use App\Http\Controllers\EkinController;
use App\Http\Controllers\FarmasiController;
use App\Http\Controllers\GudangATKController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IgdController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PendaftaranOnlineController;
use App\Http\Controllers\PromkesController;
use App\Http\Controllers\RanapCPPTController;
use App\Http\Controllers\RanapPendaftaranController;
use App\Http\Controllers\ROTransaksiController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\TataUsahaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifController;
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

Route::get('/logout-session', [LoginController::class, 'logoutSession'])->name('logout.session');

Route::get('forbidden', [HomeController::class, 'forbidden'])->name('forbidden')->middleware('auth');
Route::get('lte', [HomeController::class, 'lte'])->name('lte');

Route::get('verif/{id}', [VerifController::class, 'verif'])->name('verif');

Route::get('RO/Hasil', [ROTransaksiController::class, 'roHasil'])->name('roHasil');
Route::get('RO/Hasil/{id}', [ROTransaksiController::class, 'rontgenHasil'])->name('rontgenHasil');

//display tunggu
Route::get('display/loket', [DisplayController::class, 'loket'])->name('loket');
Route::get('display/farmasi', [DisplayController::class, 'farmasi'])->name('farmasi');
Route::get('display/tensi', [DisplayController::class, 'tensi'])->name('tensi');
Route::get('display/lab', [DisplayController::class, 'lab'])->name('lab');
Route::get('display/poli/{id}', [DisplayController::class, 'poli'])->name('poli');
Route::get('display/dokter', [DisplayController::class, 'dokter'])->name('dokter');
Route::get('grafik/dokter', [DisplayController::class, 'rme'])->name('rme');

//Booking
Route::get('pemesanan/Data', [PendaftaranOnlineController::class, 'index'])->name('pendaftaranOnline');
Route::get('pemesanan/pasien_baru ', [PendaftaranOnlineController::class, 'createBaru'])->name('patient_baru.create');
Route::get('pemesanan/pasien_lama ', [PendaftaranOnlineController::class, 'createLama'])->name('patient_lama.create');
Route::post('pemesanan', [PendaftaranOnlineController::class, 'store'])->name('patient.register');

//menu
Route::middleware('auth')->group(function () {
    Route::get('surat/medis', [SuratController::class, 'index'])->name('suratMedis');

    //pendaftaran
    Route::get('Laporan/Pendaftaran', [PendaftaranController::class, 'laporanPendaftaran'])->name('laporanPendaftaran');
    Route::get('pendaftaran', [PendaftaranController::class, 'pendaftaran'])->name('pendaftaran');

    //farmasi
    Route::get('farmasi', [FarmasiController::class, 'farmasi'])->name('farmasi')->middleware('role:farmasi');
    Route::get('farmasi/gudang', [FarmasiController::class, 'gudangFarmasi'])->name('gudangFarmasi')->middleware('role:farmasi');

    //dots
    Route::get('dots', [DotsController::class, 'dots'])->name('dots')->middleware('role:dots');

    //igd
    Route::get('Askep', [IgdController::class, 'askep'])->name('askep')->middleware('role:igd');
    Route::get('Igd', [IgdController::class, 'igd'])->name('igd')->middleware('role:igd');
    Route::get('Igd/Poin', [IgdController::class, 'reportPoin'])->name('report.poin.igd');
    Route::get('Igd/Gudang', [IgdController::class, 'gudangIGD'])->name('gudangIGD')->middleware('role:igd');

    //kinerja pegawai
    Route::get('E-kinerja', [EkinController::class, 'index'])->name('kinerja');

    //Kasir
    Route::get('kasir', [KasirController::class, 'kasir'])->name('kasir')->middleware('role:kasir');
    Route::get('kasir/master', [KasirController::class, 'masterKasir'])->name('kasir')->middleware('role:kasir');
    Route::get('kasir/report', [KasirController::class, 'rekapKasir'])->name('rekapKasir')->middleware('role:kasir');
    Route::get('kasir/pendapatan/lain', [KasirController::class, 'pendapatanLain'])->name('rekapKasir')->middleware('role:kasir');

    //Laborat
    Route::get('Laboratorium/Pendaftaran', [LaboratoriumController::class, 'lab'])->name('lab')->middleware('role:lab');
    Route::get('Laboratorium/Hasil', [LaboratoriumController::class, 'hasilLab'])->name('hasilLab')->middleware('role:lab');
    Route::get('Laboratorium/Laporan', [LaboratoriumController::class, 'laporan'])->name('riwayatLab')->middleware('role:lab');
    Route::get('Laboratorium/Master', [LaboratoriumController::class, 'masterLab'])->name('masterLab')->middleware('role:lab');
    Route::get('Laboratorium/TB04', [LaboratoriumController::class, 'tb04Lab'])->name('tb04Lab')->middleware('role:lab');

    //RO
    Route::get('Radiologi', [ROTransaksiController::class, 'ro'])->name('ro')->middleware('role:ro');
    Route::get('Radiologi/Master', [ROTransaksiController::class, 'masterRo'])->name('masterRo')->middleware('role:ro');
    Route::get('Radiologi/Laporan', [ROTransaksiController::class, 'laporanRo'])->name('hasilRo')->middleware('role:ro');
    Route::get('ro2', [ROTransaksiController::class, 'ro2'])->name('ro')->middleware('role:ro');

    //gizi
    Route::get('Gizi', [HomeController::class, 'gizi'])->name('gizi')->middleware('role:gizi');
    Route::get('Gizi/Master', [HomeController::class, 'masterGizi'])->name('masterGizi')->middleware('role:gizi');
    Route::get('Gizi/Riwayat', [HomeController::class, 'riwayatGizi'])->name('riwayatGizi')->middleware('role:gizi');

    //riwayat diagnosa
    Route::get('Riwayat/Pasien', [HomeController::class, 'riwayatKunjungan'])->name('riwayatKunjungan')->middleware('role:dpjp,dokter,perawat,dots,igd,farmasi');
    Route::get('Diagnosa/Mapping', [HomeController::class, 'mappingDx'])->name('mappingDx')->middleware('role:dpjp,dokter,perawat');

    //analisis data
    Route::get('Pusat_Data', [DataAnalisController::class, 'index'])->name('pusatData');
    Route::get('analisis/pendaftaran', [DataAnalisController::class, 'analisisPendaftaran'])->name('analisisPendaftaran');
    Route::get('analisis/riwayat', [DataAnalisController::class, 'analisisRiwayat'])->name('analisisRiwayat');

    //jadwal kerja
    Route::get('jadwal', [JadwalController::class, 'index'])->name('viewJadwal');
    Route::get('download-template', [JadwalController::class, 'getTemplate'])->name('download.template');

    //Tata Usaha Dan Keuangan
    Route::get('TataUsaha/surat', [TataUsahaController::class, 'surat'])->name('tu.surat')->middleware('role:tu');
    Route::get('TataUsaha/keuangan', [TataUsahaController::class, 'keuangan'])->name('tu.keuangan')->middleware('role:tu');
    Route::get('TataUsaha/belanja', [TataUsahaController::class, 'belanja'])->name('tu.belanja')->middleware('role:tu');
    Route::get('TataUsaha/report', [TataUsahaController::class, 'report'])->name('tu.report')->middleware('role:tu');

    //Prokes
    Route::get('Promkes', [PromkesController::class, 'index'])->name('promkes');

    //gudang atk
    Route::get('Gudang/ATK', [GudangATKController::class, 'index'])->name('gudangATK')->middleware('role:atk');

    //user
    Route::get('users', [UserController::class, 'index'])->name('index')->middleware('role:admin');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('/user/online', [UserController::class, 'userOnline'])->name('users.online');

    //RANAP
    Route::get('Ranap', [RanapPendaftaranController::class, 'home'])->name('dashboardRanap')->middleware('role:dpjp,dokter,perawat,dots,igd,farmasi,loket');
    Route::get('Ranap/Pendaftaran', [RanapPendaftaranController::class, 'index'])->name('formRawatInap')->middleware('role:dpjp,dokter,perawat,dots,igd,farmasi,loket');
    Route::get('Ranap/Cppt/{module}', [RanapCPPTController::class, 'index'])->name('formCPPT')->middleware('role:dpjp,dokter,perawat,dots,igd,farmasi,loket');

});
