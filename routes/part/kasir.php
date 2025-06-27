<?php

use App\Http\Controllers\KasirController;
use App\Http\Controllers\KasirPenutupanKasController;
use App\Http\Controllers\KasirSetoranController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->group(function () {
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

//cetakan
Route::get('cetakSBS', [KasirController::class, 'cetakSBS']);
Route::get('cetakBAPH', [KasirController::class, 'cetakBAPH']);
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

// });
