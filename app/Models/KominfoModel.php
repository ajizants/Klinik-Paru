<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class KominfoModel extends Model
{
    protected $table = 'm_pasien_kominfo';

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
            // $d = $data['response']['response']['data'];
            $res = array_map(function ($d) {

                return [
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
                    "pasien_umur" => $d["pasien_umur"] ?? 0,
                    "pasien_umur_tahun" => $d["pasien_umur_tahun"] ?? 0,
                    "pasien_umur_bulan" => $d["pasien_umur_bulan"] ?? 0,
                    "pasien_umur_hari" => $d["pasien_umur_hari"] ?? 0,
                ];
            }, $data['response']['data']);
            $res = array_values($res);
            return $res;
            // return $data;

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
                    return !empty($item['tindakan']);
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

            // Kembalikan data
            return $data;

        } catch (\Exception $e) {
            // Tangani kesalahan
            return ['error' => $e->getMessage()];
        }
    }

    public function pasienFilter($no_rm)
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
                "pasien_alamat" => ($data['response']['data']['kelurahan_nama']) . ', ' . ($data['response']['data']['pasien_rt']) . '/' . ($data['response']['data']['pasien_rw']) . ', ' . ($data['response']['data']['kecamatan_nama']) . ', ' . ($data['response']['data']['kabupaten_nama']) . ', ' . ($data['response']['data']['provinsi_nama']),
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

                if ($message["daftar_by"] == "JKN") {
                    $tunggu_daftar = 2;
                } else {
                    $tunggu_daftar = max(0, round((strtotime($message["loket_pendaftaran_panggil_waktu"]) - strtotime($message["loket_pendaftaran_menunggu_waktu"])) / 60, 2));

                }
                // if (!is_null($message["ruang_laboratorium_panggil_waktu"]) && !is_null($message["ruang_rontgen_panggil_waktu"])) {
                //     if ($message["ruang_laboratorium_panggil_waktu"] > $message["ruang_rontgen_panggil_waktu"]) {
                //         $tunggu_lab = max(0, round((strtotime($message["ruang_laboratorium_panggil_waktu"]) - strtotime($message["ruang_poli_selesai_waktu"])) / 60, 2));

                //         $tunggu_ro = max(0, round((strtotime($message["ruang_rontgen_panggil_waktu"]) - strtotime($message["ruang_laboratorium_selesai_waktu"])) / 60, 2));

                //     } else {
                //         $tunggu_lab = max(0, round((strtotime($message["ruang_laboratorium_panggil_waktu"]) - strtotime($message["ruang_rontgen_selesai_waktu"])) / 60, 2));

                //         $tunggu_ro = max(0, round((strtotime($message["ruang_rontgen_panggil_waktu"]) - strtotime($message["ruang_poli_selesai_waktu"])) / 60, 2));

                //     }
                // } else {
                //     $tunggu_lab = 0;
                //     $tunggu_ro = 0;
                // }

                $tunggu_tensi = max(0, round((strtotime($message["ruang_tensi_panggil_waktu"]) - strtotime($message["loket_pendaftaran_selesai_waktu"])) / 60, 2));

                // $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
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
                        // echo "Panggilan Lab lebih dulu.\n";
                        $tunggu_lab = max(0, round((strtotime($message["ruang_laboratorium_panggil_waktu"]) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
                        $tunggu_ro = max(0, round((strtotime($message["ruang_rontgen_panggil_waktu"]) - strtotime($message["ruang_laboratorium_selesai_waktu"])) / 60, 2));
                        $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_rontgen_panggil_waktu"])) / 60, 2));
                    } elseif ($timeLab > $timeRo) {
                        // echo "Panggilan Rontgen lebih dulu.\n";
                        $tunggu_ro = max(0, round((strtotime($message["ruang_rontgen_panggil_waktu"]) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
                        $tunggu_lab = max(0, round((strtotime($message["ruang_laboratorium_panggil_waktu"]) - strtotime($message["ruang_rontgen_selesai_waktu"])) / 60, 2));
                        $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_laboratorium_panggil_waktu"])) / 60, 2));
                    } else {
                        // echo "Panggilan Lab dan Rontgen terjadi pada waktu yang sama.\n";
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
                    $tunggu_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["ruang_tensi_selesai_waktu"])) / 60, 2));
                }

                // data local
                $roData = ROTransaksiModel::where('notrans', $message['no_trans'])->first();
                if (is_null($roData)) {
                    $selesaiRo = 0;
                } elseif (is_null($roData->created_at)) {
                    $selesaiRo = 0;
                } else {
                    $selesaiRo = date('Y-m-d H:i:s', strtotime($roData->created_at));
                    //jika $massage ruang_poli_selesai_waktu null
                    if (is_null($message["ruang_poli_selesai_waktu"])) {
                        $durasi_poli = max(0, round((strtotime($message["ruang_poli_panggil_waktu"]) - strtotime($message["loket_pendaftaran_selesai_waktu"])) / 60, 2));
                    }
                }

                return [
                    "ro" => $pangRo,
                    "lab" => $pangLab,
                    "oke" => $oke,
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
