<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerifController extends Controller
{
    public function index(Request $request)
    {
        ini_set('max_execution_time', 400);

        // Ambil parameter dari request
        $username = env('FRISTA_USERNAME');
        $password = env('FRISTA_PASSWORD');
        $nik = $request->input('nik');

        // URL API Flask di Windows
        $apiUrl = env('FRISTA_API_URL');

        // Kirim permintaan ke API Flask dengan waktu timeout yang diperpanjang
        $response = Http::timeout(600)->post($apiUrl, [
            'username' => $username,
            'password' => $password,
            'nik' => $nik,
        ]);
        // dd($response);

        // Mengembalikan hasil sebagai JSON
        return response()->json([
            'success' => $response->successful(),
            'output' => $response->json('output'),
            'error' => $response->json('error'),
        ]);
    }
}
