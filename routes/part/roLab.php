<?php

use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\RoMasterController;
use App\Http\Controllers\ROTransaksiController;
use Illuminate\Support\Facades\Route;

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
Route::get('ro/kegiatan/laporan/{tglAwal}/{tglAkhir}', [ROTransaksiController::class, 'rekapKegiatan']);
Route::post('ro/laporan/kunjungan', [ROTransaksiController::class, 'rekapKunjunganRo']);
Route::post('ro/laporan/kunjungan/item', [ROTransaksiController::class, 'rekapKunjunganRoItem']);

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

Route::get('tb04/antrian/{tanggal}', [LaboratoriumController::class, 'getDataTb04']);
Route::get('tb04/cetak/{tanggal}', [LaboratoriumController::class, 'cetakTb04']);

Route::post('lab/laporan/kunjungan', [LaboratoriumController::class, 'rekapKunjunganLab']);
Route::post('lab/laporan/kunjungan/item', [LaboratoriumController::class, 'rekapKunjunganLabItem']);

Route::post('addHasilLab', [LaboratoriumController::class, 'addHasil']);
Route::post('cariRiwayatLab', [LaboratoriumController::class, 'riwayat']);
