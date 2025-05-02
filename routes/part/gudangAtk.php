<?php

use App\Http\Controllers\GudangATKController;
use Illuminate\Support\Facades\Route;

Route::post('addAtk', [GudangATKController::class, 'addAtk'])->name('addAtk.endpoint');
Route::post('updateAddAtk', [GudangATKController::class, 'updateAddAtk'])->name('updateAddAtk.endpoint');
Route::post('hapusAddAtk', [GudangATKController::class, 'hapusAddAtk'])->name('hapusAddAtk.endpoint');

Route::post('keluarAtk', [GudangATKController::class, 'keluarAtk'])->name('keluarAtk.endpoint');
Route::post('updateKeluarAtk', [GudangATKController::class, 'updateKeluarAtk'])->name('updateKeluarAtk.endpoint');
Route::post('hapusKeluarAtk', [GudangATKController::class, 'hapusKeluarAtk'])->name('hapusKeluarAtk.endpoint');
