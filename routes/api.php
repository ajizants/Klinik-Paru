<?php

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\ApiKominfoController;
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
use App\Http\Controllers\IgdController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\KasirPenutupanKasController;
use App\Http\Controllers\KasirSetoranController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\NoAntrianController;
use App\Http\Controllers\PasienKominfoController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\RoMasterController;
use App\Http\Controllers\ROTransaksiController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SuratController;
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
Route::get('report_igd', [IgdController::class, 'report_igd'])->name('report_igd.endpoint');
Route::post('cariTransaksiBmhp', [IgdController::class, 'cariTransaksiBmhp']);
Route::get('cariSisa/{year}', [IgdController::class, 'cariSisa']);
Route::get('cariSisa2/{year}', [IgdController::class, 'cariKunjunganPerBulan']);

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

//Kasir
Route::get('layanan', [KasirController::class, 'Layanan']);
Route::post('layanan/update', [KasirController::class, 'updateLayanan']);
Route::post('layanan/add', [KasirController::class, 'add']);
Route::post('layanan/delete', [KasirController::class, 'delete']);
Route::post('tagihan', [KasirController::class, 'tagihan']);
Route::post('kasir/item/add', [KasirController::class, 'addTagihan']);
Route::post('kasir/item/delete', [KasirController::class, 'deleteTagihan']);
Route::post('kasir/tagihan/order', [KasirController::class, 'order']);
Route::post('kasir/transaksi', [KasirController::class, 'addTransaksi']);
Route::post('kasir/transaksi/delete', [KasirController::class, 'deleteTransaksi']);

//Setoran Kasir
Route::post('kasir/setorkan', [KasirSetoranController::class, 'setorkan']);
Route::get('kasir/setoran/{thn}', [KasirSetoranController::class, 'setoran']);
Route::post('pendapatanLain/simpan', [KasirSetoranController::class, 'setoranSimpan']);
Route::put('pendapatanLain/ubah/{id}', [KasirSetoranController::class, 'setoranUpdate']);
Route::post('pendapatanLain/delete', [KasirSetoranController::class, 'setoranDelete']);

// Laporan Ksirs
Route::post('kasir/kunjungan', [KasirController::class, 'kunjungan']);
Route::post('kasir/rekap', [KasirController::class, 'rekapKunjungan']);
Route::get('/pendapatan/{tahun}', [KasirController::class, 'pendapatan']);
Route::get('/pendapatanTgl/{tgl}', [KasirController::class, 'pendapatanTgl']);
Route::get('/pendapatan/item/{tahun}', [KasirController::class, 'pendapatanPerItem']);
Route::post('/pendapatan/item', [KasirController::class, 'pendapatanPerItem']);
Route::post('/pendapatan/item/bulanan', [KasirController::class, 'pendapatanPerItemBulanan']);
Route::post('/pendapatan/ruang', [KasirController::class, 'pendapatanPerRuang']);
Route::get('cetakSBS/{tgl}/{tahun}/{jaminan}', [KasirController::class, 'cetakSBS']);
Route::get('cetakBAPH/{tgl}/{tahun}/{jaminan}', [KasirController::class, 'cetakBAPH']);
Route::get('stsBruto/{bln}/{tahun}/{jaminan}', [KasirSetoranController::class, 'stsBruto']);
Route::get('stpbBruto/{bln}/{tahun}/{jaminan}', [KasirSetoranController::class, 'stpbBruto']);
Route::get('rekapBulanan/{bln}/{tahun}/{jaminan}', [KasirSetoranController::class, 'rekapBulanan']);
Route::get('bkuBruto/{bln}/{tahun}/{jaminan}', [KasirSetoranController::class, 'bkuBruto']);
Route::get('retriBruto/{bln}/{tahun}/{jaminan}', [KasirSetoranController::class, 'retriBruto']);

//kasir penutupan kasir
Route::post('/kasir/penutupanKas', [KasirPenutupanKasController::class, 'data']);
Route::post('/kasir/penutupanKas/simpan', [KasirPenutupanKasController::class, 'store']);
Route::post('/kasir/penutupanKas/ubah', [KasirPenutupanKasController::class, 'update']);
Route::delete('/kasir/penutupanKas/delete', [KasirPenutupanKasController::class, 'destroy']);
Route::get('/kasir/penutupanKas/cetak/{id}/{tgl}', [KasirPenutupanKasController::class, 'cetakRegPenutupan']);
Route::get('tutupKas/{bln}/{tahun}', [KasirPenutupanKasController::class, 'cetakRegTupan']);

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
Route::get('lab/cetakPermintaan/{notras}/{norm}/{tgl}', [LaboratoriumController::class, 'cetakPermintaan']);

