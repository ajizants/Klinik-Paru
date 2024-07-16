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
use App\Http\Controllers\PasienKominfoController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\RoMasterController;
use App\Http\Controllers\ROTransaksiController;
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

// Route::middleware('auth')->group(function () {
//sumber daya
Route::get('dokter', [PegawaiController::class, 'dokter']);
Route::get('perawat', [PegawaiController::class, 'perawat']);
Route::get('apoteker', [PegawaiController::class, 'apoteker']);
Route::get('radiografer', [PegawaiController::class, 'radiografer']);
Route::get('analis', [PegawaiController::class, 'analis']);
Route::get('dxMedis', [InputController::class, 'dxMedis']);
Route::get('jaminan', [InputController::class, 'jaminan']);
Route::get('tujuan', [InputController::class, 'tujuan']);
Route::post('waktuLayanan', [InputController::class, 'waktuLayanan']);

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
Route::post('kunjungan/Dots', [DotsController::class, 'kunjunganDots']);
Route::get('obatDots', [DotsController::class, 'obatDots']);
Route::get('blnKeDots', [DotsController::class, 'blnKeDots']);
//pasien dots
Route::POST('pasien/TB', [DotsController::class, 'Ptb']);
Route::get('pasien/TB/Kontrol', [DotsController::class, 'kontrol']);
Route::get('pasien/TB/Telat', [DotsController::class, 'telat']);
//transaksi dots
Route::post('tambah/pasien/TB', [DotsController::class, 'addPasienTb']);
Route::post('update/status/pengobatan', [DotsController::class, 'updatePengobatanPasien']);
Route::post('simpan/kunjungan/dots', [DotsController::class, 'simpanKunjungan']);
Route::get('deletePTB', [DotsController::class, 'deletePTB']);
Route::get('editPTB', [DotsController::class, 'editPTB']);

//Kasir
Route::get('layanan', [KasirController::class, 'Layanan']);
Route::post('layanan/update', [KasirController::class, 'updateLayanan']);
Route::post('layanan/add', [KasirController::class, 'add']);
Route::post('layanan/delete', [KasirController::class, 'delete']);

//laboratorium
Route::get('layananLabAll', [LaboratoriumController::class, 'layanan']);
Route::post('layananlab', [LaboratoriumController::class, 'layananlab']);
Route::post('cariTsLab', [LaboratoriumController::class, 'cariTsLab']);
Route::post('addTransaksiLab', [LaboratoriumController::class, 'addTransaksi']);
Route::post('/lab/deleteTs', [LaboratoriumController::class, 'deleteTs']);
Route::post('deleteLab', [LaboratoriumController::class, 'deleteLab']);

Route::post('rekap/Kunjungan_Lab', [LaboratoriumController::class, 'rekapKunjungan']);

Route::post('addHasilLab', [LaboratoriumController::class, 'addHasil']);
Route::post('cariRiwayatLab', [LaboratoriumController::class, 'riwayat']);
Route::post('rekapBpjsUmum', [LaboratoriumController::class, 'rekapBpjsUmum']);
Route::post('rekapReagenHari', [LaboratoriumController::class, 'rekapReagen']);
Route::post('rekapReagenBln', [LaboratoriumController::class, 'rekapReagenBln']);
Route::post('poinLab', [LaboratoriumController::class, 'poinPetugas']);

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

//No Antrian
Route::get('noantrian', [NoAntrianController::class, 'index']);
Route::post('lastNoAntri', [NoAntrianController::class, 'lastNoAntri']);
Route::post('ambilNo', [NoAntrianController::class, 'store']);

//Radiologi
Route::get('fotoRo', [RoMasterController::class, 'fotoRo']);
Route::get('filmRo', [RoMasterController::class, 'filmRo']);
Route::get('mesinRo', [RoMasterController::class, 'mesinRo']);
Route::get('proyeksiRo', [RoMasterController::class, 'proyeksiRo']);
Route::post('kondisiRo', [RoMasterController::class, 'kondisiRo']);

Route::post('simpanFotoRo', [RoMasterController::class, 'simpanFotoRo']);
Route::post('simpanFilmRo', [RoMasterController::class, 'simpanFilmRo']);
Route::post('simpanMesinRo', [RoMasterController::class, 'simpanMesinRo']);
Route::post('simpanKondisiRo', [RoMasterController::class, 'simpanKondisiRo']);
Route::post('simpanproyeksiRo', [RoMasterController::class, 'simpanproyeksiRo']);

Route::put('editfotoRo', [RoMasterController::class, 'editfotoRo']);
Route::put('editfilmRo', [RoMasterController::class, 'editfilmRo']);
Route::put('editKondisiRo', [RoMasterController::class, 'editKondisiRo']);
Route::put('editProyeksiRo', [RoMasterController::class, 'editProyeksiRo']);

Route::post('deletefotoRo', [RoMasterController::class, 'deletefotoRo']);
Route::post('deletefilmRo', [RoMasterController::class, 'deletefilmRo']);
Route::post('deletemesinRo', [RoMasterController::class, 'deletemesinRo']);
Route::post('deletekondisiRo', [RoMasterController::class, 'deletekondisiRo']);
Route::post('deleteproyeksiRo', [RoMasterController::class, 'deleteproyeksiRo']);

Route::post('addTransaksiRo', [ROTransaksiController::class, 'addTransaksiRo']);
Route::post('cariTsRO', [ROTransaksiController::class, 'cariTransaksiRo']);
Route::post('dataTransaksiRo', [ROTransaksiController::class, 'dataTransaksiRo']);
Route::post('hasilRo', [ROTransaksiController::class, 'hasilRo']);
Route::post('logBook', [ROTransaksiController::class, 'logBook']);

//API Riwayat Untuk migrasi SIM RS
Route::get('riwayatKunjungan', [RiwayatController::class, 'index']);

Route::post('daftarKominfo', [PasienKominfoController::class, 'newPendaftaran']);
Route::post('pasienKominfo', [PasienKominfoController::class, 'newPasien']);
Route::post('dataPasien', [PasienKominfoController::class, 'dataPasien']);
Route::post('noAntrianKominfo', [PasienKominfoController::class, 'newPendaftaran']);
Route::post('cpptKominfo', [PasienKominfoController::class, 'newCpptRequest']);
Route::post('antrian/kominfo', [PasienKominfoController::class, 'antrianAll']);
Route::post('poin_kominfo', [PasienKominfoController::class, 'rekapPoin']);
Route::post('kominfo/waktu_layanan', [PasienKominfoController::class, 'waktuLayanan']);
Route::post('kominfo/rata_waktu_tunggu', [PasienKominfoController::class, 'avgWaktuTunggu']);
Route::post('kominfo/pendaftaran', [PasienKominfoController::class, 'pendaftaranFilter']);
// });
