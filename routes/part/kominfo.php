<?php

use App\Http\Controllers\ApiKominfoController;
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
Route::post('kominfo/pendaftaran/report', [PasienKominfoController::class, 'reportPendaftaran']);

Route::get('kominfo/get_assesment_awal/{norm}/{tanggal}', [ApiKominfoController::class, 'get_assesment_awal']);
Route::get('kominfo/get_data_tindakan/{pendaftaran_id}', [ApiKominfoController::class, 'get_data_tindakan']);
Route::get('kominfo/get_master_obat', [ApiKominfoController::class, 'get_master_obat']);
