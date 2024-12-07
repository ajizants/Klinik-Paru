<?php

namespace App\Models;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KominfoModel extends Model
{
    protected $table = 'm_pasien_kominfo';

    public function pendaftaranRequest(array $params)
    {
        // Inisialisasi klien GuzzleHTTP
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
            // dd($data);
            // Periksa apakah data berhasil di-decode menjadi array
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Error decoding JSON response: ' . json_last_error_msg());
            }

            $res = array_map(function ($d) {
                $statusPulang = !is_null($d["ruang_poli_selesai_waktu"]) ? "Sudah Pulang" : "Belum Pulang";
                $alamat = $d['kelurahan_nama'] . ', ' .
                    $d['pasien_rt'] . '/' .
                    $d['pasien_rw'] . ', ' .
                    $d['kecamatan_nama'] . ', ' .
                    $d['kabupaten_nama'];
                $alamatMin = $d['kelurahan_nama'] . ', ' .
                    $d['kecamatan_nama'];
                $check = KunjunganWaktuSelesai::where('notrans', $d['no_trans'])->first();
                // jika $check null
                $checkRm = $check->waktu_selesai_rm ?? null;
                $igd = $check->waktu_selesai_igd ?? null;

                $checkIn = $checkRm == null ? 'danger' : 'success';
                $noSep = $check->no_sep ?? "";
                $checkInIGD = $igd == null ? 'danger' : 'success';

                return [
                    "check_in" => $checkIn,
                    "igd_selesai" => $checkInIGD,
                    "status_pulang" => $statusPulang,
                    "no_sep" => $noSep,
                    "no_reg" => $d["no_reg"] ?? 0,
                    "id" => $d["id"] ?? 0,
                    "no_trans" => $d["no_trans"] ?? 0,
                    "antrean_nomor" => $d["antrean_nomor"] ?? 0,
                    "tanggal" => $d["tanggal"] ?? 0,
                    "penjamin_nama" => $d["penjamin_nama"] ?? 0,
                    "penjamin_nomor" => $d["penjamin_nomor"] ?? 0,
                    "jenis_kunjungan_nama" => $d["jenis_kunjungan_nama"] ?? 0,
                    "nomor_referensi" => $d["nomor_referensi"] ?? 0,
                    "pasien_nik" => $d["pasien_nik"] ?? 0,
                    "pasien_nama" => $d["pasien_nama"] ?? 0,
                    "pasien_no_rm" => $d["pasien_no_rm"] ?? 0,
                    "pasien_tgl_lahir" => $d["pasien_tgl_lahir"] ?? 0,
                    "jenis_kelamin_nama" => $d["jenis_kelamin_nama"] ?? 0,
                    "pasien_lama_baru" => $d["pasien_lama_baru"] ?? 0,
                    "rs_paru_pasien_lama_baru" => $d["rs_paru_pasien_lama_baru"] ?? 0,
                    "poli_nama" => $d["poli_nama"] ?? 0,
                    "poli_sub_nama" => $d["poli_sub_nama"] ?? 0,
                    "dokter_nama" => $d["dokter_nama"] ?? 0,
                    "daftar_by" => $d["daftar_by"] ?? 0,
                    "waktu_daftar" => $d["waktu_daftar"] ?? 0,
                    "waktu_verifikasi" => $d["waktu_verifikasi"] ?? 0,
                    "admin_pendaftaran" => $d["admin_pendaftaran"] ?? 0,
                    "log_id" => $d["log_id"] ?? 0,
                    "keterangan" => $d["keterangan"] ?? 0,
                    "keterangan_urutan" => $d["keterangan_urutan"] ?? 0,
                    "pasien_umur" => ($d["pasien_umur_tahun"] ?? 0) . " Thn " . ($d["pasien_umur_bulan"] ?? 0) . " Bln ",
                    "pasien_umur_tahun" => $d["pasien_umur_tahun"] ?? 0,
                    "pasien_umur_bulan" => $d["pasien_umur_bulan"] ?? 0,
                    "pasien_umur_hari" => $d["pasien_umur_hari"] ?? 0,
                    "pasien_alamat" => $alamat ?? 0,
                    "pasien_alamat_min" => $alamatMin ?? 0,
                ];
            }, $data['response']['data']);

            $no_rm = $params['no_rm'];
            if (!empty($no_rm)) {
                // dd($no_rm);
                // Filter data berdasarkan no_rm
                $res = array_filter($res, function ($d) use ($no_rm) {
                    return $d['pasien_no_rm'] === $no_rm;
                });
            }

            $res = array_values($res); // Re-index the array

