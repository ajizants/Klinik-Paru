
<?php

    use App\Http\Controllers\CutiPegawaiController;
    use Illuminate\Support\Facades\Route;

    Route::middleware('auth')->group(function () {
        Route::get('TataUsaha/Cuti', [CutiPegawaiController::class, 'index'])->name('cuti');
        Route::post('tu/cuti/pengajuan', [CutiPegawaiController::class, 'ajukanCuti']);
        Route::post('tu/cuti/tambahkanCuti', [CutiPegawaiController::class, 'tambahkanCuti']);
        Route::get('tu/cuti/bulan/{nip}', [CutiPegawaiController::class, 'getPermohonanCutiPegawai']);
        Route::get('tu/cuti/hari/{tgl}', [CutiPegawaiController::class, 'getCutiPegawai']);
        Route::get('tu/cuti/persetujuan/{id}/{persetujuan}', [CutiPegawaiController::class, 'update']);
        Route::get('tu/cuti/sisa/get', [CutiPegawaiController::class, 'getDataSisaCuti']);
        Route::get('tu/cuti/sisa/get/{nip}', [CutiPegawaiController::class, 'getDataSisaCutiPerson']);
        Route::get('tu/cuti/hapus/{cutiPegawai}', [CutiPegawaiController::class, 'destroy']);
        Route::get('/absensi/log', [CutiPegawaiController::class, 'ambilLog']);

});