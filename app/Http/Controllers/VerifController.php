<?php
namespace App\Http\Controllers;

use App\Models\KominfoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerifController extends Controller
{
    public function frista(Request $request)
    {
        ini_set('max_execution_time', 400);

        // Ambil parameter dari request
        $id_number = $request->input('id_number');
        $id_server = $request->input('id_server');

        // URL API Flask di Windows
        if ($id_server == 1) {
            $apiUrl = env('FRISTA_API_URL');
        } else {
            $apiUrl = env('FRISTA_API_URL2');
        }

        // Kirim permintaan ke API Flask dengan waktu timeout yang diperpanjang
        $response = Http::timeout(600)->post($apiUrl, [
            'id_number' => $id_number,
        ]);

        // Mengembalikan hasil sebagai JSON
        return response()->json([
            'success' => $response->successful(),
            'output' => $response->json('output'),
            'error' => $response->json('error'),
        ]);
    }
    public function afterapp(Request $request)
    {
        ini_set('max_execution_time', 400);

        // Ambil parameter dari request
        $id_number = $request->input('id_number');
        $id_server = $request->input('id_server');

        // URL API Flask di Windows
        if ($id_server == 1) {
            $apiUrl = env('FP_API_URL');
        } else {
            $apiUrl = env('FP_API_URL2');
        }

        // Kirim permintaan ke API Flask dengan waktu timeout yang diperpanjang
        $response = Http::timeout(600)->post($apiUrl, [
            'id_number' => $id_number,
        ]);
        // dd($response);

        // Mengembalikan hasil sebagai JSON
        return response()->json([
            'success' => $response->successful(),
            'output' => $response->json('output'),
            'error' => $response->json('error'),
        ]);
    }
    public function index(Request $request)
    {
        ini_set('max_execution_time', 400);

        // Ambil parameter dari request
        $username = env('FRISTA_USERNAME');
        $password = env('FRISTA_PASSWORD');
        $nik = $request->input('nik');
        $id_server = $request->input('id_server');

        // URL API Flask di Windows
        if ($id_server == 1) {
            $apiUrl = env('FRISTA_API_URL');
        } else {
            $apiUrl = env('FRISTA_API_URL2');
        }
        // return $apiUrl;

        // Kirim permintaan ke API Flask dengan waktu timeout yang diperpanjang
        $response = Http::timeout(600)->post($apiUrl, [
            'username' => $username,
            'password' => $password,
            'nik' => $nik,
        ]);
        // dd($response);

        // Mengembalikan hasil sebagai JSON
        return response()->json([
            'success' => $response->successful(),
            'output' => $response->json('output'),
            'error' => $response->json('error'),
        ]);
    }
    public function fingerprint(Request $request)
    {
        ini_set('max_execution_time', 400);

        // Ambil parameter dari request
        $username = env('FRISTA_USERNAME');
        $password = env('FRISTA_PASSWORD');
        $id_number = $request->input('id_number');
        $id_server = $request->input('id_server');

        // URL API Flask di Windows
        if ($id_server == 1) {
            $apiUrl = env('FP_API_URL');
        } else {
            $apiUrl = env('FP_API_URL2');
        }

        // Kirim permintaan ke API Flask dengan waktu timeout yang diperpanjang
        $response = Http::timeout(600)->post($apiUrl, [
            'username' => $username,
            'password' => $password,
            'id_number' => $id_number,
        ]);
        // dd($response);

        // Mengembalikan hasil sebagai JSON
        return response()->json([
            'success' => $response->successful(),
            'output' => $response->json('output'),
            'error' => $response->json('error'),
        ]);
    }

    // public function submit(Request $request)
    // {
    //     // $res = [
    //     //     "code" => 201,
    //     //     "message" => "Antrean sudah selesai dipanggil!",
    //     //     "data" => [
    //     //         "ruang_id" => 1,
    //     //         "ruang_nama_underscore" => "loket_pendaftaran",
    //     //         "ruang_id_selanjutnya" => "2",
    //     //         "ruang_nama_selanjutnya_underscore" => "ruang_tensi_1",
    //     //     ],
    //     // ];
    //     // return $res;
    //     // return response()->json($res, 200);
    //     $client = new KominfoModel();

    //     // Ambil cookie dari browser
    //     $cookie = $_COOKIE['kominfo_cookie'] ?? null;

    //     if (!$cookie) {
    //         // dd("masuk");
    //         // Jika cookie tidak ada, lakukan login
    //         $loginResponse = $client->login();
    //         $cookie = $loginResponse['cookies'][0] ?? null;

    //         if ($cookie) {
    //             setcookie('kominfo_cookie', $cookie, time() + (86400 * 30), "/"); // Set cookie di browser
    //         } else {
    //             return response()->json(['message' => 'Login gagal'], 401);
    //         }
    //     }

    //     // return $cookie;
    //     $params = $request->all();
    //     $pendaftaranOnline = $client->pendaftaranRequest($params);
    //     $noAntri = $request->input('noAntrian') ?? null;
    //     // return $pendaftaranOnline;
    //     // dd($noAntri);
    //     if (!empty($noAntri)) {
    //         $pendaftaranOnline = array_filter($pendaftaranOnline, function ($d) use ($noAntri) {
    //             return $d['antrean_nomor'] === $noAntri;
    //         });
    //         $pendaftaranOnline = array_values($pendaftaranOnline);
    //     }
    //     $data_pendaftaran = $pendaftaranOnline[0] ?? null;
    //     // return $data_pendaftaran; //skip log 249225
    //     $keterangan = $data_pendaftaran['keterangan'] ?? null;
    //     $pasien_no_rm = $request->input('pasien_no_rm') ?? $data_pendaftaran['pasien_no_rm'] ?? null;
    //     $jenis_kunjungan_nama = $data_pendaftaran['jenis_kunjungan_nama'] ?? null;
    //     // dd($jenis_kunjungan_nama);

    //     $jenis_kunjungan_id = null;
    //     if ($jenis_kunjungan_nama == "Kontrol" || $jenis_kunjungan_nama == 0) {
    //         $jenis_kunjungan_id = 3;
    //     } else if ($jenis_kunjungan_nama == "Rujukan FKTP") {
    //         $jenis_kunjungan_id = 1;
    //     } else if ($jenis_kunjungan_nama == "Rujukan Internal") {
    //         $jenis_kunjungan_id = 2;
    //     } else if ($jenis_kunjungan_nama == "Rujukan Antar RS") {
    //         $jenis_kunjungan_id = 4;
    //     }
    //     $penjamin_nama = $data_pendaftaran['penjamin_nama'] ?? null;
    //     // dd($penjamin_nama);

    //     $penjamin_id = "";
    //     if ($penjamin_nama == "BPJS") {
    //         $penjamin_id = 2;
    //     } else {
    //         $penjamin_id = 1;
    //     }
    //     // return $penjamin_id;

    //     $log_id = $data_pendaftaran['log_id'] ?? null;
    //     // return "dari pengambilan no antri :" . $log_id; //249121

    //     // panggil pasien
    //     if ($keterangan == "MENUNGGU DIPANGGIL LOKET PENDAFTARAN" || $keterangan == "SKIP LOKET PENDAFTARAN") {
    //         // dd("menunggu panggil");
    //         $panggil = $client->panggil(['cookie' => $cookie], $log_id);
    //         $antrian = $client->get_data_antrian(['cookie' => $cookie], $request->input('noAntri'));
    //         // return $antrian;
    //         // Ambil log_id dari data antrian
    //         $log_id = isset($antrian['data'][0]['log_id']) ? $antrian['data'][0]['log_id'] : null; // Memastikan ada data
    //     }
    //     // return "setelah dipanggil :" . $log_id; //249169

    //     $pasien = $client->getDataByRM(['cookie' => $cookie], $pasien_no_rm);
    //     $data_pasien = $pasien['data'] ?? null;
    //     // return $data_pasien;
    //     $pasien_id = $data_pasien['id'] ?? null;
    //     // return $pasien_id;
    //     $dokterBefore = $client->getDokterBefore(['cookie' => $cookie], $pasien_id);
    //     // return $dokterBefore;
    //     $dokter = isset($data_pendaftaran['dokter_nama']) && $data_pendaftaran['dokter_nama'] !== 0
    //     ? $data_pendaftaran['dokter_nama']
    //     : $dokterBefore;
    //     $tglKunjungan = $pendaftaranOnline[0]['tanggal'] ?? null;
    //     //tentukan hari dengan number dari tgl kunjungan
    //     $date = new \DateTime($tglKunjungan);
    //     $dayOfWeek = $date->format('N');
    //     $reqJadwal = [
    //         'no_hari' => $dayOfWeek,
    //         'admin_nama' => $dokter,
    //     ];
    //     // return $reqJadwal;
    //     $jadwal = $client->jadwalPoli($reqJadwal);
    //     //kembalikan jadwal sebagai objek
    //     $jadwal = $jadwal[0] ?? null;

    //     // return $jadwal; //tinggal memasukan hari jsawal umum kusus

    //     $form_data = [
    //         'log_id' => $log_id ?? null,
    //         'ruang_id_selanjutnya' => 2,
    //         'penjamin_id' => $penjamin_id ?? null,
    //         'penjamin_nomor' => isset($data_pendaftaran['penjamin_nomor']) && $data_pendaftaran['penjamin_nomor'] !== 0
    //         ? $data_pendaftaran['penjamin_nomor']
    //         : '',
    //         'jenis_kunjungan_id' => $jenis_kunjungan_id ?? null,
    //         'nomor_referensi' => isset($data_pendaftaran['nomor_referensi']) && $data_pendaftaran['nomor_referensi'] !== 0
    //         ? $data_pendaftaran['nomor_referensi']
    //         : '',
    //         'daftar_by' => $data_pendaftaran['daftar_by'] ?? $daftarBy ?? null,
    //         'pasien_lama_baru' => isset($data_pendaftaran['pasien_lama_baru']) && $data_pendaftaran['pasien_lama_baru'] !== 0
    //         ? $data_pendaftaran['pasien_lama_baru']
    //         : 'LAMA',

    //         'dokter_id' => $jadwal['admin_id'] ?? null,
    //         'jadwal_umum_khusus' => 'UMUM',
    //         'jadwal_id' => $jadwal['id'] ?? null,
    //         'poli_sub_id' => $jadwal['poli_sub_id'] ?? null,

    //         'no_telp' => $data_pasien['pasien_no_hp'] ?? null,
    //         'pasien_id' => $data_pasien['id'] ?? null,
    //         'pasien_no_rm' => $data_pasien['pasien_no_rm'] ?? null,
    //         'pasien_nik' => $data_pasien['pasien_nik'] ?? null,
    //         'pasien_no_kk' => $data_pasien['pasien_no_kk'] ?? null,
    //         'pasien_nama' => $data_pasien['pasien_nama'] ?? null,
    //         'jenis_kelamin_id' => $data_pasien['jenis_kelamin_id'] ?? null,
    //         'pasien_tempat_lahir' => $data_pasien['pasien_tempat_lahir'] ?? null,
    //         'pasien_tgl_lahir' => $data_pasien['pasien_tgl_lahir'] ?? null,
    //         'pasien_no_hp' => $data_pasien['pasien_no_hp'] ?? null,
    //         'pasien_alamat' => $data_pasien['pasien_alamat'] ?? null,
    //         'pasien_kode_pos' => $data_pasien['pasien_kode_pos'] ?? null,
    //         'provinsi_id' => $data_pasien['provinsi_id'] ?? null,
    //         'kabupaten_id' => $data_pasien['kabupaten_id'] ?? null,
    //         'kecamatan_id' => $data_pasien['kecamatan_id'] ?? null,
    //         'kelurahan_id' => $data_pasien['kelurahan_id'] ?? null,
    //         'pasien_rt' => $data_pasien['pasien_rt'] ?? null,
    //         'pasien_rw' => $data_pasien['pasien_rw'] ?? null,
    //         'agama_id' => $data_pasien['agama_id'] ?? null,
    //         'goldar_id' => $data_pasien['goldar_id'] ?? null,
    //         'status_kawin_id' => $data_pasien['status_kawin_id'] ?? null,
    //         'pendidikan_id' => $data_pasien['pendidikan_id'] ?? null,
    //         'pasien_penanggung_jawab_nama' => $data_pasien['pasien_penanggung_jawab_nama'] ?? null,
    //         'pasien_penanggung_jawab_no_hp' => $data_pasien['pasien_penanggung_jawab_no_hp'] ?? null,

    //         'pasien_daftar_by' => $data_pasien['pasien_daftar_by'] ?? $daftarBy ?? null,
    //     ];

    //     // return $form_data;

    //     //coba post ke url kominfo
    //     $daftar = $client->submit(['cookie' => $cookie, 'form_data' => $form_data], $log_id);
    //     return $daftar;

    // }

    public function submit(Request $request)
    {
        $client = new KominfoModel();

        // Ambil cookie dari browser
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;

        if (!$cookie) {
            // Jika cookie tidak ada, lakukan login
            $loginResponse = $client->login();
            $cookie = $loginResponse['cookies'][0] ?? null;

            if ($cookie) {
                setcookie('kominfo_cookie', $cookie, time() + (86400 * 30), "/"); // Set cookie di browser
            } else {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }

        $params = $request->all();
        $pendaftaranOnline = $client->pendaftaranRequest($params);
        $noAntri = $request->input('noAntrian') ?? null;

        if (!empty($noAntri)) {
            $pendaftaranOnline = array_filter($pendaftaranOnline, function ($d) use ($noAntri) {
                return $d['antrean_nomor'] === $noAntri;
            });
            $pendaftaranOnline = array_values($pendaftaranOnline);
        }

        $data_pendaftaran = $pendaftaranOnline[0] ?? null;
        $keterangan = $data_pendaftaran['keterangan'] ?? null;
        $pasien_no_rm = $request->input('pasien_no_rm') ?? $data_pendaftaran['pasien_no_rm'] ?? null;
        $jenis_kunjungan_nama = $data_pendaftaran['jenis_kunjungan_nama'] ?? null;

        // Menentukan jenis_kunjungan_id
        $jenis_kunjungan_id = null;
        switch ($jenis_kunjungan_nama) {
            case "Kontrol":
            case 0:
                $jenis_kunjungan_id = 3;
                break;
            case "Rujukan FKTP":
                $jenis_kunjungan_id = 1;
                break;
            case "Rujukan Internal":
                $jenis_kunjungan_id = 2;
                break;
            case "Rujukan Antar RS":
                $jenis_kunjungan_id = 4;
                break;
        }

        $penjamin_nama = $data_pendaftaran['penjamin_nama'] ?? null;
        $penjamin_id = ($penjamin_nama == "BPJS") ? 2 : 1;

        $log_id = $data_pendaftaran['log_id'] ?? null;

        // Memanggil pasien jika perlu
        if ($keterangan == "MENUNGGU DIPANGGIL LOKET PENDAFTARAN" || $keterangan == "SKIP LOKET PENDAFTARAN") {
            $panggil = $client->panggil(['cookie' => $cookie], $log_id);
            $antrian = $client->get_data_antrian(['cookie' => $cookie], $request->input('noAntri'));
            $log_id = $antrian['data'][0]['log_id'] ?? null; // Memastikan ada data
        }

        $pasien = $client->getDataByRM(['cookie' => $cookie], $pasien_no_rm);
        $data_pasien = $pasien['data'] ?? null;
        $pasien_id = $data_pasien['id'] ?? null;
        $dokterBefore = $client->getDokterBefore(['cookie' => $cookie], $pasien_id);

        $dokter = isset($data_pendaftaran['dokter_nama']) && $data_pendaftaran['dokter_nama'] !== 0
        ? $data_pendaftaran['dokter_nama']
        : $dokterBefore;

        $tglKunjungan = $pendaftaranOnline[0]['tanggal'] ?? null;
        $date = new \DateTime($tglKunjungan);
        $dayOfWeek = $date->format('N');

        $reqJadwal = [
            'no_hari' => $dayOfWeek,
            'admin_nama' => $dokter,
        ];

        $jadwal = $client->jadwalPoli($reqJadwal)[0] ?? null;

        $form_data = [
            'log_id' => $log_id ?? null,
            'ruang_id_selanjutnya' => 2,
            'penjamin_id' => $penjamin_id,
            'penjamin_nomor' => $data_pendaftaran['penjamin_nomor'] ?? '',
            'jenis_kunjungan_id' => $jenis_kunjungan_id,
            'nomor_referensi' => $data_pendaftaran['nomor_referensi'] ?? '',
            'daftar_by' => $data_pendaftaran['daftar_by'] ?? null,
            'pasien_lama_baru' => $data_pendaftaran['pasien_lama_baru'] ?? 'LAMA',
            'dokter_id' => $jadwal['admin_id'] ?? null,
            'jadwal_umum_khusus' => 'UMUM',
            'jadwal_id' => $jadwal['id'] ?? null,
            'poli_sub_id' => $jadwal['poli_sub_id'] ?? null,
            'no_telp' => $data_pasien['pasien_no_hp'] ?? null,
            'pasien_id' => $data_pasien['id'] ?? null,
            'pasien_no_rm' => $data_pasien['pasien_no_rm'] ?? null,
            'pasien_nik' => $data_pasien['pasien_nik'] ?? null,
            'pasien_no_kk' => $data_pasien['pasien_no_kk'] ?? null,
            'pasien_nama' => $data_pasien['pasien_nama'] ?? null,
            'jenis_kelamin_id' => $data_pasien['jenis_kelamin_id'] ?? null,
            'pasien_tempat_lahir' => $data_pasien['pasien_tempat_lahir'] ?? null,
            'pasien_tgl_lahir' => $data_pasien['pasien_tgl_lahir'] ?? null,
            'pasien_no_hp' => $data_pasien['pasien_no_hp'] ?? null,
            'pasien_alamat' => $data_pasien['pasien_alamat'] ?? null,
            'pasien_kode_pos' => $data_pasien['pasien_kode_pos'] ?? null,
            'provinsi_id' => $data_pasien['provinsi_id'] ?? null,
            'kabupaten_id' => $data_pasien['kabupaten_id'] ?? null,
            'kecamatan_id' => $data_pasien['kecamatan_id'] ?? null,
            'kelurahan_id' => $data_pasien['kelurahan_id'] ?? null,
            'pasien_rt' => $data_pasien['pasien_rt'] ?? null,
            'pasien_rw' => $data_pasien['pasien_rw'] ?? null,
            'agama_id' => $data_pasien['agama_id'] ?? null,
            'goldar_id' => $data_pasien['goldar_id'] ?? null,
            'status_kawin_id' => $data_pasien['status_kawin_id'] ?? null,
            'pendidikan_id' => $data_pasien['pendidikan_id'] ?? null,
            'pasien_penanggung_jawab_nama' => $data_pasien['pasien_penanggung_jawab_nama'] ?? null,
            'pasien_penanggung_jawab_no_hp' => $data_pasien['pasien_penanggung_jawab_no_hp'] ?? null,
        ];

        // Submit data ke server Kominfo
        $daftar = $client->submit(['cookie' => $cookie, 'form_data' => $form_data], $log_id);

        return $daftar;
    }

    public function submitJKN(Request $request)
    {
        $client = new KominfoModel();

        // Ambil cookie dari browser
        $cookie = $_COOKIE['kominfo_cookie'] ?? null;

        if (!$cookie) {
            dd("masuk");
            // Jika cookie tidak ada, lakukan login
            $loginResponse = $client->login();
            $cookie = $loginResponse['cookies'][0] ?? null;

            if ($cookie) {
                setcookie('kominfo_cookie', $cookie, time() + (86400 * 30), "/"); // Set cookie di browser
            } else {
                return response()->json(['message' => 'Login gagal'], 401);
            }
        }

        // return $cookie;
        $params = $request->all();
        $pendaftaranOnline = $client->pendaftaranRequest($params);
        $data_pendaftaran = $pendaftaranOnline[0] ?? null;
        // return $pendaftaranOnline;
        $keterangan = $data_pendaftaran['keterangan'] ?? null;
        // return $data_pendaftaran;
        $pasien_no_rm = $data_pendaftaran['pasien_no_rm'] ?? null;
        $jenis_kunjungan_nama = $data_pendaftaran['jenis_kunjungan_nama'] ?? null;
        // dd($jenis_kunjungan_nama);

        $jenis_kunjungan_id = "";
        if ($jenis_kunjungan_nama == "Kontrol") {
            $jenis_kunjungan_id = 3;
        } else if ($jenis_kunjungan_nama == "Rujukan FKTP") {
            $jenis_kunjungan_id = 1;
        } else if ($jenis_kunjungan_nama == "Rujukan Internal") {
            $jenis_kunjungan_id = 2;
        } else if ($jenis_kunjungan_nama == "Rujukan Antar RS") {
            $jenis_kunjungan_id = 4;
        }
        // return $jenis_kunjungan_id;

        $log_id = $data_pendaftaran['log_id'] ?? null;

        // <-----proses ambil log id untuk panggil-----> //
        if ($log_id == null) {
            dd("logid null");
            // Gunakan cookie yang ada untuk mengambil data antrian
            $antrian = $client->get_data_antrian(['cookie' => $cookie], $request->input('noAntri'));
            // return $antrian;
            // Ambil log_id dari data antrian
            $log_id = isset($antrian['data'][0]['log_id']) ? $antrian['data'][0]['log_id'] : null; // Memastikan ada data
        }

        //panggil pasien
        if ($keterangan == "MENUNGGU DIPANGGIL LOKET PENDAFTARAN") {
            dd("menunggu panggil");
            $panggil = $client->panggil(['cookie' => $cookie], $log_id);
            // <-----proses submit-----> //
            $antrian = $client->get_data_antrian(['cookie' => $cookie], $request->input('noAntri'));
            // return $antrian;
            // Ambil log_id dari data antrian
            $log_id = isset($antrian['data'][0]['log_id']) ? $antrian['data'][0]['log_id'] : null; // Memastikan ada data
            // return $log_id; //246758
        }
        // return $log_id;

        $pasien = $client->getDataByRM(['cookie' => $cookie], $pasien_no_rm);
        $data_pasien = $pasien['data'] ?? null;
        // return $data_pasien;

        $form_data = [
            'log_id' => $log_id ?? null,
            'ruang_id_selanjutnya' => 2,
            'poli_sub_id' => 1,
            'dokter_id' => $data_pasien['dokter_id'] ?? null,
            'penjamin_id' => 1, //2 bpjs
            'penjamin_nomor' => $data_pendaftaran['penjamin_nomor'] ?? null,
            'jenis_kunjungan_id' => $jenis_kunjungan_id ?? null,
            'nomor_referensi' => $data_pendaftaran['nomor_referensi'] ?? null,
            'daftar_by' => $data_pendaftaran['daftar_by'] ?? null,
            'pasien_lama_baru' => $data_pendaftaran['pasien_lama_baru'] ?? null,

            'jadwal_umum_khusus' => $data_pasien['jadwal_umum_khusus'] ?? null,
            'jadwal_id' => $data_pasien['jadwal_id'] ?? null,

            'no_telp' => $data_pasien['pasien_no_hp'] ?? null,
            'pasien_id' => $data_pasien['id'] ?? null,
            'pasien_no_rm' => $data_pasien['pasien_no_rm'] ?? null,
            'pasien_nik' => $data_pasien['pasien_nik'] ?? null,
            'pasien_no_kk' => $data_pasien['pasien_no_kk'] ?? null,
            'pasien_nama' => $data_pasien['pasien_nama'] ?? null,
            'jenis_kelamin_id' => $data_pasien['jenis_kelamin_id'] ?? null,
            'pasien_tempat_lahir' => $data_pasien['pasien_tempat_lahir'] ?? null,
            'pasien_tgl_lahir' => $data_pasien['pasien_tgl_lahir'] ?? null,
            'pasien_no_hp' => $data_pasien['pasien_no_hp'] ?? null,
            'pasien_alamat' => $data_pasien['pasien_alamat'] ?? null,
            'pasien_kode_pos' => $data_pasien['pasien_kode_pos'] ?? null,
            'provinsi_id' => $data_pasien['provinsi_id'] ?? null,
            'kabupaten_id' => $data_pasien['kabupaten_id'] ?? null,
            'kecamatan_id' => $data_pasien['kecamatan_id'] ?? null,
            'kelurahan_id' => $data_pasien['kelurahan_id'] ?? null,
            'pasien_rt' => $data_pasien['pasien_rt'] ?? null,
            'pasien_rw' => $data_pasien['pasien_rw'] ?? null,
            'agama_id' => $data_pasien['agama_id'] ?? null,
            'goldar_id' => $data_pasien['goldar_id'] ?? null,
            'status_kawin_id' => $data_pasien['status_kawin_id'] ?? null,
            'pendidikan_id' => $data_pasien['pendidikan_id'] ?? null,
            'pasien_penanggung_jawab_nama' => $data_pasien['pasien_penanggung_jawab_nama'] ?? null,
            'pasien_penanggung_jawab_no_hp' => $data_pasien['pasien_penanggung_jawab_no_hp'] ?? null,

            'pasien_daftar_by' => $data_pasien['pasien_daftar_by'] ?? null,
        ];

        return $form_data;

        // Mengembalikan log_id atau bisa disesuaikan sesuai kebutuhan
        return response()->json(['log_id' => $log_id]);
    }

}
