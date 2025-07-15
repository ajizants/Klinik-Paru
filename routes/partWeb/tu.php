
<?php

    use App\Http\Controllers\CutiPegawaiController;
    use App\Http\Controllers\CutiTambahanController;
    use App\Http\Controllers\HariLiburController;
    use Illuminate\Support\Facades\Route;

    Route::middleware('auth')->group(function () {
        Route::get('TataUsaha/Cuti', [CutiPegawaiController::class, 'index'])->name('cuti');
        Route::post('tu/cuti/pengajuan', [CutiPegawaiController::class, 'ajukanCuti']);
        Route::post('tu/cuti/tambahkanCuti', [CutiPegawaiController::class, 'tambahkanCuti']);
        Route::get('tu/cuti/bulan/{nip}', [CutiPegawaiController::class, 'getPermohonanCutiPegawai']);
        Route::get('tu/cuti/hari/{tgl}', [CutiPegawaiController::class, 'getCutiPegawai']);
        Route::get('tu/cuti/persetujuan/{id}/{persetujuan}', [CutiPegawaiController::class, 'persetujuan']);
        Route::get('tu/cuti/sisa/get', [CutiPegawaiController::class, 'getDataSisaCuti']);
        Route::get('tu/cuti/sisa/get/{nip}', [CutiPegawaiController::class, 'getDataSisaCutiPerson']);
        Route::post('tu/cuti/sisa/edit/{nip}', [CutiPegawaiController::class, 'updateSisaCuti']);
        Route::get('tu/cuti/hapus/{cutiPegawai}', [CutiPegawaiController::class, 'destroy']);
        Route::get('tu/cuti/edit/{cutiPegawai}', [CutiPegawaiController::class, 'show']);
        Route::post('tu/cuti/update/{cutiPegawai}', [CutiPegawaiController::class, 'update']);
        Route::get('tu/cuti/form', [CutiPegawaiController::class, 'formCuti']);
        Route::get('tu/cuti/cetak/{id}', [CutiPegawaiController::class, 'cetak']);

        Route::get('tu/cuti/download/kolektif', [CutiTambahanController::class, 'downloadTemplate']);
        Route::post('tu/cuti/tambahan', [CutiTambahanController::class, 'cutiTambahanByNip']);
        Route::post('tu/cuti/tambahan/kolektif', [CutiTambahanController::class, 'cutiTambahanKolektif']);
        Route::post('tu/cuti/tambahan/edit/{cutiTambahan}', [CutiTambahanController::class, 'update']);
        Route::post('tu/cuti/tambahan/delete/{cutiTambahan}', [CutiTambahanController::class, 'destroy']);

        Route::get('tu/hariLibur/get/{tahun}', [HariLiburController::class, 'index']);
        Route::post('tu/hariLibur/tambah', [HariLiburController::class, 'store']);
        Route::post('tu/hariLibur/upload', [HariLiburController::class, 'HariLiburKolektif']);
        Route::post('tu/hariLibur/ubah/{hariLibur}', [HariLiburController::class, 'update']);
        Route::post('tu/hariLibur/hapus/{hariLibur}', [HariLiburController::class, 'destroy']);

    Route::get('/absensi/log', [CutiPegawaiController::class, 'ambilLog']);
});