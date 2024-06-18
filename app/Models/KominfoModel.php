<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class KominfoModel extends Model
{
    // Fungsi untuk melakukan request dengan basic auth
    public function pendaftaranRequest($tanggal)
    {
        // Inisialisasi klien GuzzleHTTP
        $client = new Client();

        // URL endpoint API yang ingin diakses
        $url = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/pendaftaran/data_pendaftaran';

        // Username dan password untuk basic auth
        $username = '3301010509940003';
        $password = '~@j1s@nt0sO#';

        // Data POST
        $data = [
            'tanggal' => $tanggal,
        ];

        try {
            // Lakukan permintaan POST dengan otentikasi dasar
            $response = $client->request('POST', $url, [
                'auth' => [$username, $password],
                'form_params' => $data,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            // Ambil body response
            $body = $response->getBody();

            // Konversi response body ke array
            $data = json_decode($body, true);

            // Kembalikan data
            return $data;

        } catch (\Exception $e) {
            // Tangani kesalahan
            return ['error' => $e->getMessage()];
        }
    }
    public function cpptRequest(array $params)
    {
        // Inisialisasi klien GuzzleHTTP
        $client = new Client();

        // URL endpoint API yang ingin diakses
        $url = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/cppt/data_cppt';

        // Username dan password untuk basic auth
        $username = '3301010509940003';
        $password = '~@j1s@nt0sO#';

        try {
            // Lakukan permintaan POST dengan otentikasi dasar
            $response = $client->request('POST', $url, [
                'auth' => [$username, $password],
                'form_params' => $params,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            // Ambil body response
            $body = $response->getBody();

            // Konversi response body ke array
            $data = json_decode($body, true);

            // Kembalikan data
            return $data;

        } catch (\Exception $e) {
            // Tangani kesalahan
            return ['error' => $e->getMessage()];
        }
    }
    public function pasienRequest($no_rm)
    {
        // Inisialisasi klien GuzzleHTTP
        $client = new Client();

        // URL endpoint API yang ingin diakses
        $url = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/pasien/data_pasien';

        // Username dan password untuk basic auth
        $username = '3301010509940003';
        $password = '~@j1s@nt0sO#';

        // Data POST
        $data = [
            'no_rm' => $no_rm,
        ];

        try {
            // Lakukan permintaan POST dengan otentikasi dasar
            $response = $client->request('POST', $url, [
                'auth' => [$username, $password],
                'form_params' => $data,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            // Ambil body response
            $body = $response->getBody();

            // Konversi response body ke array
            $data = json_decode($body, true);

            // Kembalikan data
            return $data;

        } catch (\Exception $e) {
            // Tangani kesalahan
            return ['error' => $e->getMessage()];
        }
    }
}
