
<?php

use App\Http\Controllers\RanapPendaftaranController;
use Illuminate\Support\Facades\Route;

Route::post('/ranap/pendaftaran', [RanapPendaftaranController::class, 'store']);