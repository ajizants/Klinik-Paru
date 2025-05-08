<?php

use App\Http\Controllers\PasienKominfoController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;

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
Route::post('kominfo/report/dokter_rme', [PasienKominfoController::class, 'grafikDokter']);
// Route::post('kominfo/pendaftaran', [PasienKominfoController::class, 'pendaftaranFilter']); //cari No RM
// Route::post('kominfo/pendaftaran/report', [PasienKominfoController::class, 'reportPendaftaran']);
// Route::get('resume/{no_rm}/{tgl}', [PasienKominfoController::class, 'resumePasien']);
// Route::post('kominfo/pendaftaran/resume', [PasienKominfoController::class, 'resumePasien']);
// Route::post('kominfo/antrian/log', [PasienKominfoController::class, 'logAntrian']);
// Route::post('kominfo/pendaftaran/faskes_perujuk', [PasienKominfoController::class, 'rekapFaskesPerujuk']);

// Route::post('kominfo/data_rencana_kontrol', [ApiKominfoController::class, 'data_rencana_kontrol']);
// Route::get('jadwal/dokter/poli', [ApiKominfoController::class, 'poliDokter']);

// //Pendaftaran Cetak
// Route::get('pendaftaran/cetak/label/{norm}', [PendaftaranController::class, 'label']);
// Route::get('pendaftaran/cetak/rm/{norm}', [PendaftaranController::class, 'biodata']);
// Route::post('pendaftaran/pasien/daftar', [PendaftaranController::class, 'daftar']);
// Route::get('pendaftaran/pasien/{norm}', [PendaftaranController::class, 'showPasien']);

// Route::post('sep/get_data', [ApiKominfoController::class, 'getDataSEP']);
// Route::post('sep/detail', [ApiKominfoController::class, 'getDetailSEP']);
// Route::get('sep/cetak/{no_sep}', [ApiKominfoController::class, 'cetakSEP']);
// Route::post('SuratKontrol/get_data', [ApiKominfoController::class, 'getDataSuratKontrol']);
// Route::post('SuratKontrol/detail', [ApiKominfoController::class, 'getDetailSuratKontrol']);
// Route::get('SuratKontrol/cetak/{no_SuratKontrol}', [ApiKominfoController::class, 'cetakSuratKontrol']);
// Route::post('bpjs/get_data', [ApiKominfoController::class, 'getDataSEPSK']);
