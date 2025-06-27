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

    public function login($username = null, $password = null)
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
            setcookie('kominfo_cookie', $cookies[0], time() + (86400 * 30), "/"); // Cookie akan kedaluwarsa dalam 30 hari
        }

        return [
            'data'    => json_decode($response->getBody(), true),
            'cookies' => $cookies,
        ];
    }

    public function get_pasien($norm)
    {
        $client = new Client();
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;

        if (! $cookie) {
            // Authenticate if no cookie is found
            $loginResponse = $this->login(env('USERNAME_KOMINFO', ''), env('PASSWORD_KOMINFO', ''));
            $cookie        = $loginResponse['cookies'][0] ?? null;

            if ($cookie) {
                setcookie('kominfo_cookie', $cookie, time() + (86400 * 30), "/");
            } else {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }

        $url = env('BASR_URL_KOMINFO', '') . '/data_pasien/get_data';

        // Format request sesuai dengan yang Anda inginkan
        $columns = [];
        for ($i = 0; $i < 3; $i++) { // Sesuaikan jumlah kolom sesuai kebutuhan
            $columns[] = [
                'data'       => ($i === 0) ? '' : 'id',
                'name'       => '',
                'searchable' => true,
                'orderable'  => false,
                'search'     => ['value' => '', 'regex' => false],
            ];
        }

        try {
            $response = $client->request('POST', $url, [
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
                'form_params' => [
                    'draw'               => 2,
                    'columns'            => $columns,
                    'start'              => 0,
                    'length'             => 100,
                    'search'             => [
                        'value' => '',
                        'regex' => false,
                    ],
                    'pasien_nik'         => $nik ?? '',
                    'pasien_nama'        => $nama ?? '',
                    'pasien_no_rm'       => $norm ?? '',
                    'jenis_kelamin_id'   => '',
                    'pasien_tgl_lahir'   => '',
                    'kelurahan'          => '',
                    'created_at'         => $createdat ?? '',
                    'penjamin_id'        => 2,
                    'nomor_referensi'    => '',
                    'penjamin_nomor'     => '',
                    'jenis_kunjungan_id' => '',
                    'no_surat_kontrol'   => '',

                ],
            ]);

            // Check if response is successful
            if ($response->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }

            $body = (string) $response->getBody();
            $data = json_decode($body, true);
            $data = $data['data'];
            return $data;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle request errors
            Log::error('Request Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghubungi server.'], 500);
        } catch (\Exception $e) {
            // Handle unexpected errors
            Log::error('Unexpected Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan yang tidak terduga.'], 500);
        }
    }

    public function get_assesment_awal(array $params)
    {
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;

        if (! $cookie) {
            $loginResponse = $this->login(env('USERNAME_KOMINFO', ''), env('PASSWORD_KOMINFO', ''));
            $cookie        = $loginResponse['cookies'][0] ?? null;

            if ($cookie) {
                setcookie('kominfo_cookie', $cookie, time() + (86400 * 30), "/");
            } else {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }

        $client = new Client();
        $url    = env('BASR_URL_KOMINFO', '') . '/ruang_tensi/show_assessment_awal';
        // dd($cookie);

        try {
            $response = $client->request('POST', $url, [
                'query'   => ['view_tensi' => 'true'],
                'headers' => [
                    'accept'             => '*/*',
                    'accept-encoding'    => 'gzip, deflate, br, zstd',
                    'accept-language'    => 'en-US,en;q=0.6',
                    'connection'         => 'keep-alive',
                    'content-length'     => '29',
                    'content-type'       => 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Cookie'             => $cookie,
                    'host'               => 'kkpm.banyumaskab.go.id',
                    'origin'             => 'https://kkpm.banyumaskab.go.id',
                    'referer'            => 'https://kkpm.banyumaskab.go.id/administrator/ruang_poli/menu_poli?poli_sub_id=1',
                    'sec-ch-ua'          => '"Not)A;Brand";v="8", "Chromium";v="138", "Brave";v="138"',
                    'sec-ch-ua-mobile'   => '?0',
                    'sec-ch-ua-platform' => '"Windows"',
                    'sec-fetch-dest'     => 'empty',
                    'sec-fetch-mode'     => 'cors',
                    'sec-fetch-site'     => 'same-origin',
                    'sec-gpc'            => '1',
                    'user-agent'         => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36',
                    'x-requested-with'   => 'XMLHttpRequest',

                ],
            ]);
                                                         // dd($response);
            $body = $response->getBody()->getContents(); // Ambil isi response
            $data = json_decode($body, true);            // Decode JSON ke array

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('JSON Decode Error: ' . json_last_error_msg() . ' | Raw: ' . $body);
            }

            return $data['data']['content'] ?? [];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function get_data_tindakan($pendaftaran_id)
    {
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;

        if (! $cookie) {
            $loginResponse = $this->login(env('USERNAME_KOMINFO', ''), env('PASSWORD_KOMINFO', ''));
            $cookie        = $loginResponse['cookies'][0] ?? null;

            if ($cookie) {
                setcookie('kominfo_cookie', $cookie, time() + (86400 * 30), "/");
            } else {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }

        // Format request sesuai dengan yang Anda inginkan
        $columns = [];
        for ($i = 0; $i < 3; $i++) { // Sesuaikan jumlah kolom sesuai kebutuhan
            $columns[] = [
                'data'       => ($i === 0) ? '' : 'id',
                'name'       => '',
                'searchable' => true,
                'orderable'  => false,
                'search'     => ['value' => '', 'regex' => false],
            ];
        }
        $client = new Client();
        $url    = env('BASR_URL_KOMINFO', '') . '/ruang_poli/get_data_tindakan';

        try {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'draw'           => 2,
                    'columns'        => $columns,
                    'start'          => 0,
                    'length'         => 100,
                    'search'         => [
                        'value' => '',
                        'regex' => false,
                    ],
                    'pendaftaran_id' => $pendaftaran_id ?? '',
                ],
                'headers'     => [
                    'accept'             => '*/*',
                    'accept-encoding'    => 'gzip, deflate, br, zstd',
                    'accept-language'    => 'en-US,en;q=0.6',
                    'connection'         => 'keep-alive',
                    'content-length'     => '29',
                    'content-type'       => 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Cookie'             => $cookie,
                    'host'               => 'kkpm.banyumaskab.go.id',
                    'origin'             => 'https://kkpm.banyumaskab.go.id',
                    'referer'            => 'https://kkpm.banyumaskab.go.id/administrator/ruang_poli/menu_poli?poli_sub_id=1',
                    'sec-ch-ua'          => '"Not)A;Brand";v="8", "Chromium";v="138", "Brave";v="138"',
                    'sec-ch-ua-mobile'   => '?0',
                    'sec-ch-ua-platform' => '"Windows"',
                    'sec-fetch-dest'     => 'empty',
                    'sec-fetch-mode'     => 'cors',
                    'sec-fetch-site'     => 'same-origin',
                    'sec-gpc'            => '1',
                    'user-agent'         => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36',
                    'x-requested-with'   => 'XMLHttpRequest',

                ],
            ]);
                                              // dd($response);                    // dd($response);
            $body = $response->getBody();     // Ambil isi response
            $data = json_decode($body, true); // Decode JSON ke array

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('JSON Decode Error: ' . json_last_error_msg() . ' | Raw: ' . $body);
            }

            return $data['data'];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

}
