<?php

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\DotsController;
use App\Http\Controllers\FarmasiController;
use App\Http\Controllers\GudangFarmasiController;
use App\Http\Controllers\IgdController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\NoAntrianController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PendaftaranKominfoController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\StokController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//sumber daya
Route::get('dokter', [PegawaiController::class, 'dokter']);
Route::get('perawat', [PegawaiController::class, 'perawat']);
Route::get('apoteker', [PegawaiController::class, 'apoteker']);
Route::get('analis', [PegawaiController::class, 'analis']);
Route::get('dxMedis', [InputController::class, 'dxMedis']);
Route::get('jaminan', [InputController::class, 'jaminan']);

Route::get('bmhp', [InputController::class, 'bmhp']);
Route::get('jenistindakan', [InputController::class, 'JenisTindakan']);

//antrian
Route::post('cariRM', [AntrianController::class, 'cariRM']);
Route::post('antrianAll', [AntrianController::class, 'all']);
Route::post('cariRMObat', [AntrianController::class, 'cariRMObat']);
Route::post('antrianIGD', [AntrianController::class, 'antrianIGD']);
Route::post('antrianKasir', [AntrianController::class, 'antrianKasir']);
Route::post('antrianFarmasi', [AntrianController::class, 'antrianFarmasi']);
Route::post('antrianLaboratorium', [AntrianController::class, 'antrianLaboratorium']);

//transaksi gudang igd
Route::post('addJenisBmhp', [InputController::class, 'addJenisBmhp']);
Route::post('deleteJenisBmhp', [InputController::class, 'deleteJenisBmhp']);
Route::post('addJenisTindakan', [InputController::class, 'addJenisTindakan']);
Route::post('deleteJenisTindakan', [InputController::class, 'deleteJenisTindakan']);

//transaksi igd
Route::post('editTindakan', [IgdController::class, 'editTindakan']);
Route::post('simpanTindakan', [IgdController::class, 'simpanTindakan']);
Route::post('deleteTindakan', [IgdController::class, 'deleteTindakan']);
Route::post('addTransaksiBmhp', [IgdController::class, 'addTransaksiBmhp']);
Route::post('deleteTransaksiBmhp', [IgdController::class, 'deleteTransaksiBmhp']);

//transaksi IGD
Route::post('cariPoin', [IgdController::class, 'cariPoin']);
Route::post('cariPoinTotal', [IgdController::class, 'cariPoinTotal']);
Route::post('cariDataTindakan', [IgdController::class, 'cariDataTindakan']);
Route::get('chart', [IgdController::class, 'chart'])->name('chart.endpoint');
Route::post('cariTransaksiBmhp', [IgdController::class, 'cariTransaksiBmhp']);

// Dots Center
Route::post('transaksiDots', [DotsController::class, 'transaksiDots']);
Route::get('obatDots', [DotsController::class, 'obatDots']);
Route::get('blnKeDots', [DotsController::class, 'blnKeDots']);
//pasien dots
Route::POST('Ptb', [DotsController::class, 'Ptb']);
Route::get('kontrolDots', [DotsController::class, 'kontrol']);
Route::get('telatDots', [DotsController::class, 'telat']);
Route::get('doDots', [DotsController::class, 'do']);
//transaksi dots
Route::post('addPTB', [DotsController::class, 'addPTB']);
Route::get('deletePTB', [DotsController::class, 'deletePTB']);
Route::get('editPTB', [DotsController::class, 'editPTB']);

//Kasir
Route::get('layanan', [KasirController::class, 'Layanan']);

//laboratorium
Route::post('layananlab', [LaboratoriumController::class, 'layananlab']);
Route::post('cariLaboratorium', [LaboratoriumController::class, 'index']);
Route::post('addTransaksiLab', [LaboratoriumController::class, 'addTransaksi']);
Route::post('deleteLab', [LaboratoriumController::class, 'deleteLab']);
Route::post('cariRiwayatLab', [LaboratoriumController::class, 'riwayat']);
Route::post('rekapBpjsUmum', [LaboratoriumController::class, 'rekapBpjsUmum']);
Route::post('rekapReagen', [LaboratoriumController::class, 'rekapReagen']);

//farmasi
//sumberdaya apotik
Route::get('stokbmhp', [StokController::class, 'stokbmhp']);
Route::get('obat', [GudangFarmasiController::class, 'gudangFarmasiIn']);

//transaksi apotik
Route::post('simpanFarmasi', [FarmasiController::class, 'simpanFarmasi']);
Route::post('deleteFarmasi', [FarmasiController::class, 'deleteFarmasi']);
Route::post('editFarmasi', [FarmasiController::class, 'editFarmasi']);
Route::post('transaksiFarmasi', [FarmasiController::class, 'datatransaksi']);
Route::post('cariTotalBmhp', [FarmasiController::class, 'cariTotalBmhp']);
Route::post('riwayatFarmasi', [FarmasiController::class, 'riwayatFarmasi']);

//sumber daya gudang farmasi
Route::get('supplier', [GudangFarmasiController::class, 'supplier']);
Route::get('pabrikan', [GudangFarmasiController::class, 'pabrikan']);
Route::get('gudangObatIN', [GudangFarmasiController::class, 'gudangObatIN']);
Route::get('daftarInObatGudang', [GudangFarmasiController::class, 'daftarInObatGudang']);
Route::get('daftarGudangObat', [GudangFarmasiController::class, 'daftarGudangObat']);
Route::get('daftarGudangObatLimit', [GudangFarmasiController::class, 'daftarGudangObatLimit']);
Route::get('namaObat', [GudangFarmasiController::class, 'namaObat']);
Route::get('gudangFarmasi', [GudangFarmasiController::class, 'gudangFarmasi']);
Route::get('gudangFarmasiLimit', [GudangFarmasiController::class, 'gudangFarmasiLimit']);
Route::get('gudangIGD', [GudangFarmasiController::class, 'gudangIGD']);
Route::post('stokOpnameFarmasi', [FarmasiController::class, 'stokOpnameFarmasi']);

//transaksi gudang farmasi
Route::post('addStokGudang', [GudangFarmasiController::class, 'addStokGudang']);
Route::post('addStokFarmasi', [GudangFarmasiController::class, 'addStokFarmasi']);
Route::post('addStokIGD', [GudangFarmasiController::class, 'addStokIGD']);
Route::post('addBasicObat', [GudangFarmasiController::class, 'addBasicObat']);
Route::post('stokOpnameGudang', [GudangFarmasiController::class, 'stokOpnameGudang']);

Route::post('addstokbmhp', [StokController::class, 'addstokbmhp']);

//API Riwayat Untuk migrasi SIM RS
Route::get('riwayatKunjungan', [RiwayatController::class, 'index']);

Route::post('antrianKominfo', [PendaftaranKominfoController::class, 'antrianKominfo']);

//No Antrian
Route::get('noantrian', [NoAntrianController::class, 'index']);
Route::post('lastNoAntri', [NoAntrianController::class, 'lastNoAntri']);
Route::post('ambilNo', [NoAntrianController::class, 'store']);
