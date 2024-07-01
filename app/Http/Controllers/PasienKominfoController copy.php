<?php

namespace App\Http\Controllers;

use App\Models\DotsTransModel;
use App\Models\FarmasiModel;
use App\Models\IGDTransModel;
use App\Models\KominfoModel;
use App\Models\ROTransaksiHasilModel;
use App\Models\ROTransaksiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PasienKominfoController extends Controller
{

    public function pasienKominfo(Request $request)
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

        } else {
            // If required parameters are not provided, return an error response
            return response()->json(['error' => 'Missing required parameters'], 400);
        }
    }
    public function antrianKominfoFullData(Request $request)
    {
        if ($request->has('tanggal')) {
            // Default username and password, can be overridden by request input
            $uname = $request->input('username', '3301010509940003');
            $pass = $request->input('password', 'banyumas');
            $tanggal = $request->input('tanggal');
            $limit = 10; // Set the limit to 5

            // Fetch data pendaftaran from API
            $dataPendaftaranResponse = $this->fetchDataFromApi(
                'https://kkpm.banyumaskab.go.id/api/pendaftaran/data_pendaftaran',
                [
                    'username' => $uname,
                    'password' => $pass,
                    'tanggal' => $tanggal,
                    'limit' => $limit,
                ]
            );

            // Check if response is successful
            if ($dataPendaftaranResponse['metadata']['code'] !== 200) {
                return response()->json($dataPendaftaranResponse['metadata'], $dataPendaftaranResponse['metadata']['code']);
            }

            // Process data pendaftaran
            $antrian = [];
            $counter = 0;
            foreach ($dataPendaftaranResponse['response']['response']['data'] as $data) {
                // Skip if pasien_no_rm is not set or is empty
                if (empty($data['pasien_no_rm'])) {
                    continue;
                }
                // Break the loop if limit is reached
                if ($counter >= $limit) {
                    break;
                }

                // Fetch data pasien based on no_rm
                $dataPasienResponse = $this->fetchDataFromApi(
                    'https://kkpm.banyumaskab.go.id/api/pasien/data_pasien',
                    [
                        'username' => $uname,
                        'password' => $pass,
                        'no_rm' => $data['pasien_no_rm'],
                    ]
                );

                // Check if response is successful and contains the necessary data
                if ($dataPasienResponse['metadata']['code'] === 200 && isset($dataPasienResponse['response']['response']['data'])) {
                    $pasienData = $dataPasienResponse['response']['response']['data'];

                    // Combine pendaftaran data and pasien data
                    $antrian[] = [
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

                        // Data pasien tambahan dari API kedua
                        'pasien_nik' => $pasienData['pasien_nik'] ?? null,
                        'pasien_nama' => $pasienData['pasien_nama'] ?? null,
                        'jenis_kelamin_nama' => $pasienData['jenis_kelamin_nama'] ?? null,
                        'pasien_tempat_lahir' => $pasienData['pasien_tempat_lahir'] ?? null,
                        'pasien_tgl_lahir' => $pasienData['pasien_tgl_lahir'] ?? null,
                        'pasien_no_hp' => $pasienData['pasien_no_hp'] ?? null,
                        'pasien_domisili' => $pasienData['pasien_alamat'] ?? null,
                        'pasien_alamat' => 'DS. ' . ($pasienData['kelurahan_nama'] ?? '') . ', ' . ($pasienData['pasien_rt'] ?? '') . '/' . ($pasienData['pasien_rw'] ?? '') . ', KEC.' . ($pasienData['kecamatan_nama'] ?? '') . ', KAB.' . ($pasienData['kabupaten_nama'] ?? ''),
                        'provinsi_id' => $pasienData['provinsi_id'] ?? null,
                        'kabupaten_id' => $pasienData['kabupaten_id'] ?? null,
                        'kecamatan_id' => $pasienData['kecamatan_id'] ?? null,
                        'kelurahan_id' => $pasienData['kelurahan_id'] ?? null,
                        'pasien_rt' => $pasienData['pasien_rt'] ?? null,
                        'pasien_rw' => $pasienData['pasien_rw'] ?? null,
                    ];

                    $counter++;
                }
            }

            if (empty($antrian)) {
                return response()->json([
                    'metadata' => [
                        'message' => 'Data Pasien Tidak Ditemukan Pada Kunjungan Hari Ini',
                        'code' => 404,
                    ],
                    'response' => null,
                ]);
            }

            // Build response
            $response = [
                'metadata' => [
                    'message' => 'Data Pasien Ditemukan',
                    'code' => 200,
                ],
                'response' => [
                    'data' => $antrian,
                ],
            ];

            return response()->json($response);

        } else {
            // If required parameters are not provided, return an error response
            return response()->json(['error' => 'Missing required parameters'], 400);
        }
    }
    public function noAntrianKominfo(Request $request)
    {
        if ($request->has('tanggal')) {
            // Default username and password, can be overridden by request input
            $uname = $request->input('username', '3301010509940003');
            $pass = $request->input('password', 'banyumas');
            $tanggal = $request->input('tanggal');

            // Fetch data pendaftaran from API
            $dataPendaftaranResponse = $this->fetchDataFromApi(
                'https://kkpm.banyumaskab.go.id/api/pendaftaran/data_pendaftaran',
                [
                    'username' => $uname,
                    'password' => $pass,
                    'tanggal' => $tanggal,
                ]
            );

            // Check if response is successful
            if ($dataPendaftaranResponse['metadata']['code'] !== 200) {
                return response()->json($dataPendaftaranResponse['metadata'], $dataPendaftaranResponse['metadata']['code']);
            }

            // Process data pendaftaran
            $antrian = [];
            foreach ($dataPendaftaranResponse['response']['response']['data'] as $data) {
                // Skip if pasien_no_rm is not set or is empty
                if (empty($data['pasien_no_rm'])) {
                    continue;
                }

                // Combine pendaftaran data and pasien data
                $antrian[] = [
                    'id' => $data['id'],
                    'no_reg' => $data['no_reg'],
                    'no_trans' => $data['no_trans'],
                    'antrean_nomor' => $data['antrean_nomor'],
                    'tanggal' => $data['tanggal'],
                    'penjamin_nama' => $data['penjamin_nama'],
                    'jenis_kunjungan_nama' => $data['jenis_kunjungan_nama'],
                    'pasien_no_rm' => $data['pasien_no_rm'],
                    'pasien_nama' => $data['pasien_nama'],
                    'pasien_nik' => $data['pasien_nik'],
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
                ];
            }

            if (empty($antrian)) {
                return response()->json([
                    'metadata' => [
                        'message' => 'Data Pasien Tidak Ditemukan Pada Kunjungan Hari Ini',
                        'code' => 404,
                    ],
                    'response' => null,
                ]);
            }

            // Build response
            $response = [
                'metadata' => [
                    'message' => 'Data Pasien Ditemukan',
                    'code' => 200,
                ],
                'response' => [
                    'data' => $antrian,
                ],
            ];

            return response()->json($response);

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

    public function antrianAll(Request $request)
    {
        if ($request->has('tanggal')) {
            $tanggal = $request->input('tanggal');
            $ruang = $request->input('ruang');
            $model = new KominfoModel();
            $params = $request->only(['tanggal']);
            // Panggil metode untuk melakukan request
            // $data = $model->pendaftaranRequest($tanggal);
            $data = $model->waktuLayananRequest($params);
            // return response()->json($data);
            // if (isset($data['response']['data']) && is_array($data['response']['data'])) {
            if (isset($data) && is_array($data)) {
                // dd("oke");
                // $filteredData = array_filter($data['response']['data'], function ($d) {
                $filteredData = array_filter($data, function ($d) {
                    // return $d['keterangan'] === 'SELESAI DIPANGGIL LOKET PENDAFTARAN';
                    return true;
                });
                $filteredData = array_values($filteredData);

                // Map of dokter_nama to nip
                $doctorNipMap = [
                    'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
                    'dr. AGIL DANANJAYA, Sp.P' => '9',
                    'dr. FILLY ULFA KUSUMAWARDANI' => '198907252019022004',
                    'dr. SIGIT DWIYANTO' => '198903142022031005',
                ];

                // Iterate over filtered data and add status and nip
                foreach ($filteredData as &$item) {
                    $notrans = $item['no_trans'];
                    $norm = $item['pasien_no_rm'];
                    $dokter_nama = $item['dokter_nama'];

                    // Check if ROTransaksiModel exists for $notrans
                    if ($ruang == "ro") {
                        try {
                            $tsRo = ROTransaksiModel::where('notrans', $notrans)->first();
                            // Attempt to retrieve data from the 'rontgen' connection
                            $foto = ROTransaksiHasilModel::where('norm', $norm)
                                ->whereDate('tanggal', $tanggal)
                                ->first();
                            // dd($foto);
                            // Determine status based on conditions
                            if (!$tsRo && !$foto) {
                                $item['status'] = 'Belum Ada Ts RO';
                            } elseif ($tsRo && !$foto) {
                                $item['status'] = 'Belum Upload Foto Thorax';
                            } else {
                                $item['status'] = 'Sudah Selesai';
                            }
                        } catch (\Exception $e) {
                            // Handle the error: log it and continue processing
                            Log::error('Database connection failed: ' . $e->getMessage());
                            $item['status'] = 'Database connection error';
                        }
                    } elseif ($ruang == "igd") {
                        try {
                            $ts = IGDTransModel::with('transbmhp')->where('norm', $norm)
                                ->whereDate('created_at', $tanggal)
                                ->first();
                            // // dd($ts->id);
                            // $tsBmhp = TransaksiBMHPModel::where('idTind', $ts->id)->first();
                            // dd($tsBmhp);
                            // Determine status based on conditions
                            if (!$ts) {
                                $item['status'] = 'Tidak Ada Permintaan';
                                // } elseif ($ts && $tsBmhp == null) {
                                //     $item['status'] = 'Belum Ada Transaksi BMHP';
                            } elseif ($ts->transbmhp == null) {
                                $item['status'] = 'Belum Ada Transaksi BMHP';
                            } else {
                                $item['status'] = 'Sudah Selesai';
                            }
                        } catch (\Exception $e) {
                            // Handle the error: log it and continue processing
                            Log::error('Database connection failed: ' . $e->getMessage());
                            $item['status'] = 'Database connection error';
                        }
                    } elseif ($ruang == "farmasi") {
                        try {
                            $ts = FarmasiModel::where('norm', $norm)
                                ->whereDate('created_at', $tanggal)
                                ->first();
                            // Determine status based on conditions
                            if (!$ts) {
                                $item['status'] = 'Belum Ada Transaksi';
                            } else {
                                $item['status'] = 'Sudah Selesai';
                            }
                        } catch (\Exception $e) {
                            // Handle the error: log it and continue processing
                            Log::error('Database connection failed: ' . $e->getMessage());
                            $item['status'] = 'Database connection error';
                        }
                    } elseif ($ruang == "dots") {
                        try {
                            $ts = DotsTransModel::where('norm', $norm)
                                ->whereDate('created_at', $tanggal)
                                ->first();
                            // Determine status based on conditions
                            if (!$ts) {
                                $item['status'] = 'Tidak Ada Transaksi';
                            } else {
                                $item['status'] = 'Sudah Selesai';
                            }
                        } catch (\Exception $e) {
                            // Handle the error: log it and continue processing
                            Log::error('Database connection failed: ' . $e->getMessage());
                            $item['status'] = 'Database connection error';
                        }

                    }

                    // Add nip based on dokter_nama
                    if (isset($doctorNipMap[$dokter_nama])) {
                        $item['nip_dokter'] = $doctorNipMap[$dokter_nama];
                    } else {
                        $item['nip_dokter'] = 'Unknown';
                    }
                }

                $response = [
                    'metadata' => [
                        'message' => 'Data Pasien Ditemukan',
                        'code' => 200,
                    ],
                    'response' => [
                        'data' => $filteredData,
                    ],
                ];
                // Kembalikan respon dalam bentuk array
                return response()->json($response);
            } else {
                return response()->json(['error' => 'Invalid data format'], 500);
            }
        } else {
            // Jika parameter 'tanggal' tidak disediakan, kembalikan respons error
            return response()->json(['error' => 'Tanggal Belum Di Isi'], 400);
        }
    }
    public function newPendaftaran(Request $request)
    {
        if ($request->has('tanggal')) {
            $tanggal = $request->input('tanggal');
            $model = new KominfoModel();

            // Panggil metode untuk melakukan request
            $data = $model->pendaftaranRequest($tanggal);

            if (isset($data['response']['data']) && is_array($data['response']['data'])) {
                $filteredData = array_filter($data['response']['data'], function ($d) {
                    return $d['keterangan'] === 'SELESAI DIPANGGIL LOKET PENDAFTARAN';
                });
                $filteredData = array_values($filteredData);

                // Map of dokter_nama to nip
                $doctorNipMap = [
                    'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
                    'dr. AGIL DANANJAYA, Sp.P' => '9',
                    'dr. FILLY ULFA KUSUMAWARDANI' => '198907252019022004',
                    'dr. SIGIT DWIYANTO' => '198903142022031005',
                ];

                // Iterate over filtered data and add status and nip
                foreach ($filteredData as &$item) {
                    $notrans = $item['no_trans'];
                    $norm = $item['pasien_no_rm'];
                    $dokter_nama = $item['dokter_nama'];

                    // Check if ROTransaksiModel exists for $notrans
                    $tsRo = ROTransaksiModel::where('notrans', $notrans)->first();

                    try {
                        // Attempt to retrieve data from the 'rontgen' connection
                        $foto = ROTransaksiHasilModel::where('norm', $norm)
                            ->whereDate('tanggal', $tanggal)
                            ->first();
                        // dd($foto);
                        // Determine status based on conditions
                        if (!$tsRo && !$foto) {
                            $item['status'] = 'Belum Ada Transaksi';
                        } elseif ($tsRo && !$foto) {
                            $item['status'] = 'Belum Upload Foto Thorax';
                        } else {
                            $item['status'] = 'Sudah Selesai';
                        }
                    } catch (\Exception $e) {
                        // Handle the error: log it and continue processing
                        Log::error('Database connection failed: ' . $e->getMessage());
                        $item['status'] = 'Database connection error';
                    }

                    // Add nip based on dokter_nama
                    if (isset($doctorNipMap[$dokter_nama])) {
                        $item['nip_dokter'] = $doctorNipMap[$dokter_nama];
                    } else {
                        $item['nip_dokter'] = 'Unknown';
                    }
                }

                $response = [
                    'metadata' => [
                        'message' => 'Data Pasien Ditemukan',
                        'code' => 200,
                    ],
                    'response' => [
                        'data' => $filteredData,
                    ],
                ];
                // Kembalikan respon dalam bentuk array
                return response()->json($response);
            } else {
                return response()->json(['error' => 'Invalid data format'], 500);
            }
        } else {
            // Jika parameter 'tanggal' tidak disediakan, kembalikan respons error
            return response()->json(['error' => 'Tanggal Belum Di Isi'], 400);
        }
    }

    public function newPasien(Request $request)
    {
        if ($request->has('no_rm')) {
            $no_rm = $request->input('no_rm');
            $model = new KominfoModel();

            // Panggil metode untuk melakukan request
            $data = $model->pasienRequest($no_rm);

            // Tampilkan data (atau lakukan apa pun yang diperlukan)
            return response()->json($data);
        } else {
            // Jika parameter 'tanggal' tidak disediakan, kembalikan respons error
            return response()->json(['error' => 'No RM Belum Di Isi'], 400);
        }
    }
    public function dataPasien(Request $request)
    {
        if ($request->has('no_rm') && $request->has('tanggal')) {
            $no_rm = $request->input('no_rm');
            $tanggal = $request->input('tanggal');
            $model = new KominfoModel();

            // Panggil metode untuk melakukan request
            $res_pasien = $model->pasienRequest($no_rm);
            $pasien[] = [
                "pasien_nik" => $res_pasien['response']['data']['pasien_nik'],
                "pasien_no_kk" => $res_pasien['response']['data']['pasien_no_kk'],
                "pasien_nama" => $res_pasien['response']['data']['pasien_nama'],
                "pasien_no_rm" => $res_pasien['response']['data']['pasien_no_rm'],
                "jenis_kelamin_id" => $res_pasien['response']['data']['jenis_kelamin_id'],
                "jenis_kelamin_nama" => $res_pasien['response']['data']['jenis_kelamin_nama'],
                "pasien_tempat_lahir" => $res_pasien['response']['data']['pasien_tempat_lahir'],
                "pasien_tgl_lahir" => $res_pasien['response']['data']['pasien_tgl_lahir'],
                "pasien_no_hp" => $res_pasien['response']['data']['pasien_no_hp'],
                "pasien_domisili" => $res_pasien['response']['data']['pasien_alamat'],
                "pasien_alamat" => $res_pasien['response']['data']['kelurahan_nama'] . ", " . $res_pasien['response']['data']['pasien_rt'] . "/" . $res_pasien['response']['data']['pasien_rw'] . ", " . $res_pasien['response']['data']['kecamatan_nama'] . ", " . $res_pasien['response']['data']['kabupaten_nama'] . ", " . $res_pasien['response']['data']['provinsi_nama'],
                "provinsi_nama" => $res_pasien['response']['data']['provinsi_nama'],
                "kabupaten_nama" => $res_pasien['response']['data']['kabupaten_nama'],
                "kecamatan_nama" => $res_pasien['response']['data']['kecamatan_nama'],
                "kelurahan_nama" => $res_pasien['response']['data']['kelurahan_nama'],
                "pasien_rt" => $res_pasien['response']['data']['pasien_rt'],
                "pasien_rw" => $res_pasien['response']['data']['pasien_rw'],
                "penjamin_nama" => $res_pasien['response']['data']['penjamin_nama'],
            ];
            // Panggil metode untuk melakukan request
            $pendaftaran = $model->pendaftaranRequest($tanggal);
            // dd($pendaftaran);
            if (isset($pendaftaran['response']['data']) && is_array($pendaftaran['response']['data'])) {
                $filteredData = array_filter($pendaftaran['response']['data'], function ($d) {
                    return $d['pasien_no_rm'] === $_REQUEST['no_rm'];
                });
                $filteredData = array_values($filteredData);
                $notrans = !empty($filteredData) ? $filteredData[0]['no_trans'] : null;
                $doctorNipMap = [
                    'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
                    'dr. AGIL DANANJAYA, Sp.P' => '9',
                    'dr. FILLY ULFA KUSUMAWARDANI ' => '198907252019022004',
                    'dr. SIGIT DWIYANTO' => '198903142022031005',
                ];

                // Iterate over filtered data and add status and nip
                foreach ($filteredData as &$item) {
                    // Add nip based on dokter_nama
                    $dokter_nama = $item['dokter_nama'];
                    if (isset($doctorNipMap[$dokter_nama])) {
                        $item['nip_dokter'] = $doctorNipMap[$dokter_nama];
                    } else {
                        $item['nip_dokter'] = 'Unknown';
                    }
                }

                if (!empty($filteredData)) {
                    $response = [
                        'metadata' => [
                            'message' => 'Data Pasien Ditemukan',
                            'code' => 200,
                        ],
                        'response' => [
                            'pendaftaran' => $filteredData,
                            'pasien' => $pasien,
                        ],
                    ];
                    // Mengembalikan respons dengan kode 200
                    return response()->json($response, 200);
                } else {
                    $response = [
                        'metadata' => [
                            'message' => 'Pasien tidak mendaftar pada hari ini',
                            'code' => 204,
                        ],
                    ];
                    // Mengembalikan respons dengan kode 204
                    return response()->json($response, 200);
                }

            }
        }
    }

    public function newCpptRequest(Request $request)
    {
        if ($request->has(['tanggal_awal', 'tanggal_akhir'])) {
            // Ambil parameter dari request
            $params = $request->only(['tanggal_awal', 'tanggal_akhir', 'no_rm']);

            $model = new KominfoModel();

            // Panggil metode untuk melakukan request
            $data = $model->cpptRequest($params);

            if (isset($data['response']['data']) && is_array($data['response']['data'])) {
                $filteredData = array_filter(array_map(function ($d) {
                    // Skip jika tindakan kosong
                    if (empty($d['tindakan'])) {
                        return null;
                    }
                    $igd = IGDTransModel::whereDate('created_at', $d['tanggal'])
                        ->where('norm', $d['pasien_no_rm'])
                        ->first();

                    $d['status'] = $igd ? 'sudah' : 'belum';

                    $doctorNipMap = [
                        'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
                        'dr. AGIL DANANJAYA, Sp.P' => '9',
                        'dr. FILLY ULFA KUSUMAWARDANI' => '198907252019022004',
                        'dr. SIGIT DWIYANTO' => '198903142022031005',
                    ];

                    $dokter_nama = $d['dokter_nama'];
                    if (isset($doctorNipMap[$dokter_nama])) {
                        $d['nip_dokter'] = $doctorNipMap[$dokter_nama];
                    } else {
                        $d['nip_dokter'] = 'Unknown';
                    }

                    return $d;
                }, $data['response']['data']));

                // Pastikan hasil filtering tidak null
                if (!empty($filteredData)) {
                    $response = [
                        'metadata' => [
                            'message' => 'Data Pasien Ditemukan',
                            'code' => 200,
                        ],
                        'response' => [
                            'data' => array_values($filteredData),
                        ],
                    ];

                    return response()->json($response);
                    //
                } else {
                    return response()->json(['error' => 'No valid data found'], 404);
                }
            } else {
                return response()->json(['error' => 'Invalid data format'], 500);
            }

        } else {
            // Jika parameter tidak disediakan, kembalikan respons error
            return response()->json(['error' => 'Missing required parameters'], 400);
        }
    }

    public function rekapPoin(Request $request)
    {

        $params = $request->only(['tanggal_awal', 'tanggal_akhir']);

        $model = new KominfoModel();

        $data = $model->poinRequest($params);

        return response()->json($data);

    }

    public function waktuLayanan(Request $request)
    {

        $params = $request->all();

        $model = new KominfoModel();

        $data = $model->waktuLayananRequest($params);

        return response()->json($data);
        // if (isset($data['error'])) {
        //     return view('waktu_layanan', ['error' => $data['error']]);
        // } else {
        //     return view('waktu_layanan', ['data' => $data]);
        // }
    }

    public function avgWaktuTunggu(Request $request)
    {
        try {
            $params = $request->all();
            // $params=[];
            // dd($params);
            $model = new KominfoModel();

            // Ambil data dari model menggunakan metode waktuLayananRequest
            $data = $model->waktuLayananRequest($params);
            // dd($data);
            // Hitung rata-rata waktu tunggu
            $averages = $this->calculateAverages($data);

            // Kembalikan response dalam format JSON
            return response()->json(['data' => $averages]); // Ubah struktur respons di sini jika perlu
        } catch (\Exception $e) {
            // Tangani kesalahan
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function calculateAverages($data)
    {
        // Menghitung jumlah data yang valid
        $total = count($data);
        // dd($data);
        // Inisialisasi variabel total dan rata-rata
        $total_tunggu_daftar = 0;
        $total_tunggu_lab = 0;
        $total_tunggu_poli = 0;
        $total_durasi_poli = 0;
        $total_tunggu_ro = 0;
        $total_tunggu_tensi = 0;
        $total_tunggu_igd = 0;
        $total_tunggu_farmasi = 0;
        $total_tunggu_kasir = 0;

        // Iterasi data dan mengakumulasikan total waktu tunggu
        foreach ($data as $message) {
            $total_tunggu_daftar += $message['tunggu_daftar'];
            $total_tunggu_lab += $message['tunggu_lab'];
            $total_tunggu_poli += $message['tunggu_poli'];
            $total_durasi_poli += $message['durasi_poli'];
            $total_tunggu_ro += $message['tunggu_ro'];
            $total_tunggu_tensi += $message['tunggu_tensi'];
            $total_tunggu_igd += $message['tunggu_igd'];
            $total_tunggu_farmasi += $message['tunggu_farmasi'];
            $total_tunggu_kasir += $message['tunggu_kasir'];
        }

        // Menghitung rata-rata
        $avg_tunggu_daftar = round($total_tunggu_daftar / $total, 2);
        $avg_tunggu_lab = round($total_tunggu_lab / $total, 2);
        $avg_tunggu_poli = round($total_tunggu_poli / $total, 2);
        $avg_durasi_poli = round($total_durasi_poli / $total, 2);
        $avg_tunggu_ro = round($total_tunggu_ro / $total, 2);
        $avg_tunggu_tensi = round($total_tunggu_tensi / $total, 2);
        $avg_tunggu_igd = round($total_tunggu_igd / $total, 2);
        $avg_tunggu_farmasi = round($total_tunggu_farmasi / $total, 2);
        $avg_tunggu_kasir = round($total_tunggu_kasir / $total, 2);

        // Menyimpan hasil dalam array untuk pengembalian
        $averages = [
            'avg_tunggu_daftar' => $avg_tunggu_daftar,
            'avg_tunggu_lab' => $avg_tunggu_lab,
            'avg_tunggu_poli' => $avg_tunggu_poli,
            'avg_durasi_poli' => $avg_durasi_poli,
            'avg_tunggu_ro' => $avg_tunggu_ro,
            'avg_tunggu_tensi' => $avg_tunggu_tensi,
            'avg_tunggu_igd' => $avg_tunggu_igd,
            'avg_tunggu_farmasi' => $avg_tunggu_farmasi,
            'avg_tunggu_kasir' => $avg_tunggu_kasir,
        ];

        return $averages;
    }

}
