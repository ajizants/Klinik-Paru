<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PendaftaranKominfoController extends Controller
{
    public function antrianKominfo()
    {
        // URL tujuan
        $url = 'https://kkpm.banyumaskab.go.id/api/pendaftaran/data_pendaftaran';

        // Data POST
        $data = [
            'username' => '3301010509940003',
            'password' => 'banyumas',
        ];

        // Menggunakan HTTP Client Laravel
        $response = Http::post($url, $data);

        // return $response->body();
        echo $response;
        // return $response->json(); // jika Anda ingin mendapatkan respons dalam bentuk json
    }
}
