<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\DotsController;
use App\Http\Controllers\FarmasiController;
use App\Http\Controllers\GudangFarmasiController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\IgdController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\NoAntrianController;
use App\Http\Controllers\PendaftaranKominfoController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\StokController;


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
Route::get('dokter', [InputController::class, 'dokter']);
Route::get('perawat', [InputController::class, 'perawat']);
Route::get('apoteker', [InputController::class, 'apoteker']);
Route::get('dxMedis', [InputController::class, 'dxMedis']);

Route::get('bmhp', [InputController::class, 'bmhp']);
Route::get('jenistindakan', [InputController::class, 'JenisTindakan']);

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


//antrian tindakan
Route::post('cariRMObat', [AntrianController::class, 'cariRMObat']);
Route::post('cariRM', [AntrianController::class, 'cariRM']);
Route::post('antrianIGD', [AntrianController::class, 'antrianIGD']);

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
Route::post('antrianKasir', [KasirController::class, 'index']);


//farmasi

//sumberdaya apotik
Route::post('antrianFarmasi', [FarmasiController::class, 'index']);
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

Route::post('antrianAll', [AntrianController::class, 'all']);

Route::post('antrianKominfo', [PendaftaranKominfoController::class, 'antrianKominfo']);


//antrian
Route::get('noantrian', [NoAntrianController::class, 'index']);
Route::post('lastNoAntri', [NoAntrianController::class, 'lastNoAntri']);
Route::post('ambilNo', [NoAntrianController::class, 'store']);
