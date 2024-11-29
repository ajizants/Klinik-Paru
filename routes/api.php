<?php

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\DotsController;
use App\Http\Controllers\FarmasiController;
use App\Http\Controllers\GiziAsesmenAwalController;
use App\Http\Controllers\GiziDxModelController;
use App\Http\Controllers\GiziKunjunganController;
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
use App\Http\Controllers\VerifController;
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
Route::post('pendaftaran/selesai', [AntrianController::class, 'selesaiRM']);
Route::post('igd/selesai', [AntrianController::class, 'selesaiIGD']);

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
Route::post('tagihan', [KasirController::class, 'tagihan']);
Route::post('kasir/item/add', [KasirController::class, 'addTagihan']);
Route::post('kasir/tagihan/order', [KasirController::class, 'order']);
Route::post('kasir/transaksi', [KasirController::class, 'addTransaksi']);
Route::post('kasir/kunjungan', [KasirController::class, 'kunjungan']);
Route::post('kasir/rekap', [KasirController::class, 'rekapKunjungan']);
Route::get('cetakSBS/{id}', [KasirController::class, 'cetakSBS']);
Route::post('cetakBAPH', [KasirController::class, 'cetakBAPH']);
Route::get('/pendapatan/{tahun}', [KasirController::class, 'pendapatan']);

//laboratorium
Route::get('layananLabAll', [LaboratoriumController::class, 'layanan']);
Route::post('layananlab', [LaboratoriumController::class, 'layananlab']);
Route::post('cariTsLab', [LaboratoriumController::class, 'cariTsLab']);
Route::post('getNoSampel', [LaboratoriumController::class, 'noSampel']);
Route::post('addTransaksiLab', [LaboratoriumController::class, 'addTransaksi']);
Route::post('/lab/deleteTs', [LaboratoriumController::class, 'deleteTs']);
Route::post('deleteLab', [LaboratoriumController::class, 'deleteLab']);

Route::post('hasil/lab', [LaboratoriumController::class, 'hasil']);
Route::get('hasil/lab/cetak/{notrans}/{tgl}', [LaboratoriumController::class, 'cetak'])->name('cetak-lab');
Route::post('hasil/antrian', [LaboratoriumController::class, 'antrianHasil']);
Route::post('rekap/Kunjungan_Lab', [LaboratoriumController::class, 'rekapKunjungan']);
Route::post('rekap/lab/poin', [LaboratoriumController::class, 'poinPetugas']);
Route::post('rekap/lab/jumlah_pemeriksaan', [LaboratoriumController::class, 'jumlah_pemeriksaan']);
Route::post('rekap/lab/waktu_pemeriksaan', [LaboratoriumController::class, 'waktu_pemeriksaan']);

Route::post('addHasilLab', [LaboratoriumController::class, 'addHasil']);
Route::post('cariRiwayatLab', [LaboratoriumController::class, 'riwayat']);

//farmasi

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
Route::post('updateRo', [ROTransaksiController::class, 'updateGambar']);
Route::post('deleteFotoPasien', [ROTransaksiController::class, 'deleteGambar']);
Route::post('cariTsRO', [ROTransaksiController::class, 'cariTransaksiRo']);
Route::post('dataTransaksiRo', [ROTransaksiController::class, 'dataTransaksiRo']);
Route::post('hasilRo', [ROTransaksiController::class, 'hasilRo']);
Route::post('logBook', [ROTransaksiController::class, 'logBook']);

//Gizi
Route::post('gizi/asesmenAwal', [GiziAsesmenAwalController::class, 'search']);
Route::post('gizi/asesmenAwal/add', [GiziAsesmenAwalController::class, 'store']);
Route::post('gizi/asesmenAwal/delete', [GiziAsesmenAwalController::class, 'destroy']);

Route::post('gizi/kunjungan', [GiziKunjunganController::class, 'search']);
Route::post('gizi/kunjungan/add', [GiziKunjunganController::class, 'store']);
Route::post('gizi/kunjungan/delete', [GiziKunjunganController::class, 'destroy']);

//dx gizi
Route::get('gizi/dx/subKelas', [GiziDxModelController::class, 'subKelas']);
Route::post('gizi/dx/subKelas', [GiziDxModelController::class, 'simpanSubKelas']);
Route::post('gizi/dx/subKelas/delete', [GiziDxModelController::class, 'deleteSubKelas']);
Route::get('gizi/dx/kelas', [GiziDxModelController::class, 'kelas']);
Route::post('gizi/dx/kelas', [GiziDxModelController::class, 'simpanKelas']);
Route::post('gizi/dx/kelas/delete', [GiziDxModelController::class, 'deleteKelas']);
Route::get('gizi/dx/domain', [GiziDxModelController::class, 'domain']);
Route::post('gizi/dx/domain', [GiziDxModelController::class, 'simpanDomain']);
Route::post('gizi/dx/domain/delete', [GiziDxModelController::class, 'deleteDomain']);

//API Riwayat Untuk migrasi SIM RS
Route::post('riwayatKunjungan', [RiwayatController::class, 'index']);
Route::post('riwayatKunjungan/jumlahDx', [RiwayatController::class, 'CountDxMedis']);

Route::post('noAntrianKominfo', [PasienKominfoController::class, 'newPendaftaran']);
Route::post('pasienKominfo', [PasienKominfoController::class, 'newPasien']);
Route::post('dataPasien', [PasienKominfoController::class, 'dataPasien']);
Route::post('cpptKominfo', [PasienKominfoController::class, 'newCpptRequest']);
Route::post('antrian/kominfo', [PasienKominfoController::class, 'antrianAll']);
Route::post('poin_kominfo', [PasienKominfoController::class, 'rekapPoin']);
Route::post('poin_kominfo/pecah', [PasienKominfoController::class, 'rekapPoinPecah']);
Route::post('kominfo/waktu_layanan', [PasienKominfoController::class, 'waktuLayanan']);
Route::post('kominfo/rata_waktu_tunggu', [PasienKominfoController::class, 'avgWaktuTunggu']);
Route::post('kominfo/pendaftaran', [PasienKominfoController::class, 'pendaftaranFilter']); //cari No RM
Route::post('kominfo/pendaftaran/report', [PasienKominfoController::class, 'reportPendaftaran']);
Route::get('resume/{no_rm}/{tgl}', [PasienKominfoController::class, 'resumePasien']);
Route::post('kominfo/pendaftaran/resume', [PasienKominfoController::class, 'resumePasien']);
Route::post('kominfo/report/dokter_rme', [PasienKominfoController::class, 'grafikDokter']);

Route::post('verif/pendaftaran/fr', [VerifController::class, 'frista']);
Route::post('verif/pendaftaran/fp', [VerifController::class, 'afterapp']);
Route::post('/verif/pendaftaran/kominfo/submit', [VerifController::class, 'submit']);
// Route::post('verif/pendaftaran/fr', [VerifController::class, 'index']);
// Route::post('verif/pendaftaran/fp', [VerifController::class, 'fingerprint']);
Route::post('kominfo/submit', [VerifController::class, 'submit']);
Route::post('ambil/no/kominfo', [PasienKominfoController::class, 'ambilAntrean']);
Route::post('list/tunggu/tensi', [DisplayController::class, 'listTungguTensi']);
Route::get('list/tunggu/lab', [DisplayController::class, 'tungguLab']);
Route::get('list/tunggu/ro', [DisplayController::class, 'tungguRo']);
Route::get('list/tunggu/farmasi', [DisplayController::class, 'listTungguFarmasi']);
Route::get('list/tunggu/loket', [DisplayController::class, 'listTungguLoket']);

// });
