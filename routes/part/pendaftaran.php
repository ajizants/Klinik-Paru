<?php

use App\Http\Controllers\ApiKominfoController;
use App\Http\Controllers\PasienKominfoController;
use App\Http\Controllers\PendaftaranController;
use Illuminate\Support\Facades\Route;

Route::post('kominfo/pendaftaran', [PasienKominfoController::class, 'pendaftaranFilter']); //cari No RM
Route::post('pendaftaran/report', [PasienKominfoController::class, 'reportPendaftaran']);
Route::get('pendaftaran/report/{tahun}', [PasienKominfoController::class, 'reportPusatDataPendaftaran']);
Route::get('resume/{no_rm}/{tgl}', [PasienKominfoController::class, 'resumePasien']);
Route::post('kominfo/antrian/log', [PasienKominfoController::class, 'logAntrian']);
Route::post('pendaftaran/faskes_perujuk', [PasienKominfoController::class, 'rekapFaskesPerujuk']);

Route::post('pendaftaran/data_rencana_kontrol', [ApiKominfoController::class, 'data_rencana_kontrol']);
Route::get('jadwal/dokter/poli', [ApiKominfoController::class, 'poliDokter']);

//Pendaftaran Cetak
Route::get('pendaftaran/cetak/label/{norm}', [PendaftaranController::class, 'label']);
Route::get('pendaftaran/cetak/rm/{norm}', [PendaftaranController::class, 'biodata']);
Route::post('pendaftaran/pasien/daftar', [PendaftaranController::class, 'daftar']);
Route::get('pendaftaran/pasien/{norm}', [PendaftaranController::class, 'showPasien']);

Route::post('sep/get_data', [ApiKominfoController::class, 'getDataSEP']);
Route::post('sep/detail', [ApiKominfoController::class, 'getDetailSEP']);
Route::get('sep/cetak/{no_sep}', [ApiKominfoController::class, 'cetakSEP']);
Route::get('sep/billing/cetak/{no_sep}', [ApiKominfoController::class, 'cetakSEPBilling']);
Route::post('SuratKontrol/get_data', [ApiKominfoController::class, 'getDataSuratKontrol']);
Route::post('SuratKontrol/detail', [ApiKominfoController::class, 'getDetailSuratKontrol']);
Route::get('SuratKontrol/cetak/{no_SuratKontrol}', [ApiKominfoController::class, 'cetakSuratKontrol']);
Route::post('bpjs/get_data', [ApiKominfoController::class, 'getDataSEPSK']);

Route::get('laporan/dokter_periksa/{tahun}/{bln}', [ApiKominfoController::class, 'getJumlahPemeriksaanDokter']);
