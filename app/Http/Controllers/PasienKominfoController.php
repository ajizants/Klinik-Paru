<?php

namespace App\Http\Controllers;

use App\Models\DotsTransModel;
use App\Models\FarmasiModel;
use App\Models\IGDTransModel;
use App\Models\KominfoModel;
use App\Models\LaboratoriumKunjunganModel;
use App\Models\ROTransaksiHasilModel;
use App\Models\ROTransaksiModel;
use Carbon\Carbon;
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
    public function reportPendaftaran1(Request $request)
    {
        // $limit = 10; // Set the limit to 5
        $params = $request->all();
        $tglAwal = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');

        $kominfo = new KominfoModel();
        $dataPendaftaranResponse = $kominfo->pendaftaranRequest($params);

        // Build response
        $res = $dataPendaftaranResponse;

        $res = [
            "status_pulang" => "Belum Pulang",
            "no_reg" => "2024072000001",
            "id" => "105052",
            "no_trans" => 0,
            "antrean_nomor" => "001",
            "tanggal" => "2024-07-20",
            "penjamin_nama" => "BPJS",
            "penjamin_nomor" => "0002056884254",
            "jenis_kunjungan_nama" => "Kontrol",
            "nomor_referensi" => "1111R0020624K000343",
            "pasien_nik" => "3302155506160001",
            "pasien_nama" => "ALMIRA KHALIQA RAMADHANI",
            "pasien_no_rm" => "024797",
            "pasien_tgl_lahir" => "2016-06-15",
            "jenis_kelamin_nama" => "P",
            "pasien_lama_baru" => "LAMA",
            "rs_paru_pasien_lama_baru" => "L",
            "poli_nama" => "PARU",
            "poli_sub_nama" => "PARU",
            "dokter_nama" => "dr. AGIL DANANJAYA, Sp.P",
            "daftar_by" => "JKN",
            "waktu_daftar" => "2024-06-23 14=>12=>23",
            "waktu_verifikasi" => "2024-07-20 07=>44=>24",
            "admin_pendaftaran" => "MUTMAINAH,A.Md.Kes",
            "log_id" => "204706",
            "keterangan" => "SKIP LOKET PENDAFTARAN",
            "keterangan_urutan" => "3",
            "pasien_umur" => "8 Tahun 1 Bulan ",
            "pasien_umur_tahun" => "8",
            "pasien_umur_bulan" => "1",
            "pasien_umur_hari" => "5",
        ];
        return response()->json($res);

    }
    public function reportPendaftaran(Request $request)
    {
        // ini_set('max_execution_time', 300); // 300 seconds = 5 minutes
        // ini_set('memory_limit', '512M');
        $params = $request->all();
        // dd($params);
        $tglAwal = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');

        $kominfo = new KominfoModel();
        $dataPendaftaranResponse = $kominfo->pendaftaranRequest($params);

        // Debugging: print the data received
        // dd($dataPendaftaranResponse);

        // Filter data dengan keterangan "SELESAI DOPANGGIL PENDAFTARAN"
        // $filteredData = $dataPendaftaranResponse;
        $filteredData = array_filter($dataPendaftaranResponse, function ($item) {
            return isset($item['keterangan']) && $item['keterangan'] === 'SELESAI DIPANGGIL LOKET PENDAFTARAN';
        });

        // Debugging: print the filtered data
        // dd($filteredData);

        // Hitung jumlah berdasarkan penjamin_nama
        $jumlahBPJS = count(array_filter($filteredData, function ($item) {
            return isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS';
        }));
        $jumlahUMUM = count(array_filter($filteredData, function ($item) {
            return isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'UMUM';
        }));

        // Hitung jumlah berdasarkan pasien_lama_baru
        $jumlahLama = count(array_filter($filteredData, function ($item) {
            return isset($item['pasien_lama_baru']) && $item['pasien_lama_baru'] === 'LAMA';
        }));
        $jumlahBaru = count(array_filter($filteredData, function ($item) {
            return isset($item['pasien_lama_baru']) && $item['pasien_lama_baru'] === 'BARU';
        }));

        // Hitung jumlah berdasarkan daftar_by
        $jumlahOTS = count(array_filter($filteredData, function ($item) {
            return isset($item['daftar_by']) && $item['daftar_by'] === 'OTS';
        }));
        $jumlahJKN = count(array_filter($filteredData, function ($item) {
            return isset($item['daftar_by']) && $item['daftar_by'] === 'JKN';
        }));
        $jumlahBatal = count(array_filter($dataPendaftaranResponse, function ($item) {
            return isset($item['keterangan']) && strpos($item['keterangan'], 'DIBATALKAN PADA') !== false;
        }));
        $jumlahSkip = count(array_filter($dataPendaftaranResponse, function ($item) {
            return isset($item['keterangan']) && strpos($item['keterangan'], 'SKIP LOKET PENDAFTARAN') !== false;
        }));
        $jumlahTunggu = count(array_filter($dataPendaftaranResponse, function ($item) {
            return isset($item['keterangan']) && strpos($item['keterangan'], 'MENUNGGU DIPANGGIL LOKET PENDAFTARAN') !== false;
        }));

        // Build response
        $jumlah = [
            'jumlah_no_antrian' => (int) count($dataPendaftaranResponse),
            'jumlah_no_menunggu' => (int) $jumlahTunggu,
            'jumlah_pasien' => (int) count($filteredData),
            'jumlah_pasien_batal' => (int) $jumlahBatal,
            'jumlah_nomor_skip' => (int) $jumlahSkip,
            'jumlah_BPJS' => (int) $jumlahBPJS,
            'jumlah_UMUM' => (int) $jumlahUMUM,
            'jumlah_pasien_LAMA' => (int) $jumlahLama,
            'jumlah_pasien_BARU' => (int) $jumlahBaru,
            'jumlah_daftar_OTS' => (int) $jumlahOTS,
            'jumlah_daftar_JKN' => (int) $jumlahJKN,
        ];

        $data = array_values($filteredData);

        $res = [
            "total" => $jumlah,
            "data" => $data,
        ];

        return response()->json($res);
    }

    public function pendaftaran2(Request $request)
    {
        $limit = 10; // Set the limit to 5
        $params = $request->all();

        $kominfo = new KominfoModel();
        $dataPendaftaranResponse = $kominfo->pendaftaranRequest($params);

        // Process data pendaftaran
        $antrian = [];
        $counter = 0;
        foreach ($dataPendaftaranResponse as $data) {
            // Skip if pasien_no_rm is not set or is empty
            if (empty($data['pasien_no_rm'])) {
                continue;
            }
            // Break the loop if limit is reached
            if ($counter >= $limit) {
                break;
            }
            $norm = $data['pasien_no_rm'];

            $dataPasienResponse = $kominfo->pasienRequest($norm);

            // Check if response is successful and contains the necessary data
            $pasienData = $dataPasienResponse;

            // Combine pendaftaran data and pasien data
            $antrian[] = [
                'id' => $data['id'],
                'no_reg' => $data['no_reg'],
                'no_trans' => $data['no_trans'],
                'antrean_nomor' => $data['antrean_nomor'],
                'tanggal' => $data['tanggal'],
                'penjamin_nama' => $data['penjamin_nama'],
                'jenis_kunjungan_nama' => $data['jenis_kunjungan_nama'],
                "penjamin_nomor" => $data['penjamin_nomor'],
                "jenis_kunjungan_nama" => $data['jenis_kunjungan_nama'],
                "nomor_referensi" => $data['nomor_referensi'],
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
                'pasien_umur' => $data['pasien_umur_tahun'] . ' Thn ' . $data['pasien_umur_bulan'] . ' Bln ',

                // Data pasien tambahan dari API kedua
                'pasien_nik' => $pasienData['pasien_nik'] ?? null,
                'pasien_nama' => $pasienData['pasien_nama'] ?? null,
                'jenis_kelamin_nama' => $pasienData['jenis_kelamin_nama'] ?? null,
                'pasien_tempat_lahir' => $pasienData['pasien_tempat_lahir'] ?? null,
                'pasien_tgl_lahir' => $pasienData['pasien_tgl_lahir'] ?? null,
                'pasien_no_hp' => $pasienData['pasien_no_hp'] ?? null,
                'pasien_alamat' => $pasienData['pasien_alamat'] ?? null,
                'provinsi_id' => $pasienData['provinsi_id'] ?? null,
                'kabupaten_id' => $pasienData['kabupaten_id'] ?? null,
                'kecamatan_id' => $pasienData['kecamatan_id'] ?? null,
                'kelurahan_id' => $pasienData['kelurahan_id'] ?? null,
                'pasien_rt' => $pasienData['pasien_rt'] ?? null,
                'pasien_rw' => $pasienData['pasien_rw'] ?? null,
            ];

            $counter++;
            // }
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
        $res = [
            'metadata' => [
                'message' => 'Data Pendaftaran Ditemukan',
                'code' => 200,
            ],
            'response' => [
                'data' => $antrian,
            ],
        ];

        return response()->json($res);

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

    public function antrianAll1(Request $request)
    {
        if ($request->has('tanggal')) {
            $tanggal = $request->input('tanggal', date('Y-m-d'));
            $ruang = $request->input('ruang');
            $model = new KominfoModel();
            $params = $request->only(['tanggal']);
            // Panggil metode untuk melakukan request
            $data = $model->pendaftaranRequest($tanggal);
            // $data = $model->waktuLayananRequest($params);
            // return response()->json($data);
            // if (isset($data['response']['data']) && is_array($data['response']['data'])) {
            if (isset($data) && is_array($data)) {
                // dd("oke");
                // $filteredData = array_filter($data['response']['data'], function ($d) {
                $filteredData = array_filter($data, function ($d) {
                    return $d['keterangan'] === 'SELESAI DIPANGGIL LOKET PENDAFTARAN';
                    // return true;
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
                    // $notrans = $item['no_trans'];
                    $norm = $item['pasien_no_rm'];
                    $dokter_nama = $item['dokter_nama'];

                    // Check if ROTransaksiModel exists for $notrans
                    if ($ruang == "ro") {
                        try {
                            $tsRo = ROTransaksiModel::where('norm', $norm)
                                ->whereDate('tgltrans', $tanggal)->first();
                            // Attempt to retrieve data from the 'rontgen' connection
                            $foto = ROTransaksiHasilModel::where('norm', $norm)
                                ->whereDate('tanggal', $tanggal)
                                ->first();
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

                    } elseif ($ruang == "lab") {
                        try {
                            $ts = LaboratoriumKunjunganModel::where('norm', $norm)
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

    public function antrianAll(Request $request)
    {
        if (!$request->has('tanggal')) {
            return response()->json(['error' => 'Tanggal Belum Di Isi'], 400);
        }

        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $ruang = $request->input('ruang');
        $params = [
            'tanggal_awal' => $tanggal,
            'tanggal_akhir' => $tanggal,
            'no_rm' => '',
        ];
        $model = new KominfoModel();
        $data = $model->pendaftaranRequest($params);
        // dd($data);

        if (!isset($data) || !is_array($data)) {
            return response()->json(['error' => 'Invalid data format'], 500);
        }

        $filteredData = array_values(array_filter($data, function ($d) {
            return $d['keterangan'] === 'SELESAI DIPANGGIL LOKET PENDAFTARAN';
        }));

        $doctorNipMap = [
            'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
            'dr. AGIL DANANJAYA, Sp.P' => '9',
            'dr. FILLY ULFA KUSUMAWARDANI' => '198907252019022004',
            'dr. SIGIT DWIYANTO' => '198903142022031005',
        ];

        foreach ($filteredData as &$item) {
            $norm = $item['pasien_no_rm'];
            $dokter_nama = $item['dokter_nama'];

            try {
                switch ($ruang) {
                    case 'ro':
                        $tsRo = ROTransaksiModel::where('norm', $norm)
                            ->whereDate('tgltrans', $tanggal)->first();
                        $foto = ROTransaksiHasilModel::where('norm', $norm)
                            ->whereDate('tanggal', $tanggal)->first();
                        $item['status'] = !$tsRo && !$foto ? 'Belum Ada Ts RO' :
                        ($tsRo && !$foto ? 'Belum Upload Foto Thorax' : 'Sudah Selesai');
                        break;

                    case 'igd':
                        $ts = IGDTransModel::with('transbmhp')->where('norm', $norm)
                            ->whereDate('created_at', $tanggal)->first();
                        $item['status'] = !$ts ? 'Tidak Ada Permintaan' :
                        ($ts->transbmhp == null ? 'Belum Ada Transaksi BMHP' : 'Sudah Selesai');
                        break;

                    case 'farmasi':
                        $ts = FarmasiModel::where('norm', $norm)
                            ->whereDate('created_at', $tanggal)->first();
                        $item['status'] = !$ts ? 'Belum Ada Transaksi' : 'Sudah Selesai';
                        break;

                    case 'dots':
                        $ts = DotsTransModel::where('norm', $norm)
                            ->whereDate('created_at', $tanggal)->first();
                        $item['status'] = !$ts ? 'Tidak Ada Transaksi' : 'Sudah Selesai';
                        break;

                    case 'lab':
                        $ts = LaboratoriumKunjunganModel::where('norm', $norm)
                            ->whereDate('created_at', $tanggal)->first();
                        $item['status'] = !$ts ? 'Tidak Ada Transaksi' : 'Sudah Selesai';
                        break;

                    default:
                        $item['status'] = 'Unknown ruang';
                }
            } catch (\Exception $e) {
                Log::error('Database connection failed: ' . $e->getMessage());
                $item['status'] = 'Database connection error';
            }

            $item['nip_dokter'] = $doctorNipMap[$dokter_nama] ?? 'Unknown';
        }

        return response()->json([
            'metadata' => [
                'message' => 'Data Pasien Ditemukan',
                'code' => 200,
            ],
            'response' => [
                'data' => $filteredData,
            ],
        ]);
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
            $tanggal = $request->input('tanggal', Carbon::now()->format('Y-m-d'));
            $model = new KominfoModel();
            $params = [
                'tanggal_awal' => $tanggal,
                'tanggal_akhir' => $tanggal,
                'no_rm' => $no_rm,
            ];

            // Panggil metode untuk melakukan request pasien
            $res_pasien = $model->pasienRequest($no_rm);
            // dd($res_pasien);
            if ($res_pasien == "Data tidak ditemukan!") {
                $response = [
                    'metadata' => [
                        'message' => 'Pasien dengan No. RM ' . $no_rm . ' tidak ditemukan',
                        'code' => 204,
                    ],
                ];
                return response()->json($response);
            } else {
                // $pasienData = $res_pasien;
                $pasien = [
                    "pasien_nik" => $res_pasien['pasien_nik'] ?? null,
                    "pasien_no_kk" => $res_pasien['pasien_no_kk'] ?? null,
                    "pasien_nama" => $res_pasien['pasien_nama'] ?? null,
                    "pasien_no_rm" => $res_pasien['pasien_no_rm'] ?? null,
                    "jenis_kelamin_id" => $res_pasien['jenis_kelamin_id'] ?? null,
                    "jenis_kelamin_nama" => $res_pasien['jenis_kelamin_nama'] ?? null,
                    "pasien_tempat_lahir" => $res_pasien['pasien_tempat_lahir'] ?? null,
                    "pasien_tgl_lahir" => $res_pasien['pasien_tgl_lahir'] ?? null,
                    "pasien_no_hp" => $res_pasien['pasien_no_hp'] ?? null,
                    "pasien_domisili" => $res_pasien['pasien_alamat'] ?? null,
                    "pasien_alamat" => $res_pasien['pasien_alamat'] ?? null,
                    "provinsi_nama" => $res_pasien['provinsi_nama'] ?? null,
                    "kabupaten_nama" => $res_pasien['kabupaten_nama'] ?? null,
                    "kecamatan_nama" => $res_pasien['kecamatan_nama'] ?? null,
                    "kelurahan_nama" => $res_pasien['kelurahan_nama'] ?? null,
                    "pasien_rt" => $res_pasien['pasien_rt'] ?? null,
                    "pasien_rw" => $res_pasien['pasien_rw'] ?? null,
                    "penjamin_nama" => $res_pasien['penjamin_nama'] ?? null,
                ];

                // Panggil metode untuk melakukan request pendaftaran
                $cpptRes = $model->cpptRequest($params);
                if (!isset($cpptRes['response']['data'])) {
                    $cppt = null;
                } else {
                    $cppt = $cpptRes['response']['data'];
                }

                $pendaftaran = $model->pendaftaranRequest($params);
                // return response()->json($pendaftaran);

                if (isset($pendaftaran) && is_array($pendaftaran)) {
                    $filteredData = array_filter($pendaftaran, function ($d) use ($no_rm) {
                        return $d['pasien_no_rm'] === $no_rm;
                    });
                    $filteredData = array_values($filteredData);

                    $doctorNipMap = [
                        'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
                        'dr. AGIL DANANJAYA, Sp.P' => '9',
                        'dr. FILLY ULFA KUSUMAWARDANI' => '198907252019022004',
                        'dr. SIGIT DWIYANTO' => '198903142022031005',
                    ];

                    // Iterate over filtered data and add nip
                    foreach ($filteredData as &$item) {
                        $dokter_nama = $item['dokter_nama'];
                        $item['nip_dokter'] = $doctorNipMap[$dokter_nama] ?? 'Unknown';
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
                                'cppt' => $cppt,
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
                        // Mengembalikan respons dengan kode 200
                        return response()->json($response, 200);
                    }
                } else {
                    return response()->json(['message' => 'Data pendaftaran tidak ditemukan'], 404);
                }
            }
        } else {
            return response()->json(['message' => 'Parameter tidak valid'], 404);
        }
    }

    public function pendaftaranFilter(Request $request)
    {
        $norm = $request->input('norm');
        // Jika tgl tidak ada maka gunakan tgl saat ini
        $tanggal = $request->input('tanggal', Carbon::now()->format('Y-m-d'));
        $params = [
            'tanggal_awal' => $tanggal,
            'tanggal_akhir' => $tanggal,
            'no_rm' => $norm,
        ];
        $model = new KominfoModel();
        $data = $model->pendaftaranRequest($params);

        // // Filter hasil yang normnya sama dengan $norm
        // $filteredData = array_filter($data, function ($message) use ($norm) {
        //     return $message['pasien_no_rm'] === $norm;
        // });

        // // Ambil elemen pertama dari hasil yang difilter
        $result = reset($data);

        // Jika tidak ada hasil yang sesuai, berikan respons yang sesuai
        if ($result === false) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Kembalikan hasil sebagai JSON
        return response()->json($result);
    }

    public function newCpptRequest1(Request $request)
    {
        // Ambil parameter dari request
        $params = $request->only(['tanggal_awal', 'tanggal_akhir', 'no_rm', 'ruang']);
        $ruang = $params['ruang'] ?? '';

        $model = new KominfoModel();

        // Panggil metode untuk melakukan request
        $data = $model->cpptRequest($params);
        // dd($data);
        if (isset($data['response']['data']) && is_array($data['response']['data'])) {
            $filteredData = array_filter(array_map(function ($d) use ($ruang) {
                if ($ruang == "igd") {
                    // Filter data yang memiliki tindakan saja
                    $igd = IGDTransModel::whereDate('created_at', $d['tanggal'])
                        ->where('norm', $d['pasien_no_rm'])
                        ->first();

                    $d['status'] = $igd ? 'sudah' : 'belum';
                    if (empty($d['tindakan'])) {
                        return null;
                    }
                } elseif ($ruang == "dots") {
                    // Filter data yang memiliki dots saja
                    $dots = DotsTransModel::whereDate('created_at', $d['tanggal'])
                        ->where('norm', $d['pasien_no_rm'])
                        ->first();

                    $d['status'] = $dots ? 'sudah' : 'belum';
                    $hasTuberculosis = false;
                    // foreach ($d['diagnosa'] as $item) {
                    //     if (stripos($item['nama_diagnosa'], 'tuberculosis') !== false) {
                    //         $hasTuberculosis = true;
                    //         break;
                    //     }
                    // }
                    foreach ($d['diagnosa'] as $item) {
                        if (stripos($item['nama_diagnosa'], 'tuberculosis') !== false &&
                            stripos($item['nama_diagnosa'], 'Observation for suspected tuberculosis') === false) {
                            $hasTuberculosis = true;
                            break;
                        }
                    }

                    if (!$hasTuberculosis) {
                        return null;
                    }
                } elseif ($ruang == "ro") {
                    $lab = ROTransaksiModel::whereDate('created_at', $d['tanggal'])
                        ->where('norm', $d['pasien_no_rm'])
                        ->first();
                    $d['status'] = $lab ? 'sudah' : 'belum';
                    if (empty($d['radiologi'])) {
                        return null;
                    }
                } elseif ($ruang == "lab") {
                    $lab = LaboratoriumKunjunganModel::whereDate('created_at', $d['tanggal'])
                        ->where('norm', $d['pasien_no_rm'])
                        ->first();
                    $d['status'] = $lab ? 'sudah' : 'belum';
                    if (empty($d['lab'])) {
                        return null;
                    }
                }

                $doctorNipMap = [
                    'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
                    'dr. AGIL DANANJAYA, Sp.P' => '9',
                    'dr. FILLY ULFA KUSUMAWARDANI' => '198907252019022004',
                    'dr. SIGIT DWIYANTO' => '198903142022031005',
                ];

                $dokter_nama = $d['dokter_nama'] ?? '';
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
            } else {
                if ($ruang == "IGD") {
                    return response()->json(['error' => 'Tidak ada data permintaan Tindakan'], 404);
                } elseif ($ruang == "lab") {
                    return response()->json(['error' => 'Tidak ada data permintaan Laboratorium'], 404);
                } elseif ($ruang == "ro") {
                    return response()->json(['error' => 'Tidak ada data permintaan Radiologi'], 404);
                }
            }
        } else {
            return response()->json(['error' => 'Invalid data format'], 500);
        }
    }

    public function newCpptRequest(Request $request)
    {
        // Ambil parameter dari req
        $params = $request->only(['tanggal_awal', 'tanggal_akhir', 'no_rm', 'ruang']);
        $ruang = $params['ruang'] ?? '';

        $model = new KominfoModel();
        $data = $model->cpptRequest($params);

        if (isset($data['response']['data']) && is_array($data['response']['data'])) {
            $filteredData = array_filter(array_map(function ($d) use ($ruang) {
                $d['status'] = 'belum';

                if ($ruang === 'igd') {
                    $igd = IGDTransModel::whereDate('created_at', $d['tanggal'])->where('norm', $d['pasien_no_rm'])->first();
                    $d['status'] = $igd ? 'sudah' : 'belum';
                    if (empty($d['tindakan'])) {
                        return null;
                    }

                } elseif ($ruang === 'dots') {
                    $dots = DotsTransModel::whereDate('created_at', $d['tanggal'])->where('norm', $d['pasien_no_rm'])->first();
                    $d['status'] = $dots ? 'sudah' : 'belum';
                    $hasTuberculosis = false;

                    foreach ($d['diagnosa'] as $item) {
                        if (stripos($item['nama_diagnosa'], 'tuberculosis') !== false ||
                            stripos($item['nama_diagnosa'], 'tb lung') !== false &&
                            stripos($item['nama_diagnosa'], 'Observation for suspected tuberculosis') === false) {
                            $hasTuberculosis = true;
                            break;
                        }
                    }

                    if (!$hasTuberculosis) {
                        return null;
                    }
                    $tb = DotsTransModel::whereDate('created_at', $d['tanggal'])->where('norm', $d['pasien_no_rm'])->first();
                    $d['status'] = $tb ? 'sudah' : 'belum';

                } elseif ($ruang === 'ro') {
                    $ro = ROTransaksiModel::whereDate('created_at', $d['tanggal'])->where('norm', $d['pasien_no_rm'])->first();
                    $d['status'] = $ro ? 'sudah' : 'belum';
                    if (empty($d['radiologi'])) {
                        return null;
                    }

                } elseif ($ruang === 'lab') {
                    $lab = LaboratoriumKunjunganModel::whereDate('created_at', $d['tanggal'])->where('norm', $d['pasien_no_rm'])->first();
                    $d['status'] = $lab ? 'sudah' : 'belum';
                    if (empty($d['laboratorium'])) {
                        return null;
                    }

                }

                $doctorNipMap = [
                    'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
                    'dr. AGIL DANANJAYA, Sp.P' => '9',
                    'dr. FILLY ULFA KUSUMAWARDANI' => '198907252019022004',
                    'dr. SIGIT DWIYANTO' => '198903142022031005',
                ];

                $d['nip_dokter'] = $doctorNipMap[$d['dokter_nama']] ?? 'Unknown';

                return $d;
            }, $data['response']['data']));

            if (!empty($filteredData)) {
                return response()->json([
                    'metadata' => [
                        'message' => 'Data Pasien Ditemukan',
                        'code' => 200,
                    ],
                    'response' => [
                        'data' => array_values($filteredData),
                    ],
                ]);
            } else {
                $errorMessages = [
                    'IGD' => 'Tidak ada data permintaan Tindakan',
                    'lab' => 'Tidak ada data permintaan Laboratorium',
                    'ro' => 'Tidak ada data permintaan Radiologi',
                ];

                return response()->json(['error' => $errorMessages[$ruang] ?? 'Tidak ada data ditemukan'], 404);
            }
        } else {
            return response()->json([
                'metadata' => [
                    'message' => 'Data Tidak Ditemukan',
                    'code' => 404,
                ],
            ], 200);
        }

        return response()->json(['error' => 'Internal Server Error'], 500);
    }

    public function rekapPoin(Request $request)
    {

        $params = $request->only(['tanggal_awal', 'tanggal_akhir']);

        $model = new KominfoModel();

        $data = $model->poinRequest($params);

        return response()->json($data);

    }
    public function rekapPoinPecah(Request $request)
    {

        $params = $request->only(['tanggal_awal', 'tanggal_akhir']);

        $model = new KominfoModel();

        $data = $model->cpptRequest($params);
        $res = $data['response']['data'];

        return response()->json($res);

    }

    public function waktuLayanan(Request $request)
    {

        $params = $request->all();

        $model = new KominfoModel();

        $data = $model->waktuLayananRequest($params);

        return response()->json($data);
    }

    // public function avgWaktuTunggu(Request $request)
    // {
    //     try {
    //         $params = $request->all();
    //         $model = new KominfoModel();

    //         // Ambil data dari model menggunakan metode waktuLayananRequest
    //         $data = $model->waktuLayananRequest($params);

    //         // Hitung rata-rata dan waktu terlama
    //         $results = $this->calculateAverages($data);

    //         // Kembalikan response dalam format JSON
    //         return response()->json(['data' => $results]);
    //     } catch (\Exception $e) {
    //         // Tangani kesalahan
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    // private function calculateAverages($data)
    // {
    //     // dd($data);
    //     $total = count($data);
    //     $total_rm = count(array_filter($data, function ($item) {
    //         return isset($item['rm']) && $item['rm'] === true;
    //     }));
    //     $total_ro = count(array_filter($data, function ($item) {
    //         return isset($item['rodata']) && $item['rodata'] === true;
    //     }));
    //     $total_lab = count(array_filter($data, function ($item) {
    //         return isset($item['labdata']) && $item['labdata'] === true;
    //     }));
    //     $total_igd = count(array_filter($data, function ($item) {
    //         return isset($item['igddata']) && $item['igddata'] === true;
    //     }));
    //     $total_tanpa_tambahan = count(array_filter($data, function ($item) {
    //         return isset($item['oke']) && $item['oke'] === false;
    //     }));

    //     $total_tunggu_daftar = 0;
    //     $total_tunggu_rm = 0;
    //     $total_tunggu_lab = 0;
    //     $total_tunggu_hasil_lab = 0;
    //     $total_tunggu_ro = 0;
    //     $total_tunggu_hasil_ro = 0;
    //     $total_tunggu_poli = 0;
    //     $total_durasi_poli = 0;
    //     $total_tunggu_tensi = 0;
    //     $total_tunggu_igd = 0;
    //     $total_tunggu_farmasi = 0;
    //     $total_tunggu_kasir = 0;

    //     $max_tunggu_daftar = 0;
    //     $max_tunggu_rm = 0;
    //     $max_tunggu_lab = 0;
    //     $max_tunggu_hasil_lab = 0;
    //     $max_tunggu_hasil_ro = 0;
    //     $max_tunggu_ro = 0;
    //     $max_tunggu_poli = 0;
    //     $max_durasi_poli = 0;
    //     $max_tunggu_tensi = 0;
    //     $max_tunggu_igd = 0;
    //     $max_tunggu_farmasi = 0;
    //     $max_tunggu_kasir = 0;

    //     foreach ($data as $message) {
    //         $total_tunggu_daftar += $message['tunggu_daftar'];
    //         $total_tunggu_rm += $message['lama_selesai_RM'];
    //         $total_tunggu_lab += $message['tunggu_lab'];
    //         $total_tunggu_hasil_lab += $message['tunggu_hasil_lab'];
    //         $total_tunggu_hasil_ro += $message['tunggu_hasil_ro'];
    //         $total_tunggu_ro += $message['tunggu_ro'];
    //         $total_tunggu_poli += $message['tunggu_poli'];
    //         $total_durasi_poli += $message['durasi_poli'];
    //         $total_tunggu_tensi += $message['tunggu_tensi'];
    //         $total_tunggu_igd += $message['tunggu_igd'];
    //         $total_tunggu_farmasi += $message['tunggu_farmasi'];
    //         $total_tunggu_kasir += $message['tunggu_kasir'];

    //         // Update max values
    //         $max_tunggu_daftar = max($max_tunggu_daftar, $message['tunggu_daftar']);
    //         $max_tunggu_rm = max($max_tunggu_rm, $message['lama_selesai_RM']);
    //         $max_tunggu_lab = max($max_tunggu_lab, $message['tunggu_lab']);
    //         $max_tunggu_hasil_lab = max($max_tunggu_hasil_lab, $message['tunggu_hasil_lab']);
    //         $max_tunggu_hasil_ro = max($max_tunggu_hasil_ro, $message['tunggu_hasil_ro']);
    //         $max_tunggu_ro = max($max_tunggu_ro, $message['tunggu_ro']);
    //         $max_tunggu_poli = max($max_tunggu_poli, $message['tunggu_poli']);
    //         $max_durasi_poli = max($max_durasi_poli, $message['durasi_poli']);
    //         $max_tunggu_tensi = max($max_tunggu_tensi, $message['tunggu_tensi']);
    //         $max_tunggu_igd = max($max_tunggu_igd, $message['tunggu_igd']);
    //         $max_tunggu_farmasi = max($max_tunggu_farmasi, $message['tunggu_farmasi']);
    //         $max_tunggu_kasir = max($max_tunggu_kasir, $message['tunggu_kasir']);
    //     }

    //     $avg_tunggu_daftar = round($total_tunggu_daftar / $total, 2);
    //     $avg_tunggu_poli = round($total_tunggu_poli / $total, 2);
    //     $avg_durasi_poli = round($total_durasi_poli / $total, 2);
    //     $avg_tunggu_tensi = round($total_tunggu_tensi / $total, 2);
    //     $avg_tunggu_farmasi = round($total_tunggu_farmasi / $total, 2);
    //     $avg_tunggu_kasir = round($total_tunggu_kasir / $total, 2);

    //     if ($message['oke'] === true) {
    //         $avg_tunggu_rm = round($total_tunggu_rm / $total_rm ?: $total, 2);
    //         $avg_tunggu_igd = round($total_tunggu_igd / $total_igd ?: $total, 2);
    //         $avg_tunggu_lab = round($total_tunggu_lab / $total_lab, 2);
    //         $avg_tunggu_hasil_lab = round($total_tunggu_hasil_lab / $total_lab, 2);
    //         $avg_tunggu_hasil_ro = round($total_tunggu_hasil_ro / $total_ro, 2);
    //         $avg_tunggu_ro = round($total_tunggu_ro / $total_ro, 2);
    //     } else {
    //         $avg_tunggu_rm = 0;
    //         $avg_tunggu_igd = 0;
    //         $avg_tunggu_lab = 0;
    //         $avg_tunggu_hasil_lab = 0;
    //         $avg_tunggu_hasil_ro = 0;
    //         $avg_tunggu_ro = 0;
    //     }
    //     $results = [
    //         'total_pasien' => $total,
    //         'total_ro' => $total_ro,
    //         'total_lab' => $total_lab,
    //         'total_igd' => $total_igd,
    //         'total_tanpa_tambahan' => $total_tanpa_tambahan,
    //         'total_rm' => $total_rm,
    //         'avg_tunggu_daftar' => $avg_tunggu_daftar,
    //         'avg_tunggu_rm' => $avg_tunggu_rm,
    //         'avg_tunggu_lab' => $avg_tunggu_lab,
    //         'avg_tunggu_hasil_lab' => $avg_tunggu_hasil_lab,
    //         'avg_tunggu_hasil_ro' => $avg_tunggu_hasil_ro,
    //         'avg_tunggu_ro' => $avg_tunggu_ro,
    //         'avg_tunggu_poli' => $avg_tunggu_poli,
    //         'avg_durasi_poli' => $avg_durasi_poli,
    //         'avg_tunggu_tensi' => $avg_tunggu_tensi,
    //         'avg_tunggu_igd' => $avg_tunggu_igd,
    //         'avg_tunggu_farmasi' => $avg_tunggu_farmasi,
    //         'avg_tunggu_kasir' => $avg_tunggu_kasir,

    //         'max_tunggu_daftar' => $max_tunggu_daftar,
    //         'max_tunggu_rm' => $max_tunggu_rm,
    //         'max_tunggu_lab' => $max_tunggu_lab,
    //         'max_tunggu_hasil_lab' => $max_tunggu_hasil_lab,
    //         'max_tunggu_hasil_ro' => $max_tunggu_hasil_ro,
    //         'max_tunggu_ro' => $max_tunggu_ro,
    //         'max_tunggu_poli' => $max_tunggu_poli,
    //         'max_durasi_poli' => $max_durasi_poli,
    //         'max_tunggu_tensi' => $max_tunggu_tensi,
    //         'max_tunggu_igd' => $max_tunggu_igd,
    //         'max_tunggu_farmasi' => $max_tunggu_farmasi,
    //         'max_tunggu_kasir' => $max_tunggu_kasir,

    //         'total_tunggu_daftar' => round($total_tunggu_daftar, 2),
    //         'total_tunggu_rm' => round($total_tunggu_rm, 2),
    //         'total_tunggu_lab' => round($total_tunggu_lab, 2),
    //         'total_tunggu_hasil_lab' => round($total_tunggu_hasil_lab, 2),
    //         'total_tunggu_hasil_ro' => round($total_tunggu_hasil_ro, 2),
    //         'total_tunggu_ro' => round($total_tunggu_ro, 2),
    //         'total_tunggu_poli' => round($total_tunggu_poli, 2),
    //         'total_durasi_poli' => round($total_durasi_poli, 2),
    //         'total_tunggu_tensi' => round($total_tunggu_tensi, 2),
    //         'total_tunggu_igd' => round($total_tunggu_igd, 2),
    //         'total_tunggu_farmasi' => round($total_tunggu_farmasi, 2),
    //         'total_tunggu_kasir' => round($total_tunggu_kasir, 2),
    //     ];

    //     return $results;
    // }

    public function avgWaktuTunggu(Request $request)
    {
        try {
            $params = $request->all();
            $model = new KominfoModel();

            // Ambil data dari model menggunakan metode waktuLayananRequest
            $data = $model->waktuLayananRequest($params);

            // Hitung rata-rata dan waktu terlama
            $results = $this->calculateAverages($data);

            // Kembalikan response dalam format JSON
            return response()->json(['data' => $results]);
        } catch (\Exception $e) {
            // Tangani kesalahan
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function calculateAverages($data)
    {
        // Initialize totals and max values
        // return $data;
        $totals = [
            'tunggu_daftar' => 0,
            'tunggu_rm' => 0,
            'tunggu_lab' => 0,
            'tunggu_hasil_lab' => 0,
            'tunggu_hasil_ro' => 0,
            'tunggu_ro' => 0,
            'tunggu_poli' => 0,
            'durasi_poli' => 0,
            'tunggu_tensi' => 0,
            'tunggu_igd' => 0,
            'tunggu_farmasi' => 0,
            'tunggu_kasir' => 0,
        ];

        $maxValues = $totals;

        $counts = [
            'rm' => 0,
            'ro' => 0,
            'lab' => 0,
            'igd' => 0,
        ];

        foreach ($data as $message) {
            // Check for valid values and handle them properly
            foreach ($totals as $key => &$total) {
                $value = $message[$key] ?? 0;
                if (is_array($value)) {
                    $value = 0; // Or handle the array case as needed
                }
                $total += $value;
                $maxValues[$key] = max($maxValues[$key], $value);
            }

            // Update counts for specific categories
            $counts['rm'] += isset($message['rm']) && $message['rm'] === true ? 1 : 0;
            $counts['ro'] += isset($message['rodata']) && $message['rodata'] === true ? 1 : 0;
            $counts['lab'] += isset($message['labdata']) && $message['labdata'] === true ? 1 : 0;
            $counts['igd'] += isset($message['igddata']) && $message['igddata'] === true ? 1 : 0;
        }

        // Calculate averages
        $results = [];
        foreach ($totals as $key => $total) {
            $results["avg_$key"] = round($total / count($data), 2);
            $results["max_$key"] = $maxValues[$key];
            $results["total_$key"] = round($total, 2);
        }

        // Special handling for cases where counts are zero
        foreach ($counts as $key => $count) {
            $results["avg_tunggu_{$key}"] = $count > 0 ? round($totals["tunggu_{$key}"] / $count, 2) : 0;
        }

        // Include total counts
        $results = array_merge($results, [
            'total_pasien' => count($data),
            'total_ro' => $counts['ro'],
            'total_lab' => $counts['lab'],
            'total_igd' => $counts['igd'],
            'total_tanpa_tambahan' => count(array_filter($data, fn($item) => isset($item['oke']) && $item['oke'] === false)),
            'total_rm' => $counts['rm'],
        ]);

        return $results;
    }

}
