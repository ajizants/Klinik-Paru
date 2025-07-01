
<?php

    use App\Http\Controllers\CutiPegawaiController;
    use Illuminate\Support\Facades\Route;

    Route::post('tu/cuti/pengajuan', [CutiPegawaiController::class, 'ajukanCuti']);
    Route::get('tu/cuti/{bulan}', [CutiPegawaiController::class, 'getPermohonanCutiPegawai']);
Route::get('tu/cuti/hari/{tgl}', [CutiPegawaiController::class, 'getCutiPegawai']);