            // dd($res);
            return $res;
        } catch (\Exception $e) {
            // Tangani kesalahan
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
            return $data;
        } catch (\Exception $e) {
            // Tangani kesalahan
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
                'auth' => [$username, $password],
                'form_params' => $params,
                'headers' => [
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

                // Update the 'data' key with the filtered data
                $data['response']['data'] = $filteredData;
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
                'timeout' => 200,
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
                'timeout' => 200,
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
                    "pasien_nik" => $data['response']['data']['pasien_nik'],
                    "pasien_no_kk" => $data['response']['data']['pasien_no_kk'],
                    "pasien_nama" => $data['response']['data']['pasien_nama'],
                    "pasien_no_rm" => $data['response']['data']['pasien_no_rm'],
                    "jenis_kelamin_id" => $data['response']['data']['jenis_kelamin_id'],
                    "jenis_kelamin_nama" => $data['response']['data']['jenis_kelamin_nama'],
                    "pasien_tempat_lahir" => $data['response']['data']['pasien_tempat_lahir'],
                    "pasien_tgl_lahir" => $data['response']['data']['pasien_tgl_lahir'],
                    "pasien_no_hp" => $data['response']['data']['pasien_no_hp'],
                    "pasien_domisili" => $data['response']['data']['pasien_alamat'],
                    // "pasien_alamat" => ($data['response']['data']['kelurahan_nama']) . ', ' . ($data['response']['data']['pasien_rt']) . '/' . ($data['response']['data']['pasien_rw']) . ', ' . ($data['response']['data']['kecamatan_nama']) . ', ' . ($data['response']['data']['kabupaten_nama']) . ', ' . ($data['response']['data']['provinsi_nama']),
                    "pasien_alamat" => $alamat,
                    "pasien_kode_pos" => $data['response']['data']['pasien_kode_pos'],
                    "provinsi_id" => $data['response']['data']['provinsi_id'],
                    "provinsi_nama" => $data['response']['data']['provinsi_nama'],
                    "kabupaten_id" => $data['response']['data']['kabupaten_id'],
                    "kabupaten_nama" => $data['response']['data']['kabupaten_nama'],
                    "kecamatan_id" => $data['response']['data']['kecamatan_id'],
                    "kecamatan_nama" => $data['response']['data']['kecamatan_nama'],
                    "kelurahan_id" => $data['response']['data']['kelurahan_id'],
                    "kelurahan_nama" => $data['response']['data']['kelurahan_nama'],
                    "pasien_rt" => $data['response']['data']['pasien_rt'],
                    "pasien_rw" => $data['response']['data']['pasien_rw'],
                    "penjamin_id" => $data['response']['data']['penjamin_id'],
                    "penjamin_nama" => $data['response']['data']['penjamin_nama'],
                    "penjamin_nomor" => $data['response']['data']['penjamin_nomor'],
                    "agama_id" => $data['response']['data']['agama_id'],
                    "agama_nama" => $data['response']['data']['agama_nama'],
                    "rs_paru_agama_id" => $data['response']['data']['rs_paru_agama_id'],
                    "goldar_id" => $data['response']['data']['goldar_id'],
                    "goldar_nama" => $data['response']['data']['goldar_nama'],
                    "status_kawin_id" => $data['response']['data']['status_kawin_id'],
                    "status_kawin_nama" => $data['response']['data']['status_kawin_nama'],
                    "rs_paru_status_kawin" => $data['response']['data']['rs_paru_status_kawin'],
                    "pendidikan_id" => $data['response']['data']['pendidikan_id'],
                    "pendidikan_nama" => $data['response']['data']['pendidikan_nama'],
                    "rs_paru_pendidikan_id" => $data['response']['data']['rs_paru_pendidikan_id'],
                    "pasien_daftar_by" => $data['response']['data']['pasien_daftar_by'],
                    "pasien_penanggung_jawab_nama" => $data['response']['data']['pasien_penanggung_jawab_nama'],
                    "pasien_penanggung_jawab_no_hp" => $data['response']['data']['pasien_penanggung_jawab_no_hp'],
                    "created_at" => $data['response']['data']['created_at'],
                    "created_at_tanggal" => $data['response']['data']['created_at_tanggal'],
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
                'timeout' => 200,
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

    public function waktuLayananRequest(array $params)
    {
        $client = new Client();
        $url = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/pendaftaran/data_pendaftaran';
        $username = env('API_USERNAME', '');
        $password = env('API_PASSWORD', '');
        // dd($params);

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
            $mentah = json_decode($body, true);
            $responseData = $mentah['response']['data'];

            // dd($mentah);
            // Filter data sesuai dengan kondisi
            if (!isset($params['no_rm']) || empty($params['no_rm'])) {
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
                $lama_daftar = max(0, round((strtotime($message["loket_pendaftaran_selesai_waktu"]) - strtotime($message["loket_pendaftaran_panggil_waktu"])) / 60, 2));

                $selesaiRm = KunjunganWaktuSelesai::where('norm', $message['pasien_no_rm'])->whereDate('waktu_selesai_rm', $message['tanggal'])->first();
                $Rmdata = $selesaiRm ? true : false;
                $waktuSelesaiIgd = 0;
                if (is_null($selesaiRm)) {
                    $waktuSelesaiRM = $message["loket_pendaftaran_selesai_waktu"];
                    $lamaSelesaiRM = 0;
                } else {
                    $waktuSelesaiRM = date('Y-m-d H:i:s', strtotime($selesaiRm->waktu_selesai_rm));
                    $lamaSelesaiRM = max(0, round((strtotime($selesaiRm->waktu_selesai_rm) - strtotime($message["loket_pendaftaran_panggil_waktu"])) / 60, 2));
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
                $lama_poli = max(0, round((strtotime($message["ruang_poli_selesai_waktu"]) - strtotime($message["ruang_poli_panggil_waktu"])) / 60, 2));
                // dd($tunggu_poli);
                // Tentukan waktu panggil farmasi
                $panggilFarmasi = isset($message["ruang_poli_selesai_waktu"])
                ? new DateTime($message["ruang_poli_selesai_waktu"])
                : new DateTime('0000-00-00 00:00:00');
                // Tambahkan 3 menit
                $panggilFarmasi->modify('+3 minutes');
                $panggilFarmasi = $panggilFarmasi->format('Y-m-d H:i:s');

                // Inisialisasi waktu tunggu lainnya
                $tunggu_igd = $tunggu_farmasi = $tunggu_kasir = 0;
                $statusPulang = !is_null($message["ruang_poli_selesai_waktu"]) ? "Sudah Pulang" : "Belum Pulang";
                $lama_pelayanan_tiap_pasien = !is_null($message["ruang_poli_selesai_waktu"]) ? max(0, round((strtotime($message["ruang_poli_selesai_waktu"]) - strtotime($message["loket_pendaftaran_selesai_waktu"])) / 60, 2)) : 0;

                $roData = ROTransaksiModel::where('norm', $message['pasien_no_rm'])
                    ->whereDate('tgltrans', $message['tanggal'])->first();
                $Rdata = $roData ? true : false;
                $selesaiRo = $panggilRo = $lama_ro = 0;
                if ($roData && $roData->created_at) {
                    $selesaiRo = date('Y-m-d H:i:s', strtotime($roData->updated_at));
                    $panggilRo = date('Y-m-d H:i:s', strtotime($roData->created_at));
                    $lama_ro = max(0, round((strtotime($roData->updated_at) - strtotime($roData->created_at)) / 60, 2));
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
                    $lama_lab = max(0, round((strtotime($selesaiLab) - strtotime($panggilLab)) / 60, 2));
                }

                $igdData = IGDTransModel::where('norm', $message['pasien_no_rm'])->whereDate('created_at', $message['tanggal'])->first();
                // dd($igdData);
                $igd = $igdData ? true : false;
                $panggilIgd = $selesaiIgd = $lama_igd = 0;
                // Periksa data IGD
                if ($igdData && $igdData->updated_at) {
                    $panggilIgd = date('Y-m-d H:i:s', strtotime($igdData->created_at));
                    $selesaiIgd = $waktuSelesaiIgd ?: date('Y-m-d H:i:s', strtotime($igdData->updated_at));
                    $lama_igd = max(0, round((strtotime($selesaiIgd) - strtotime($panggilIgd)) / 60, 2));
                    $panggilFarmasi = $selesaiIgd;
                }

                $oke = false;
                if ($Rdata || $Ldata || $igd) {
                    $oke = true;
                }

                // Menentukan waktu tunggu lab dan rontgen dan poli
                $selesaiTensi = strtotime($message['ruang_tensi_selesai_waktu']);
                $panggilPoli = strtotime($message['ruang_poli_panggil_waktu']) ?: null;
                $selesaiPoli = strtotime($message['ruang_poli_selesai_waktu']) ?: null;
                $panggilLabMat = strtotime($panggilLab);
                $panggilRoMat = strtotime($panggilRo);
                $selesaiLabMat = strtotime($selesaiLab);
                $selesaiRoMat = strtotime($selesaiRo);
                $tunggu_lab = $tunggu_ro = 0;

                if ($Ldata && $Rdata) {
                    $waktuTunggu = $this->urutan($panggilPoli, $panggilLabMat, $panggilRoMat, $selesaiTensi);
                    $tunggu_lab = $waktuTunggu['tunggu_lab'];
                    $tunggu_ro = $waktuTunggu['tunggu_ro'];
                    $tunggu_poli = $waktuTunggu['tunggu_poli'];

                } elseif ($Ldata) {
                    if (!is_null($panggilPoli) && $panggilPoli < $panggilLabMat) {
                        $tunggu_lab = max(0, round(($panggilLabMat - $selesaiPoli) / 60, 2));
                    } else {
                        $tunggu_lab = max(0, round(($panggilLabMat - $selesaiTensi) / 60, 2));
                        $tunggu_poli = max(0, round(($panggilPoli - $selesaiLabMat) / 60, 2));
                    }
                } elseif ($Rdata) {
                    if (strtotime($message['ruang_poli_panggil_waktu']) < $panggilRoMat) {
                        $tunggu_ro = max(0, round(($panggilRoMat - $selesaiTensi) / 60, 2));
                    } else {
                        $tunggu_ro = max(0, round(($panggilRoMat - $selesaiPoli) / 60, 2));
                        $tunggu_poli = max(0, round($panggilPoli - $selesaiRoMat) / 60, 2);
                    }
                }
                return [
                    "oke" => $oke,
                    "ro_kominfo" => !is_null($message["ruang_rontgen_panggil_waktu"]),
                    "lab_kominfo" => !is_null($message["ruang_laboratorium_panggil_waktu"]),

                    "rm" => $Rmdata,
                    "rodata" => $Rdata,
                    "labdata" => $Ldata,
                    "igddata" => $igd,

                    "lama_pelayanan_pasien" => $lama_pelayanan_tiap_pasien,
                    "no_reg" => $message["no_reg"] ?? 0,
                    "no_trans" => $message["no_trans"] ?? 0,
                    "daftar_by" => $message["daftar_by"] ?? 0,
                    "antrean_nomor" => $message["antrean_nomor"] ?? 0,
                    "tanggal" => $message["tanggal"] ?? 0,
                    "penjamin_nama" => $message["penjamin_nama"] ?? 0,
                    "status_pasien" => $message["pasien_lama_baru"] ?? 0,
                    "pasien_no_rm" => $message["pasien_no_rm"] ?? 0,
                    "pasien_nama" => $message["pasien_nama"] ?? 0,
                    'pasien_umur' => ($message["pasien_umur_tahun"] ?? 0) . " Thn " . ($message["pasien_umur_bulan"] ?? 0) . " Bln ",
                    "jenis_kelamin" => $message["jenis_kelamin_nama"] ?? 0,
                    "poli_nama" => $message["poli_nama"] ?? 0,
                    "dokter_nama" => $message["dokter_nama"] ?? 0,

                    "waktu_daftar" => $message["waktu_daftar"] ?? 0,
                    "ambil_no" => $message["loket_pendaftaran_menunggu_waktu"] ?? 0,
                    "mulai_panggil" => $mulaiPanggil ?? 0,

                    "status_pulang" => $statusPulang,

                    "tunggu_daftar" => $tunggu_panggil_daftar ?? 0,
                    "pendaftaran_panggil" => $message["loket_pendaftaran_panggil_waktu"] ?? 0,
                    "pendaftaran_skip" => $message["loket_pendaftaran_skip_waktu"] ?? 0,
                    "pendaftaran_selesai" => $message["loket_pendaftaran_selesai_waktu"] ?? 0,
                    "lama_pendaftaran" => $lama_daftar ?? 0,
                    "waktu_selesai_rm" => $waktuSelesaiRM ?? 0,
                    "tunggu_rm" => $lamaSelesaiRM ?? 0,

                    "tunggu_tensi" => $tunggu_tensi ?? 0,
                    "tensi_panggil" => $message["ruang_tensi_panggil_waktu"] ?? 0,
                    "tensi_skip" => $message["ruang_tensi_skip_waktu"] ?? 0,
                    "tensi_selesai" => $message["ruang_tensi_selesai_waktu"] ?? 0,
                    "lama_tensi" => $lama_tensi ?? 0,

                    "durasi_poli" => $durasi_poli ?? 0,
                    "tunggu_poli" => $tunggu_poli ?? 0,
                    "poli_panggil" => $message["ruang_poli_panggil_waktu"] ?? 0,
                    "poli_skip" => $message["ruang_poli_skip_waktu"] ?? 0,
                    "poli_selesai" => $message["ruang_poli_selesai_waktu"] ?? 0,
                    "lama_poli" => $lama_poli ?? 0,

                    "tunggu_lab" => $tunggu_lab ?? 0,
                    "laboratorium_panggil" => $panggilLab ?? $message["ruang_laboratorium_panggil_waktu"] ?? 0,
                    "laboratorium_skip" => $message["ruang_laboratorium_skip_waktu"] ?? 0,
                    "laboratorium_selesai" => $selesaiLab ?? $message["ruang_laboratorium_selesai_waktu"] ?? 0,
                    "selesai_lab" => $selesaiLab,
                    "tunggu_hasil_lab" => $lama_lab,

                    "tunggu_ro" => $tunggu_ro ?? 0,
                    "rontgen_panggil" => $panggilRo ?? $message["ruang_rontgen_panggil_waktu"] ?? 0,
                    "rontgen_skip" => $message["ruang_rontgen_skip_waktu"] ?? 0,
                    "rontgen_selesai" => $selesaiRo ?? $message["ruang_rontgen_selesai_waktu"] ?? 0,
                    "selesai_ro" => $selesaiRo,
                    "tunggu_hasil_ro" => $lama_ro,

                    "tunggu_igd" => $tunggu_igd ?? 0,
                    "igd_panggil" => $panggilIgd ?? $message["ruang_igd_panggil_waktu"] ?? $panggilIgd,
                    "igd_skip" => $selesaiIgd ?? $message["ruang_igd_skip_waktu"] ?? 0,
                    "igd_selesai" => $message["ruang_igd_selesai_waktu"] ?? $selesaiIgd,
                    "lama_igd" => $lama_igd,

                    "tunggu_kasir" => $tunggu_kasir ?? 0,
                    "kasir_panggil" => $message["loket_kasir_panggil_waktu"] ?? 0,
                    "kasir_skip" => $message["loket_kasir_skip_waktu"] ?? 0,
                    "kasir_selesai" => $message["loket_kasir_selesai_waktu"] ?? 0,

                    "tunggu_farmasi" => $tunggu_farmasi ?? 0,
                    "farmasi_panggil" => $message["loket_farmasi_panggil_waktu"] ?? $panggilFarmasi,
                    "farmasi_skip" => $message["loket_farmasi_skip_waktu"] ?? 0,
                    "farmasi_selesai" => $message["loket_farmasi_selesai_waktu"] ?? 0,
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
            'panggilPoli' => $panggilPoli,
            'panggilLabMat' => $panggilLabMat,
            'panggilRoMat' => $panggilRoMat,
        ];

        // // Filter array untuk menghapus nilai yang null, kosong, atau 0
        $waktuArray = array_filter($waktuArray, function ($value) {
            return !is_null($value) && $value !== '' && $value !== 0;
        });

        // Urutkan array berdasarkan waktu
        asort($waktuArray);

        // Ambil urutan waktu dan kunci
        $waktuUrut = array_values($waktuArray);
        $kunciUrut = array_keys($waktuArray);

        // dd($waktuUrut, $kunciUrut, $selesaiTensi);

        // Tentukan waktu yang lebih awal dan selisih antar waktu
        $waktuPertama = $kunciUrut[0];
        $waktuKedua = $kunciUrut[1];
        // $waktuKetiga = $kunciUrut[2];

        $selisih0_1 = max(0, round(($waktuUrut[0] - $selesaiTensi) / 60, 2));
        $selisih1_2 = max(0, round(($waktuUrut[1] - $waktuUrut[0]) / 60, 2));
        if (count($waktuArray) > 2) {
            // Mengambil waktu urut yang sudah diurutkan
            $waktuUrut = array_values($waktuArray); // Mengambil nilai dari array yang sudah diurutkan
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
                $tunggu_ro = $selisih2_3;
            } elseif ($waktuKedua == 'panggilRoMat') {
                // dd("waktu kedua ro");
                $tunggu_ro = $selisih1_2;
                $tunggu_lab = $selisih2_3;
            }
        } elseif ($waktuPertama == 'panggilLabMat') {
            // dd("waktu pertama lab");
            $tunggu_lab = $selisih0_1;
            if ($waktuKedua == 'panggilRoMat') {
                $tunggu_ro = $selisih1_2;
                $tunggu_poli = $selisih2_3;
            } elseif ($waktuKedua == 'panggilPoli') {
                $tunggu_poli = $selisih1_2;
                $tunggu_ro = $selisih2_3;
                $tunggu_lab = $selisih2_3;
            }
        } else {
            // dd("waktu pertama ro");
            $tunggu_ro = $selisih0_1;
            if ($waktuKedua == 'panggilLabMat') {
                $tunggu_lab = $selisih1_2;
                $tunggu_poli = $selisih2_3;
            } elseif ($waktuKedua == 'panggilPoli') {
                $tunggu_poli = $selisih1_2;
                $tunggu_lab = $selisih2_3;
            }
        }

        return [
            'tunggu_poli' => $tunggu_poli,
            'tunggu_lab' => $tunggu_lab,
            'tunggu_ro' => $tunggu_ro,
        ];
    }

    public function login()
    {
        // dd("masuk");
        $username = env('USERNAME_KOMINFO', '');
        $password = env('PASSWORD_KOMINFO', '');
        $client = new Client();
        $url = env('BASR_URL_KOMINFO', '') . '/auth/login';

        $response = $client->request('POST', $url, [
            'form_params' => [
                'admin_username' => $username,
                'admin_password' => $password,
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);
        if ($response->getStatusCode() != 200) {
            return [
                'data' => json_decode($response->getBody(), true),
                'cookies' => [],
            ];
        }

        // Ambil cookie dari header respons
        $cookies = $response->getHeader('Set-Cookie');

        // Mengambil body response untuk memastikan login berhasil
        $body = $response->getBody();
        $data = json_decode($body, true);

        if (isset($cookies[0])) {
            // Set cookie di browser
            setcookie('kominfo_cookie', $cookies[0], time() + (86400 * 30), "/"); // Cookie akan kedaluwarsa dalam 30 hari
        }

        return [
            'data' => json_decode($response->getBody(), true),
            'cookies' => $cookies,
        ];
    }

    public function get_data_antrian(array $data, $pasien_no_rm = null)
    {
        $client = new Client();
        $url = env('BASR_URL_KOMINFO', '') . '/loket_pendaftaran/get_data';

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
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie' => $data['cookie'],
            ],
        ]);

        $body = $response->getBody();
        $data = json_decode($body, true);
        return $data;
    }
    // public function get_data_antrian(array $data, $antrean_nomor = null)
    // {
    //     $client = new Client();
    //     $url = env('BASR_URL_KOMINFO', '') . '/loket_pendaftaran/get_data';

    //     // Persiapkan form_params dengan parameter length dan antrean_nomor
    //     $form_params = [
    //         'length' => 1000, // Menambahkan parameter length dengan nilai 1000
    //     ];

    //     // Tambahkan antrean_nomor jika ada
    //     if ($antrean_nomor !== null) {
    //         $form_params['antrean_nomor'] = $antrean_nomor; // Menambahkan filter antrean_nomor
    //     }

    //     $response = $client->request('POST', $url, [
    //         'form_params' => $form_params,
    //         'headers' => [
    //             'Content-Type' => 'application/x-www-form-urlencoded',
    //             'Cookie' => $data['cookie'],
    //         ],
    //     ]);

    //     $body = $response->getBody();
    //     $data = json_decode($body, true);
    //     return $data;
    // }

    public function panggil(array $data, $log_id = null)
    {
        $client = new Client();
        $url = env('BASR_URL_KOMINFO', '') . '/loket_pendaftaran/panggil';

        $response = $client->request('POST', $url, [
            'form_params' => [
                'log_id' => $log_id,
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie' => $data['cookie'],
            ],
        ]);
        $body = $response->getBody();
        $data = json_decode($body, true);
        return $data;
    }
    public function getDataByRM(array $data, $pasien_no_rm = null)
    {
        $client = new Client();
        $url = env('BASR_URL_KOMINFO', '') . '/data_pasien/getDataByRM';

        $response = $client->request('POST', $url, [
            'form_params' => [
                'pasien_no_rm' => $pasien_no_rm,
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie' => $data['cookie'],
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
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie' => $data['cookie'],
            ],
        ]);

        $body = $response->getBody();
        $responseData = json_decode($body, true);

        return $responseData;

    }
    public function getDokterBefore(array $data, $pasien_id = null)
    {
        $client = new Client();
        $url = env('BASR_URL_KOMINFO', '') . '/loket_pendaftaran/kunjunganDokterSebelumnya';

        $response = $client->request('POST', $url, [
            'form_params' => [
                'pasien_id' => $pasien_id,
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie' => $data['cookie'],
            ],
        ]);

        $body = $response->getBody();
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

        if (!$cookie) {
            // Authenticate if no cookie is found
            $loginResponse = $this->login();
            $cookie = $loginResponse['cookies'][0] ?? null;

            if ($cookie) {
                setcookie('kominfo_cookie', $cookie, time() + (86400 * 30), "/"); // Set cookie in the browser
            } else {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }

        $url = env('BASR_URL_KOMINFO', '') . '/ruang_tensi/get_data?id_ruang_tensi=2';

        try {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'id_ruang_tensi' => 2,
                    'length' => 1000,
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie' => $cookie,
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
    public function getTungguFaramsi()
    {
        $client = new Client();
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;
        $tgl = date('Y-m-d');
        $tanggal = $tgl . ' - ' . $tgl;

        if (!$cookie) {
            // Authenticate if no cookie is found
            $loginResponse = $this->login();
            $cookie = $loginResponse['cookies'][0] ?? null;

            if ($cookie) {
                setcookie('kominfo_cookie', $cookie, time() + (86400 * 30), "/"); // Set cookie in the browser
            } else {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }

        $url = env('BASR_URL_KOMINFO', '') . '/ruang_poli/get_data?poli_sub_id=1';

        try {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'tanggal' => $tanggal,
                    'length' => 1000,
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie' => $cookie,
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
    public function getTungguLoket()
    {
        $client = new Client();
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;
        $tgl = date('Y-m-d');
        $tanggal = $tgl . ' - ' . $tgl;

        if (!$cookie) {
            // Authenticate if no cookie is found
            $loginResponse = $this->login();
            $cookie = $loginResponse['cookies'][0] ?? null;

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
                    'tanggal' => $tanggal,
                    'length' => 1000,
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie' => $cookie,
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

    public function jadwalPoli(array $params)
    {
        $jadwal = [
            [
                "id" => "2",
                "admin_id" => "8",
                "admin_nama" => "dr. AGIL DANANJAYA, Sp.P",
                "admin_kode_bpjs" => "87139",
                "poli_id" => "1",
                "poli_nama" => "PARU",
                "poli_sub_id" => "1",
                "poli_sub_nama" => "PARU",
                "no_hari" => "1",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1715472900",
                "waktu_selesai_poli" => "1715493600",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1715472300",
                "kuota_pendaftaran_ots" => "50",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "40",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 08 November 2023",
                "nama_hari" => "Senin",
                "waktu_mulai_poli_tampil" => "07=>15=>00",
                "waktu_selesai_poli_tampil" => "13=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "3",
                "admin_id" => "8",
                "admin_nama" => "dr. AGIL DANANJAYA, Sp.P",
                "admin_kode_bpjs" => "87139",
                "poli_id" => "1",
                "poli_nama" => "PARU",
                "poli_sub_id" => "1",
                "poli_sub_nama" => "PARU",
                "no_hari" => "2",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1715472900",
                "waktu_selesai_poli" => "1715493600",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1715472300",
                "kuota_pendaftaran_ots" => "50",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "40",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 08 November 2023",
                "nama_hari" => "Selasa",
                "waktu_mulai_poli_tampil" => "07=>15=>00",
                "waktu_selesai_poli_tampil" => "13=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "4",
                "admin_id" => "8",
                "admin_nama" => "dr. AGIL DANANJAYA, Sp.P",
                "admin_kode_bpjs" => "87139",
                "poli_id" => "1",
                "poli_nama" => "PARU",
                "poli_sub_id" => "1",
                "poli_sub_nama" => "PARU",
                "no_hari" => "3",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1715472900",
                "waktu_selesai_poli" => "1715493600",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1715472300",
                "kuota_pendaftaran_ots" => "50",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "40",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 08 November 2023",
                "nama_hari" => "Rabu",
                "waktu_mulai_poli_tampil" => "07=>15=>00",
                "waktu_selesai_poli_tampil" => "13=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "5",
                "admin_id" => "8",
                "admin_nama" => "dr. AGIL DANANJAYA, Sp.P",
                "admin_kode_bpjs" => "87139",
                "poli_id" => "1",
                "poli_nama" => "PARU",
                "poli_sub_id" => "1",
                "poli_sub_nama" => "PARU",
                "no_hari" => "4",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1715472900",
                "waktu_selesai_poli" => "1715493600",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1715472300",
                "kuota_pendaftaran_ots" => "50",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "40",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 08 November 2023",
                "nama_hari" => "Kamis",
                "waktu_mulai_poli_tampil" => "07=>15=>00",
                "waktu_selesai_poli_tampil" => "13=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "6",
                "admin_id" => "8",
                "admin_nama" => "dr. AGIL DANANJAYA, Sp.P",
                "admin_kode_bpjs" => "87139",
                "poli_id" => "1",
                "poli_nama" => "PARU",
                "poli_sub_id" => "1",
                "poli_sub_nama" => "PARU",
                "no_hari" => "5",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1715472900",
                "waktu_selesai_poli" => "1715530500",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1715472300",
                "kuota_pendaftaran_ots" => "50",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "40",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 08 November 2023",
                "nama_hari" => "Jumat",
                "waktu_mulai_poli_tampil" => "07=>15=>00",
                "waktu_selesai_poli_tampil" => "23=>15=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "7",
                "admin_id" => "8",
                "admin_nama" => "dr. AGIL DANANJAYA, Sp.P",
                "admin_kode_bpjs" => "87139",
                "poli_id" => "1",
                "poli_nama" => "PARU",
                "poli_sub_id" => "1",
                "poli_sub_nama" => "PARU",
                "no_hari" => "6",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1715472900",
                "waktu_selesai_poli" => "1715492700",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1715472300",
                "kuota_pendaftaran_ots" => "50",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "40",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 08 November 2023",
                "nama_hari" => "Sabtu",
                "waktu_mulai_poli_tampil" => "07=>15=>00",
                "waktu_selesai_poli_tampil" => "12=>45=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "8",
                "admin_id" => "7",
                "admin_nama" => "dr. Cempaka Nova Intani, Sp.P, FISR., MM.",
                "admin_kode_bpjs" => "20169",
                "poli_id" => "1",
                "poli_nama" => "PARU",
                "poli_sub_id" => "1",
                "poli_sub_nama" => "PARU",
                "no_hari" => "1",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1715472900",
                "waktu_selesai_poli" => "1715490900",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1715472300",
                "kuota_pendaftaran_ots" => "50",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "40",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 08 November 2023",
                "nama_hari" => "Senin",
                "waktu_mulai_poli_tampil" => "07=>15=>00",
                "waktu_selesai_poli_tampil" => "12=>15=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "9",
                "admin_id" => "7",
                "admin_nama" => "dr. Cempaka Nova Intani, Sp.P, FISR., MM.",
                "admin_kode_bpjs" => "20169",
                "poli_id" => "1",
                "poli_nama" => "PARU",
                "poli_sub_id" => "1",
                "poli_sub_nama" => "PARU",
                "no_hari" => "2",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1715472900",
                "waktu_selesai_poli" => "1715490900",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1715472300",
                "kuota_pendaftaran_ots" => "50",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "40",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 08 November 2023",
                "nama_hari" => "Selasa",
                "waktu_mulai_poli_tampil" => "07=>15=>00",
                "waktu_selesai_poli_tampil" => "12=>15=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "10",
                "admin_id" => "7",
                "admin_nama" => "dr. Cempaka Nova Intani, Sp.P, FISR., MM.",
                "admin_kode_bpjs" => "20169",
                "poli_id" => "1",
                "poli_nama" => "PARU",
                "poli_sub_id" => "1",
                "poli_sub_nama" => "PARU",
                "no_hari" => "3",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1715472000",
                "waktu_selesai_poli" => "1715490900",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1715472300",
                "kuota_pendaftaran_ots" => "50",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "40",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 08 November 2023",
                "nama_hari" => "Rabu",
                "waktu_mulai_poli_tampil" => "07=>00=>00",
                "waktu_selesai_poli_tampil" => "12=>15=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "11",
                "admin_id" => "7",
                "admin_nama" => "dr. Cempaka Nova Intani, Sp.P, FISR., MM.",
                "admin_kode_bpjs" => "20169",
                "poli_id" => "1",
                "poli_nama" => "PARU",
                "poli_sub_id" => "1",
                "poli_sub_nama" => "PARU",
                "no_hari" => "4",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1715472000",
                "waktu_selesai_poli" => "1715490900",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1715472300",
                "kuota_pendaftaran_ots" => "50",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "40",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 08 November 2023",
                "nama_hari" => "Kamis",
                "waktu_mulai_poli_tampil" => "07=>00=>00",
                "waktu_selesai_poli_tampil" => "12=>15=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "12",
                "admin_id" => "7",
                "admin_nama" => "dr. Cempaka Nova Intani, Sp.P, FISR., MM.",
                "admin_kode_bpjs" => "20169",
                "poli_id" => "1",
                "poli_nama" => "PARU",
                "poli_sub_id" => "1",
                "poli_sub_nama" => "PARU",
                "no_hari" => "5",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1715472000",
                "waktu_selesai_poli" => "1715487300",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1715472300",
                "kuota_pendaftaran_ots" => "50",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "40",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 08 November 2023",
                "nama_hari" => "Jumat",
                "waktu_mulai_poli_tampil" => "07=>00=>00",
                "waktu_selesai_poli_tampil" => "11=>15=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "13",
                "admin_id" => "7",
                "admin_nama" => "dr. Cempaka Nova Intani, Sp.P, FISR., MM.",
                "admin_kode_bpjs" => "20169",
                "poli_id" => "1",
                "poli_nama" => "PARU",
                "poli_sub_id" => "1",
                "poli_sub_nama" => "PARU",
                "no_hari" => "6",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1715472000",
                "waktu_selesai_poli" => "1715492700",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1715472300",
                "kuota_pendaftaran_ots" => "50",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "40",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 08 November 2023",
                "nama_hari" => "Sabtu",
                "waktu_mulai_poli_tampil" => "07=>00=>00",
                "waktu_selesai_poli_tampil" => "12=>45=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "39",
                "admin_id" => "15",
                "admin_nama" => "dr. FILLY ULFA KUSUMAWARDANI",
                "admin_kode_bpjs" => "",
                "poli_id" => "4",
                "poli_nama" => "UMUM",
                "poli_sub_id" => "7",
                "poli_sub_nama" => "UMUM",
                "no_hari" => "1",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1705622400",
                "waktu_selesai_poli" => "1705640400",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1705622700",
                "kuota_pendaftaran_ots" => "100",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "100",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 13 Desember 2023",
                "nama_hari" => "Senin",
                "waktu_mulai_poli_tampil" => "07=>00=>00",
                "waktu_selesai_poli_tampil" => "12=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "40",
                "admin_id" => "15",
                "admin_nama" => "dr. FILLY ULFA KUSUMAWARDANI",
                "admin_kode_bpjs" => "",
                "poli_id" => "4",
                "poli_nama" => "UMUM",
                "poli_sub_id" => "7",
                "poli_sub_nama" => "UMUM",
                "no_hari" => "2",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1705622400",
                "waktu_selesai_poli" => "1705640400",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1705622700",
                "kuota_pendaftaran_ots" => "100",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "100",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 13 Desember 2023",
                "nama_hari" => "Selasa",
                "waktu_mulai_poli_tampil" => "07=>00=>00",
                "waktu_selesai_poli_tampil" => "12=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "41",
                "admin_id" => "15",
                "admin_nama" => "dr. FILLY ULFA KUSUMAWARDANI",
                "admin_kode_bpjs" => "",
                "poli_id" => "4",
                "poli_nama" => "UMUM",
                "poli_sub_id" => "7",
                "poli_sub_nama" => "UMUM",
                "no_hari" => "3",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1702429200",
                "waktu_selesai_poli" => "1702443600",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1702425900",
                "kuota_pendaftaran_ots" => "100",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "100",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 13 Desember 2023",
                "nama_hari" => "Rabu",
                "waktu_mulai_poli_tampil" => "08=>00=>00",
                "waktu_selesai_poli_tampil" => "12=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "42",
                "admin_id" => "15",
                "admin_nama" => "dr. FILLY ULFA KUSUMAWARDANI",
                "admin_kode_bpjs" => "",
                "poli_id" => "4",
                "poli_nama" => "UMUM",
                "poli_sub_id" => "7",
                "poli_sub_nama" => "UMUM",
                "no_hari" => "4",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1702429200",
                "waktu_selesai_poli" => "1702443600",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1702425900",
                "kuota_pendaftaran_ots" => "100",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "100",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 13 Desember 2023",
                "nama_hari" => "Kamis",
                "waktu_mulai_poli_tampil" => "08=>00=>00",
                "waktu_selesai_poli_tampil" => "12=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "43",
                "admin_id" => "15",
                "admin_nama" => "dr. FILLY ULFA KUSUMAWARDANI",
                "admin_kode_bpjs" => "",
                "poli_id" => "4",
                "poli_nama" => "UMUM",
                "poli_sub_id" => "7",
                "poli_sub_nama" => "UMUM",
                "no_hari" => "5",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1702429200",
                "waktu_selesai_poli" => "1702443600",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1702425900",
                "kuota_pendaftaran_ots" => "100",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "100",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 13 Desember 2023",
                "nama_hari" => "Jumat",
                "waktu_mulai_poli_tampil" => "08=>00=>00",
                "waktu_selesai_poli_tampil" => "12=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "44",
                "admin_id" => "15",
                "admin_nama" => "dr. FILLY ULFA KUSUMAWARDANI",
                "admin_kode_bpjs" => "",
                "poli_id" => "4",
                "poli_nama" => "UMUM",
                "poli_sub_id" => "7",
                "poli_sub_nama" => "UMUM",
                "no_hari" => "6",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1705622400",
                "waktu_selesai_poli" => "1705640400",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1705622700",
                "kuota_pendaftaran_ots" => "100",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "100",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 13 Desember 2023",
                "nama_hari" => "Sabtu",
                "waktu_mulai_poli_tampil" => "07=>00=>00",
                "waktu_selesai_poli_tampil" => "12=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "57",
                "admin_id" => "15",
                "admin_nama" => "dr. FILLY ULFA KUSUMAWARDANI",
                "admin_kode_bpjs" => "",
                "poli_id" => "4",
                "poli_nama" => "UMUM",
                "poli_sub_id" => "7",
                "poli_sub_nama" => "UMUM",
                "no_hari" => "7",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1716727500",
                "waktu_selesai_poli" => "1716738300",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1716725100",
                "kuota_pendaftaran_ots" => "10",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "10",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Minggu, 26 Mei 2024",
                "nama_hari" => "Minggu",
                "waktu_mulai_poli_tampil" => "19=>45=>00",
                "waktu_selesai_poli_tampil" => "22=>45=>00",
                "waktu_mulai_pendaftaran_tampil" => "19=>05=>00",
            ],
            [
                "id" => "51",
                "admin_id" => "36",
                "admin_nama" => "dr. SIGIT DWIYANTO",
                "admin_kode_bpjs" => "",
                "poli_id" => "4",
                "poli_nama" => "UMUM",
                "poli_sub_id" => "7",
                "poli_sub_nama" => "UMUM",
                "no_hari" => "1",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1702429200",
                "waktu_selesai_poli" => "1702443600",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1702425900",
                "kuota_pendaftaran_ots" => "100",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "100",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 13 Desember 2023",
                "nama_hari" => "Senin",
                "waktu_mulai_poli_tampil" => "08=>00=>00",
                "waktu_selesai_poli_tampil" => "12=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "52",
                "admin_id" => "36",
                "admin_nama" => "dr. SIGIT DWIYANTO",
                "admin_kode_bpjs" => "",
                "poli_id" => "4",
                "poli_nama" => "UMUM",
                "poli_sub_id" => "7",
                "poli_sub_nama" => "UMUM",
                "no_hari" => "2",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1702429200",
                "waktu_selesai_poli" => "1702443600",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1702425900",
                "kuota_pendaftaran_ots" => "100",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "100",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 13 Desember 2023",
                "nama_hari" => "Selasa",
                "waktu_mulai_poli_tampil" => "08=>00=>00",
                "waktu_selesai_poli_tampil" => "12=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "53",
                "admin_id" => "36",
                "admin_nama" => "dr. SIGIT DWIYANTO",
                "admin_kode_bpjs" => "",
                "poli_id" => "4",
                "poli_nama" => "UMUM",
                "poli_sub_id" => "7",
                "poli_sub_nama" => "UMUM",
                "no_hari" => "3",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1702429200",
                "waktu_selesai_poli" => "1702443600",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1702425900",
                "kuota_pendaftaran_ots" => "100",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "100",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 13 Desember 2023",
                "nama_hari" => "Rabu",
                "waktu_mulai_poli_tampil" => "08=>00=>00",
                "waktu_selesai_poli_tampil" => "12=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "54",
                "admin_id" => "36",
                "admin_nama" => "dr. SIGIT DWIYANTO",
                "admin_kode_bpjs" => "",
                "poli_id" => "4",
                "poli_nama" => "UMUM",
                "poli_sub_id" => "7",
                "poli_sub_nama" => "UMUM",
                "no_hari" => "4",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1702429200",
                "waktu_selesai_poli" => "1702443600",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1702425900",
                "kuota_pendaftaran_ots" => "100",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "100",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 13 Desember 2023",
                "nama_hari" => "Kamis",
                "waktu_mulai_poli_tampil" => "08=>00=>00",
                "waktu_selesai_poli_tampil" => "12=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "55",
                "admin_id" => "36",
                "admin_nama" => "dr. SIGIT DWIYANTO",
                "admin_kode_bpjs" => "",
                "poli_id" => "4",
                "poli_nama" => "UMUM",
                "poli_sub_id" => "7",
                "poli_sub_nama" => "UMUM",
                "no_hari" => "5",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1702429200",
                "waktu_selesai_poli" => "1702443600",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1702425900",
                "kuota_pendaftaran_ots" => "100",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "100",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 13 Desember 2023",
                "nama_hari" => "Jumat",
                "waktu_mulai_poli_tampil" => "08=>00=>00",
                "waktu_selesai_poli_tampil" => "12=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
            [
                "id" => "56",
                "admin_id" => "36",
                "admin_nama" => "dr. SIGIT DWIYANTO",
                "admin_kode_bpjs" => "",
                "poli_id" => "4",
                "poli_nama" => "UMUM",
                "poli_sub_id" => "7",
                "poli_sub_nama" => "UMUM",
                "no_hari" => "6",
                "sesi_id" => "1",
                "sesi_nama" => "PAGI",
                "waktu_mulai_poli" => "1705622400",
                "waktu_selesai_poli" => "1705640400",
                "waktu_mulai_ambil_antrean" => null,
                "waktu_selesai_ambil_antrean" => null,
                "waktu_mulai_pendaftaran" => "1705622700",
                "kuota_pendaftaran_ots" => "100",
                "kuota_pendaftaran_web" => null,
                "kuota_pendaftaran_jkn" => "100",
                "estimasi_pelayanan_detik" => "300",
                "created_at" => "Rabu, 13 Desember 2023",
                "nama_hari" => "Sabtu",
                "waktu_mulai_poli_tampil" => "07=>00=>00",
                "waktu_selesai_poli_tampil" => "12=>00=>00",
                "waktu_mulai_pendaftaran_tampil" => "07=>05=>00",
            ],
        ];

        // return ($params);
        if (empty($params)) {
            return $jadwal;
        }
        $jadwal_terpilih = array_filter($jadwal, function ($item) use ($params) {
            return $item['no_hari'] == $params['no_hari'] && strpos($item['admin_nama'], $params['admin_nama']) !== false;
        });

        $jadwal_terpilih = array_values($jadwal_terpilih);

        return $jadwal_terpilih;

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
        if (!$params) {
            $tgl = date('Y-m-d');
            $tanggal = $tgl . ' - ' . $tgl;
        } else {
            $tgl_awal = $params['tgl_awal'];
            $tgl_akhir = $params['tgl_akhir'];
            $tanggal = $this->formatTanggal($tgl_awal) . ' - ' . $this->formatTanggal($tgl_akhir);
        }
        // return $tanggal;

        if (!$cookie) {
            // Authenticate if no cookie is found
            $loginResponse = $this->login();
            $cookie = $loginResponse['cookies'][0] ?? null;

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
                    'length' => 1000,
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie' => $cookie,
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
        if (!$params) {
            $tgl = date('Y-m-d');
            $tanggal = $tgl . ' - ' . $tgl;
        } else {
            $tgl_awal = $params['tgl_awal'];
            $tgl_akhir = $params['tgl_akhir'];
            $tanggal = $this->formatTanggal($tgl_awal) . ' - ' . $this->formatTanggal($tgl_akhir);
        }
        // return $tanggal;

        if (!$cookie) {
            // Authenticate if no cookie is found
            $loginResponse = $this->login();
            $cookie = $loginResponse['cookies'][0] ?? null;

            if ($cookie) {
                setcookie('kominfo_cookie', $cookie, time() + (86400 * 30), "/"); // Set cookie in the browser
            } else {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }

        $url = env('BASR_URL_KOMINFO', '') . '/ruang_poli/get_data?poli_sub_id=1';

        try {
            $response = $client->request('POST', $url, [
                'form_params' => [
                    'tanggal' => $tanggal,
                    'length' => 1000,
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie' => $cookie,
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

}
