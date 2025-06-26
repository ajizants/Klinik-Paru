<?php
namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiKominfo extends Model
{
    protected $table = 'm_pasien_kominfo';

    public function data_pasien_kontrol(array $params)
    {
        $client = new Client();

        // URL endpoint API yang ingin diakses
        $url = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/pasien/data_pasien_kontrol';

        // Username dan password untuk basic auth
        $username = env('API_USERNAME', '');
        $password = env('API_PASSWORD', '');

        // dd($params);

        try {
            // Lakukan permintaan POST dengan otentikasi dasar
            $response = $client->request('POST', $url, [
                'auth'        => [$username, $password],
                'form_params' => $params,
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            // Ambil body response
            $body = $response->getBody();
            // Konversi response body ke array
            $data = json_decode($body, true);
            // dd($data);
            // Periksa apakah data berhasil di-decode menjadi array
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Error decoding JSON response: ' . json_last_error_msg());
            }
            $data = $data['response']['data'];

            return $data;
        } catch (\Exception $e) {
            // Tangani kesalahan
            return ['error' => $e->getMessage()];
        }

    }

    private function login($username = null, $password = null)
    {
        // dd("masuk");
        $username = $username ?? env('USERNAME_KOMINFO', '');
        $password = env('PASSWORD_KOMINFO', '');
        $client   = new Client();
        $url      = env('BASR_URL_KOMINFO', '') . '/auth/login';

        $response = $client->request('POST', $url, [
            'form_params' => [
                'admin_username' => $username,
                'admin_password' => $password,
            ],
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);
        if ($response->getStatusCode() != 200) {
            return [
                'data'    => json_decode($response->getBody(), true),
                'cookies' => [],
            ];
        }

        // Ambil cookie dari header respons
        $cookies = $response->getHeader('Set-Cookie');

        // Mengambil body response untuk memastikan login berhasil
        $body = $response->getBody();
        $data = json_decode($body, true);
        if (isset($cookies[0])) {
            setcookie('kominfo_cookie', $cookies[0], time() + (86400 * 30), "/");
        }

        return [
            'data'    => json_decode($response->getBody(), true),
            'cookies' => $cookies,
        ];
    }

    public function getDataObat($namaObat = "")
    {
        $client = new Client();
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;
        if (! $cookie) {
            // Authenticate if no cookie is found
            $loginResponse = $this->login(env('USERNAME_KOMINFO', ''), env('PASSWORD_KOMINFO', ''));
            $cookie        = $loginResponse['cookies'][0] ?? null;

            if ($cookie) {
                setcookie('kominfo_cookie', $cookie, time() + (86400 * 30), "/"); // Set cookie in the browser
            } else {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }

        $url = env('BASR_URL_KOMINFO', '') . '/data_obat/get_data';

        try {
            $response = $client->request('POST', $url, [
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
                'form_params' => [
                    'draw'              => 1,
                    'start'             => 0,
                    'length'            => 1000,
                    'search'            => [
                        'value' => '',
                        'regex' => false,
                    ],
                    // columns[0] sampai [9]
                    'columns'           => [
                        ['data' => '', 'name' => '', 'searchable' => true, 'orderable' => false, 'search' => ['value' => '', 'regex' => false]],
                        ['data' => 'kode_obat', 'name' => '', 'searchable' => true, 'orderable' => false, 'search' => ['value' => '', 'regex' => false]],
                        ['data' => 'nama_obat', 'name' => '', 'searchable' => true, 'orderable' => false, 'search' => ['value' => '', 'regex' => false]],
                        ['data' => 'jenis_obat_nama', 'name' => '', 'searchable' => true, 'orderable' => false, 'search' => ['value' => '', 'regex' => false]],
                        ['data' => 'nama_bentuk', 'name' => '', 'searchable' => true, 'orderable' => false, 'search' => ['value' => '', 'regex' => false]],
                        ['data' => 'dosis', 'name' => '', 'searchable' => true, 'orderable' => false, 'search' => ['value' => '', 'regex' => false]],
                        ['data' => 'nama_kategori', 'name' => '', 'searchable' => true, 'orderable' => false, 'search' => ['value' => '', 'regex' => false]],
                        ['data' => 'nama_satuan', 'name' => '', 'searchable' => true, 'orderable' => false, 'search' => ['value' => '', 'regex' => false]],
                        ['data' => 'stok', 'name' => '', 'searchable' => true, 'orderable' => false, 'search' => ['value' => '', 'regex' => false]],
                        ['data' => 'id', 'name' => '', 'searchable' => true, 'orderable' => false, 'search' => ['value' => '', 'regex' => false]],
                    ],
                    // Filter tambahan
                    'kode_obat_filter'  => '',
                    'nama_obat_filter'  => $namaObat,
                    'stok_filter'       => 't',
                    'kategori_filter'   => '',
                    'satuan_filter'     => '',
                    'bentuk_filter'     => '',
                    'jenis_obat_filter' => '',
                ],
            ]);

            // Check if the response status is 200
            if ($response->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }

            $body = (string) $response->getBody();
            $data = json_decode($body, true);
            return $data;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle network or request errors
            Log::error('Request Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghubungi server.'], 500);
        } catch (\Exception $e) {
            // Handle unexpected errors
            Log::error('Unexpected Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan yang tidak terduga.'], 500);
        }
    }
}
