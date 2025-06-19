<?php

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\DataAnalisController;
use App\Http\Controllers\DiagnosaMappingController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\DotsController;
use App\Http\Controllers\EkinController;
use App\Http\Controllers\FarmasiController;
use App\Http\Controllers\GiziAsesmenAwalController;
use App\Http\Controllers\GiziDxModelController;
use App\Http\Controllers\GiziKunjunganController;
use App\Http\Controllers\GudangFarmasiController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\NoAntrianController;
use App\Http\Controllers\PasienKominfoController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PromkesController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifController;
use App\Models\DiagnosaIcdXModel;
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

Route::get('dxMedis', [InputController::class, 'dxMedis']);
Route::get('jaminan', [InputController::class, 'jaminan']);
Route::get('tujuan', [InputController::class, 'tujuan']);
Route::post('waktuLayanan', [InputController::class, 'waktuLayanan']);
Route::get('tes', [InputController::class, 'tes']);

Route::get('bmhp', [InputController::class, 'bmhp']);
Route::get('jenistindakan', [InputController::class, 'JenisTindakan']);

Route::get('/diagnosa_icd_x', function (Request $request) {
    $search = $request->get('search', '');
    $limit = $request->get('limit', 20);

    $diagnosas = DiagnosaIcdXModel::where(function ($query) use ($search) {
        $query->where('diagnosa', 'like', '%' . $search . '%')
            ->orWhere('kdDx', 'like', '%' . $search . '%');
    })
        ->limit($limit)
        ->get(['kdDx', 'diagnosa']);

    return response()->json($diagnosas);
});
Route::get('/dxMapping', [DiagnosaMappingController::class, 'index']);
Route::post('/dxMapping/simpan', [DiagnosaMappingController::class, 'store']);
Route::post('/dxMapping/update', [DiagnosaMappingController::class, 'update']);
Route::delete('/dxMapping/{kdDx}', [DiagnosaMappingController::class, 'destroy']);

//pegawai
Route::get('dokter', [PegawaiController::class, 'dokter']);
Route::get('perawat', [PegawaiController::class, 'perawat']);
Route::get('apoteker', [PegawaiController::class, 'apoteker']);
Route::get('radiografer', [PegawaiController::class, 'radiografer']);
Route::get('analis', [PegawaiController::class, 'analis']);

Route::get('pegawai/{id}', [PegawaiController::class, 'show']);
Route::post('pegawai', [PegawaiController::class, 'store']);
Route::post('pegawai/update/{id}', [PegawaiController::class, 'update']);
Route::post('pegawai/delete', [PegawaiController::class, 'destroy']);
Route::post('pegawai/cek', [PegawaiController::class, 'cek']);
Route::post('pegawai/cekLogin', [PegawaiController::class, 'cekLogin']);

//ekin
Route::get('ekin/poin', [EkinController::class, 'export']);
Route::post('ekin/cek', [EkinController::class, 'kegiatanLain']);
Route::post('ekin/poin/cari', [EkinController::class, 'show'])->name('cariPekerjaanPegawai');
Route::post('ekin/poin', [EkinController::class, 'store'])->name('tambahPekerjaanPegawai');
Route::put('ekin/poin', [EkinController::class, 'update'])->name('updatePekerjaanPegawai');
Route::delete('ekin/poin/{id}', [EkinController::class, 'destroy'])->name('hapusPekerjaanPegawai');

//antrians
Route::post('cariRM', [AntrianController::class, 'cariRM']);
Route::post('antrianAll', [AntrianController::class, 'all']);
Route::post('cariRMObat', [AntrianController::class, 'cariRMObat']);
Route::post('antrianIGD', [AntrianController::class, 'antrianIGD']);
Route::post('antrianKasir', [AntrianController::class, 'antrianKasir']);
Route::post('antrianFarmasi', [AntrianController::class, 'antrianFarmasi']);
Route::post('antrianLaboratorium', [AntrianController::class, 'antrianLaboratorium']);
Route::post('pendaftaran/selesai', [AntrianController::class, 'selesaiRM']);
Route::post('igd/selesai', [AntrianController::class, 'selesaiIGD']);

