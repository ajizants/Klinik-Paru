<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//login
Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');

Route::get('home', [HomeController::class, 'home'])->name('home')->middleware('auth');
Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');

//menu
Route::middleware('auth')->group(function () {
    Route::get('report', [HomeController::class, 'report'])->name('report');
    //farmasi
    Route::get('farmasi', [HomeController::class, 'farmasi'])->name('farmasi')->middleware('role:farmasi');
    Route::get('logFarmasi', [HomeController::class, 'logFarmasi'])->name('logFarmasi')->middleware('role:farmasi');
    Route::get('gudangFarmasi', [HomeController::class, 'gudangFarmasi'])->name('gudangFarmasi')->middleware('role:farmasi');
    //dots
    Route::get('dots', [HomeController::class, 'dots'])->name('dots')->middleware('role:dots');
    //igd
    Route::get('askep', [HomeController::class, 'askep'])->name('askep')->middleware('role:igd');
    Route::get('igd', [HomeController::class, 'igd'])->name('igd')->middleware('role:igd');
    Route::get('gudangIGD', [HomeController::class, 'gudangIGD'])->name('gudangIGD')->middleware('role:igd');
    //Kasir
    Route::get('kasir', [HomeController::class, 'kasir'])->name('kasir')->middleware('role:kasir');
    Route::get('lte', [HomeController::class, 'lte'])->name('lte')->middleware('role:kasir');
    //dispenser
});
Route::get('dispenser', [HomeController::class, 'dispenser'])->name('dispenser');
