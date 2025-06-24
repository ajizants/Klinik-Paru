
<?php

    use App\Http\Controllers\RanapCPPTController;
    use App\Http\Controllers\RanapPendaftaranController;
    use Illuminate\Support\Facades\Route;

    Route::post('/ranap/pendaftaran', [RanapPendaftaranController::class, 'store']);
    Route::get('/ranap/pendaftaran/{ranapPendaftaran}', [RanapPendaftaranController::class, 'show']);
    Route::put('/ranap/pendaftaran/{ranapPendaftaran}', [RanapPendaftaranController::class, 'update']);
    Route::delete('/ranap/pendaftaran/{ranapPendaftaran}', [RanapPendaftaranController::class, 'destroy']);
    Route::post('/ranap/pendaftaran/pulangkanPasien', [RanapPendaftaranController::class, 'pulangkanPasien']);

    Route::get('/ranap/cppt/getFormId', [RanapCPPTController::class, 'getFormId']);
    Route::post('ranap/cppt', [RanapCPPTController::class, 'store']);
    Route::get('/ranap/cppt/{notrans}', [RanapCPPTController::class, 'show']);

Route::put('/ranap/cppt/{ranapCPPT}', [RanapCPPTController::class, 'update']);
Route::delete('/ranap/cppt/{ranapCPPT}', [RanapCPPTController::class, 'destroy']);