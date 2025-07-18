<?php
namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KominfoModel extends Model
{
    protected $table = 'm_pasien_kominfo';

    public function antrianAll(array $params)
    {
        // return ($params);
        $tanggal = $params['tanggal'];
        $ruang   = $params['ruang'];
        $params  = [
            'tanggal_awal'  => $tanggal,
            'tanggal_akhir' => $tanggal,
            'no_rm'         => '',
        ];
        $data = $this->pendaftaranRequest($params);
        // dd($data);

        if (! isset($data) || ! is_array($data)) {
            return response()->json(['error' => 'Invalid data format'], 500);
        }

        $filteredData = array_values(array_filter($data, function ($d) {
            return $d['keterangan'] === 'SELESAI DIPANGGIL LOKET PENDAFTARAN';
        }));

        $doctorNipMap = [
            'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
            'dr. Agil Dananjaya, Sp.P'                  => '9',
            'dr. Filly Ulfa Kusumawardani'              => '198907252019022004',
            'dr. Sigit Dwiyanto'                        => '198903142022031005',
        ];
        $tes = $filteredData;

        foreach ($filteredData as &$item) {
            $norm        = $item['pasien_no_rm'];
            $dokter_nama = $item['dokter_nama'];

            try {
                switch ($ruang) {
                    case 'ro':
                        $tsRo = ROTransaksiModel::where('norm', $norm)
                            ->whereDate('tgltrans', $tanggal)->first();
                        // $foto = ROTransaksiHasilModel::where('norm', $norm)
                        $foto = RoHasilModel::where('norm', $norm)
                            ->whereDate('tanggal', $tanggal)->first();
                        $item['status'] = ! $tsRo && ! $foto ? 'Tidak Ada Transaksi' :
                        ($tsRo && ! $foto ? 'Belum Upload Foto Thorax' : 'Sudah Selesai');
                        break;

                    case 'igd':
                        $ts = IGDTransModel::with('transbmhp')->where('norm', $norm)
                            ->whereDate('created_at', $tanggal)->first();
                        $item['status'] = ! $ts ? 'Tidak Ada Transaksi' :
                        ($ts->transbmhp == null ? 'Belum Ada Transaksi BMHP' : 'Sudah Selesai');
                        break;

                    case 'farmasi':
                        $ts = FarmasiModel::where('norm', $norm)
                            ->whereDate('created_at', $tanggal)->first();
                        $item['status'] = ! $ts ? 'Tidak Ada Transaksi' : 'Sudah Selesai';
                        break;

                    case 'dots':
                        $ts = DotsTransModel::where('norm', $norm)
                            ->whereDate('created_at', $tanggal)->first();
                        $item['status'] = ! $ts ? 'Tidak Ada Transaksi' : 'Sudah Selesai';
                        break;

                    case 'lab':
                        $ts = LaboratoriumKunjunganModel::where('norm', $norm)
                            ->whereDate('created_at', $tanggal)->first();
                        $item['status'] = ! $ts ? 'Tidak Ada Transaksi' : 'Sudah Selesai';
                        break;
                    case 'kasir':
                        $ts = KasirTransModel::where('norm', $norm)
                            ->whereDate('created_at', $tanggal)->first();
                        $item['status'] = ! $ts ? 'Tidak Ada Transaksi' : 'Sudah Selesai';
                        break;
                    case 'surat':
                        $ts = KasirTransModel::where('norm', $norm)
                            ->whereDate('created_at', $tanggal)->first();
                        $item['status'] = ! $ts ? 'Tidak Ada Transaksi' : 'Sudah Selesai';
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
                'code'    => 200,
            ],
            'response' => [
                'data' => $filteredData,
                // 'data' => $tes,
            ],
        ]);
    }

    public function pendaftaran(array $params)
    {
        $client = new Client();

        // URL endpoint API yang ingin diakses
        $url = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/pendaftaran/data_pendaftaran';

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

            $res = array_map(function ($d) {
                $statusPulang = ! is_null($d["loket_farmasi_menunggu_waktu"]) ? "Sudah Pulang" : "Belum Pulang";
                // $statusPulang = !is_null($d["ruang_poli_selesai_waktu"]) ? "Sudah Pulang" : "Belum Pulang";
                $alamat = $d['kelurahan_nama'] . ', ' .
                    $d['pasien_rt'] . '/' .
                    $d['pasien_rw'] . ', ' .
                    $d['kecamatan_nama'] . ', ' .
                    $d['kabupaten_nama'];
                $alamatMin = $d['kelurahan_nama'] . ', ' .
                    $d['kecamatan_nama'];
                $alamatPang = 'Desa ' . $d['kelurahan_nama'] . ', Kecamatan ' .
                    $d['kecamatan_nama'];
                $notrans = $d['tanggal'] < '2024-12-19' ? $d['no_trans'] : $d['no_reg'];

                return [
                    "status_pulang"            => $statusPulang,
                    "no_reg"                   => $d["no_reg"] ?? 0,
                    "id"                       => $d["id"] ?? 0,
                    "no_trans"                 => $d["no_trans"] ?? 0,
                    "notrans"                  => $notrans,
                    "antrean_nomor"            => $d["antrean_nomor"] ?? 0,
                    "tanggal"                  => $d["tanggal"] ?? 0,
                    "penjamin_nama"            => $d["penjamin_nama"] ?? 0,
                    "penjamin_nomor"           => $d["penjamin_nomor"] ?? 0,
                    "jenis_kunjungan_nama"     => $d["jenis_kunjungan_nama"] ?? 0,
                    "nomor_referensi"          => $d["nomor_referensi"] ?? 0,
                    "pasien_nik"               => $d["pasien_nik"] ?? 0,
                    "pasien_nama"              => $d["pasien_nama"] ?? 0,
                    "pasien_no_rm"             => $d["pasien_no_rm"] ?? 0,
                    "pasien_tgl_lahir"         => $d["pasien_tgl_lahir"] ?? 0,
                    "jenis_kelamin_nama"       => $d["jenis_kelamin_nama"] ?? 0,
                    "pasien_lama_baru"         => $d["pasien_lama_baru"] ?? 0,
                    "rs_paru_pasien_lama_baru" => $d["rs_paru_pasien_lama_baru"] ?? 0,
                    "poli_nama"                => $d["poli_nama"] ?? 0,
                    "poli_sub_nama"            => $d["poli_sub_nama"] ?? 0,
                    "dokter_nama"              => $d["dokter_nama"] ?? 0,
                    "daftar_by"                => $d["daftar_by"] ?? 0,
                    "waktu_daftar"             => $d["waktu_daftar"] ?? 0,
                    "waktu_verifikasi"         => $d["waktu_verifikasi"] ?? 0,
                    "admin_pendaftaran"        => $d["admin_pendaftaran"] ?? 0,
                    "log_id"                   => $d["log_id"] ?? 0,
                    "keterangan"               => $d["keterangan"] ?? 0,
                    "keterangan_urutan"        => $d["keterangan_urutan"] ?? 0,
                    "pasien_umur"              => ($d["pasien_umur_tahun"] ?? 0) . " Thn " . ($d["pasien_umur_bulan"] ?? 0) . " Bln ",
                    "pasien_umur_tahun"        => $d["pasien_umur_tahun"] ?? 0,
                    "pasien_umur_bulan"        => $d["pasien_umur_bulan"] ?? 0,
                    "pasien_umur_hari"         => $d["pasien_umur_hari"] ?? 0,
                    "pasien_alamat"            => $alamat ?? 0,
                    "pasien_alamat_min"        => $alamatMin ?? 0,
                    "pasien_alamat_pang"       => $alamatPang ?? 0,
                    "kabupaten"                => $d["kabupaten_nama"] ?? 0,
                    "kecamatan"                => $d["kecamatan_nama"] ?? 0,
                    "kelurahan"                => $d["kelurahan_nama"] ?? 0,
                ];
            }, $data['response']['data']);

            return $res;
        } catch (\Exception $e) {
            // Tangani kesalahan
            return ['error' => $e->getMessage()];
        }

    }

    public function pendaftaranRequest(array $params)
    {
        $client   = new Client();
        $url      = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/pendaftaran/data_pendaftaran';
        $username = env('API_USERNAME', '');
        $password = env('API_PASSWORD', '');

        try {
            $response = $client->request('POST', $url, [
                'auth'        => [$username, $password],
                'form_params' => $params,
                'headers'     => ['Content-Type' => 'application/x-www-form-urlencoded'],
            ]);

            $data = json_decode($response->getBody(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Error decoding JSON response: ' . json_last_error_msg());
            }

            if ($data['metadata']['code'] == 201) {
                return [
                    'error' => $data['metadata']['message'],
                    'code'  => $data['metadata']['code'],
                ];
            }

            $pendaftarans = collect($data['response']['data']);

            // Ambil semua no_trans dan no_rm dari response
            $noTransList = $pendaftarans->pluck('no_trans')->merge($pendaftarans->pluck('no_reg'))->unique()->filter();
            $normList    = $pendaftarans->pluck('pasien_no_rm')->unique()->filter();

            // Ambil semua data sekali query
            $kunjunganWaktuSelesai = KunjunganWaktuSelesai::whereIn('notrans', $noTransList)->get()->keyBy('notrans');
            $kasirList             = KasirTransModel::whereIn('notrans', $noTransList)->get()->keyBy('notrans');
            $obatList              = KasirAddModel::whereIn('notrans', $noTransList)->where('idLayanan', '2')->get()->groupBy('notrans');
            $pendaftaranLocalList  = KunjunganModel::whereIn('norm', $normList)->whereDate('tgltrans', $params['tanggal_awal'] ?? date('Y-m-d'))->get()->keyBy('norm');

            // Ambil status lab dan RO
            $tanggal   = $params['tanggal_awal'] ?? date('Y-m-d');
            $tungguLab = collect((new LaboratoriumKunjunganModel())->tungguLab($tanggal))->keyBy('norm');
            $tungguRo  = collect((new ROTransaksiModel())->tungguRo($tanggal))->keyBy('norm');

            $result = $pendaftarans->map(function ($d) use (
                $kunjunganWaktuSelesai,
                $kasirList,
                $obatList,
                $pendaftaranLocalList,
                $tungguLab,
                $tungguRo
            ) {
                $no_trans = $d['tanggal'] < '2024-12-19' ? $d['no_trans'] : $d['no_reg'];
                $check    = $kunjunganWaktuSelesai[$no_trans] ?? null;

                $alamat     = $d['kelurahan_nama'] . ', ' . $d['pasien_rt'] . '/' . $d['pasien_rw'] . ', ' . $d['kecamatan_nama'] . ', ' . $d['kabupaten_nama'];
                $alamatMin  = $d['kelurahan_nama'] . ', ' . $d['kecamatan_nama'];
                $alamatPang = 'Desa ' . $d['kelurahan_nama'] . ', Kecamatan ' . $d['kecamatan_nama'];

                return [
                    "check_in"           => $check && $check->waktu_selesai_rm ? 'success' : 'danger',
                    "igd_selesai"        => $check && $check->waktu_selesai_igd ? 'success' : 'danger',
                    "status_pulang"      => ! is_null($d["loket_farmasi_menunggu_waktu"]) ? "Sudah Pulang" : "Belum Pulang",
                    "status_kasir"       => $kasirList->has($no_trans) ? 'Sudah' : 'Belum',
                    "status_obat"        => $obatList->has($no_trans) ? 'Sudah' : 'Belum',
                    "statusDaftar"       => $pendaftaranLocalList->has($d['pasien_no_rm']) ? 'lime' : 'warning',
                    "konsul_ro"          => $check && $check->konsul_ro == 1 ? "success" : "danger",
                    "no_sep"             => $check->no_sep ?? '',
                    "pasien_alamat"      => $alamat,
                    "pasien_alamat_min"  => $alamatMin,
                    "pasien_alamat_pang" => $alamatPang,
                    "statusLab"          => $tungguLab[$d['pasien_no_rm']]['status'] ?? null,
                    "statusRO"           => $tungguRo[$d['pasien_no_rm']]['status'] ?? null,
                    // Tambahkan data lainnya langsung
                ] + collect($d)->only([
                    'no_reg', 'id', 'no_trans', 'antrean_nomor', 'tanggal', 'penjamin_nama', 'penjamin_nomor',
                    'jenis_kunjungan_nama', 'nomor_referensi', 'pasien_nik', 'pasien_nama', 'pasien_no_rm',
                    'pasien_tgl_lahir', 'jenis_kelamin_nama', 'pasien_lama_baru', 'rs_paru_pasien_lama_baru',
                    'poli_nama', 'poli_sub_nama', 'dokter_nama', 'daftar_by', 'waktu_daftar',
                    'waktu_verifikasi', 'admin_pendaftaran', 'log_id', 'keterangan', 'keterangan_urutan',
                    'pasien_umur_tahun', 'pasien_umur_bulan', 'pasien_umur_hari',
                ])->toArray() + [
                    'pasien_umur' => ($d["pasien_umur_tahun"] ?? 0) . " Thn " . ($d["pasien_umur_bulan"] ?? 0) . " Bln",
                ];
            });

            if (! empty($params['no_rm'])) {
                $result = $result->where('pasien_no_rm', $params['no_rm'])->values();
            }

            return $result->values()->toArray();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function cpptRequestAll(array $params)
    {
        // Inisialisasi klien GuzzleHTTP
        $client = new Client();

        // URL endpoint API yang ingin diakses
        $url = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/cppt/data_cppt';

        // Username dan password untuk basic auth
        $username = env('API_USERNAME', '');
        $password = env('API_PASSWORD', '');

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
            return $data;
        } catch (\Exception $e) {
            // Tangani kesalahan
            return ['error' => $e->getMessage()];
        }
    }
    public function rekapFaskesPerujuk(array $params)
    {
        $client = new Client();
        $url    = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/SEP/jumlah_rujukan_asal';

        $username = env('API_USERNAME', '');
        $password = env('API_PASSWORD', '');

        try {
            $response = $client->request('POST', $url, [
                'auth'        => [$username, $password],
                'form_params' => $params,
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function cpptRequest(array $params)
    {
        // dd($params);
        // Inisialisasi klien GuzzleHTTP
        $client = new Client();

        // URL endpoint API yang ingin diakses
        $url = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/cppt/data_cppt';

        // Username dan password untuk basic auth
        $username = env('API_USERNAME', '');
        $password = env('API_PASSWORD', '');

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
            // dd($body);

            // Konversi response body ke array
            $data = json_decode($body, true);
            if (isset($data['response']['data']) && is_array($data['response']['data'])) {
                // Filter data, jika tindakan kosong maka skip
                $filteredData = array_filter($data['response']['data'], function ($item) {
                    // return !empty($item['tindakan']);
                    return true;
                });

                $data['response']['data'] = $filteredData;
                $tanggal                  = $params['tanggal_awal'] ?? date('Y-m-d');
                // dd($tanggal);
                $kunjunganLab = new LaboratoriumKunjunganModel();
                $tungguLab    = $kunjunganLab->tungguLab($tanggal);

                $kunjunganRo = new ROTransaksiModel();
                $tungguRo    = $kunjunganRo->tungguRo($tanggal);

                // Tambahkan statusLab dan statusRO jika norm ditemukan
                foreach ($data['response']['data'] as &$pendaftar) {
                    $rm = $pendaftar['pasien_no_rm'];

                    // Cari di tungguLab
                    $lab                    = collect($tungguLab)->firstWhere('norm', $rm);
                    $pendaftar['statusLab'] = $lab['status'] ?? null;

                    // Cari di tungguRo
                    $ro                    = collect($tungguRo)->firstWhere('norm', $rm);
                    $pendaftar['statusRO'] = $ro['status'] ?? null;
                }
                // Update the 'data' key with the filtered data
            } else {
                // Handle the case where 'response' or 'data' key is not present
                $data = [];
            }

            // dd($data);
            return $data;

        } catch (\Exception $e) {
            // Tangani kesalahan
            return ['error' => $e->getMessage()];
        }
    }
    public function poinRequest(array $params)
    {
        // Inisialisasi klien GuzzleHTTP
        $client = new Client();

        // URL endpoint API yang ingin diakses
        $url = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/laporan/rekap_petugas';

        // Username dan password untuk basic auth
        $username = env('API_USERNAME', '');
        $password = env('API_PASSWORD', '');

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

            // Kembalikan data
            return $data;

        } catch (\Exception $e) {
            // Tangani kesalahan
            return ['error' => $e->getMessage()];
        }
    }
    public function pasienRequestfull($no_rm)
    {
        // Inisialisasi klien GuzzleHTTP
        $client = new Client([
            'timeout' => 200, // timeout dalam detik
        ]);

        // URL endpoint API yang ingin diakses
        $url = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/pasien/data_pasien';

        // Username dan password untuk basic auth
        $username = env('API_USERNAME', '');
        $password = env('API_PASSWORD', '');

        // Data POST
        $data = [
            'no_rm' => $no_rm,
        ];

        try {
            // Lakukan permintaan POST dengan otentikasi dasar
            $response = $client->request('POST', $url, [
                'timeout'     => 200,
                'auth'        => [$username, $password],
                'form_params' => $data,
                'headers'     => [
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
        $client = new Client([
            'timeout' => 200, // timeout dalam detik
        ]);

        // URL endpoint API yang ingin diakses
        $url = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/pasien/data_pasien';

        // Username dan password untuk basic auth
        $username = env('API_USERNAME', '');
        $password = env('API_PASSWORD', '');

        // Data POST
        $data = [
            'no_rm' => $no_rm,
        ];

        try {
            // Lakukan permintaan POST dengan otentikasi dasar
            $response = $client->request('POST', $url, [
                'timeout'     => 200,
                'auth'        => [$username, $password],
                'form_params' => $data,
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            // Ambil body response
            $body = $response->getBody();

            // Konversi response body ke array
            $data = json_decode($body, true);
            // dd($data);
            // jika "metadata" => array:2 [▼"message" => "Data tidak ditemukan!""code" => 201
            if ($data['metadata']['message'] == "Data tidak ditemukan!") {
                $res = "Data tidak ditemukan!";
            } else {
                $alamat = $data['response']['data']['kelurahan_nama'] . ', ' .
                    $data['response']['data']['pasien_rt'] . '/' .
                    $data['response']['data']['pasien_rw'] . ', ' .
                    $data['response']['data']['kecamatan_nama'] . ', ' .
                    $data['response']['data']['kabupaten_nama'] . ', ' .
                    $data['response']['data']['provinsi_nama'];
                $res = [
                    "pasien_nik"                    => $data['response']['data']['pasien_nik'],
                    "pasien_no_kk"                  => $data['response']['data']['pasien_no_kk'],
                    "pasien_nama"                   => $data['response']['data']['pasien_nama'],
                    "pasien_no_rm"                  => $data['response']['data']['pasien_no_rm'],
                    "jenis_kelamin_id"              => $data['response']['data']['jenis_kelamin_id'],
                    "jenis_kelamin_nama"            => $data['response']['data']['jenis_kelamin_nama'],
                    "pasien_tempat_lahir"           => $data['response']['data']['pasien_tempat_lahir'],
                    "pasien_tgl_lahir"              => $data['response']['data']['pasien_tgl_lahir'],
                    "pasien_no_hp"                  => $data['response']['data']['pasien_no_hp'],
                    "pasien_domisili"               => $data['response']['data']['pasien_alamat'],
                    // "pasien_alamat" => ($data['response']['data']['kelurahan_nama']) . ', ' . ($data['response']['data']['pasien_rt']) . '/' . ($data['response']['data']['pasien_rw']) . ', ' . ($data['response']['data']['kecamatan_nama']) . ', ' . ($data['response']['data']['kabupaten_nama']) . ', ' . ($data['response']['data']['provinsi_nama']),
                    "pasien_alamat"                 => $alamat,
                    "pasien_kode_pos"               => $data['response']['data']['pasien_kode_pos'],
                    "provinsi_id"                   => $data['response']['data']['provinsi_id'],
                    "provinsi_nama"                 => $data['response']['data']['provinsi_nama'],
                    "kabupaten_id"                  => $data['response']['data']['kabupaten_id'],
                    "kabupaten_nama"                => $data['response']['data']['kabupaten_nama'],
                    "kecamatan_id"                  => $data['response']['data']['kecamatan_id'],
                    "kecamatan_nama"                => $data['response']['data']['kecamatan_nama'],
                    "kelurahan_id"                  => $data['response']['data']['kelurahan_id'],
                    "kelurahan_nama"                => $data['response']['data']['kelurahan_nama'],
                    "pasien_rt"                     => $data['response']['data']['pasien_rt'],
                    "pasien_rw"                     => $data['response']['data']['pasien_rw'],
                    "penjamin_id"                   => $data['response']['data']['penjamin_id'],
                    "penjamin_nama"                 => $data['response']['data']['penjamin_nama'],
                    "penjamin_nomor"                => $data['response']['data']['penjamin_nomor'],
                    "agama_id"                      => $data['response']['data']['agama_id'],
                    "agama_nama"                    => $data['response']['data']['agama_nama'],
                    "rs_paru_agama_id"              => $data['response']['data']['rs_paru_agama_id'],
                    "goldar_id"                     => $data['response']['data']['goldar_id'],
                    "goldar_nama"                   => $data['response']['data']['goldar_nama'],
                    "status_kawin_id"               => $data['response']['data']['status_kawin_id'],
                    "status_kawin_nama"             => $data['response']['data']['status_kawin_nama'],
                    "rs_paru_status_kawin"          => $data['response']['data']['rs_paru_status_kawin'],
                    "pendidikan_id"                 => $data['response']['data']['pendidikan_id'],
                    "pendidikan_nama"               => $data['response']['data']['pendidikan_nama'],
                    "pekerjaan_nama"                => $data['response']['data']['pekerjaan_nama'] ?? "-",
                    "rs_paru_pendidikan_id"         => $data['response']['data']['rs_paru_pendidikan_id'],
                    "pasien_daftar_by"              => $data['response']['data']['pasien_daftar_by'],
                    "pasien_penanggung_jawab_nama"  => $data['response']['data']['pasien_penanggung_jawab_nama'],
                    "pasien_penanggung_jawab_no_hp" => $data['response']['data']['pasien_penanggung_jawab_no_hp'],
                    "created_at"                    => $data['response']['data']['created_at'],
                    "created_at_tanggal"            => $data['response']['data']['created_at_tanggal'],
                ];
            }

            // return $data;
            return $res;

        } catch (\Exception $e) {
            // Tangani kesalahan
            return ['error' => $e->getMessage()];
        }
    }
    public function ambilNoRequest($penjamin_id)
    {
        // Inisialisasi klien GuzzleHTTP
        $client = new Client;

        // URL endpoint API yang ingin diakses
        $url = 'https://kkpm.banyumaskab.go.id/administrator/display_tv/ambil_antrean';

        // Data POST
        $data = [
            'penjamin_id' => $penjamin_id,
        ];

        try {
            // Lakukan permintaan POST dengan otentikasi dasar
            $response = $client->request('POST', $url, [
                'timeout'     => 200,
                'form_params' => $data,
                'headers'     => [
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

    public function waktuLayananRequest(array $params)
    {
        $client   = new Client();
        $url      = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/pendaftaran/data_pendaftaran';
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
            $mentah       = json_decode($body, true);
            $responseData = $mentah['response']['data'];

            // dd($mentah);
            // Filter data sesuai dengan kondisi
            if (! isset($params['no_rm']) || empty($params['no_rm'])) {
                $data = array_filter($responseData, function ($message) {
                    return $message['keterangan'] === 'SELESAI DIPANGGIL LOKET PENDAFTARAN';
                });
            } else {
                $data = array_filter($responseData, function ($message) use ($params) {
                    return $message['keterangan'] === 'SELESAI DIPANGGIL LOKET PENDAFTARAN' &&
                        $message['pasien_no_rm'] === $params['no_rm'];
                });
            }

            $res = array_map(function ($message) {

                $ambilNoAntri = $message["waktu_daftar"];
                $mulaiPanggil = new DateTime($message["loket_pendaftaran_panggil_waktu"]);
                $mulaiPanggil->setTime(7, 15, 0);
                $mulaiPanggil = $mulaiPanggil->format('Y-m-d H:i:s');

                if ($ambilNoAntri > $mulaiPanggil) {
                    $mulaiPanggil = $message["loket_pendaftaran_menunggu_waktu"];
                }

                // $tunggu_panggil_daftar = ($message["daftar_by"] == "JKN") ? 2 : max(0, round((strtotime($message["loket_pendaftaran_panggil_waktu"]) - strtotime($message["loket_pendaftaran_skip_waktu"] ?? $mulaiPanggil)) / 60, 2));
                $tunggu_panggil_daftar = max(0, round((strtotime($message["loket_pendaftaran_panggil_waktu"]) - strtotime($message["loket_pendaftaran_skip_waktu"] ?? $mulaiPanggil)) / 60, 2));
                $lama_daftar           = max(0, round((strtotime($message["loket_pendaftaran_selesai_waktu"]) - strtotime($message["loket_pendaftaran_panggil_waktu"])) / 60, 2));

                $selesaiRm       = KunjunganWaktuSelesai::where('norm', $message['pasien_no_rm'])->whereDate('waktu_selesai_rm', $message['tanggal'])->first();
                $Rmdata          = $selesaiRm ? true : false;
                $waktuSelesaiIgd = 0;
                if (is_null($selesaiRm)) {
                    $waktuSelesaiRM = $message["loket_pendaftaran_selesai_waktu"];
                    $lamaSelesaiRM  = 0;
                } else {
                    $waktuSelesaiRM  = date('Y-m-d H:i:s', strtotime($selesaiRm->waktu_selesai_rm));
                    $lamaSelesaiRM   = max(0, round((strtotime($selesaiRm->waktu_selesai_rm) - strtotime($message["loket_pendaftaran_panggil_waktu"])) / 60, 2));
                    $waktuSelesaiIgd = date('Y-m-d H:i:s', strtotime($selesaiRm->waktu_selesai_igd));
                }

                // Menentukan waktu tunggu tensi
                $tunggu_tensi = 0;
                if (strtotime($message["ruang_tensi_panggil_waktu"]) < strtotime($waktuSelesaiRM)) {
                    $tunggu_tensi = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["loket_pendaftaran_selesai_waktu"])) / 60, 2));
                } else {
                    $tunggu_tensi = max(0, round((strtotime($message["ruang_tensi_panggil_waktu"]) - strtotime($message["ruang_tensi_skip_waktu"] ?? $waktuSelesaiRM)) / 60, 2));
                }
                $lama_tensi = max(0, round((strtotime($message["ruang_tensi_selesai_waktu"]) - strtotime($message["ruang_tensi_panggil_waktu"])) / 60, 2));

                // Menentukan durasi poli
                $durasi_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["loket_pendaftaran_selesai_waktu"])) / 60, 2));
                $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_poli_skip_waktu"] ?? $message["ruang_tensi_selesai_waktu"])) / 60, 2));
                $lama_poli   = max(0, round((strtotime($message["ruang_poli_selesai_waktu"]) - strtotime($message["ruang_poli_panggil_waktu"])) / 60, 2));
                // dd($tunggu_poli);
                // Tentukan waktu panggil farmasi
                $panggilFarmasi = isset($message["ruang_poli_selesai_waktu"])
                ? new DateTime($message["ruang_poli_selesai_waktu"])
                : new DateTime('0000-00-00 00:00:00');
                // Tambahkan 3 menit
                $panggilFarmasi->modify('+3 minutes');
                $panggilFarmasi = $panggilFarmasi->format('Y-m-d H:i:s');

                // Inisialisasi waktu tunggu lainnya
                $tunggu_igd                 = $tunggu_farmasi                 = $tunggu_kasir                 = 0;
                $statusPulang               = ! is_null($message["ruang_poli_selesai_waktu"]) ? "Sudah Pulang" : "Belum Pulang";
                $lama_pelayanan_tiap_pasien = ! is_null($message["ruang_poli_selesai_waktu"]) ? max(0, round((strtotime($message["ruang_poli_selesai_waktu"]) - strtotime($message["loket_pendaftaran_selesai_waktu"])) / 60, 2)) : 0;

                $roData = ROTransaksiModel::where('norm', $message['pasien_no_rm'])
                    ->whereDate('tgltrans', $message['tanggal'])->first();
                $Rdata     = $roData ? true : false;
                $selesaiRo = $panggilRo = $lama_ro = 0;
                if ($roData && $roData->created_at) {
                    $selesaiRo = date('Y-m-d H:i:s', strtotime($roData->updated_at));
                    $panggilRo = date('Y-m-d H:i:s', strtotime($roData->created_at));
                    $lama_ro   = max(0, round((strtotime($roData->updated_at) - strtotime($roData->created_at)) / 60, 2));
                }

                $labData = LaboratoriumKunjunganModel::where('norm', $message['pasien_no_rm'])
                    ->whereDate('created_at', $message['tanggal'])
                    ->first();
                // dd($labData);
                $Ldata = $labData ? true : false;
                // Inisialisasi variabel default
                $panggilLab = $selesaiLab = $lama_lab = 0;
                // Periksa data lab
                if ($labData && $labData->created_at) {
                    $panggilLab = date('Y-m-d H:i:s', strtotime($labData->created_at));
                    $selesaiLab = date('Y-m-d H:i:s', strtotime($labData->waktu_selesai ?: $labData->updated_at));
                    $lama_lab   = max(0, round((strtotime($selesaiLab) - strtotime($panggilLab)) / 60, 2));
                }

                $igdData = IGDTransModel::where('norm', $message['pasien_no_rm'])->whereDate('created_at', $message['tanggal'])->first();
                // dd($igdData);
                $igd        = $igdData ? true : false;
                $panggilIgd = $selesaiIgd = $lama_igd = 0;
                // Periksa data IGD
                if ($igdData && $igdData->updated_at) {
                    $panggilIgd     = date('Y-m-d H:i:s', strtotime($igdData->created_at));
                    $selesaiIgd     = $waktuSelesaiIgd ?: date('Y-m-d H:i:s', strtotime($igdData->updated_at));
                    $lama_igd       = max(0, round((strtotime($selesaiIgd) - strtotime($panggilIgd)) / 60, 2));
                    $panggilFarmasi = $selesaiIgd;
                }

                $oke = false;
                if ($Rdata || $Ldata || $igd) {
                    $oke = true;
                }

                // Menentukan waktu tunggu lab dan rontgen dan poli
                $selesaiTensi  = strtotime($message['ruang_tensi_selesai_waktu']);
                $panggilPoli   = strtotime($message['ruang_poli_panggil_waktu']) ?: null;
                $selesaiPoli   = strtotime($message['ruang_poli_selesai_waktu']) ?: null;
                $panggilLabMat = strtotime($panggilLab);
                $panggilRoMat  = strtotime($panggilRo);
                $selesaiLabMat = strtotime($selesaiLab);
                $selesaiRoMat  = strtotime($selesaiRo);
                $tunggu_lab    = $tunggu_ro    = 0;

                if ($Ldata && $Rdata) {
                    $waktuTunggu = $this->urutan($panggilPoli, $panggilLabMat, $panggilRoMat, $selesaiTensi);
                    $tunggu_lab  = $waktuTunggu['tunggu_lab'];
                    $tunggu_ro   = $waktuTunggu['tunggu_ro'];
                    $tunggu_poli = $waktuTunggu['tunggu_poli'];

                } elseif ($Ldata) {
                    if (! is_null($panggilPoli) && $panggilPoli < $panggilLabMat) {
                        $tunggu_lab = max(0, round(($panggilLabMat - $selesaiPoli) / 60, 2));
                    } else {
                        $tunggu_lab  = max(0, round(($panggilLabMat - $selesaiTensi) / 60, 2));
                        $tunggu_poli = max(0, round(($panggilPoli - $selesaiLabMat) / 60, 2));
                    }
                } elseif ($Rdata) {
                    if (strtotime($message['ruang_poli_panggil_waktu']) < $panggilRoMat) {
                        $tunggu_ro = max(0, round(($panggilRoMat - $selesaiTensi) / 60, 2));
                    } else {
                        $tunggu_ro   = max(0, round(($panggilRoMat - $selesaiPoli) / 60, 2));
                        $tunggu_poli = max(0, round($panggilPoli - $selesaiRoMat) / 60, 2);
                    }
                }
                return [
                    "oke"                   => $oke,
                    "ro_kominfo"            => ! is_null($message["ruang_rontgen_panggil_waktu"]),
                    "lab_kominfo"           => ! is_null($message["ruang_laboratorium_panggil_waktu"]),

                    "rm"                    => $Rmdata,
                    "rodata"                => $Rdata,
                    "labdata"               => $Ldata,
                    "igddata"               => $igd,

                    "lama_pelayanan_pasien" => $lama_pelayanan_tiap_pasien,
                    "no_reg"                => $message["no_reg"] ?? 0,
                    "no_trans"              => $message["no_trans"] ?? 0,
                    "daftar_by"             => $message["daftar_by"] ?? 0,
                    "antrean_nomor"         => $message["antrean_nomor"] ?? 0,
                    "tanggal"               => $message["tanggal"] ?? 0,
                    "penjamin_nama"         => $message["penjamin_nama"] ?? 0,
                    "status_pasien"         => $message["pasien_lama_baru"] ?? 0,
                    "pasien_no_rm"          => $message["pasien_no_rm"] ?? 0,
                    "pasien_nama"           => $message["pasien_nama"] ?? 0,
                    'pasien_umur'           => ($message["pasien_umur_tahun"] ?? 0) . " Thn " . ($message["pasien_umur_bulan"] ?? 0) . " Bln ",
                    "jenis_kelamin"         => $message["jenis_kelamin_nama"] ?? 0,
                    "poli_nama"             => $message["poli_nama"] ?? 0,
                    "dokter_nama"           => $message["dokter_nama"] ?? 0,

                    "waktu_daftar"          => $message["waktu_daftar"] ?? 0,
                    "ambil_no"              => $message["loket_pendaftaran_menunggu_waktu"] ?? 0,
                    "mulai_panggil"         => $mulaiPanggil ?? 0,

                    "status_pulang"         => $statusPulang,

                    "tunggu_daftar"         => $tunggu_panggil_daftar ?? 0,
                    "pendaftaran_panggil"   => $message["loket_pendaftaran_panggil_waktu"] ?? 0,
                    "pendaftaran_skip"      => $message["loket_pendaftaran_skip_waktu"] ?? 0,
                    "pendaftaran_selesai"   => $message["loket_pendaftaran_selesai_waktu"] ?? 0,
                    "lama_pendaftaran"      => $lama_daftar ?? 0,
                    "waktu_selesai_rm"      => $waktuSelesaiRM ?? 0,
                    "tunggu_rm"             => $lamaSelesaiRM ?? 0,

                    "tunggu_tensi"          => $tunggu_tensi ?? 0,
                    "tensi_panggil"         => $message["ruang_tensi_panggil_waktu"] ?? 0,
                    "tensi_skip"            => $message["ruang_tensi_skip_waktu"] ?? 0,
                    "tensi_selesai"         => $message["ruang_tensi_selesai_waktu"] ?? 0,
                    "lama_tensi"            => $lama_tensi ?? 0,

                    "durasi_poli"           => $durasi_poli ?? 0,
                    "tunggu_poli"           => $tunggu_poli ?? 0,
                    "poli_panggil"          => $message["ruang_poli_panggil_waktu"] ?? 0,
                    "poli_skip"             => $message["ruang_poli_skip_waktu"] ?? 0,
                    "poli_selesai"          => $message["ruang_poli_selesai_waktu"] ?? 0,
                    "lama_poli"             => $lama_poli ?? 0,

                    "tunggu_lab"            => $tunggu_lab ?? 0,
                    "laboratorium_panggil"  => $panggilLab ?? $message["ruang_laboratorium_panggil_waktu"] ?? 0,
                    "laboratorium_skip"     => $message["ruang_laboratorium_skip_waktu"] ?? 0,
                    "laboratorium_selesai"  => $selesaiLab ?? $message["ruang_laboratorium_selesai_waktu"] ?? 0,
                    "selesai_lab"           => $selesaiLab,
                    "tunggu_hasil_lab"      => $lama_lab,

                    "tunggu_ro"             => $tunggu_ro ?? 0,
                    "rontgen_panggil"       => $panggilRo ?? $message["ruang_rontgen_panggil_waktu"] ?? 0,
                    "rontgen_skip"          => $message["ruang_rontgen_skip_waktu"] ?? 0,
                    "rontgen_selesai"       => $selesaiRo ?? $message["ruang_rontgen_selesai_waktu"] ?? 0,
                    "selesai_ro"            => $selesaiRo,
                    "tunggu_hasil_ro"       => $lama_ro,

                    "tunggu_igd"            => $tunggu_igd ?? 0,
                    "igd_panggil"           => $panggilIgd ?? $message["ruang_igd_panggil_waktu"] ?? $panggilIgd,
                    "igd_skip"              => $selesaiIgd ?? $message["ruang_igd_skip_waktu"] ?? 0,
                    "igd_selesai"           => $message["ruang_igd_selesai_waktu"] ?? $selesaiIgd,
                    "lama_igd"              => $lama_igd,

                    "tunggu_kasir"          => $tunggu_kasir ?? 0,
                    "kasir_panggil"         => $message["loket_kasir_panggil_waktu"] ?? 0,
                    "kasir_skip"            => $message["loket_kasir_skip_waktu"] ?? 0,
                    "kasir_selesai"         => $message["loket_kasir_selesai_waktu"] ?? 0,

                    "tunggu_farmasi"        => $tunggu_farmasi ?? 0,
                    "farmasi_panggil"       => $message["loket_farmasi_panggil_waktu"] ?? $panggilFarmasi,
                    "farmasi_skip"          => $message["loket_farmasi_skip_waktu"] ?? 0,
                    "farmasi_selesai"       => $message["loket_farmasi_selesai_waktu"] ?? 0,
                ];
            }, $data);
            $res = array_values($res);
            return $res;
            // return $data;

        } catch (\Exception $e) {
            // Tangani kesalahan
            return ['error' => $e->getMessage()];
        }
    }
    private function urutan($panggilPoli, $panggilLabMat, $panggilRoMat, $selesaiTensi)
    {

        $waktuArray = [
            'panggilPoli'   => $panggilPoli,
            'panggilLabMat' => $panggilLabMat,
            'panggilRoMat'  => $panggilRoMat,
        ];

        // // Filter array untuk menghapus nilai yang null, kosong, atau 0
        $waktuArray = array_filter($waktuArray, function ($value) {
            return ! is_null($value) && $value !== '' && $value !== 0;
        });

        // Urutkan array berdasarkan waktu
        asort($waktuArray);

        // Ambil urutan waktu dan kunci
        $waktuUrut = array_values($waktuArray);
        $kunciUrut = array_keys($waktuArray);

        // dd($waktuUrut, $kunciUrut, $selesaiTensi);

        // Tentukan waktu yang lebih awal dan selisih antar waktu
        $waktuPertama = $kunciUrut[0];
        $waktuKedua   = $kunciUrut[1];
        // $waktuKetiga = $kunciUrut[2];

        $selisih0_1 = max(0, round(($waktuUrut[0] - $selesaiTensi) / 60, 2));
        $selisih1_2 = max(0, round(($waktuUrut[1] - $waktuUrut[0]) / 60, 2));
        if (count($waktuArray) > 2) {
                                                     // Mengambil waktu urut yang sudah diurutkan
            $waktuUrut  = array_values($waktuArray); // Mengambil nilai dari array yang sudah diurutkan
            $selisih2_3 = max(0, round(($waktuUrut[2] - $waktuUrut[1]) / 60, 2));
        } else {
            $selisih2_3 = 0;
        }

        // dd($selisih0_1, $selisih1_2, $selisih2_3);

        if ($waktuPertama == 'panggilPoli') {
            // dd("waktu pertama poli");
            $tunggu_poli = $selisih0_1;
            if ($waktuKedua == 'panggilLabMat') {
                // dd("waktu kedua lab");
                $tunggu_lab = $selisih1_2;
                $tunggu_ro  = $selisih2_3;
            } elseif ($waktuKedua == 'panggilRoMat') {
                // dd("waktu kedua ro");
                $tunggu_ro  = $selisih1_2;
                $tunggu_lab = $selisih2_3;
            }
        } elseif ($waktuPertama == 'panggilLabMat') {
            // dd("waktu pertama lab");
            $tunggu_lab = $selisih0_1;
            if ($waktuKedua == 'panggilRoMat') {
                $tunggu_ro   = $selisih1_2;
                $tunggu_poli = $selisih2_3;
            } elseif ($waktuKedua == 'panggilPoli') {
                $tunggu_poli = $selisih1_2;
                $tunggu_ro   = $selisih2_3;
                $tunggu_lab  = $selisih2_3;
            }
        } else {
            // dd("waktu pertama ro");
            $tunggu_ro = $selisih0_1;
            if ($waktuKedua == 'panggilLabMat') {
                $tunggu_lab  = $selisih1_2;
                $tunggu_poli = $selisih2_3;
            } elseif ($waktuKedua == 'panggilPoli') {
                $tunggu_poli = $selisih1_2;
                $tunggu_lab  = $selisih2_3;
            }
        }

        return [
            'tunggu_poli' => $tunggu_poli,
            'tunggu_lab'  => $tunggu_lab,
            'tunggu_ro'   => $tunggu_ro,
        ];
    }
    public function resep_obat($pendaftaran_id)
    {
        $client = new Client();
        $url    = 'https://kkpm.banyumaskab.go.id/administrator/loket_farmasi/lihat_resep?pendaftaran_id=' . $pendaftaran_id;
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;
        if (! $cookie) {
            $loginResponse = $this->login(env('USERNAME_KOMINFO', ''), env('PASSWORD_KOMINFO', ''));
            $cookie        = $loginResponse['cookies'][0] ?? null;

            if ($cookie) {
                setcookie('kominfo_cookie', $cookie, time() + (86400 * 30), "/"); // Set cookie in the browser
            } else {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }
        $response = $client->request('GET', $url, [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie'       => $cookie,
                'User-Agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
                'Referer'      => 'https://kkpm.banyumaskab.go.id/',
                'Accept'       => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            ],
        ]);

        // dd($response);
        $responseBody = (string) $response->getBody();
        if (empty($responseBody)) {
            return response()->json(['message' => 'Response kosong'], 500);
        }

        $jsonData = json_decode($responseBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['message' => 'Response bukan JSON', 'body' => $responseBody], 500);
        }
        dd($responseBody);

        $data = response()->json(json_decode($response->getBody(), true));

        return $data;
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
        // DB::table('login_logs')->insert([
        //     'username'   => $username,
        //     'ip'         => request()->ip(),
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        if (isset($cookies[0])) {
                                                                                  // Set cookie di browser
            setcookie('kominfo_cookie', $cookies[0], time() + (86400 * 30), "/"); // Cookie akan kedaluwarsa dalam 30 hari
        }

        return [
            'data'    => json_decode($response->getBody(), true),
            'cookies' => $cookies,
        ];
    }
    public function get_data_antrian(array $data, $pasien_no_rm = null)
    {
        $client = new Client();
        $url    = env('BASR_URL_KOMINFO', '') . '/loket_pendaftaran/get_data';

        // Persiapkan form_params dengan parameter length dan pasien_no_rm
        $form_params = [
            'length' => 1000, // Menambahkan parameter length dengan nilai 1000
        ];

        // Tambahkan pasien_no_rm jika ada
        if ($pasien_no_rm !== null) {
            $form_params['pasien_no_rm'] = $pasien_no_rm; // Menambahkan filter pasien_no_rm
        }

        $response = $client->request('POST', $url, [
            'form_params' => $form_params,
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie'       => $data['cookie'],
            ],
        ]);

        $body = $response->getBody();
        $data = json_decode($body, true);
        return $data;
    }

    public function panggil(array $data, $log_id = null, $loket)
    {
        $client = new Client();
        $url    = env('BASR_URL_KOMINFO', '') . '/' . $loket . '/panggil';

        $response = $client->request('POST', $url, [
            'form_params' => [
                'log_id' => $log_id,
            ],
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie'       => $data['cookie'],
            ],
        ]);
        $body = $response->getBody();
        $data = json_decode($body, true);
        return $data;
    }
    public function getDataByRM(array $data, $pasien_no_rm = null)
    {
        $client = new Client();
        $url    = env('BASR_URL_KOMINFO', '') . '/data_pasien/getDataByRM';

        $response = $client->request('POST', $url, [
            'form_params' => [
                'pasien_no_rm' => $pasien_no_rm,
            ],
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie'       => $data['cookie'],
            ],
        ]);
        $body = $response->getBody();
        $data = json_decode($body, true);
        return $data;
    }
    public function submit(array $data, $log_id = null)
    {
        $client = new Client();
        // https://kkpm.banyumaskab.go.id/administrator/loket_pendaftaran/verifikasi/249235
        $url = env('BASR_URL_KOMINFO', '') . '/loket_pendaftaran/verifikasi/' . $log_id;
        // return $url;
        $form_data = $data['form_data'];
        // dd($form_data);

        $response = $client->request('POST', $url, [
            'form_params' => $form_data,
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie'       => $data['cookie'],
            ],
        ]);

        $body         = $response->getBody();
        $responseData = json_decode($body, true);

        return $responseData;

    }
    public function getDokterBefore(array $data, $pasien_id = null)
    {
        $client = new Client();
        $url    = env('BASR_URL_KOMINFO', '') . '/loket_pendaftaran/kunjunganDokterSebelumnya';

        $response = $client->request('POST', $url, [
            'form_params' => [
                'pasien_id' => $pasien_id,
            ],
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie'       => $data['cookie'],
            ],
        ]);

        $body         = $response->getBody();
        $responseData = json_decode($body, true);

        // Cek apakah data tersedia
        $text = $responseData['data']['text'] ?? null;

        if ($text) {
            // Pecah teks berdasarkan "Kunjungan Sebelumnya Oleh Dokter :"
            $parts = explode('Kunjungan Sebelumnya Oleh Dokter : ', $text);

            if (isset($parts[1])) {
                // Pecah lagi berdasarkan tanggal, asumsikan tanggal setelah nama dokter dipisahkan oleh koma
                $dokterInfo = explode(',', $parts[1]);
                return trim($dokterInfo[0]); // Ambil hanya nama dokter, tanpa tanggal
            }
        }

        return null; // Jika tidak ditemukan, kembalikan null
    }

    public function getTungguTensi()
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

        $url  = env('BASR_URL_KOMINFO', '') . '/ruang_tensi/get_data?id_ruang_tensi=2';
        $url2 = env('BASR_URL_KOMINFO', '') . '/display_tv/ruang_tensi_get_data';

        try {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'id_ruang_tensi' => 2,
                    'length'         => 1000,
                ],
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
            ]);
            $response2 = $client->request('POST', $url2, [
                'form_params' => [
                    'draw'   => 3,
                    'start'  => 0,
                    'length' => 10,
                ],
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
            ]);

            // Check if the response status is 200
            if ($response->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }
            if ($response2->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response2->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }

            $body  = (string) $response->getBody();
            $data  = json_decode($body, true);
            $body2 = (string) $response2->getBody();
            $data2 = json_decode($body2, true);

            return [
                'dataAtas' => $data2,
                'data'     => $data,
            ];

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

    public function getTungguFaramsi($tanggal = null, $cookie = null)
    {
        $client  = new Client();
        $tgl     = $tanggal ?? date('Y-m-d');
        $tanggal = $tgl . ' - ' . $tgl;

        if (! $cookie) {
            // dd($cookie);
            $loginResponse = $this->login(env('USERNAME_KOMINFO', ''), env('PASSWORD_KOMINFO', ''));
            $cookie        = $loginResponse['cookies'][0] ?? null;

            if ($cookie) {
                setcookie('kominfo_cookie', $cookie, time() + (86400 * 30), "/");
            } else {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }

        $url = env('BASR_URL_KOMINFO', '') . '/loket_farmasi/get_data';

        try {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'tanggal' => $tanggal,
                    'length'  => 1000,
                ],
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }

            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            return $data;

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Request Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghubungi server.'], 500);
        } catch (\Exception $e) {
            Log::error('Unexpected Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan yang tidak terduga.'], 500);
        }
    }
    public function loket_pendaftaran_get_data($tanggal = null, $cookie = null)
    {
        $client  = new Client();
        $tgl     = $tanggal ?? date('Y-m-d');
        $tanggal = $tgl . ' - ' . $tgl;

        if (! $cookie) {
            $loginResponse = $this->login(env('USERNAME_KOMINFO', ''), env('PASSWORD_KOMINFO', ''));
            $cookie        = $loginResponse['cookies'][0] ?? null;

            if ($cookie) {
                setcookie('kominfo_cookie', $cookie, time() + (86400 * 30), "/");
            } else {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }

        $url = env('BASR_URL_KOMINFO', '') . '/loket_farmasi/get_data';

        try {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'tanggal' => $tanggal,
                    'length'  => 1000,
                ],
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }

            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            return $data;

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Request Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghubungi server.'], 500);
        } catch (\Exception $e) {
            Log::error('Unexpected Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan yang tidak terduga.'], 500);
        }
    }

    public function getTungguLoket($status = null)
    {
        $client  = new Client();
        $cookie  = $_COOKIE['kominfo_cookie'] ?? null;
        $tgl     = date('Y-m-d');
        $tanggal = $tgl . ' - ' . $tgl;
        // dd($cookie);

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

        $url = env('BASR_URL_KOMINFO', '') . '/loket_pendaftaran/get_data';

        try {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'tanggal'          => $tanggal,
                    'length'           => 1000,
                    'pasien_lama_baru' => $status,
                ],
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
            ]);
            // dd($response);

            // Check if the response status is 200
            if ($response->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }

            $body = (string) $response->getBody();
            // dd($body);
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
    public function getTungguLoketFilter(array $params)
    {
        $client    = new Client();
        $cookie    = $_COOKIE['kominfo_cookie'] ?? null;
        $tgl_akhir = $params['tgl_akhir'];
        $tgl_awal  = $params['tgl_awal'];
        $status    = $params['status'] ?? null;

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

        $url = env('BASR_URL_KOMINFO', '') . '/loket_pendaftaran/get_data';

        try {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'tanggal'          => $tgl_awal . ' - ' . $tgl_akhir,
                    'length'           => 1000,
                    'pasien_lama_baru' => $status,
                ],
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
            ]);
            // dd($response);

            // Check if the response status is 200
            if ($response->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }

            $body = (string) $response->getBody();
            // dd($body);
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
    public function getDataLoket($status = null)
    {
        $client  = new Client();
        $cookie  = $_COOKIE['kominfo_cookie'] ?? null;
        $tgl     = date('Y-m-d');
        $tanggal = $tgl . ' - ' . $tgl;
        // dd($cookie);

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

        $url = 'https://kkpm.banyumaskab.go.id/administrator/display_tv/loket_pendaftaran_get_data';

        try {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'tanggal'          => $tanggal,
                    'length'           => 1000,
                    'pasien_lama_baru' => $status,
                ],
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
            ]);
            // dd($response);

            // Check if the response status is 200
            if ($response->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }

            $body = (string) $response->getBody();
            // dd($body);
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

    public function jadwalPoli(array $params)
    {
        $dokter = '
                <ul>
                <li>dr. Agil Dananjaya, Sp.P</li>
                <li>dr. Cempaka Nova I., Sp.P</li>
                <li>dr. Filly Ulfa K.</li>
                <li>dr. Sigit Dwiyanto</li>
                </ul>
        ';
        $jadwal = [
            [
                "dokter" => $dokter,
                "hari"   => "Senin - Kamis",
                "jam"    => "07:15 - 14:15",
            ],
            [
                "dokter" => $dokter,
                "hari"   => "Jumat",
                "jam"    => "07:15 - 11:15",
            ],
            [
                "dokter" => $dokter,
                "hari"   => "Sabtu",
                "jam"    => "07:15 - 12:45",
            ]];

        $html = '<table class="table-auto table table-bordered table-striped table-hover">
        <tbody>';

        foreach ($jadwal as $index => $row) {
            $html .= '<tr>
            <td class="border px-4 py-2 fs2 col-1">' . ($index + 1) . '</td>
            <td class="border px-4 py-2 fs2 col-5">' . $row['dokter'] . '</td>
            <td class="border px-4 py-2 fs2 col-3">' . $row['hari'] . '</td>
            <td class="border px-4 py-2 fs3">' . $row['jam'] . '</td>
          </tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }

    private function formatTanggal($tanggal)
    {
        try {
            // Coba parsing tanggal menggunakan DateTime
            $date = new DateTime($tanggal);
            // Kembalikan dalam format 'Y-m-d' (YYYY-MM-DD)
            return $date->format('Y-m-d');
        } catch (Exception $e) {
                         // Jika parsing gagal, tangani kesalahan
            return null; // Atau kembalikan nilai default
        }
    }

    public function getGrafikDokter($params)
    {
        // return $params;
        $client = new Client();
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;
        if (! $params) {
            $tgl     = date('Y-m-d');
            $tanggal = $tgl . ' - ' . $tgl;
        } else {
            $tgl_awal  = $params['tgl_awal'];
            $tgl_akhir = $params['tgl_akhir'];
            $tanggal   = $this->formatTanggal($tgl_awal) . ' - ' . $this->formatTanggal($tgl_akhir);
        }
        // return $tanggal;

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

        $url = env('BASR_URL_KOMINFO', '') . '/loket_farmasi/get_data';

        try {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'tanggal' => $tanggal,
                    'length'  => 1000,
                ],
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
            ]);
            // dd($response);

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
    public function getTungguPoli($params)
    {
        // return $params;
        $client = new Client();
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;
        if (! $params) {
            $tgl     = date('Y-m-d');
            $tanggal = $tgl . ' - ' . $tgl;
        } else {
            $tgl_awal  = $params['tgl_awal'];
            $tgl_akhir = $params['tgl_akhir'];
            $tanggal   = $this->formatTanggal($tgl_awal) . ' - ' . $this->formatTanggal($tgl_akhir);
        }
        // return $tanggal;

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

        $url  = env('BASR_URL_KOMINFO', '') . '/ruang_poli/get_data?poli_sub_id=1';
        $url2 = env('BASR_URL_KOMINFO', '') . '/ruang_poli/data_atas?poli_sub_id=1';
        $url3 = env('BASR_URL_KOMINFO', '') . '/display_tv/ruang_poli_get_data';

        try {
            // $response = $client->request('POST', $url, [
            //     'form_params' => [
            //         'tanggal' => $tanggal,
            //         'length' => 1000,
            //     ],
            //     'headers' => [
            //         'Content-Type' => 'application/x-www-form-urlencoded',
            //         'Cookie' => $cookie,
            //     ],
            // ]);
            // $response2 = $client->request('POST', $url2, [
            //     'form_params' => [
            //         'tanggal' => $tanggal,
            //         'length' => 1000,
            //     ],
            //     'headers' => [
            //         'Content-Type' => 'application/x-www-form-urlencoded',
            //         'Cookie' => $cookie,
            //     ],
            // ]);
            $response3 = $client->request('POST', $url3, [
                'form_params' => [
                    'tanggal' => $tanggal,
                    'length'  => 1000,
                ],
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
            ]);
            // dd($response);

            // Check if the response status is 200
            // if ($response->getStatusCode() !== 200) {
            //     Log::error('Error response body: ' . (string) $response->getBody());
            //     return response()->json(['error' => 'Internal Server Error'], 500);
            // }
            // if ($response2->getStatusCode() !== 200) {
            //     Log::error('Error response body: ' . (string) $response->getBody());
            //     return response()->json(['error' => 'Internal Server Error Data Atas'], 500);
            // }
            if ($response3->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response3->getBody());
                return response()->json(['error' => 'Internal Server Error Data Atas'], 500);
            }

            // $body = (string) $response->getBody();
            // $data = json_decode($body, true);
            // $body2 = (string) $response2->getBody();
            // $data2 = json_decode($body2, true);
            $body3 = (string) $response3->getBody();
            $data3 = json_decode($body3, true);

            return [
                // 'data' => $data,
                // 'data2' => $data2,
                'data3' => $data3,
            ];

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
    public function tungguPoli($params)
    {
        // return $params;
        $client = new Client();
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;
        if (! $params) {
            $tgl     = date('Y-m-d');
            $tanggal = $tgl . ' - ' . $tgl;
        } else {
            $tgl_awal  = $params['tgl_awal'];
            $tgl_akhir = $params['tgl_akhir'];
            $tanggal   = $this->formatTanggal($tgl_awal) . ' - ' . $this->formatTanggal($tgl_akhir);
        }
        // return $tanggal;

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

        $url = env('BASR_URL_KOMINFO', '') . '/ruang_poli/get_data?poli_sub_id=1';
        // $url2 = env('BASR_URL_KOMINFO', '') . '/ruang_poli/data_atas?poli_sub_id=1';

        try {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'tanggal' => $tanggal,
                    'length'  => 1000,
                ],
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
            ]);
            // $response2 = $client->request('POST', $url2, [
            //     'form_params' => [
            //         'tanggal' => $tanggal,
            //         'length' => 1000,
            //     ],
            //     'headers' => [
            //         'Content-Type' => 'application/x-www-form-urlencoded',
            //         'Cookie' => $cookie,
            //     ],
            // ]);
            // dd($response);

            // Check if the response status is 200
            if ($response->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }
            // if ($response2->getStatusCode() !== 200) {
            //     Log::error('Error response body: ' . (string) $response->getBody());
            //     return response()->json(['error' => 'Internal Server Error Data Atas'], 500);
            // }

            $body = (string) $response->getBody();
            $data = json_decode($body, true);
            // $body2 = (string) $response2->getBody();
            // $data2 = json_decode($body2, true);
            return $data;
            // return [
            //     'data' => $data,
            //     'data2' => $data2,
            // ];

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
    public function tungguRoLab($params)
    {
        // return $params;
        $client = new Client();
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;
        if (! $params) {
            $tgl     = date('Y-m-d');
            $tanggal = $tgl . ' - ' . $tgl;
        } else {
            $tgl_awal  = $params['tgl_awal'];
            $tgl_akhir = $params['tgl_akhir'];
            $tanggal   = $this->formatTanggal($tgl_awal) . ' - ' . $this->formatTanggal($tgl_akhir);
        }
        // return $tanggal;

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

        $url  = env('BASR_URL_KOMINFO', '') . '/ruang_laboratorium/get_data';
        $url2 = env('BASR_URL_KOMINFO', '') . '/ruang_rontgen/get_data';

        try {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'tanggal' => $tanggal,
                    'length'  => 1000,
                ],
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
            ]);
            $response2 = $client->request('POST', $url2, [
                'form_params' => [
                    'tanggal' => $tanggal,
                    'length'  => 1000,
                ],
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
            ]);
            // dd($response);

            // Check if the response status is 200
            if ($response->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }
            if ($response2->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error Data Atas'], 500);
            }

            $body  = (string) $response->getBody();
            $data  = json_decode($body, true);
            $body2 = (string) $response2->getBody();
            $data2 = json_decode($body2, true);
            // return $data;
            return [
                'data'  => $data,
                'data2' => $data2,
            ];

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
    public function getLogAntrian($id)
    {
        // return $params;
        $client = new Client();
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;
        if (! $id) {
            return null;
        }
        // return $tanggal;

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

        $url = env('BASR_URL_KOMINFO', '') . '/loket_pendaftaran/get_log_antrean?pendaftaran_id=' . $id;

        try {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'pendaftaran_id' => $id,
                ],
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
            ]);

            // Check if the response status is 200
            if ($response->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }

            $body = (string) $response->getBody();
            $data = json_decode($body, true);
            // return $data;
            // Pastikan 'data' adalah array dan ambil elemen terakhir
            if (isset($data['data']) && is_array($data['data'])) {
                $lastEntry = end($data['data']);
                return $lastEntry;
            }

            return null; // Kembalikan null jika data tidak v

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

    public function getAkssLoket()
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

        $url = env('BASR_URL_KOMINFO', '') . '/akses_loket/get_data';

        try {
            $response = $client->request('POST', $url, [
                'headers'     => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
                'form_params' => [
                    'draw'       => 3,
                    'columns'    => [
                        ['data' => '', 'name' => '', 'searchable' => true, 'orderable' => false, 'search' => ['value' => '', 'regex' => false]],
                        ['data' => 'admin_nama', 'name' => '', 'searchable' => true, 'orderable' => false, 'search' => ['value' => '', 'regex' => false]],
                        ['data' => 'loket_nama', 'name' => '', 'searchable' => true, 'orderable' => false, 'search' => ['value' => '', 'regex' => false]],
                        ['data' => 'created_at', 'name' => '', 'searchable' => true, 'orderable' => false, 'search' => ['value' => '', 'regex' => false]],
                        ['data' => 'id', 'name' => '', 'searchable' => true, 'orderable' => false, 'search' => ['value' => '', 'regex' => false]],
                    ],
                    'start'      => 0,
                    'length'     => 200,
                    'search'     => [
                        'value' => '',
                        'regex' => false,
                    ],
                    'loket_id'   => '',
                    'admin_id'   => '',
                    'created_at' => '',
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
    // public function getDataSEP($params)
    // {
    //     $client = new Client();
    //     $cookie = $_COOKIE['kominfo_cookie'] ?? null;
    //     $tglSep = $params["tanggal_awal"] . ' - ' . $params["tanggal_akhir"];

    //     if (! $cookie) {
    //         // Authenticate if no cookie is found
    //         $loginResponse = $this->login(env('USERNAME_KOMINFO', ''), env('PASSWORD_KOMINFO', ''));
    //         $cookie        = $loginResponse['cookies'][0] ?? null;

    //         if ($cookie) {
    //             setcookie('kominfo_cookie', $cookie, time() + (86400 * 30), "/"); // Set cookie in the browser
    //         } else {
    //             return response()->json(['message' => 'Login gagal'], 401);
    //         }
    //     }

    //     // $url = env('BASR_URL_KOMINFO', '') . '/sep/get_dataSEP'; //lama
    //     $url = env('BASR_URL_KOMINFO', '') . '/sep/get_data';

    //     // Format request sesuai dengan yang Anda inginkan
    //     $columns = [];
    //     for ($i = 0; $i < 3; $i++) { // Sesuaikan jumlah kolom sesuai kebutuhan
    //         $columns[] = [
    //             'data'       => ($i === 0) ? '' : 'id',
    //             'name'       => '',
    //             'searchable' => true,
    //             'orderable'  => false,
    //             'search'     => ['value' => '', 'regex' => false],
    //         ];
    //     }

    //     try {
    //         $response = $client->request('POST', $url, [
    //             'headers'     => [
    //                 'Content-Type' => 'application/x-www-form-urlencoded',
    //                 'Cookie'       => $cookie,
    //             ],
    //             'form_params' => [
    //                 'draw'               => 2,
    //                 'columns'            => $columns,
    //                 'start'              => 0,
    //                 'length'             => 100,
    //                 'search'             => [
    //                     'value' => '',
    //                     'regex' => false,
    //                 ],
    //                 'tanggal'            => $tglSep,
    //                 'antrean_nomor'      => '',
    //                 'no_reg'             => '',
    //                 'daftar_by'          => '',
    //                 'penjamin_id'        => 2,
    //                 'nomor_referensi'    => '',
    //                 'penjamin_nomor'     => '',
    //                 'jenis_kunjungan_id' => '',
    //                 'pasien'             => '',
    //                 'pasien_nik'         => '',
    //                 'no_sep'             => '',
    //                 'tanggal_sep'        => $tglSep,
    //             ],
    //         ]);

    //         // Check if response is successful
    //         if ($response->getStatusCode() !== 200) {
    //             Log::error('Error response body: ' . (string) $response->getBody());
    //             return response()->json(['error' => 'Internal Server Error'], 500);
    //         }

    //         $body = (string) $response->getBody();
    //         $data = json_decode($body, true);

    //         return $data;
    //     } catch (\GuzzleHttp\Exception\RequestException $e) {
    //         // Handle request errors
    //         Log::error('Request Error: ' . $e->getMessage());
    //         return response()->json(['error' => 'Terjadi kesalahan saat menghubungi server.'], 500);
    //     } catch (\Exception $e) {
    //         // Handle unexpected errors
    //         Log::error('Unexpected Error: ' . $e->getMessage());
    //         return response()->json(['error' => 'Terjadi kesalahan yang tidak terduga.'], 500);
    //     }
    // }

    public function getDataSEP($params)
    {
        $client = new Client();
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;
        $tglSep = $params["tanggal_awal"] . ' - ' . $params["tanggal_akhir"];

        if (! $cookie) {
            $loginResponse = $this->login(env('USERNAME_KOMINFO', ''), env('PASSWORD_KOMINFO', ''));
            $cookie        = $loginResponse['cookies'][0] ?? null;

            if ($cookie) {
                setcookie('kominfo_cookie', $cookie, time() + (86400 * 30), "/");
            } else {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }

        $url = env('BASR_URL_KOMINFO', '') . '/sep/get_data';

        $columns = [];
        for ($i = 0; $i < 3; $i++) {
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
                    'draw'        => 2,
                    'columns'     => $columns,
                    'start'       => 0,
                    'length'      => 100,
                    'search'      => ['value' => '', 'regex' => false],
                    'tanggal'     => $tglSep,
                    'tanggal_sep' => $tglSep,
                    'penjamin_id' => 2,
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }

            $data = json_decode((string) $response->getBody(), true);
            $list = collect($data['data'] ?? []);

            // Ambil no_trans & norm dari data
            $noTransList = $list->pluck('no_reg')->filter()->unique();
            $normList    = $list->pluck('pasien_no_rm')->filter()->unique();

            // Ambil semua data lokal sekaligus
            $waktuSelesaiList = KunjunganWaktuSelesai::whereIn('notrans', $noTransList)->get()->keyBy('notrans');
            // dd($waktuSelesaiList);
            $kasirList  = KasirTransModel::whereIn('notrans', $noTransList)->get()->keyBy('notrans');
            $obatList   = KasirAddModel::whereIn('notrans', $noTransList)->where('idLayanan', '2')->get()->groupBy('notrans');
            $daftarList = KunjunganModel::whereIn('norm', $normList)->get()->groupBy('norm');

            // Tambahkan status-status ke setiap item
            $data['data'] = $list->map(function ($d) use ($waktuSelesaiList, $kasirList, $obatList, $daftarList) {
                $no_trans = $d['no_reg'] ?? null;
                $norm     = $d['pasien_no_rm'] ?? null;
                $check    = $waktuSelesaiList[$no_trans] ?? null;

                return array_merge($d, [
                    'check_in'     => $check && $check->waktu_selesai_rm ? 'success' : 'danger',
                    'igd_selesai'  => $check && $check->waktu_selesai_igd ? 'success' : 'danger',
                    'status_kasir' => $kasirList->has($no_trans) ? 'Sudah' : 'Belum',
                    'status_obat'  => $obatList->has($no_trans) ? 'Sudah' : 'Belum',
                    'statusDaftar' => isset($daftarList[$norm]) ? 'lime' : 'warning',
                    'konsul_ro'    => $check && $check->konsul_ro == 1 ? 'success' : 'danger',
                ]);
            })->values()->toArray();

            return $data;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Request Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghubungi server.'], 500);
        } catch (\Exception $e) {
            Log::error('Unexpected Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan yang tidak terduga.'], 500);
        }
    }

    public function getDetailSEP($no_sep)
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

        $url = env('BASR_URL_KOMINFO', '') . '/sep/lihat_detail_sep/' . $no_sep;

        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
            ]);

            // Check if response is successful
            if ($response->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }

            $body = (string) $response->getBody();
            $data = json_decode($body, true);
            return $data;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Request Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghubungi server.'], 500);
        } catch (\Exception $e) {
            Log::error('Unexpected Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan yang tidak terduga.'], 500);
        }
    }

    public function getDataSuratKontrol($params)
    {
        $client = new Client();
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;
        $tglSep = $params["tanggal_awal"] . ' - ' . $params["tanggal_akhir"];

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

        $url = env('BASR_URL_KOMINFO', '') . '/sep/get_dataSuratKontrol';

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
                    'draw'                    => 2,
                    'columns'                 => $columns,
                    'start'                   => 0,
                    'length'                  => 100,
                    'search'                  => [
                        'value' => '',
                        'regex' => false,
                    ],
                    'tanggal'                 => '',
                    'antrean_nomor'           => '',
                    'no_reg'                  => '',
                    'daftar_by'               => '',
                    'penjamin_id'             => 2,
                    'nomor_referensi'         => '',
                    'penjamin_nomor'          => '',
                    'jenis_kunjungan_id'      => '',
                    'pasien'                  => '',
                    'pasien_nik'              => '',
                    'no_surat_kontrol'        => '',
                    'tanggal_rencana_kontrol' => $tglSep,
                ],
            ]);

            // Check if response is successful
            if ($response->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }

            $body = (string) $response->getBody();
            $data = json_decode($body, true);
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

    public function getDetailSuratKontrol($id)
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

        $url = env('BASR_URL_KOMINFO', '') . '/sep/lihat_detail_surat_kontrol/' . $id;

        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'       => $cookie,
                ],
            ]);

            // Check if response is successful
            if ($response->getStatusCode() !== 200) {
                Log::error('Error response body: ' . (string) $response->getBody());
                return response()->json(['error' => 'Internal Server Error'], 500);
            }

            $body = (string) $response->getBody();
            $data = json_decode($body, true);
            return $data;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Request Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghubungi server.'], 500);
        } catch (\Exception $e) {
            Log::error('Unexpected Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan yang tidak terduga.'], 500);
        }
    }

    public function reportPendaftaran($request)
    {

        // dd($params);
        $tglAwal  = $request['tanggal_awal'] ?? Carbon::now()->format('Y-m-d');
        $tglAkhir = $request['tanggal_akhir'] ?? Carbon::now()->format('Y-m-d');
        $params   = [
            'tanggal_awal'  => $tglAwal,
            'tanggal_akhir' => $tglAkhir,
        ];

        $dataPendaftaranResponse = $this->pendaftaranRequest($params);

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
        $jumlahBPJS2 = count(array_filter($filteredData, function ($item) {
            return isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS PERIODE 2';
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
            'jumlah_no_antrian'   => (int) count($dataPendaftaranResponse),
            'jumlah_no_menunggu'  => (int) $jumlahTunggu,
            'jumlah_pasien'       => (int) count($filteredData),
            'jumlah_pasien_batal' => (int) $jumlahBatal,
            'jumlah_nomor_skip'   => (int) $jumlahSkip,
            'jumlah_BPJS'         => (int) $jumlahBPJS,
            'jumlah_BPJS_2'       => (int) $jumlahBPJS2,
            'jumlah_UMUM'         => (int) $jumlahUMUM,
            'jumlah_pasien_LAMA'  => (int) $jumlahLama,
            'jumlah_pasien_BARU'  => (int) $jumlahBaru,
            'jumlah_daftar_OTS'   => (int) $jumlahOTS,
            'jumlah_daftar_JKN'   => (int) $jumlahJKN,
        ];

        $data = array_values($filteredData);

        $res = [
            "total" => $jumlah,
            "data"  => $data,
        ];

        return response()->json($res);
    }
}
