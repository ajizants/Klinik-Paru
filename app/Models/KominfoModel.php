<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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
        $username = '3301010509940003';
        $password = '~@j1s@nt0sO#';
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

                $check = KunjunganWaktuSelesai::where('notrans', $d['no_trans'])->first();
                // jika $check null
                $checkIn = $check == null ? 'danger' : 'success';

                return [
                    "check_in" => $checkIn,
                    "status_pulang" => $statusPulang,
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
                ];
            }, $data['response']['data']);

            $no_rm = $params['no_rm'];
            if (!empty($no_rm)) {
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
    public function pasienRequestfull($no_rm)
    {
        // Inisialisasi klien GuzzleHTTP
        $client = new Client([
            'timeout' => 200, // timeout dalam detik
        ]);

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
        $username = '3301010509940003';
        $password = '~@j1s@nt0sO#';

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

    public function waktuLayananRequest(array $params)
    {
        $client = new Client();
        $url = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/pendaftaran/data_pendaftaran';
        $username = '3301010509940003';
        $password = '~@j1s@nt0sO#';
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

                // Menentukan waktu tunggu daftar
                // $tunggu_daftar = ($message["daftar_by"] == "JKN") ? 2 : max(0, round((strtotime($message["loket_pendaftaran_panggil_waktu"]) - strtotime($message["loket_pendaftaran_skip_waktu"] ?? $message["loket_pendaftaran_menunggu_waktu"])) / 60, 2));
                $tunggu_daftar = ($message["daftar_by"] == "JKN") ? 2 : max(0, round((strtotime($message["loket_pendaftaran_selesai_waktu"]) - strtotime($message["loket_pendaftaran_panggil_waktu"])) / 60, 2));

                // Menentukan waktu tunggu tensi
                $tunggu_tensi = max(0, round((strtotime($message["ruang_tensi_panggil_waktu"]) - strtotime($message["ruang_tensi_skip_waktu"] ?? $message["loket_pendaftaran_selesai_waktu"])) / 60, 2));

                // Menentukan durasi poli
                $durasi_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["loket_pendaftaran_selesai_waktu"])) / 60, 2));

                // Inisialisasi waktu tunggu lainnya
                $tunggu_igd = $tunggu_farmasi = $tunggu_kasir = 0;
                $statusPulang = !is_null($message["ruang_poli_selesai_waktu"]) ? "Sudah Pulang" : "Belum Pulang";
                $lama_pelayanan_tiap_pasien = !is_null($message["ruang_poli_selesai_waktu"]) ? max(0, round((strtotime($message["ruang_poli_selesai_waktu"]) - strtotime($message["loket_pendaftaran_selesai_waktu"])) / 60, 2)) : 0;

                // Menentukan waktu tunggu lab dan rontgen
                $pangLab = !is_null($message["ruang_laboratorium_panggil_waktu"]);
                $pangRo = !is_null($message["ruang_rontgen_panggil_waktu"]);

                if ($pangLab && $pangRo) {
                    $oke = "ada semua";
                    if (strtotime($message["ruang_laboratorium_panggil_waktu"]) < strtotime($message["ruang_rontgen_panggil_waktu"])) {
                        $tunggu_lab = max(0, round((strtotime($message["ruang_laboratorium_panggil_waktu"]) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
                        $tunggu_ro = max(0, round((strtotime($message["ruang_rontgen_panggil_waktu"]) - strtotime($message["ruang_laboratorium_selesai_waktu"])) / 60, 2));
                    } else {
                        $tunggu_ro = max(0, round((strtotime($message["ruang_rontgen_panggil_waktu"]) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
                        $tunggu_lab = max(0, round((strtotime($message["ruang_laboratorium_panggil_waktu"]) - strtotime($message["ruang_rontgen_selesai_waktu"])) / 60, 2));
                    }
                    $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_rontgen_panggil_waktu"])) / 60, 2));
                } elseif ($pangLab) {
                    $oke = "ada lab";
                    $tunggu_lab = max(0, round((strtotime($message["ruang_laboratorium_panggil_waktu"]) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
                    $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_laboratorium_panggil_waktu"])) / 60, 2));
                    $tunggu_ro = 0;
                } elseif ($pangRo) {
                    $oke = "ada ro";
                    $tunggu_ro = max(0, round((strtotime($message["ruang_rontgen_panggil_waktu"]) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
                    $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_rontgen_panggil_waktu"])) / 60, 2));
                    $tunggu_lab = 0;
                } else {
                    $oke = "kosong";
                    $tunggu_lab = $tunggu_ro = 0;
                    $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_poli_skip_waktu"] ?? $message["ruang_tensi_selesai_waktu"])) / 60, 2));
                }

                $roData = ROTransaksiModel::where('norm', $message['pasien_no_rm'])
                    ->whereDate('tgltrans', $message['tanggal'])->first();
                $Rdata = $roData ? true : false;
                if (is_null($roData)) {
                    $selesaiRo = 0;
                    $lama_ro = 0;
                    $panggilRo = 0;
                } elseif (is_null($roData->created_at)) {
                    $selesaiRo = 0;
                    $lama_ro = 0;
                    $panggilRo = 0;
                } else {
                    $selesaiRo = date('Y-m-d H:i:s', strtotime($roData->updated_at));
                    $panggilRo = date('Y-m-d H:i:s', strtotime($roData->created_at));
                    // $r = $message["ruang_rontgen_panggil_waktu"];
                    // if ($pangRo) {
                    //     $lama_ro = max(0, round((strtotime($roData->updated_at) - strtotime($message["ruang_rontgen_panggil_waktu"])) / 60, 2));
                    // } else {
                    $lama_ro = max(0, round((strtotime($roData->updated_at) - strtotime($roData->created_at)) / 60, 2));
                    // }
                    // dd($lama_ro);
                }

                $labData = LaboratoriumHasilModel::where('norm', $message['pasien_no_rm'])
                    ->whereDate('created_at', $message['tanggal'])->first();
                // dd($labData);
                $Ldata = $labData ? true : false;
                // dd($Ldata);
                if (is_null($labData)) {
                    $panggilLab = 0;
                    $selesaiLab = 0;
                    $lama_lab = 0;
                } elseif (is_null($labData->created_at)) {
                    $panggilLab = 0;
                    $selesaiLab = 0;
                    $lama_lab = 0;

                } else {
                    $panggilLab = date('Y-m-d H:i:s', strtotime($labData->created_at));
                    $selesaiLab = date('Y-m-d H:i:s', strtotime($labData->updated_at));

                    // if ($message["ruang_laboratorium_panggil_waktu"] == null) {
                    //     if (strtotime($message["ruang_poli_selesai_waktu"]) > strtotime($message["ruang_tensi_selesai_waktu"])) {
                    $lama_lab = max(0, round((strtotime($labData->updated_at) - strtotime($labData->created_at)) / 60, 2));
                    // $lama_lab = max(0, round((strtotime($labData->updated_at) - strtotime($message["ruang_poli_selesai_waktu"])) / 60, 2));
                    //     } else {
                    //         $lama_lab = max(0, round((strtotime($labData->updated_at) - strtotime($labData->created_at)) / 60, 2));
                    //     }
                    //     $lama_lab = max(0, round((strtotime($labData->updated_at) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
                    // } else {
                    //     $lama_lab = max(0, round((strtotime($labData->updated_at) - strtotime($message["ruang_laboratorium_panggil_waktu"])) / 60, 2));
                    // }
                }

                $igdData = IGDTransModel::where('norm', $message['pasien_no_rm'])->whereDate('created_at', $message['tanggal'])->first();
                // dd($igdData);
                $igd = $igdData ? true : false;
                if (is_null($igdData)) {
                    $selesaiIgd = 0;
                    $lama_igd = 0;
                    $panggilIgd = 0;
                    $panggilFarmasi = $message["ruang_poli_selesai_waktu"] !== null ? date('Y-m-d H:i:s', strtotime($message["ruang_poli_selesai_waktu"])) : 0;
                } elseif (is_null($igdData->updated_at)) {
                    $selesaiIgd = 0;
                    $lama_igd = 0;
                    $panggilIgd = 0;
                    $panggilFarmasi = $message["ruang_poli_selesai_waktu"] !== null ? date('Y-m-d H:i:s', strtotime($message["ruang_poli_selesai_waktu"])) : 0;
                } else {
                    $panggilIgd = date('Y-m-d H:i:s', strtotime($message["ruang_poli_selesai_waktu"]));
                    $selesaiIgd = date('Y-m-d H:i:s', strtotime($igdData->updated_at));
                    $panggilFarmasi = $selesaiIgd;
                    if ($message["ruang_poli_skip_waktu"] == 0) {
                        $lama_igd = max(0, round((strtotime($igdData->updated_at) - strtotime($message["ruang_poli_selesai_waktu"])) / 60, 2));
                    } else {
                        $lama_igd = max(0, round((strtotime($igdData->updated_at) - strtotime($message["ruang_poli_skip_waktu"])) / 60, 2));
                    }
                }

                return [
                    "ro" => $pangRo,
                    "lab" => $pangLab,
                    "oke" => $oke,
                    "rodata" => $Rdata,
                    "labdata" => $Ldata,
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
                    "pendaftaran_menunggu" => $message["loket_pendaftaran_menunggu_waktu"] ?? 0,
                    "status_pulang" => $statusPulang,

                    "tunggu_daftar" => $tunggu_daftar ?? 0,
                    "pendaftaran_panggil" => $message["loket_pendaftaran_panggil_waktu"] ?? 0,
                    "pendaftaran_skip" => $message["loket_pendaftaran_skip_waktu"] ?? 0,
                    "pendaftaran_selesai" => $message["loket_pendaftaran_selesai_waktu"] ?? 0,

                    "tunggu_tensi" => $tunggu_tensi ?? 0,
                    // "tensi_menunggu" => $message["ruang_tensi_menunggu_waktu"]??0,
                    "tensi_panggil" => $message["ruang_tensi_panggil_waktu"] ?? 0,
                    "tensi_skip" => $message["ruang_tensi_skip_waktu"] ?? 0,
                    "tensi_selesai" => $message["ruang_tensi_selesai_waktu"] ?? 0,

                    "durasi_poli" => $durasi_poli ?? 0,
                    "tunggu_poli" => $tunggu_poli ?? 0,
                    // "poli_menunggu" => $message["ruang_poli_menunggu_waktu"]??0,
                    "poli_panggil" => $message["ruang_poli_panggil_waktu"] ?? 0,
                    "poli_skip" => $message["ruang_poli_skip_waktu"] ?? 0,
                    "poli_selesai" => $message["ruang_poli_selesai_waktu"] ?? 0,

                    "tunggu_lab" => $tunggu_lab ?? 0,
                    // "laboratorium_menunggu" => $message["ruang_laboratorium_menunggu_waktu"]??0,
                    "laboratorium_panggil" => $message["ruang_laboratorium_panggil_waktu"] ?? 0,
                    "laboratorium_skip" => $message["ruang_laboratorium_skip_waktu"] ?? 0,
                    "laboratorium_selesai" => $message["ruang_laboratorium_selesai_waktu"] ?? 0,
                    "laboratorium_selesai" => $selesaiLab ?? 0,
                    "selesai_lab" => $selesaiLab,
                    "tunggu_hasil_lab" => $lama_lab,

                    // // "rontgen_menunggu" => $message["ruang_rontgen_menunggu_waktu"],
                    "tunggu_ro" => $tunggu_ro ?? 0,
                    "rontgen_panggil" => $message["ruang_rontgen_panggil_waktu"] ?? $panggilRo,
                    "rontgen_skip" => $message["ruang_rontgen_skip_waktu"] ?? 0,
                    // "rontgen_selesai" => $message["ruang_rontgen_selesai_waktu"] ?? $selesaiRo,
                    "rontgen_selesai" => $selesaiRo ?? 0,
                    "selesai_ro" => $selesaiRo,
                    "tunggu_hasil_ro" => $lama_ro,
                    // // "igd_menunggu" => $message["ruang_igd_menunggu_waktu"]??0,
                    "tunggu_igd" => $tunggu_igd ?? 0,
                    "igd_panggil" => $message["ruang_igd_panggil_waktu"] ?? $panggilIgd,
                    "igd_skip" => $message["ruang_igd_skip_waktu"] ?? 0,
                    "igd_selesai" => $message["ruang_igd_selesai_waktu"] ?? $selesaiIgd,
                    "lama_igd" => $lama_igd,
                    // // "kasir_menunggu" => $message["loket_kasir_menunggu_waktu"]??0,
                    "tunggu_kasir" => $tunggu_kasir ?? 0,
                    "kasir_panggil" => $message["loket_kasir_panggil_waktu"] ?? 0,
                    "kasir_skip" => $message["loket_kasir_skip_waktu"] ?? 0,
                    "kasir_selesai" => $message["loket_kasir_selesai_waktu"] ?? 0,

                    // // "farmasi_menunggu" => $message["loket_farmasi_menunggu_waktu"]??0,
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
    public function waktuLayananRequestold(array $params)
    {
        $client = new Client();
        $url = 'https://kkpm.banyumaskab.go.id/api_kkpm/v1/pendaftaran/data_pendaftaran';
        $username = '3301010509940003';
        $password = '~@j1s@nt0sO#';
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

                if ($message["daftar_by"] == "JKN") {
                    $tunggu_daftar = 2;
                } else {
                    if (!is_null($message["loket_pendaftaran_skip_waktu"])) {
                        $tunggu_daftar = max(0, round((strtotime($message["loket_pendaftaran_panggil_waktu"]) - strtotime($message["loket_pendaftaran_skip_waktu"])) / 60, 2));
                    } else {
                        $tunggu_daftar = max(0, round((strtotime($message["loket_pendaftaran_panggil_waktu"]) - strtotime($message["loket_pendaftaran_menunggu_waktu"])) / 60, 2));
                    }
                }

                if (!is_null($message["ruang_tensi_skip_waktu"])) {
                    $tunggu_tensi = max(0, round((strtotime($message["ruang_tensi_panggil_waktu"]) - strtotime($message["ruang_tensi_skip_waktu"])) / 60, 2));
                } else {
                    $tunggu_tensi = max(0, round((strtotime($message["ruang_tensi_panggil_waktu"]) - strtotime($message["loket_pendaftaran_selesai_waktu"])) / 60, 2));
                }

                $durasi_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["loket_pendaftaran_selesai_waktu"])) / 60, 2));

                $tunggu_igd = 0;
                $tunggu_farmasi = 0;
                $tunggu_kasir = 0;
                if (!is_null($message["ruang_poli_selesai_waktu"])) {
                    $statusPulang = "Sudah Pulang";
                } else {
                    $statusPulang = "Belum Pulang";
                }

                //selisih selesai tensi ke penunjang
                $pangLab = $message["ruang_laboratorium_panggil_waktu"] ? true : false;
                $pangRo = $message["ruang_rontgen_panggil_waktu"] ? true : false;

                $pangLabWaktu = $message["ruang_laboratorium_panggil_waktu"];
                $pangRoWaktu = $message["ruang_rontgen_panggil_waktu"];

                $timeLab = strtotime($pangLabWaktu);
                $timeRo = strtotime($pangRoWaktu);

                if ($pangLab && $pangRo) {
                    $oke = "ada semua";
                    if ($timeLab < $timeRo) {
                        $tunggu_lab = max(0, round((strtotime($message["ruang_laboratorium_panggil_waktu"]) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
                        $tunggu_ro = max(0, round((strtotime($message["ruang_rontgen_panggil_waktu"]) - strtotime($message["ruang_laboratorium_selesai_waktu"])) / 60, 2));
                        $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_rontgen_panggil_waktu"])) / 60, 2));
                    } elseif ($timeLab > $timeRo) {
                        $tunggu_ro = max(0, round((strtotime($message["ruang_rontgen_panggil_waktu"]) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
                        $tunggu_lab = max(0, round((strtotime($message["ruang_laboratorium_panggil_waktu"]) - strtotime($message["ruang_rontgen_selesai_waktu"])) / 60, 2));
                        $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_laboratorium_panggil_waktu"])) / 60, 2));
                    } else {
                        $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_rontgen_panggil_waktu"])) / 60, 2));
                        $tunggu_lab = max(0, round((strtotime($message["ruang_laboratorium_panggil_waktu"]) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
                        $tunggu_ro = max(0, round((strtotime($message["ruang_rontgen_panggil_waktu"]) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
                    }
                } else if ($pangLab && !$pangRo) {
                    $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_laboratorium_panggil_waktu"])) / 60, 2));
                    $tunggu_lab = max(0, round((strtotime($message["ruang_laboratorium_panggil_waktu"]) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
                    $tunggu_ro = 0;
                    $oke = "ada lab";
                } else if (!$pangLab && $pangRo) {
                    $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_rontgen_panggil_waktu"])) / 60, 2));
                    $tunggu_ro = max(0, round((strtotime($message["ruang_rontgen_panggil_waktu"]) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
                    $tunggu_lab = 0;
                    $oke = "ada ro";
                } else {
                    $oke = "kosong";
                    $tunggu_lab = 0;
                    $tunggu_ro = 0;
                    if (!is_null($message["ruang_poli_skip_waktu"])) {
                        $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_poli_skip_waktu"])) / 60, 2));
                    } else {
                        $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
                    }
                }

                // data local
                // $roData = ROTransaksiModel::where('notrans', $message['no_trans'])->first();
                $roData = ROTransaksiModel::where('norm', $message['pasien_no_rm'])
                    ->whereDate('tgltrans', $message['tanggal'])->first();
                $Rdata = $roData ? true : false;
                if (is_null($roData)) {
                    $selesaiRo = 0;
                } elseif (is_null($roData->created_at)) {
                    $selesaiRo = 0;
                } else {
                    $selesaiRo = date('Y-m-d H:i:s', strtotime($roData->created_at));
                }
                return [
                    "ro" => $pangRo,
                    "lab" => $pangLab,
                    "oke" => $oke,
                    "rodata" => $Rdata,
                    "no_reg" => $message["no_reg"] ?? 0,
                    "no_trans" => $message["no_trans"] ?? 0,
                    "daftar_by" => $message["daftar_by"] ?? 0,
                    "antrean_nomor" => $message["antrean_nomor"] ?? 0,
                    "tanggal" => $message["tanggal"] ?? 0,
                    "penjamin_nama" => $message["penjamin_nama"] ?? 0,
                    "pasien_no_rm" => $message["pasien_no_rm"] ?? 0,
                    "pasien_nama" => $message["pasien_nama"] ?? 0,
                    "poli_nama" => $message["poli_nama"] ?? 0,
                    "dokter_nama" => $message["dokter_nama"] ?? 0,
                    "pendaftaran_menunggu" => $message["loket_pendaftaran_menunggu_waktu"] ?? 0,
                    "status_pulang" => $statusPulang,

                    "tunggu_daftar" => $tunggu_daftar ?? 0,
                    "pendaftaran_panggil" => $message["loket_pendaftaran_panggil_waktu"] ?? 0,
                    "pendaftaran_skip" => $message["loket_pendaftaran_skip_waktu"] ?? 0,
                    "pendaftaran_selesai" => $message["loket_pendaftaran_selesai_waktu"] ?? 0,

                    "tunggu_tensi" => $tunggu_tensi ?? 0,
                    // "tensi_menunggu" => $message["ruang_tensi_menunggu_waktu"]??0,
                    "tensi_panggil" => $message["ruang_tensi_panggil_waktu"] ?? 0,
                    "tensi_skip" => $message["ruang_tensi_skip_waktu"] ?? 0,
                    "tensi_selesai" => $message["ruang_tensi_selesai_waktu"] ?? 0,

                    "durasi_poli" => $durasi_poli ?? 0,
                    "tunggu_poli" => $tunggu_poli ?? 0,
                    // "poli_menunggu" => $message["ruang_poli_menunggu_waktu"]??0,
                    "poli_panggil" => $message["ruang_poli_panggil_waktu"] ?? 0,
                    "poli_skip" => $message["ruang_poli_skip_waktu"] ?? 0,
                    "poli_selesai" => $message["ruang_poli_selesai_waktu"] ?? 0,

                    "tunggu_lab" => $tunggu_lab ?? 0,
                    // "laboratorium_menunggu" => $message["ruang_laboratorium_menunggu_waktu"]??0,
                    "laboratorium_panggil" => $message["ruang_laboratorium_panggil_waktu"] ?? 0,
                    "laboratorium_skip" => $message["ruang_laboratorium_skip_waktu"] ?? 0,
                    "laboratorium_selesai" => $message["ruang_laboratorium_selesai_waktu"] ?? 0,

                    // // "rontgen_menunggu" => $message["ruang_rontgen_menunggu_waktu"],
                    "tunggu_ro" => $tunggu_ro ?? 0,
                    "rontgen_panggil" => $message["ruang_rontgen_panggil_waktu"] ?? 0,
                    "rontgen_skip" => $message["ruang_rontgen_skip_waktu"] ?? 0,
                    "rontgen_selesai" => $message["ruang_rontgen_selesai_waktu"] ?? 0,
                    "selesai_ro" => $selesaiRo,
                    // // "igd_menunggu" => $message["ruang_igd_menunggu_waktu"]??0,
                    "tunggu_igd" => $tunggu_igd ?? 0,
                    "igd_panggil" => $message["ruang_igd_panggil_waktu"] ?? 0,
                    "igd_skip" => $message["ruang_igd_skip_waktu"] ?? 0,
                    "igd_selesai" => $message["ruang_igd_selesai_waktu"] ?? 0,
                    // // "kasir_menunggu" => $message["loket_kasir_menunggu_waktu"]??0,
                    "tunggu_kasir" => $tunggu_kasir ?? 0,
                    "kasir_panggil" => $message["loket_kasir_panggil_waktu"] ?? 0,
                    "kasir_skip" => $message["loket_kasir_skip_waktu"] ?? 0,
                    "kasir_selesai" => $message["loket_kasir_selesai_waktu"] ?? 0,

                    // // "farmasi_menunggu" => $message["loket_farmasi_menunggu_waktu"]??0,
                    "tunggu_farmasi" => $tunggu_farmasi ?? 0,
                    "farmasi_panggil" => $message["loket_farmasi_panggil_waktu"] ?? 0,
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

}
