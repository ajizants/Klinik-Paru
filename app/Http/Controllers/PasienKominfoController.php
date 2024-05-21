<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PasienKominfoController extends Controller
{
    public function getDataPasienGuzzel(Request $request)
    {
        // dd($no_rm);
        // Periksa apakah 'no_rm' diset dalam data POST
        if ($request->has('norm')) {
            $no_rm = $request->input('norm');
            $uname = $request->input('username');
            $pass = $request->input('password');
            // Ekstrak nilai 'no_rm' dari data POST

            // URL tujuan
            $url = 'https://kkpm.banyumaskab.go.id/api/pasien/data_pasien';

            // Data POST
            $data = [
                'username' => $uname,
                'password' => $pass,
                'no_rm' => $no_rm,
            ];
            // dd($data);
            // Kirim permintaan POST menggunakan GuzzleHTTP
            $response = Http::post($url, $data);

            // Periksa status kode respons
            if ($response->successful()) {
                // Ambil data respons dalam bentuk JSON
                $responseData = $response->json();

                // Kembalikan respons dalam bentuk JSON
                return response()->json($responseData);
            } else {
                // Tangani jika respons tidak berhasil
                return response()->json(['error' => 'Failed to fetch data'], $response->status());
            }
        } else {
            // Jika 'no_rm' tidak diset, kembalikan respons error
            return response()->json(['error' => 'No "no_rm" parameter provided'], 400);
        }
    }

    public function getDataPasien(Request $request)
    {
        // Periksa apakah 'no_rm' diset dalam data POST
        if ($request->has('no_rm')) {
            $no_rm = $request->input('no_rm');
            // $uname = $request->input('username');
            // $pass = $request->input('password');

            // URL tujuan
            $url = 'https://kkpm.banyumaskab.go.id/api/pasien/data_pasien';

            // Data POST
            $data = [
                // 'username' => $uname,
                // 'password' => $pass,
                'username' => '3301010509940003',
                'password' => 'banyumas',
                'no_rm' => $no_rm,
            ];

            // Inisialisasi CURL
            $ch = curl_init($url);

            // Set pilihan CURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

            // Eksekusi CURL
            $response = curl_exec($ch);

            // Tangani kesalahan CURL
            if ($response === false) {
                return response()->json(['error' => 'Failed to fetch data from external URL'], 500);
            }

            // Periksa kode status CURL
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode !== 200) {
                return response()->json(['error' => 'Failed to fetch data'], $httpCode);
            }

            // Tutup CURL
            curl_close($ch);

            // Dekode respons JSON
            $responseData = json_decode($response);

            // Periksa apakah respons valid
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Invalid JSON response'], 500);
            }

            // Kembalikan respons dalam bentuk JSON
            return response()->json($responseData);
        } else {
            // Jika 'no_rm' tidak diset, kembalikan respons error
            return response()->json(['error' => 'No "no_rm" parameter provided'], 400);
        }
    }
    public function getDataPasienDaftarP(Request $request)
    {
        // Periksa apakah 'no_rm' diset dalam data POST
        if ($request->has('norm')) {
            $no_rm = $request->input('norm');
            $uname = $request->input('username');
            $pass = $request->input('password');

            // URL tujuan
            $url = 'http://kkpm.banyumaskab.go.id/api/pendaftaran/data_pendaftaran';

            // Data POST
            $data = [
                'username' => $uname,
                'password' => $pass,
                'no_rm' => $no_rm,
            ];

            // Inisialisasi CURL
            $ch = curl_init($url);

            // Set pilihan CURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

            // Eksekusi CURL
            $response = curl_exec($ch);

            // Tangani kesalahan CURL
            if ($response === false) {
                return response()->json(['error' => 'Failed to fetch data from external URL'], 500);
            }
            dd($response);

            // Periksa kode status CURL
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode !== 200) {
                return response()->json(['error' => 'Failed to fetch data'], $httpCode);
            }

            // Tutup CURL
            curl_close($ch);

            // Dekode respons JSON
            $responseData = json_decode($response);

            // Periksa apakah respons valid
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Invalid JSON response'], 500);
            }

            // Kembalikan respons dalam bentuk JSON
            return response()->json($responseData);
        } else {
            // Jika 'no_rm' tidak diset, kembalikan respons error
            return response()->json(['error' => 'No "no_rm" parameter provided'], 400);
        }
    }

}