Route::post('addHasilLab', [LaboratoriumController::class, 'addHasil']);
Route::post('cariRiwayatLab', [LaboratoriumController::class, 'riwayat']);

//farmasi0
Route::get('stokbmhp', [StokController::class, 'stokbmhp']);
Route::get('obat', [GudangFarmasiController::class, 'gudangFarmasiIn']);

//transaksi apotok
Route::post('lists/obat', [FarmasiController::class, 'obats']);
Route::get('resep/{norm}/{tgl}', [FarmasiController::class, 'cetakObat']);
Route::get('resep2/{norm}/{tgl}', [FarmasiController::class, 'cetakResepKominnfo']);
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
Route::post('deleteTransaksiRo', [ROTransaksiController::class, 'deleteTransaksiRo']);
Route::post('updateRo', [ROTransaksiController::class, 'updateGambar']);
Route::post('deleteFotoPasien', [ROTransaksiController::class, 'deleteGambar']);
Route::post('cariTsRO', [ROTransaksiController::class, 'cariTransaksiRo']);
Route::post('dataTransaksiRo', [ROTransaksiController::class, 'dataTransaksiRo']);
Route::post('hasilRo', [ROTransaksiController::class, 'hasilRo']);
Route::post('logBook', [ROTransaksiController::class, 'logBook']);
Route::post('ro/konsul', [ROTransaksiController::class, 'konsulRo']);

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
Route::post('kominfo/kunjungan/riwayat', [PasienKominfoController::class, 'kunjungan']);
Route::post('poin_kominfo', [PasienKominfoController::class, 'rekapPoin']);
Route::post('poin_kominfo/pecah', [PasienKominfoController::class, 'rekapPoinPecah']);
Route::post('kominfo/waktu_layanan', [PasienKominfoController::class, 'waktuLayanan']);
Route::post('kominfo/rata_waktu_tunggu', [PasienKominfoController::class, 'avgWaktuTunggu']);
Route::post('kominfo/pendaftaran', [PasienKominfoController::class, 'pendaftaranFilter']); //cari No RM
Route::post('kominfo/pendaftaran/report', [PasienKominfoController::class, 'reportPendaftaran']);
Route::get('resume/{no_rm}/{tgl}', [PasienKominfoController::class, 'resumePasien']);
Route::post('kominfo/pendaftaran/resume', [PasienKominfoController::class, 'resumePasien']);
Route::post('kominfo/report/dokter_rme', [PasienKominfoController::class, 'grafikDokter']);
Route::post('kominfo/antrian/log', [PasienKominfoController::class, 'logAntrian']);
Route::post('kominfo/pendaftaran/faskes_perujuk', [PasienKominfoController::class, 'rekapFaskesPerujuk']);

Route::post('kominfo/data_rencana_kontrol', [ApiKominfoController::class, 'data_rencana_kontrol']);
Route::get('jadwal/dokter/poli', [ApiKominfoController::class, 'poliDokter']);
Route::post('sep/get_data', [ApiKominfoController::class, 'getDataSEP']);
Route::post('sep/getSEP', [ApiKominfoController::class, 'getSEP']);

// sb
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

//Surat Medis
Route::get('surat/medis/{id}/{tgl}', [SuratController::class, 'cetakSM']);
Route::post('surat/medis', [SuratController::class, 'store']);
Route::post('surat/medis/update', [SuratController::class, 'update']);
Route::post('surat/medis/delete', [SuratController::class, 'destroy']);
Route::post('surat/medis/riwayat', [SuratController::class, 'riwayat']);
Route::post('surat/medis/riwayat', [SuratController::class, 'riwayat']);

Route::post('data/analis/biaya_pasien', [DataAnalisController::class, 'DataBiayaKunjungan']);
Route::post('data/analis/faskes_perujuk', [DataAnalisController::class, 'faskesPerujuk']);
Route::post('data/analis/kunjungan_lab', [DataAnalisController::class, 'kunjunganLab']);

Route::post('jadwal/upload', [JadwalController::class, 'import'])->name('jadwal.import');
Route::post('jadwal/get', [JadwalController::class, 'getJadwal'])->name('jadwal.getJadwal');
Route::delete('jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
Route::put('jadwal/{id}', [JadwalController::class, 'update'])->name('jadwal.update');

//Pendaftaran Cetak
Route::get('pendaftaran/cetak/label/{norm}', [PendaftaranController::class, 'label']);
Route::get('pendaftaran/cetak/rm/{norm}', [PendaftaranController::class, 'biodata']);
Route::post('pendaftaran/pasien/daftar', [PendaftaranController::class, 'daftar']);

// });
