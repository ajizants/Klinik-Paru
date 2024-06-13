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
    public function pasienKominfo(Request $request)
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
    // public function getDataPasienDaftar(Request $request)
    // {
    //     // Periksa apakah 'no_rm' diset dalam data POST
    //     if ($request->has('norm')) {
    //         $no_rm = $request->input('norm');
    //         $uname = $request->input('username');
    //         $pass = $request->input('password');

    //         // URL tujuan
    //         $url = 'https://kkpm.banyumaskab.go.id/api/pendaftaran/data_pendaftaran';

    //         // Data POST
    //         $data = [
    //             'username' => $uname,
    //             'password' => $pass,
    //         ];

    //         // Inisialisasi CURL
    //         $ch = curl_init($url);

    //         // Set pilihan CURL
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    //         curl_setopt($ch, CURLOPT_POST, true);
    //         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    //         // Eksekusi CURL
    //         $response = curl_exec($ch);

    //         // Tangani kesalahan CURL
    //         if ($response === false) {
    //             return response()->json(['error' => 'Failed to fetch data from external URL'], 500);
    //         }

    //         // Periksa kode status CURL
    //         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //         if ($httpCode !== 200) {
    //             return response()->json(['error' => 'Failed to fetch data'], $httpCode);
    //         }

    //         // Tutup CURL
    //         curl_close($ch);

    //         // Dekode respons JSON
    //         $responseData = json_decode($response, true);

    //         // Periksa apakah respons valid
    //         if (json_last_error() !== JSON_ERROR_NONE) {
    //             return response()->json(['error' => 'Invalid JSON response'], 500);
    //         }

    //         // Periksa apakah response mengandung data
    //         if (!isset($responseData['response']['data']) || !is_array($responseData['response']['data'])) {
    //             return response()->json(['error' => 'No data found in the response'], 500);
    //         }

    //         // Filter data berdasarkan no_rm
    //         $filteredData = array_filter($responseData['response']['data'], function ($item) use ($no_rm) {
    //             return isset($item['pasien_no_rm']) && $item['pasien_no_rm'] == $no_rm;
    //         });

    //         // Kembalikan respons dalam bentuk JSON
    //         return response()->json(array_values($filteredData));
    //     } else {
    //         // Jika 'no_rm' tidak diset, kembalikan respons error
    //         return response()->json(['error' => 'No "no_rm" parameter provided'], 400);
    //     }
    // }

    // public function getDataPasien(Request $request)
    // {
    //     // Periksa apakah 'no_rm' diset dalam data POST
    //     if ($request->has('no_rm')) {
    //         $no_rm = $request->input('no_rm');
    //         $uname = $request->input('username', '3301010509940003');
    //         $pass = $request->input('password', 'banyumas');

    //         // Fetch data from the first API
    //         $dataPasienUrl = 'https://kkpm.banyumaskab.go.id/api/pasien/data_pasien';
    //         $dataPasienPostData = [
    //             'username' => $uname,
    //             'password' => $pass,
    //             'no_rm' => $no_rm,
    //         ];

    //         $dataPasienResponse = $this->fetchDataFromApi($dataPasienUrl, $dataPasienPostData);
    //         if ($dataPasienResponse['metadata']['code'] !== 200) {
    //             return response()->json($dataPasienResponse, $dataPasienResponse['metadata']['code']);
    //         }

    //         // Fetch data from the second API
    //         $dataPendaftaranUrl = 'https://kkpm.banyumaskab.go.id/api/pendaftaran/data_pendaftaran';
    //         $dataPendaftaranPostData = [
    //             'username' => $uname,
    //             'password' => $pass,
    //             'pasien_no_rm' => $no_rm,
    //             'pasien_no_rm' => $no_rm,
    //         ];

    //         $dataPendaftaranResponse = $this->fetchDataFromApi($dataPendaftaranUrl, $dataPendaftaranPostData);
    //         if ($dataPendaftaranResponse['metadata']['code'] !== 200) {
    //             return response()->json($dataPendaftaranResponse, $dataPendaftaranResponse['metadata']['code']);
    //         }
    //         // dd($dataPendaftaranResponse);
    //         // Filter dataPendaftaran response based on no_rm
    //         $filteredDataPendaftaran = array_filter($dataPendaftaranResponse['response']['response']['data'], function ($item) use ($no_rm) {
    //             return isset($item['pasien_no_rm']) && $item['pasien_no_rm'] == $no_rm;
    //         });

    //         // Combine data from both responses
    //         $combinedData = [
    //             'pasien' => $dataPasienResponse['response'],
    //             'pendaftaran' => array_values($filteredDataPendaftaran),
    //         ];

    //         // Return the combined response
    //         return response()->json($combinedData);

    //         $formattedData = [];
    //         foreach ($combinedData as $d) {
    //             dd($d["pasien"]["response"]["data"]["pasien_no_rm"]);
    //             $formattedData[] = [
    //                 "norm" => $d["pasien"]["response"]["data"]["pasien_no_rm"] ?? null,
    //                 "nama" => $d["pasien"]["response"]["data"]["pasien_nama"] ?? null,
    //             ];
    //         }

    //         // return response()->json($formattedData);
    //         return response()->json([
    //             'metadata' => [
    //                 'message' => 'Data Ditemukan..!!',
    //                 'code' => 200,
    //             ],
    //             'response' => $formattedData,
    //         ], 400);

    //     } else {
    //         // Jika 'no_rm' tidak diset, kembalikan respons error
    //         return response()->json([
    //             'metadata' => [
    //                 'message' => 'No "no_rm" parameter provided',
    //                 'code' => 400,
    //             ],
    //             'response' => null,
    //         ], 200);
    //     }
    // }

    // private function fetchDataFromApi($url, $data)
    // {
    //     // Inisialisasi CURL
    //     $ch = curl_init($url);

    //     // Set pilihan CURL
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    //     // Eksekusi CURL
    //     $response = curl_exec($ch);

    //     // Tangani kesalahan CURL
    //     if ($response === false) {
    //         return [
    //             'metadata' => [
    //                 'message' => 'Failed to fetch data from external URL',
    //                 'code' => 500,
    //             ],
    //             'response' => null,
    //         ];
    //     }

    //     // Periksa kode status CURL
    //     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     if ($httpCode !== 200) {
    //         return [
    //             'metadata' => [
    //                 'message' => 'Failed to fetch data',
    //                 'code' => $httpCode,
    //             ],
    //             'response' => null,
    //         ];
    //     }

    //     // Tutup CURL
    //     curl_close($ch);

    //     // Dekode respons JSON
    //     $responseData = json_decode($response, true);

    //     // Periksa apakah respons valid
    //     if (json_last_error() !== JSON_ERROR_NONE) {
    //         return [
    //             'metadata' => [
    //                 'message' => 'Invalid JSON response',
    //                 'code' => 500,
    //             ],
    //             'response' => null,
    //         ];
    //     }

    //     return [
    //         'metadata' => [
    //             'message' => 'Data found',
    //             'code' => 200,
    //         ],
    //         'response' => $responseData,
    //     ];
    // }

    public function getDataPasien(Request $request)
    {
        if ($request->has('no_rm')) {
                    $uname = $request->input('username', '3301010509940003');
                    $pass = $request->input('password', 'banyumas');
            // $uname = $request->input('username');
            // $pass = $request->input('password');
            $no_rm = $request->input('no_rm');

            // Fetch data from both APIs
            $dataPasienResponse = $this->fetchDataFromApi(
                'https://kkpm.banyumaskab.go.id/api/pasien/data_pasien',
                [
                    'username' => $uname,
                    'password' => $pass,
                    'no_rm' => $no_rm,
                ]
            );

            $dataPendaftaranResponse = $this->fetchDataFromApi(
                'https://kkpm.banyumaskab.go.id/api/pendaftaran/data_pendaftaran',
                [
                    'username' => $uname,
                    'password' => $pass,
                ]
            );

            // Check if both responses are successful
            if ($dataPasienResponse['metadata']['code'] !== 200) {
                return response()->json($dataPasienResponse['metadata'], $dataPasienResponse['metadata']['code']);
            }
            if ($dataPendaftaranResponse['metadata']['code'] !== 200) {
                return response()->json($dataPendaftaranResponse['metadata'], $dataPendaftaranResponse['metadata']['code']);
            }

            // Extract the data we want
            $pendaftaranData = [];
            foreach ($dataPendaftaranResponse['response']['response']['data'] as $data) {
                if ($data['pasien_no_rm'] == $no_rm) {
                    $pendaftaranData[] = [
                        'id' => $data['id'],
                        'no_reg' => $data['no_reg'],
                        'no_trans' => $data['no_trans'],
                        'antrean_nomor' => $data['antrean_nomor'],
                        'tanggal' => $data['tanggal'],
                        'penjamin_nama' => $data['penjamin_nama'],
                        'jenis_kunjungan_nama' => $data['jenis_kunjungan_nama'],
                        'pasien_no_rm' => $data['pasien_no_rm'],
                        'pasien_lama_baru' => $data['pasien_lama_baru'],
                        'rs_paru_pasien_lama_baru' => $data['rs_paru_pasien_lama_baru'],
                        'poli_nama' => $data['poli_nama'],
                        'poli_sub_nama' => $data['poli_sub_nama'],
                        'dokter_nama' => $data['dokter_nama'],
                        'waktu_daftar' => $data['waktu_daftar'],
                        'waktu_verifikasi' => $data['waktu_verifikasi'],
                        'admin_pendaftaran' => $data['admin_pendaftaran'],
                        'log_id' => $data['log_id'],
                        'keterangan_urutan' => $data['keterangan_urutan'],
                        'pasien_umur' => $data['pasien_umur_tahun'] . ' Tahun ' . $data['pasien_umur_bulan'] . ' Bulan ' . $data['pasien_umur_hari'] . ' Hari',

                        'pasien_nik' => $dataPasienResponse['response']['response']['data']['pasien_nik'],
                        'pasien_nama' => $dataPasienResponse['response']['response']['data']['pasien_nama'],
                        'pasien_no_rm' => $dataPasienResponse['response']['response']['data']['pasien_no_rm'],
                        'jenis_kelamin_nama' => $dataPasienResponse['response']['response']['data']['jenis_kelamin_nama'],
                        'pasien_tempat_lahir' => $dataPasienResponse['response']['response']['data']['pasien_tempat_lahir'],
                        'pasien_tgl_lahir' => $dataPasienResponse['response']['response']['data']['pasien_tgl_lahir'],
                        'pasien_no_hp' => $dataPasienResponse['response']['response']['data']['pasien_no_hp'],
                        'pasien_domisili' => $dataPasienResponse['response']['response']['data']['pasien_alamat'],
                        'pasien_alamat' => 'DS. ' . $dataPasienResponse['response']['response']['data']['kelurahan_nama'] . ', ' . $dataPasienResponse['response']['response']['data']['pasien_rt'] . '/' . $dataPasienResponse['response']['response']['data']['pasien_rw'] . ', KEC.' . $dataPasienResponse['response']['response']['data']['kecamatan_nama'] . ', KAB.' . $dataPasienResponse['response']['response']['data']['kabupaten_nama'],
                        'provinsi_id' => $dataPasienResponse['response']['response']['data']['provinsi_id'],
                        'kabupaten_id' => $dataPasienResponse['response']['response']['data']['kabupaten_id'],
                        'kecamatan_id' => $dataPasienResponse['response']['response']['data']['kecamatan_id'],
                        'kelurahan_id' => $dataPasienResponse['response']['response']['data']['kelurahan_id'],
                        'pasien_rt' => $dataPasienResponse['response']['response']['data']['pasien_rt'],
                        'pasien_rw' => $dataPasienResponse['response']['response']['data']['pasien_rw'],
                    ];
                }
            }

            if (empty($pendaftaranData)) {
                return response()->json([
                    'metadata' => [
                        'message' => 'Data Pasien Tidak Ditemukan Pada Kunjungan Hari Ini',
                        'code' => 404, // Not Found
                    ],
                    'response' => null,
                ]);
            }

            // Build the response
            $response = [
                'metadata' => [
                    'message' => 'Data Pasien Ditemukan',
                    'code' => 200,
                ],
                'response' => [
                    'data' => $pendaftaranData,
                ],
            ];

            return response()->json($response);

            // $d = [
            //     'metadata' => [
            //         'message' => 'Data Pasien Ditemukan',
            //         'code' => 200,
            //     ],
            //     'response' => [
            //         'data' => $pendaftaranData,
            //     ],
            // ];


            // // Return the combined response
            // return response()->json($d);
        } else {
            // If required parameters are not provided, return an error response
            return response()->json(['error' => 'Missing required parameters'], 400);
        }
    }

    private function fetchDataFromApi($url, $data)
    {
        // Initialize CURL
        $ch = curl_init($url);

        // Set CURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        // Execute CURL
        $response = curl_exec($ch);

        // Handle CURL errors
        if ($response === false) {
            return [
                'metadata' => [
                    'message' => 'Failed to fetch data from external URL',
                    'code' => 500,
                ],
                'response' => null,
            ];
        }

        // Check CURL status code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            return [
                'metadata' => [
                    'message' => 'Failed to fetch data',
                    'code' => $httpCode,
                ],
                'response' => null,
            ];
        }

        // Close CURL
        curl_close($ch);

        // Decode JSON response
        $responseData = json_decode($response, true);

        // Check if the response is valid JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'metadata' => [
                    'message' => 'Invalid JSON response',
                    'code' => 500,
                ],
                'response' => null,
            ];
        }

        return [
            'metadata' => [
                'message' => 'Data found',
                'code' => 200,
            ],
            'response' => $responseData,
        ];
    }

}