// Dots Center
Route::post('kunjungan/Dots', [DotsController::class, 'kunjunganDots']);
Route::get('kunjungan/Dots/edit/{id}', [DotsController::class, 'FindKunjunganDots']);
Route::post('kunjungan/Dots/update', [DotsController::class, 'simpanKunjungan']);
Route::get('obatDots', [DotsController::class, 'obatDots']);
Route::get('blnKeDots', [DotsController::class, 'blnKeDots']);
//pasien dots
Route::POST('pasien/TB', [DotsController::class, 'Ptb']);
Route::get('pasien/TB/Kontrol', [DotsController::class, 'kontrol']);
Route::get('pasien/TB/Telat', [DotsController::class, 'telat']);
//transaksi dots
Route::post('tambah/pasien/TB', [DotsController::class, 'addPasienTb']);
Route::post('/pasien/TB_update', [DotsController::class, 'updatePasienTB']);
Route::post('simpan/kunjungan/dots', [DotsController::class, 'simpanKunjungan']);
Route::get('deletePTB', [DotsController::class, 'deletePTB']);
Route::get('editPTB', [DotsController::class, 'editPTB']);
Route::post('poinDots', [DotsController::class, 'poinPetugas']);
Route::post('dots/rencana_kontrol', [DotsController::class, 'rencanaKontrol']);

//farmasi0
Route::get('stokbmhp', [StokController::class, 'stokbmhp']);
Route::get('obat', [GudangFarmasiController::class, 'gudangFarmasiIn']);

//transaksi apotok
Route::post('lists/obat', [FarmasiController::class, 'obats']);
Route::get('resep/{norm}/{tgl}', [FarmasiController::class, 'cetakObat']);
Route::get('resep2/{norm}/{tgl}', [FarmasiController::class, 'cetakResepKominnfo']);
Route::get('etiket/{norm}/{tgl}', [FarmasiController::class, 'cetakEtiket']);
Route::post('farmasi/panggil', [FarmasiController::class, 'panggil']);
// Route::post('farmasi/cetak/{norm}/{notrans}', [FarmasiController::class, 'selesaiFarmasi']);
Route::post('farmasi/pulangkan', [FarmasiController::class, 'pulangkan']);

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

// Display
Route::post('verif/pendaftaran/fr', [VerifController::class, 'frista']);
Route::post('verif/pendaftaran/fp', [VerifController::class, 'afterapp']);
Route::post('/verif/pendaftaran/kominfo/submit', [VerifController::class, 'submit']);
// Route::post('verif/pendaftaran/fr', [VerifController::class, 'index']);
// Route::post('verif/pendaftaran/fp', [VerifController::class, 'fingerprint']);
Route::post('kominfo/submit', [VerifController::class, 'submit']);
Route::post('ambil/no/kominfo', [PasienKominfoController::class, 'ambilAntrean']);
Route::get('list/tunggu/tensi', [DisplayController::class, 'listTungguTensi']);
Route::get('list/tunggu/lab', [DisplayController::class, 'tungguLab']);
Route::get('list/tunggu/ro', [DisplayController::class, 'tungguRo']);
Route::get('list/tunggu/farmasi', [DisplayController::class, 'listTungguFarmasi']);
Route::get('list/tunggu/loket', [DisplayController::class, 'listTungguLoket']);
Route::get('list/tunggu/poli/{id}', [DisplayController::class, 'listTungguPoli']);
Route::get('list/tunggu/dokter', [DisplayController::class, 'dataJumlahTiapdokter']);

//Surat Medis
Route::get('surat/medis/{id}/{tgl}', [SuratController::class, 'cetakSM']);
Route::post('surat/medis', [SuratController::class, 'store']);
Route::post('surat/medis/update', [SuratController::class, 'update']);
Route::post('surat/medis/delete', [SuratController::class, 'destroy']);
Route::post('surat/medis/riwayat', [SuratController::class, 'riwayat']);

//Data Analis
Route::post('data/analis/biaya_pasien', [DataAnalisController::class, 'DataBiayaKunjungan']);
Route::post('data/analis/faskes_perujuk', [DataAnalisController::class, 'faskesPerujuk']);
Route::post('data/analis/kunjungan_lab', [DataAnalisController::class, 'kunjunganLab']);
Route::get('data/analis/diagnosa/{tahun}', [DataAnalisController::class, 'jumlahDiagnosa']);

Route::post('jadwal/upload', [JadwalController::class, 'import'])->name('jadwal.import');
Route::post('jadwal/get', [JadwalController::class, 'getJadwal'])->name('jadwal.getJadwal');
Route::delete('jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
Route::put('jadwal/{id}', [JadwalController::class, 'update'])->name('jadwal.update');

//promkes
Route::post('promkes', [PromkesController::class, 'store']);
Route::post('promkes/cari', [PromkesController::class, 'data']);
Route::get('promkes/{id}', [PromkesController::class, 'show']);
Route::put('promkes/{promkesModel}', [PromkesController::class, 'update']);
Route::delete('promkes/{id}', [PromkesController::class, 'destroy']);

// });

Route::get('userOnline', [UserController::class, 'userOnline'])->name('userOnline');
