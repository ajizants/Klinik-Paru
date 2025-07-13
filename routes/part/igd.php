
<?php

    use App\Http\Controllers\IgdController;
    use App\Http\Controllers\InputController;
    use App\Http\Controllers\SpirometriModelController;
    use Illuminate\Support\Facades\Route;

    //transaksi gudang igd
    Route::post('addJenisBmhp', [InputController::class, 'addJenisBmhp']);
    Route::post('deleteJenisBmhp', [InputController::class, 'deleteJenisBmhp']);
    Route::post('addJenisTindakan', [InputController::class, 'addJenisTindakan']);
    Route::post('deleteJenisTindakan', [InputController::class, 'deleteJenisTindakan']);

    //transaksi igd
    Route::post('editTindakan', [IgdController::class, 'editTindakan']);
    Route::post('simpanTindakan', [IgdController::class, 'simpanTindakan']);
    Route::post('deleteTindakan', [IgdController::class, 'deleteTindakan']);
    Route::post('addTransaksiBmhp', [IgdController::class, 'addTransaksiBmhp']);
    Route::post('deleteTransaksiBmhp', [IgdController::class, 'deleteTransaksiBmhp']);

    //transaksi IGD
    Route::post('cariDataTindakan', [IgdController::class, 'cariDataTindakan']);
    Route::get('chart', [IgdController::class, 'chart'])->name('chart.endpoint');
    Route::get('report_igd', [IgdController::class, 'report_igd'])->name('report_igd.endpoint');
    Route::post('cariTransaksiBmhp', [IgdController::class, 'cariTransaksiBmhp']);
    Route::get('cariSisa/{year}', [IgdController::class, 'cariSisa']);
    Route::get('cariSisa2/{year}', [IgdController::class, 'cariKunjunganPerBulan']);

    //Poin
    Route::post('cariPoin', [IgdController::class, 'cariPoin']);
    Route::post('cariPoinTotal', [IgdController::class, 'cariPoinTotal']);
    //jumalh Poin format jaspel
    Route::get('getRekapJumlahPoin/{bln}/{tahun}', [IgdController::class, 'poinPegawai']);

    //jumalh pemeriksaan
    Route::post('getRekapJumlahTindakan', [IgdController::class, 'getRekapJumlahTindakan']);

    //Spirometri
    Route::post('spiro/simpan', [SpirometriModelController::class, 'store']);
Route::post('spiro/update/{spirometriModel}', [SpirometriModelController::class, 'update']);
Route::get('spiro/antrian', [SpirometriModelController::class, 'index']);