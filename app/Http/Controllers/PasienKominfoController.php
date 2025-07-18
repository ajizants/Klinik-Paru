<?php
namespace App\Http\Controllers;

use App\Models\DiagnosaMapModel;
use App\Models\DotsTransModel;
use App\Models\IGDTransModel;
use App\Models\KasirTransModel;
use App\Models\KominfoModel;
use App\Models\KunjunganWaktuSelesai;
use App\Models\LaboratoriumHasilModel;
use App\Models\LaboratoriumKunjunganModel;
use App\Models\RoHasilModel;
use App\Models\ROTransaksiModel;
use Carbon\Carbon;
use function Laravel\Prompts\error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PasienKominfoController extends Controller
{
    public function ambilAntrean(Request $request)
    {
        $penjamin_id = $request->input('penjamin_id');
        $koneksi     = new KominfoModel();

        $noAtian = $koneksi->ambilNoRequest($penjamin_id);

        return response()->json($noAtian);
    }
    public function pasienKominfo(Request $request)
    {
        if ($request->has('no_rm')) {
            $uname = env('USERNAME_KOMINFO');
            $pass  = env('PASSWORD_KOMINFO');
            // $uname = $request->input('username');
            // $pass = $request->input('password');
            $no_rm = $request->input('no_rm');

            // Fetch data from both APIs
            $dataPasienResponse = $this->fetchDataFromApi(
                'https://kkpm.banyumaskab.go.id/api/pasien/data_pasien',
                [
                    'username' => $uname,
                    'password' => $pass,
                    'no_rm'    => $no_rm,
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
                        'id'                       => $data['id'],
                        'no_reg'                   => $data['no_reg'],
                        'no_trans'                 => $data['no_trans'],
                        'antrean_nomor'            => $data['antrean_nomor'],
                        'tanggal'                  => $data['tanggal'],
                        'penjamin_nama'            => $data['penjamin_nama'],
                        'jenis_kunjungan_nama'     => $data['jenis_kunjungan_nama'],
                        'pasien_no_rm'             => $data['pasien_no_rm'],
                        'pasien_lama_baru'         => $data['pasien_lama_baru'],
                        'rs_paru_pasien_lama_baru' => $data['rs_paru_pasien_lama_baru'],
                        'poli_nama'                => $data['poli_nama'],
                        'poli_sub_nama'            => $data['poli_sub_nama'],
                        'dokter_nama'              => $data['dokter_nama'],
                        'waktu_daftar'             => $data['waktu_daftar'],
                        'waktu_verifikasi'         => $data['waktu_verifikasi'],
                        'admin_pendaftaran'        => $data['admin_pendaftaran'],
                        'log_id'                   => $data['log_id'],
                        'keterangan_urutan'        => $data['keterangan_urutan'],
                        'pasien_umur'              => $data['pasien_umur_tahun'] . ' Tahun ' . $data['pasien_umur_bulan'] . ' Bulan ' . $data['pasien_umur_hari'] . ' Hari',

                        'pasien_nik'               => $dataPasienResponse['response']['response']['data']['pasien_nik'],
                        'pasien_nama'              => $dataPasienResponse['response']['response']['data']['pasien_nama'],
                        'pasien_no_rm'             => $dataPasienResponse['response']['response']['data']['pasien_no_rm'],
                        'jenis_kelamin_nama'       => $dataPasienResponse['response']['response']['data']['jenis_kelamin_nama'],
                        'pasien_tempat_lahir'      => $dataPasienResponse['response']['response']['data']['pasien_tempat_lahir'],
                        'pasien_tgl_lahir'         => $dataPasienResponse['response']['response']['data']['pasien_tgl_lahir'],
                        'pasien_no_hp'             => $dataPasienResponse['response']['response']['data']['pasien_no_hp'],
                        'pasien_domisili'          => $dataPasienResponse['response']['response']['data']['pasien_alamat'],
                        'pasien_alamat'            => 'DS. ' . $dataPasienResponse['response']['response']['data']['kelurahan_nama'] . ', ' . $dataPasienResponse['response']['response']['data']['pasien_rt'] . '/' . $dataPasienResponse['response']['response']['data']['pasien_rw'] . ', KEC.' . $dataPasienResponse['response']['response']['data']['kecamatan_nama'] . ', KAB.' . $dataPasienResponse['response']['response']['data']['kabupaten_nama'],
                        'provinsi_id'              => $dataPasienResponse['response']['response']['data']['provinsi_id'],
                        'kabupaten_id'             => $dataPasienResponse['response']['response']['data']['kabupaten_id'],
                        'kecamatan_id'             => $dataPasienResponse['response']['response']['data']['kecamatan_id'],
                        'kelurahan_id'             => $dataPasienResponse['response']['response']['data']['kelurahan_id'],
                        'pasien_rt'                => $dataPasienResponse['response']['response']['data']['pasien_rt'],
                        'pasien_rw'                => $dataPasienResponse['response']['response']['data']['pasien_rw'],
                    ];
                }
            }

            if (empty($pendaftaranData)) {
                return response()->json([
                    'metadata' => [
                        'message' => 'Data Pasien Tidak Ditemukan Pada Kunjungan Hari Ini',
                        'code'    => 404, // Not Found
                    ],
                    'response' => null,
                ]);
            }

            // Build the response
            $response = [
                'metadata' => [
                    'message' => 'Data Pasien Ditemukan',
                    'code'    => 200,
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
        $params   = $request->all();
        $tglAwal  = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');

        $kominfo                 = new KominfoModel();
        $dataPendaftaranResponse = $kominfo->pendaftaranRequest($params);

        // Build response
        $res = $dataPendaftaranResponse;

        $res = [
            "status_pulang"            => "Belum Pulang",
            "no_reg"                   => "2024072000001",
            "id"                       => "105052",
            "no_trans"                 => 0,
            "antrean_nomor"            => "001",
            "tanggal"                  => "2024-07-20",
            "penjamin_nama"            => "BPJS",
            "penjamin_nomor"           => "0002056884254",
            "jenis_kunjungan_nama"     => "Kontrol",
            "nomor_referensi"          => "1111R0020624K000343",
            "pasien_nik"               => "3302155506160001",
            "pasien_nama"              => "ALMIRA KHALIQA RAMADHANI",
            "pasien_no_rm"             => "024797",
            "pasien_tgl_lahir"         => "2016-06-15",
            "jenis_kelamin_nama"       => "P",
            "pasien_lama_baru"         => "LAMA",
            "rs_paru_pasien_lama_baru" => "L",
            "poli_nama"                => "PARU",
            "poli_sub_nama"            => "PARU",
            "dokter_nama"              => "dr. Agil Dananjaya, Sp.P",
            "daftar_by"                => "JKN",
            "waktu_daftar"             => "2024-06-23 14=>12=>23",
            "waktu_verifikasi"         => "2024-07-20 07=>44=>24",
            "admin_pendaftaran"        => "MUTMAINAH,A.Md.Kes",
            "log_id"                   => "204706",
            "keterangan"               => "SKIP LOKET PENDAFTARAN",
            "keterangan_urutan"        => "3",
            "pasien_umur"              => "8 Tahun 1 Bulan ",
            "pasien_umur_tahun"        => "8",
            "pasien_umur_bulan"        => "1",
            "pasien_umur_hari"         => "5",
        ];
        return response()->json($res);

    }
    // public function reportPendaftaran(Request $request)
    // {
    //     // ini_set('max_execution_time', 300); // 300 seconds = 5 minutes
    //     // ini_set('memory_limit', '512M');
    //     $params = $request->all();
    //     $kominfo = new KominfoModel();
    //     $dataPendaftaranResponse = $kominfo->pendaftaranRequest($params);
    //     // return $dataPendaftaranResponse;

    //     $jumlah_no_antrian = count($dataPendaftaranResponse);
    //     if (isset($dataPendaftaranResponse['code']) && $dataPendaftaranResponse['code'] == 201) {
    //         $jumlah_no_antrian = 0;
    //     }

    //     // Debugging: print the data received
    //     // return $dataPendaftaranResponse;

    //     // Filter data dengan keterangan "SELESAI DOPANGGIL PENDAFTARAN"
    //     // $filteredData = $dataPendaftaranResponse;
    //     $filteredData = array_filter($dataPendaftaranResponse, function ($item) {
    //         return isset($item['keterangan']) && $item['keterangan'] === 'SELESAI DIPANGGIL LOKET PENDAFTARAN';
    //     });

    //     // Debugging: print the filtered data
    //     // dd($filteredData);

    //     // Hitung jumlah berdasarkan penjamin_nama
    //     $jumlahBPJS = count(array_filter($filteredData, function ($item) {
    //         return isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS';
    //     }));
    //     $jumlahBPJS2 = count(array_filter($filteredData, function ($item) {
    //         return isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS PERIODE 2';
    //     }));
    //     $jumlahUMUM = count(array_filter($filteredData, function ($item) {
    //         return isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'UMUM';
    //     }));

    //     // Hitung jumlah berdasarkan pasien_lama_baru
    //     $jumlahLama = count(array_filter($filteredData, function ($item) {
    //         return isset($item['pasien_lama_baru']) && $item['pasien_lama_baru'] === 'LAMA';
    //     }));
    //     $jumlahLamaUmum = count(array_filter($filteredData, function ($item) {
    //         return isset($item['pasien_lama_baru']) && $item['pasien_lama_baru'] === 'LAMA' && isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'UMUM';
    //     }));

    //     $jumlahLamaBpjs = count(array_filter($filteredData, function ($item) {
    //         return isset($item['pasien_lama_baru']) && $item['pasien_lama_baru'] === 'LAMA' && isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS';
    //     }));
    //     $jumlahLamaBpjs2 = count(array_filter($filteredData, function ($item) {
    //         return isset($item['pasien_lama_baru']) && $item['pasien_lama_baru'] === 'LAMA' && isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS PERIODE 2';
    //     }));

    //     $jumlahBaru = count(array_filter($filteredData, function ($item) {
    //         return isset($item['pasien_lama_baru']) && $item['pasien_lama_baru'] === 'BARU';
    //     }));

    //     $jumlahBaruUmum = count(array_filter($filteredData, function ($item) {
    //         return isset($item['pasien_lama_baru']) && $item['pasien_lama_baru'] === 'BARU' && isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'UMUM';
    //     }));
    //     $jumlahBaruBpjs = count(array_filter($filteredData, function ($item) {
    //         return isset($item['pasien_lama_baru']) && $item['pasien_lama_baru'] === 'BARU' && isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS';
    //     }));
    //     $jumlahBaruBpjs2 = count(array_filter($filteredData, function ($item) {
    //         return isset($item['pasien_lama_baru']) && $item['pasien_lama_baru'] === 'BARU' && isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS PERIODE 2';
    //     }));

    //     // Hitung jumlah berdasarkan daftar_by
    //     $jumlahOTS = count(array_filter($filteredData, function ($item) {
    //         return isset($item['daftar_by']) && $item['daftar_by'] === 'OTS';
    //     }));
    //     $jumlahOTSUmum = count(array_filter($filteredData, function ($item) {
    //         return isset($item['daftar_by']) && $item['daftar_by'] === 'OTS' && isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'UMUM';
    //     }))
    //     ;
    //     $jumlahOTSBpjs = count(array_filter($filteredData, function ($item) {
    //         return isset($item['daftar_by']) && $item['daftar_by'] === 'OTS' && isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS';
    //     }));
    //     $jumlahOTSBpjs2 = count(array_filter($filteredData, function ($item) {
    //         return isset($item['daftar_by']) && $item['daftar_by'] === 'OTS' && isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS PERIODE 2';
    //     }));

    //     $jumlahJKN = count(array_filter($filteredData, function ($item) {
    //         return isset($item['daftar_by']) && $item['daftar_by'] === 'JKN';
    //     }));
    //     $jumlahJKNUmum = count(array_filter($filteredData, function ($item) {
    //         return isset($item['daftar_by']) && $item['daftar_by'] === 'JKN' && isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'UMUM';
    //     }));
    //     $jumlahJKNBpjs = count(array_filter($filteredData, function ($item) {
    //         return isset($item['daftar_by']) && $item['daftar_by'] === 'JKN' && isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS';
    //     }));
    //     $jumlahJKNBpjs2 = count(array_filter($filteredData, function ($item) {
    //         return isset($item['daftar_by']) && $item['daftar_by'] === 'JKN' && isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS PERIODE 2';
    //     }));

    //     $jumlahBatal = count(array_filter($dataPendaftaranResponse, function ($item) {
    //         return isset($item['keterangan']) && strpos($item['keterangan'], 'DIBATALKAN PADA') !== false;
    //     }));
    //     $jumlahBatalUmum = count(array_filter($dataPendaftaranResponse, function ($item) {
    //         return isset($item['keterangan']) && strpos($item['keterangan'], 'DIBATALKAN PADA') !== false && isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'UMUM';
    //     }));
    //     $jumlahBatalBpjs = count(array_filter($dataPendaftaranResponse, function ($item) {
    //         return isset($item['keterangan']) && strpos($item['keterangan'], 'DIBATALKAN PADA') !== false && isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS';
    //     }));
    //     $jumlahBatalBpjs2 = count(array_filter($dataPendaftaranResponse, function ($item) {
    //         return isset($item['keterangan']) && strpos($item['keterangan'], 'DIBATALKAN PADA') !== false && isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS PERIODE 2';
    //     }));

    //     $jumlahNoUmum = count(array_filter($dataPendaftaranResponse, function ($item) {
    //         return isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'UMUM';
    //     }));
    //     $jumlahNoBpjs = count(array_filter($dataPendaftaranResponse, function ($item) {
    //         return isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS';
    //     }));
    //     $jumlahNoBpjs2 = count(array_filter($dataPendaftaranResponse, function ($item) {
    //         return isset($item['penjamin_nama']) && $item['penjamin_nama'] === 'BPJS PERIODE 2';
    //     }));

    //     // Build response
    //     $jumlah = [
    //         'jumlah_no_antrian' => (int) $jumlah_no_antrian,
    //         'jumlah_no_umum' => (int) $jumlahNoUmum,
    //         'jumlah_no_bpjs' => (int) $jumlahNoBpjs,
    //         'jumlah_no_bpjs2' => (int) $jumlahNoBpjs2,
    //         'jumlah_pasien' => (int) count($filteredData),
    //         'jumlah_pasien_batal' => (int) $jumlahBatal,
    //         'jumlah_pasien_batal_UMUM' => (int) $jumlahBatalUmum,
    //         'jumlah_pasien_batal_BPJS' => (int) $jumlahBatalBpjs,
    //         'jumlah_pasien_batal_BPJS_2' => (int) $jumlahBatalBpjs2,
    //         'jumlah_UMUM' => (int) $jumlahUMUM,
    //         'jumlah_BPJS' => (int) $jumlahBPJS,
    //         'jumlah_BPJS_2' => (int) $jumlahBPJS2,
    //         'jumlah_pasien_LAMA' => (int) $jumlahLama,
    //         'jumlah_pasien_LAMA_UMUM' => (int) $jumlahLamaUmum,
    //         'jumlah_pasien_LAMA_BPJS' => (int) $jumlahLamaBpjs,
    //         'jumlah_pasien_LAMA_BPJS_2' => (int) $jumlahLamaBpjs2,
    //         'jumlah_pasien_BARU' => (int) $jumlahBaru,
    //         'jumlah_pasien_BARU_UMUM' => (int) $jumlahBaruUmum,
    //         'jumlah_pasien_BARU_BPJS' => (int) $jumlahBaruBpjs,
    //         'jumlah_pasien_BARU_BPJS_2' => (int) $jumlahBaruBpjs2,
    //         'jumlah_daftar_OTS' => (int) $jumlahOTS,
    //         'jumlah_daftar_OTS_UMUM' => (int) $jumlahOTSUmum,
    //         'jumlah_daftar_OTS_BPJS' => (int) $jumlahOTSBpjs,
    //         'jumlah_daftar_OTS_BPJS_2' => (int) $jumlahOTSBpjs2,
    //         'jumlah_daftar_JKN' => (int) $jumlahJKN,
    //         'jumlah_daftar_JKN_UMUM' => (int) $jumlahJKNUmum,
    //         'jumlah_daftar_JKN_BPJS' => (int) $jumlahJKNBpjs,
    //         'jumlah_daftar_JKN_BPJS_2' => (int) $jumlahJKNBpjs2,
    //     ];

    //     $rows = [
    //         'Jumlah No Antrian' => [
    //             'total' => $jumlah_no_antrian,
    //             'bpjs' => $jumlahNoBpjs,
    //             'bpjs2' => $jumlahNoBpjs2,
    //             'umum' => $jumlahNoUmum,
    //         ],
    //         'Jumlah Pasien' => [
    //             'total' => count($filteredData),
    //             'bpjs' => $jumlahBPJS,
    //             'bpjs2' => $jumlahBPJS2,
    //             'umum' => $jumlahUMUM,
    //         ],
    //         'Pasien Lama' => [
    //             'total' => $jumlahLama,
    //             'bpjs' => $jumlahLamaBpjs,
    //             'bpjs2' => $jumlahLamaBpjs2,
    //             'umum' => $jumlahLamaUmum,
    //         ],
    //         'Pasien Baru' => [
    //             'total' => $jumlahBaru,
    //             'bpjs' => $jumlahBaruBpjs,
    //             'bpjs2' => $jumlahBaruBpjs2,
    //             'umum' => $jumlahBaruUmum,
    //         ],
    //         'Jumlah Pasien Batal' => [
    //             'total' => $jumlahBatal,
    //             'bpjs' => $jumlahBatalBpjs,
    //             'bpjs2' => $jumlahBatalBpjs2,
    //             'umum' => $jumlahBatalUmum,
    //         ],
    //         'Daftar OTS' => [
    //             'total' => $jumlahOTS,
    //             'bpjs' => $jumlahOTSBpjs,
    //             'bpjs2' => $jumlahOTSBpjs2,
    //             'umum' => $jumlahOTSUmum,
    //         ],
    //         'Daftar JKN' => [
    //             'total' => $jumlahJKN,
    //             'bpjs' => $jumlahJKNBpjs,
    //             'bpjs2' => $jumlahJKNBpjs2,
    //             'umum' => $jumlahJKNUmum,
    //         ],
    //     ];

    //     $html = '<table class="table table-bordered table-hover dataTable dtr-inline" id="rekapTotal" width="100%" cellspacing="0">';
    //     $html .= '
    //         <thead class="bg bg-teal table-bordered border-warning">
    //             <tr>
    //                 <th rowspan="2" class="align-middle">Keterangan</th>
    //                 <th rowspan="2" class="text-center align-middle">Total</th>
    //                 <th colspan="3" class="text-center">Jaminan</th>
    //             </tr>
    //             <tr>
    //                 <th class="text-center">BPJS</th>
    //                 <th class="text-center">BPJS PERIODE 2</th>
    //                 <th class="text-center">UMUM</th>
    //             </tr>
    //         </thead>
    //         <tbody>';

    //     foreach ($rows as $label => $data) {
    //         $html .= '<tr>';
    //         $html .= '<td>' . $label . '</td>';
    //         $html .= '<td class="text-center">' . $data['total'] . '</td>';
    //         $html .= '<td class="text-center">' . $data['bpjs'] . '</td>';
    //         $html .= '<td class="text-center">' . $data['bpjs2'] . '</td>';
    //         $html .= '<td class="text-center">' . $data['umum'] . '</td>';
    //         $html .= '</tr>';
    //     }

    //     $html .= '</tbody></table>';

    //     $data = array_values($filteredData);

    //     $res = [
    //         "total" => $jumlah,
    //         "data" => $data,
    //         "html" => $html,
    //     ];

    //     return response()->json($res);
    // }

    public function reportPendaftaran(Request $request)
    {
        $params  = $request->all();
        $kominfo = new KominfoModel();
        $data    = $kominfo->pendaftaranRequest($params);

        $res = $this->reportPendaftaranProses($data);

        return response()->json($res);

    }
    public function reportPusatDataPendaftaran($tahun)
    {
        $kominfo = new KominfoModel();
        $result  = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            // Format bulan dengan leading zero, misal: 01, 02, ..., 12
            $bulanFormatted = str_pad($bulan, 2, '0', STR_PAD_LEFT);

            // Buat tanggal awal dan akhir bulan
            $tanggal_awal  = "$tahun-$bulanFormatted-01";
            $tanggal_akhir = date("Y-m-t", strtotime($tanggal_awal)); // tanggal akhir bulan

            // Siapkan parameter untuk request
            $params = [
                'tanggal_awal'  => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
                'no_rm'         => '',
            ];

            // Ambil data dari model
            $data = $kominfo->pendaftaranRequest($params);

            // Proses data jika perlu
            $res = $this->reportPendaftaranProses($data, $bulanFormatted);

            // Simpan hasil ke dalam array result dengan struktur baru
            $result['html'][$bulanFormatted]  = $res['html'];
            $result['total'][$bulanFormatted] = $res['total'];

        }

        return response()->json($result);
    }

    public function reportPendaftaranProses($data, $bulan = null)
    {

        $jumlah_no_antrian = is_array($data) ? count($data) : 0;
        if (isset($data['code']) && $data['code'] == 201) {
            $jumlah_no_antrian = 0;
            $data              = []; // Tidak ada data valid
        }

        $filtered = array_filter($data, fn($item) => ($item['keterangan'] ?? '') === 'SELESAI DIPANGGIL LOKET PENDAFTARAN');

        $countIf = fn($list, $callback) => count(array_filter($list, $callback));

        $penjamins = ['UMUM', 'BPJS', 'BPJS PERIODE 2'];
        $statuses  = ['LAMA', 'BARU'];
        $daftarBy  = ['OTS', 'JKN'];

        $result = [
            'jumlah_no_antrian'   => $jumlah_no_antrian,
            'jumlah_pasien'       => count($filtered),
            'jumlah_pasien_batal' => $countIf($data, fn($d) => str_contains($d['keterangan'] ?? '', 'DIBATALKAN PADA')),
        ];

        // Batal per penjamin
        foreach ($penjamins as $penjamin) {
            $key                                  = strtolower(str_replace(' ', '_', $penjamin));
            $result["jumlah_no_{$key}"]           = $countIf($data, fn($d) => ($d['penjamin_nama'] ?? '') === $penjamin);
            $result["jumlah_pasien_batal_{$key}"] = $countIf($data, fn($d) =>
                str_contains($d['keterangan'] ?? '', 'DIBATALKAN PADA') && ($d['penjamin_nama'] ?? '') === $penjamin
            );
            $result["jumlah_{$key}"] = $countIf($filtered, fn($d) => ($d['penjamin_nama'] ?? '') === $penjamin);
        }

        // Status LAMA/BARU per penjamin
        foreach ($statuses as $status) {
            $status_key                            = strtolower($status);
            $result["jumlah_pasien_{$status_key}"] = $countIf($filtered, fn($d) => ($d['pasien_lama_baru'] ?? '') === $status);

            foreach ($penjamins as $penjamin) {
                $penjamin_key                                          = strtolower(str_replace(' ', '_', $penjamin));
                $result["jumlah_pasien_{$status_key}_{$penjamin_key}"] = $countIf($filtered, fn($d) =>
                    ($d['pasien_lama_baru'] ?? '') === $status && ($d['penjamin_nama'] ?? '') === $penjamin
                );
            }
        }

        // Daftar_by: OTS / JKN
        foreach ($daftarBy as $method) {
            $method_key                     = strtolower($method);
            $result["jumlah_{$method_key}"] = $countIf($filtered, fn($d) => ($d['daftar_by'] ?? '') === $method);

            foreach ($penjamins as $penjamin) {
                $penjamin_key                                   = strtolower(str_replace(' ', '_', $penjamin));
                $result["jumlah_{$method_key}_{$penjamin_key}"] = $countIf($filtered, fn($d) =>
                    ($d['daftar_by'] ?? '') === $method && ($d['penjamin_nama'] ?? '') === $penjamin
                );
            }
        }

        $html = $this->getTablePendaftaran($result, $bulan);

        $res = [
            "total" => $result,
            "data"  => array_values($filtered),
            "html"  => $html,
        ];

        return $res;
    }

    private function getTablePendaftaran($data, $id)
    {
        // dd($data);
        $id   = $id !== null ? $id : '';
        $rows = [
            'Jumlah No Antrian' => [
                'total' => $data['jumlah_no_antrian'],
                'bpjs'  => $data['jumlah_no_bpjs'],
                'bpjs2' => $data['jumlah_no_bpjs_periode_2'],
                'umum'  => $data['jumlah_no_umum'],
            ],
            'Pasien Terdaftar'  => [
                'total' => $data['jumlah_pasien'],
                'bpjs'  => $data['jumlah_bpjs'],
                'bpjs2' => $data['jumlah_bpjs_periode_2'],
                'umum'  => $data['jumlah_umum'],
            ],
            'Pasien Batal'      => [
                'total' => $data['jumlah_pasien_batal'],
                'bpjs'  => $data['jumlah_pasien_batal_bpjs'],
                'bpjs2' => $data['jumlah_pasien_batal_bpjs_periode_2'],
                'umum'  => $data['jumlah_pasien_batal_umum'],
            ],

            'Paien Lama'        => [
                'total' => $data['jumlah_pasien_lama'],
                'bpjs'  => $data['jumlah_pasien_lama_bpjs'],
                'bpjs2' => $data['jumlah_pasien_lama_bpjs_periode_2'],
                'umum'  => $data['jumlah_pasien_lama_umum'],
            ],
            'Pasien Baru'       => [
                'total' => $data['jumlah_pasien_baru'],
                'bpjs'  => $data['jumlah_pasien_baru_bpjs'],
                'bpjs2' => $data['jumlah_pasien_baru_bpjs_periode_2'],
                'umum'  => $data['jumlah_pasien_baru_umum'],
            ],

            'Daftar OTS'        => [
                'total' => $data['jumlah_ots'],
                'bpjs'  => $data['jumlah_ots_bpjs'],
                'bpjs2' => $data['jumlah_ots_bpjs_periode_2'],
                'umum'  => $data['jumlah_ots_umum'],
            ],
            'Daftar JKN'        => [
                'total' => $data['jumlah_jkn'],
                'bpjs'  => $data['jumlah_jkn_bpjs'],
                'bpjs2' => $data['jumlah_jkn_bpjs_periode_2'],
                'umum'  => $data['jumlah_jkn_umum'],
            ],
        ];

        $html = '<table class="table table-bordered table-hover dataTable dtr-inline" id="rekapTotal.' . $id . '" width="100%" cellspacing="0">';
        $html .= '
                    <thead class="bg bg-teal table-bordered border-warning">
                        <tr>
                            <th rowspan="2" class="align-middle">Keterangan</th>
                            <th rowspan="2" class="text-center align-middle">Total</th>
                            <th colspan="3" class="text-center">Jaminan</th>
                        </tr>
                        <tr>
                            <th class="text-center">BPJS</th>
                            <th class="text-center">BPJS PERIODE 2</th>
                            <th class="text-center">UMUM</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($rows as $label => $data) {
            $html .= '<tr>';
            $html .= '<td>' . $label . '</td>';
            $html .= '<td class="text-center">' . $data['total'] . '</td>';
            $html .= '<td class="text-center">' . $data['bpjs'] . '</td>';
            $html .= '<td class="text-center">' . $data['bpjs2'] . '</td>';
            $html .= '<td class="text-center">' . $data['umum'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        return $html;
    }

    private function generateQrCodeWithLogo($dokter, $no_rm, $nama)
    {
        // Data untuk QR Code (misalnya tanda tangan)
        // $data = 'Dokumen resume medis a.n' . $nama . ' (' . $no_rm . ') telah di tandatangani oleh ' . $dokter.'. pada ';
        $data = 'Saya, ' . $dokter . ' telah menandatangani resume medis pasien a.n ' . $nama . ' (' . $no_rm . ') pada ';

        // Buat QR Code dengan logo
        $qrCode = QrCode::format('png')
            ->size(300)
            ->errorCorrection('H') // Tingkat toleransi tinggi agar QR Code tetap terbaca
            ->generate($data);
        // dd($qrCode);
        $base64QrCode = base64_encode($qrCode);
        return $base64QrCode;
    }

    public function resumePasienAll($tgl)
    {
        $params = [
            'no_rm'         => "",
            'tanggal_awal'  => $tgl,
            'tanggal_akhir' => $tgl,
        ];
        $client = new KominfoModel();

        try {
            $data              = $client->cpptRequest($params);
            $kunjungan         = $client->pendaftaranRequest($params)[0]['rs_paru_pasien_lama_baru'];
            $resumePasienArray = array_filter($data['response']['data'], function ($item) {
                return isset($item['penjamin_nama']) && $item['penjamin_nama'] !== 'UMUM';
            });
            $resumePasienArray = array_values($resumePasienArray);

            $semuaPasien = [];

            if (is_array($resumePasienArray) && count($resumePasienArray) > 0) {
                foreach ($resumePasienArray as $resumePasien) {
                    if (! isset($resumePasien['id_cppt'])) {
                        continue;
                    }

                    $resumePasien = (object) $resumePasien;

                    // Obat
                    $obats = [];
                    foreach ($resumePasien->resep_obat ?? [] as $obat) {
                        $obats[] = [
                            'no_resep' => $obat['no_resep'],
                            'aturan'   => $obat['signa_1'] . ' X ' . $obat['signa_2'] . ' ' . $obat['aturan_pakai'],
                            'nm_obat'  => $obat['resep_obat_detail'],
                        ];
                    }

                    // Diagnosa
                    $dxs = [];
                    foreach ($resumePasien->diagnosa ?? [] as $dx) {
                        $kdDx  = $dx['kode_diagnosa'];
                        $dxMap = DiagnosaMapModel::where('kdDx', $kdDx)->first();
                        $nmDX  = $dxMap ? $dxMap->mapping : $dx['nama_diagnosa'];
                        $dxs[] = [
                            'kode_diagnosa' => $dx['kode_diagnosa'],
                            'nama_diagnosa' => $dx['nama_diagnosa'],
                            'nmDx'          => $nmDX,
                        ];
                    }

                    // Alamat
                    $alamat = ucwords(strtolower($resumePasien->kelurahan_nama)) . ', ' .
                    $resumePasien->pasien_rt . '/' . $resumePasien->pasien_rw . ', ' .
                    ucwords(strtolower($resumePasien->kecamatan_nama)) . ', ' .
                    ucwords(strtolower($resumePasien->kabupaten_nama)) . ', ' .
                    ucwords(strtolower($resumePasien->provinsi_nama));
                    $norm = $resumePasien->pasien_no_rm;

                    // RO
                    $dataRo = ROTransaksiModel::with('film', 'foto', 'proyeksi')
                        ->where('norm', $norm)
                        ->where('tgltrans', $tgl)
                        ->first();
                    $ro = $dataRo ? [
                        'noReg'     => $dataRo->noreg,
                        'tglRo'     => Carbon::parse($dataRo->tgltrans)->format('d-m-Y'),
                        'jenisFoto' => $dataRo->foto->nmFoto ?? '',
                        'proyeksi'  => $dataRo->proyeksi->proyeksi ?? '',
                    ] : [];

                    // Lab
                    $lab     = [];
                    $dataLab = LaboratoriumHasilModel::with('pemeriksaan')
                        ->where('norm', $norm)
                        ->whereDate('created_at', $tgl)
                        ->get();
                    foreach ($dataLab as $item) {
                        $lab[] = [
                            'idLab'       => $item->idLab,
                            'idLayanan'   => $item->idLayanan,
                            'tanggal'     => Carbon::parse($item->created_at)->format('d-m-Y'),
                            'hasil'       => $item->hasil,
                            'pemeriksaan' => str_replace(' (Stik)', '', $item->pemeriksaan->nmLayanan),
                            'satuan'      => $item->pemeriksaan->satuan,
                            'normal'      => $item->pemeriksaan->normal,
                        ];
                    }

                    // Tindakan
                    $tindakan     = [];
                    $dataTindakan = IGDTransModel::with('tindakan', 'transbmhp.bmhp')
                        ->where('norm', $norm)
                        ->whereDate('created_at', $tgl)
                        ->get();
                    foreach ($dataTindakan as $item) {
                        $bmhp = [];
                        foreach ($item->transbmhp as $key) {
                            $bmhp[] = [
                                'jumlah'  => $key->jml,
                                'nmBmhp'  => $key->bmhp->nmObat,
                                'sediaan' => $key->sediaan,
                            ];
                        }

                        $tindakan[] = [
                            'id'       => $item->id,
                            'kdTind'   => $item->kdTind,
                            'tanggal'  => Carbon::parse($item->created_at)->format('d-m-Y'),
                            'tindakan' => preg_replace('/\s?\(.*?\)/', '', $item->tindakan->nmTindakan ?? "Tidak ada Tindakan"),
                            'bmhp'     => $bmhp,
                        ];
                    }

                    // Simpan semua data pasien
                    $semuaPasien[] = [
                        'resume'   => $resumePasien,
                        'alamat'   => $alamat,
                        'ro'       => $ro,
                        'lab'      => $lab,
                        'tindakan' => $tindakan,
                        'obats'    => $obats,
                        'dxs'      => $dxs,
                    ];
                }
            }
            // return $semuaPasien;

            return view('Laporan.Pasien.resumeAll', compact('semuaPasien', 'kunjungan', 'tgl'));
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mencari data: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat mencari data: ' . $e->getMessage()], 500);
        }
    }

    public function resumePasien($no_rm, $tgl)
    {
        // $title = 'Laporan Pendaftaran';

        // return view('Template.newPage')->with('title', $title);
        // $params = $request->all();
        $params = [
            'no_rm'         => $no_rm,
            'tanggal_awal'  => $tgl,
            'tanggal_akhir' => $tgl,
        ];
        $client = new KominfoModel();
        try {
            $data = $client->cpptRequest($params);
            // dd($data);
            $kunjungan         = $client->pendaftaranRequest($params)[0]['rs_paru_pasien_lama_baru'];
            $resumePasienArray = $data['response']['data'];
            // return ($resumePasienArray);
            // Cek jika $resumePasienArray adalah array
            if (is_array($resumePasienArray) && count($resumePasienArray) > 0 && $resumePasienArray[0]['id_cppt'] == null) {
                // Ambil objek pertama dari array
                $resumePasien = (object) $resumePasienArray[1];
            } elseif (is_array($resumePasienArray) && count($resumePasienArray) > 0) {
                $resumePasien = (object) $resumePasienArray[0];
            } else {
                // Jika tidak ada data, kembalikan sebagai objek kosong
                $resumePasien = new \stdClass();
            }

            // return $resumePasien; // Mengembalikan objek $resumePasien
            $dataObats = $resumePasien->resep_obat;
            $obats     = [];
            // return $dataObats;

            foreach ($dataObats as $obat) {

                $obats[] = [
                    'no_resep' => $obat['no_resep'],
                    'aturan'   => $obat['signa_1'] . ' X ' . $obat['signa_2'] . ' ' . $obat['aturan_pakai'],
                    'nm_obat'  => $obat['resep_obat_detail'],
                ];
            }

            // return $obats;
            $dataDx = $resumePasien->diagnosa;
            $dxs    = [];
            // return $dataDx;

            foreach ($dataDx as $dx) {
                $kdDx  = $dx['kode_diagnosa'];
                $dxMap = DiagnosaMapModel::where('kdDx', $kdDx)->first();
                $nmDX  = $dxMap != null ? $dxMap->mapping : $dx['nama_diagnosa'];
                $dxs[] = [
                    'kode_diagnosa' => $dx['kode_diagnosa'],
                    'nama_diagnosa' => $dx['nama_diagnosa'],
                    'nmDx'          => $nmDX,
                ];
            }

            // return $dxs;

            $alamat = ucwords(strtolower($resumePasien->kelurahan_nama)) . ', ' .
            $resumePasien->pasien_rt . '/' . $resumePasien->pasien_rw . ', ' .
            ucwords(strtolower($resumePasien->kecamatan_nama)) . ', ' .
            ucwords(strtolower($resumePasien->kabupaten_nama)) . ', ' .
            ucwords(strtolower($resumePasien->provinsi_nama));
            $norm    = $no_rm;
            $tanggal = $tgl;
            // $norm = '027820';
            // $tanggal = '2024-10-05';

            //data ro
            $dataRo = ROTransaksiModel::with('film', 'foto', 'proyeksi')
                ->where('norm', $norm)
                ->where('tgltrans', $tanggal)
                ->first();
            // dd($dataRo);
            if (! $dataRo) {
                $ro = [];
            } else {
                $ro = [
                    'noReg'     => $dataRo->noreg,
                    'tglRo'     => Carbon::parse($dataRo->tgltrans)->format('d-m-Y'),
                    'jenisFoto' => $dataRo->foto->nmFoto,
                    'proyeksi'  => $dataRo->proyeksi->proyeksi,
                ];
            }
            // return $ro;

            //data lab
            $dataLab = LaboratoriumHasilModel::with('pemeriksaan')
                ->where('norm', $norm)
                ->where('created_at', 'like', '%' . Carbon::parse($tanggal)->format('Y-m-d') . '%')->get();
            // return $dataLab;
            if (! $dataLab) {
                $lab = [];
            } else {
                $lab = [];
                foreach ($dataLab as $item) {
                    $lab[] = [
                        'idLab'       => $item->idLab,
                        'idLayanan'   => $item->idLayanan,
                        'tanggal'     => Carbon::parse($item->created_at)->format('d-m-Y'),
                        'hasil'       => $item->hasil,
                        // Menghapus (stik) dari nama pemeriksaan
                        'pemeriksaan' => str_replace(' (Stik)', '', $item->pemeriksaan->nmLayanan),
                        'satuan'      => $item->pemeriksaan->satuan,
                        'normal'      => $item->pemeriksaan->normal,
                        'totalItem'   => count($dataLab),
                    ];
                }
            }

            // return $lab;

            //data tindakan
            $dataTindakan = IGDTransModel::with('tindakan', 'transbmhp.bmhp')
                ->where('norm', $norm)
                ->where('created_at', 'like', '%' . Carbon::parse($tanggal)->format('Y-m-d') . '%')->get();
            // return $dataTindakan;

            $tindakan = [];
            foreach ($dataTindakan as $item) {
                $bmhp = [];
                foreach ($item->transbmhp as $key) {
                    $bmhp[] = [
                        'jumlah'  => $key->jml,
                        'nmBmhp'  => $key->bmhp->nmObat,
                        'sediaan' => $key->sediaan,
                    ];
                }

                $tindakan[] = [
                    'id'        => $item->id,
                    'kdTind'    => $item->kdTind,
                    'tanggal'   => Carbon::parse($item->created_at)->format('d-m-Y'),
                    // 'tindakan' => $item->tindakan->nmTindakan,
                    'tindakan'  => preg_replace('/\s?\(.*?\)/', '', $item->tindakan->nmTindakan ?? "Tidak ada Tindakan"),
                    'bmhp'      => $bmhp,
                    'totalItem' => count($dataTindakan),
                ];
            }
            // return $tindakan;
            // $lab = [];
            // $ro = [];

            return view('Laporan.resume', compact('resumePasien', 'alamat', 'ro', 'lab', 'tindakan', 'obats', 'dxs', 'kunjungan'));
            // $ttd = $this->generateQrCodeWithLogo($resumePasien->dokter_nama, $no_rm, $resumePasien->pasien_nama);
            // return view('Laporan.resume1', compact('resumePasien', 'alamat', 'ro', 'lab', 'tindakan'));

        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mencari data: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat mencari data: ' . $e->getMessage()], 500);
        }
    }

    public function pendaftaran2(Request $request)
    {
        $limit  = 10; // Set the limit to 5
        $params = $request->all();

        $kominfo                 = new KominfoModel();
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
                'id'                       => $data['id'],
                'no_reg'                   => $data['no_reg'],
                'no_trans'                 => $data['no_trans'],
                'antrean_nomor'            => $data['antrean_nomor'],
                'tanggal'                  => $data['tanggal'],
                'penjamin_nama'            => $data['penjamin_nama'],
                'jenis_kunjungan_nama'     => $data['jenis_kunjungan_nama'],
                "penjamin_nomor"           => $data['penjamin_nomor'],
                "jenis_kunjungan_nama"     => $data['jenis_kunjungan_nama'],
                "nomor_referensi"          => $data['nomor_referensi'],
                'pasien_no_rm'             => $data['pasien_no_rm'],
                'pasien_lama_baru'         => $data['pasien_lama_baru'],
                'rs_paru_pasien_lama_baru' => $data['rs_paru_pasien_lama_baru'],
                'poli_nama'                => $data['poli_nama'],
                'poli_sub_nama'            => $data['poli_sub_nama'],
                'dokter_nama'              => $data['dokter_nama'],
                'waktu_daftar'             => $data['waktu_daftar'],
                'waktu_verifikasi'         => $data['waktu_verifikasi'],
                'admin_pendaftaran'        => $data['admin_pendaftaran'],
                'log_id'                   => $data['log_id'],
                'keterangan_urutan'        => $data['keterangan_urutan'],
                'pasien_umur'              => $data['pasien_umur_tahun'] . ' Thn ' . $data['pasien_umur_bulan'] . ' Bln ',

                // Data pasien tambahan dari API kedua
                'pasien_nik'               => $pasienData['pasien_nik'] ?? null,
                'pasien_nama'              => $pasienData['pasien_nama'] ?? null,
                'jenis_kelamin_nama'       => $pasienData['jenis_kelamin_nama'] ?? null,
                'pasien_tempat_lahir'      => $pasienData['pasien_tempat_lahir'] ?? null,
                'pasien_tgl_lahir'         => $pasienData['pasien_tgl_lahir'] ?? null,
                'pasien_no_hp'             => $pasienData['pasien_no_hp'] ?? null,
                'pasien_alamat'            => $pasienData['pasien_alamat'] ?? null,
                'provinsi_id'              => $pasienData['provinsi_id'] ?? null,
                'kabupaten_id'             => $pasienData['kabupaten_id'] ?? null,
                'kecamatan_id'             => $pasienData['kecamatan_id'] ?? null,
                'kelurahan_id'             => $pasienData['kelurahan_id'] ?? null,
                'pasien_rt'                => $pasienData['pasien_rt'] ?? null,
                'pasien_rw'                => $pasienData['pasien_rw'] ?? null,
            ];

            $counter++;
            // }
        }

        if (empty($antrian)) {
            return response()->json([
                'metadata' => [
                    'message' => 'Data Pasien Tidak Ditemukan Pada Kunjungan Hari Ini',
                    'code'    => 404,
                ],
                'response' => null,
            ]);
        }

        // Build response
        $res = [
            'metadata' => [
                'message' => 'Data Pendaftaran Ditemukan',
                'code'    => 200,
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
            $uname   = env('USERNAME_KOMINFO');
            $pass    = env('PASSWORD_KOMINFO');
            $tanggal = $request->input('tanggal');

            // Fetch data pendaftaran from API
            $dataPendaftaranResponse = $this->fetchDataFromApi(
                'https://kkpm.banyumaskab.go.id/api/pendaftaran/data_pendaftaran',
                [
                    'username' => $uname,
                    'password' => $pass,
                    'tanggal'  => $tanggal,
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
                    'id'                       => $data['id'],
                    'no_reg'                   => $data['no_reg'],
                    'no_trans'                 => $data['no_trans'],
                    'antrean_nomor'            => $data['antrean_nomor'],
                    'tanggal'                  => $data['tanggal'],
                    'penjamin_nama'            => $data['penjamin_nama'],
                    'jenis_kunjungan_nama'     => $data['jenis_kunjungan_nama'],
                    'pasien_no_rm'             => $data['pasien_no_rm'],
                    'pasien_nama'              => $data['pasien_nama'],
                    'pasien_nik'               => $data['pasien_nik'],
                    'pasien_lama_baru'         => $data['pasien_lama_baru'],
                    'rs_paru_pasien_lama_baru' => $data['rs_paru_pasien_lama_baru'],
                    'poli_nama'                => $data['poli_nama'],
                    'poli_sub_nama'            => $data['poli_sub_nama'],
                    'dokter_nama'              => $data['dokter_nama'],
                    'waktu_daftar'             => $data['waktu_daftar'],
                    'waktu_verifikasi'         => $data['waktu_verifikasi'],
                    'admin_pendaftaran'        => $data['admin_pendaftaran'],
                    'log_id'                   => $data['log_id'],
                    'keterangan_urutan'        => $data['keterangan_urutan'],
                    'pasien_umur'              => $data['pasien_umur_tahun'] . ' Tahun ' . $data['pasien_umur_bulan'] . ' Bulan ' . $data['pasien_umur_hari'] . ' Hari',
                ];
            }

            if (empty($antrian)) {
                return response()->json([
                    'metadata' => [
                        'message' => 'Data Pasien Tidak Ditemukan Pada Kunjungan Hari Ini',
                        'code'    => 404,
                    ],
                    'response' => null,
                ]);
            }

            // Build response
            $response = [
                'metadata' => [
                    'message' => 'Data Pasien Ditemukan',
                    'code'    => 200,
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
                    'code'    => 500,
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
                    'code'    => $httpCode,
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
                    'code'    => 500,
                ],
                'response' => null,
            ];
        }

        return [
            'metadata' => [
                'message' => 'Data found',
                'code'    => 200,
            ],
            'response' => $responseData,
        ];
    }

    public function logAntrian(Request $request)
    {
        $id    = $request->input('id');
        $model = new KominfoModel();
        $data  = $model->getLogAntrian($id);

        return response()->json($data);
    }

    // public function antrianAll(Request $request)
    // {
    //     if (! $request->has('tanggal')) {
    //         return response()->json(['error' => 'Tanggal Belum Di Isi'], 400);
    //     }

    //     $tanggal = $request->input('tanggal', date('Y-m-d'));
    //     $ruang   = $request->input('ruang');
    //     $params  = [
    //         'tanggal_awal'  => $tanggal,
    //         'tanggal_akhir' => $tanggal,
    //         'no_rm'         => '',
    //     ];
    //     $model = new KominfoModel();
    //     $data  = $model->pendaftaranRequest($params);

    //     // return $data;
    //     //jika respon {"error":"Error decoding JSON response: Syntax error"}
    //     if (isset($data['error'])) {
    //         return response()->json(['error' => $data['error']], 500);
    //     }

    //     if (! isset($data) || ! is_array($data)) {
    //         return response()->json(['error' => 'Invalid data format'], 500);
    //     }

    //     if (isset($request['tes'])) {
    //         return response()->json($data);
    //     }

    //     $filteredData = array_values(array_filter($data, function ($d) {
    //         // return $d['pasien_nama'] !== 0;
    //         // return $d['no_trans'] !== 0;
    //         return $d['keterangan'] === 'SELESAI DIPANGGIL LOKET PENDAFTARAN';
    //     }));

    //     $doctorNipMap = [
    //         'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
    //         'dr. Agil Dananjaya, Sp.P'                  => '9',
    //         'dr. Filly Ulfa Kusumawardani'              => '198907252019022004',
    //         'dr. Sigit Dwiyanto'                        => '198903142022031005',
    //     ];
    //     $tes = $filteredData;

    //     foreach ($filteredData as &$item) {
    //         $norm        = $item['pasien_no_rm'];
    //         $dokter_nama = $item['dokter_nama'];

    //         try {
    //             switch ($ruang) {
    //                 case 'ro':
    //                     $tsRo = ROTransaksiModel::where('norm', $norm)
    //                         ->whereDate('tgltrans', $tanggal)->first();
    //                     // $foto = ROTransaksiHasilModel::where('norm', $norm)
    //                     $foto = RoHasilModel::where('norm', $norm)
    //                         ->whereDate('tanggal', $tanggal)->first();
    //                     $item['status'] = ! $tsRo && ! $foto ? 'Tidak Ada Transaksi' :
    //                     ($tsRo && ! $foto ? 'Belum Upload Foto Thorax' : 'Sudah Selesai');
    //                     break;

    //                 case 'igd':
    //                     $ts = IGDTransModel::with('transbmhp')->where('norm', $norm)
    //                         ->whereDate('created_at', $tanggal)->first();
    //                     $item['status'] = ! $ts ? 'Tidak Ada Transaksi' :
    //                     ($ts->transbmhp == null ? 'Belum Ada Transaksi BMHP' : 'Sudah Selesai');
    //                     break;

    //                 case 'dots':
    //                     $ts = DotsTransModel::where('norm', $norm)
    //                         ->whereDate('created_at', $tanggal)->first();
    //                     $item['status'] = ! $ts ? 'Tidak Ada Transaksi' : 'Sudah Selesai';
    //                     break;

    //                 case 'lab':
    //                     $ts = LaboratoriumKunjunganModel::where('norm', $norm)
    //                         ->whereDate('created_at', $tanggal)->first();
    //                     $item['status'] = ! $ts ? 'Tidak Ada Transaksi' : 'Sudah Selesai';
    //                     break;
    //                 case 'kasir':
    //                     $ts = KasirTransModel::where('norm', $norm)
    //                         ->whereDate('created_at', $tanggal)->first();
    //                     $item['status'] = ! $ts ? 'Tidak Ada Transaksi' : 'Sudah Selesai';
    //                     break;
    //                 case 'farmasi':
    //                     $ts = KasirTransModel::where('norm', $norm)
    //                         ->whereDate('created_at', $tanggal)->first();
    //                     $pulang = KunjunganWaktuSelesai::where('norm', $norm)
    //                         ->whereDate('created_at', $tanggal)->first();
    //                     $item['status'] = ! $ts ? 'Tidak Ada Transaksi' : 'Sudah Selesai';
    //                     $item['button'] = $pulang->waktu_selesai_farmasi !== null ? 'success' : 'warning';
    //                     break;

    //                 default:
    //                     $item['status'] = 'Unknown ruang';
    //             }
    //         } catch (\Exception $e) {
    //             Log::error('Database connection failed: ' . $e->getMessage());
    //             $item['status'] = 'Database connection error';
    //         }

    //         $item['nip_dokter'] = $doctorNipMap[$dokter_nama] ?? 'Unknown';
    //     }

    //     return response()->json([
    //         'metadata' => [
    //             'message' => 'Data Pasien Ditemukan',
    //             'code'    => 200,
    //         ],
    //         'response' => [
    //             'data' => $filteredData,
    //             // 'data' => $tes,
    //         ],
    //     ]);
    // }

    public function antrianAll(Request $request)
    {
        if (! $request->has('tanggal')) {
            return response()->json(['error' => 'Tanggal Belum Di Isi'], 400);
        }

        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $ruang   = $request->input('ruang');

        $params = [
            'tanggal_awal'  => $tanggal,
            'tanggal_akhir' => $tanggal,
            'no_rm'         => '',
        ];

        $model = new KominfoModel();
        $data  = $model->pendaftaranRequest($params);

        if (isset($data['error'])) {
            return response()->json(['error' => $data['error']], 500);
        }

        if (! isset($data) || ! is_array($data)) {
            return response()->json(['error' => 'Invalid data format'], 500);
        }

        if ($request->has('tes')) {
            return response()->json($data);
        }

        $filteredData = array_values(array_filter($data, function ($item) {
            return $item['keterangan'] === 'SELESAI DIPANGGIL LOKET PENDAFTARAN';
        }));

        $doctorNipMap = [
            'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
            'dr. Agil Dananjaya, Sp.P'                  => '9',
            'dr. Filly Ulfa Kusumawardani'              => '198907252019022004',
            'dr. Sigit Dwiyanto'                        => '198903142022031005',
        ];

        foreach ($filteredData as &$item) {
            $norm               = $item['pasien_no_rm'];
            $dokterNama         = $item['dokter_nama'];
            $item['nip_dokter'] = $doctorNipMap[$dokterNama] ?? 'Unknown';

            try {
                $item['status'] = $this->getStatusByRuang($ruang, $norm, $tanggal, $item);
            } catch (\Exception $e) {
                Log::error('Database error: ' . $e->getMessage());
                $item['status'] = 'Database connection error';
            }
        }

        return response()->json([
            'metadata' => ['message' => 'Data Pasien Ditemukan', 'code' => 200],
            'response' => ['data' => $filteredData],
        ]);
    }

    protected function getStatusByRuang($ruang, $norm, $tanggal, &$item)
    {
        switch ($ruang) {
            case 'ro':
                $tsRo = ROTransaksiModel::where('norm', $norm)->whereDate('tgltrans', $tanggal)->first();
                $foto = RoHasilModel::where('norm', $norm)->whereDate('tanggal', $tanggal)->first();
                return ! $tsRo && ! $foto
                ? 'Tidak Ada Transaksi'
                : ($tsRo && ! $foto ? 'Belum Upload Foto Thorax' : 'Sudah Selesai');

            case 'igd':
                $ts = IGDTransModel::with('transbmhp')->where('norm', $norm)->whereDate('created_at', $tanggal)->first();
                return ! $ts
                ? 'Tidak Ada Transaksi'
                : ($ts->transbmhp === null ? 'Belum Ada Transaksi BMHP' : 'Sudah Selesai');

            case 'dots':
                $ts = DotsTransModel::where('norm', $norm)->whereDate('created_at', $tanggal)->first();
                return $ts ? 'Sudah Selesai' : 'Tidak Ada Transaksi';

            case 'lab':
                $ts = LaboratoriumKunjunganModel::where('norm', $norm)->whereDate('created_at', $tanggal)->first();
                return $ts ? 'Sudah Selesai' : 'Tidak Ada Transaksi';

            case 'kasir':
                $ts = KasirTransModel::where('norm', $norm)->whereDate('created_at', $tanggal)->first();
                return $ts ? 'Sudah Selesai' : 'Tidak Ada Transaksi';

            case 'farmasi':
                $ts             = KasirTransModel::where('norm', $norm)->whereDate('created_at', $tanggal)->first();
                $pulang         = KunjunganWaktuSelesai::where('norm', $norm)->whereDate('created_at', $tanggal)->first();
                $item['button'] = $pulang && $pulang->waktu_selesai_farmasi !== null ? 'success' : 'warning';
                return $ts ? 'Sudah Selesai' : 'Tidak Ada Transaksi';

            default:
                return 'Unknown ruang';
        }
    }

    public function newPasien(Request $request)
    {
        if ($request->has('no_rm')) {
            $no_rm = $request->input('no_rm');
            $model = new KominfoModel();

            try {
                // Ambil data dari model
                $data = $model->pasienRequest($no_rm);

                // Jika data kosong atau tidak sesuai format
                if (empty($data) || ! is_array($data)) {
                    return response()->json(['error' => 'Data pasien tidak ditemukan'], 404);
                }

                // Hitung umur jika tanggal lahir tersedia dan valid
                if (! empty($data['pasien_tgl_lahir'])) {
                    try {
                        $tglLahir = Carbon::parse($data['pasien_tgl_lahir']);
                        $now      = Carbon::now();

                        $tahun = $tglLahir->diffInYears($now);
                        $bulan = $tglLahir->diffInMonths($now) % 12;

                        $data['umur'] = "{$tahun} thn {$bulan} bln";
                    } catch (\Exception $e) {
                        $data['umur']       = "-";
                        $data['umur_error'] = "Format tanggal lahir tidak valid";
                    }
                } else {
                    $data['umur']       = "-";
                    $data['umur_error'] = "Tanggal lahir kosong";
                }

                return response()->json($data);
            } catch (\Exception $e) {
                return response()->json([
                    'error'   => 'Terjadi kesalahan saat mengambil data',
                    'message' => $e->getMessage(),
                ], 500);
            }
        } else {
            return response()->json(['error' => 'No RM belum diisi'], 400);
        }
    }
    public function dataPasien(Request $request)
    {
        // dd($request->all());
        if ($request->has('no_rm') && $request->has('tanggal')) {
            $no_rm   = $request->input('no_rm');
            $tanggal = $request->input('tanggal', Carbon::now()->format('Y-m-d'));
            $model   = new KominfoModel();
            $params  = [
                'tanggal_awal'  => $tanggal,
                'tanggal_akhir' => $tanggal,
                'no_rm'         => $no_rm,
            ];

            // Panggil metode untuk melakukan request pasien
            $res_pasien = $model->pasienRequest($no_rm);
            // dd($res_pasien);
            if ($res_pasien == "Data tidak ditemukan!") {
                $response = [
                    'metadata' => [
                        'message' => 'Pasien dengan No. RM ' . $no_rm . ' tidak ditemukan',
                        'code'    => 204,
                    ],
                ];
                return response()->json($response);
            } else {
                // $pasienData = $res_pasien;
                $pasien = [
                    "pasien_nik"          => $res_pasien['pasien_nik'] ?? null,
                    "pasien_no_kk"        => $res_pasien['pasien_no_kk'] ?? null,
                    "pasien_nama"         => $res_pasien['pasien_nama'] ?? null,
                    "pasien_no_rm"        => $res_pasien['pasien_no_rm'] ?? null,
                    "jenis_kelamin_id"    => $res_pasien['jenis_kelamin_id'] ?? null,
                    "jenis_kelamin_nama"  => $res_pasien['jenis_kelamin_nama'] ?? null,
                    "pasien_tempat_lahir" => $res_pasien['pasien_tempat_lahir'] ?? null,
                    "pasien_tgl_lahir"    => $res_pasien['pasien_tgl_lahir'] ?? null,
                    "pasien_no_hp"        => $res_pasien['pasien_no_hp'] ?? null,
                    "pasien_domisili"     => $res_pasien['pasien_alamat'] ?? null,
                    "pasien_alamat"       => $res_pasien['pasien_alamat'] ?? null,
                    "provinsi_nama"       => $res_pasien['provinsi_nama'] ?? null,
                    "kabupaten_nama"      => $res_pasien['kabupaten_nama'] ?? null,
                    "kecamatan_nama"      => $res_pasien['kecamatan_nama'] ?? null,
                    "kelurahan_nama"      => $res_pasien['kelurahan_nama'] ?? null,
                    "pasien_rt"           => $res_pasien['pasien_rt'] ?? null,
                    "pasien_rw"           => $res_pasien['pasien_rw'] ?? null,
                    "penjamin_nama"       => $res_pasien['penjamin_nama'] ?? null,
                ];

                // Panggil metode untuk melakukan request pendaftaran
                $cpptRes = $model->cpptRequest($params);
                if (! isset($cpptRes['response']['data'])) {
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
                        'dr. Agil Dananjaya, Sp.P'                  => '9',
                        'dr. Filly Ulfa Kusumawardani'              => '198907252019022004',
                        'dr. Sigit Dwiyanto'                        => '198903142022031005',
                    ];

                    // Iterate over filtered data and add nip
                    foreach ($filteredData as &$item) {
                        $dokter_nama        = $item['dokter_nama'];
                        $item['nip_dokter'] = $doctorNipMap[$dokter_nama] ?? 'Unknown';
                    }

                    if (! empty($filteredData)) {
                        $response = [
                            'metadata' => [
                                'message' => 'Data Pasien Ditemukan',
                                'code'    => 200,
                            ],
                            'response' => [
                                'pendaftaran' => $filteredData,
                                'pasien'      => $pasien,
                                'cppt'        => $cppt,
                            ],
                        ];
                        // Mengembalikan respons dengan kode 200
                        return response()->json($response, 200);
                    } else {
                        $response = [
                            'metadata' => [
                                'message' => 'Pasien tidak mendaftar pada hari ini',
                                'code'    => 204,
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
        // dd($request->all());
        $norm = $request->input('norm');
        // Jika tgl tidak ada maka gunakan tgl saat ini
        $tanggal = $request->input('tanggal', Carbon::now()->format('Y-m-d'));
        $params  = [
            'tanggal_awal'  => $tanggal,
            'tanggal_akhir' => $tanggal,
            'no_rm'         => $norm ?? '',
        ];
        // dd($params);
        $model = new KominfoModel();
        $data  = $model->pendaftaranRequest($params);
        // dd($data);

        // Filter hasil yang normnya sama dengan $norm
        if ($norm === "" || $norm === null) {
            $filteredData = array_filter($data, function ($message) {
                return $message['status_pulang'] === "Belum Pulang";
            });
            $result = array_values($filteredData);
            return response()->json($result);
        }
        // // Ambil elemen pertama dari hasil yang difilter
        $result = reset($data);

        // Jika tidak ada hasil yang sesuai, berikan respons yang sesuai
        if ($result === false) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Kembalikan hasil sebagai JSON
        return response()->json($result);
    }

    public function kunjungan(Request $request)
    {
        // Ambil parameter dari request
        $params = $request->only(['tanggal_awal', 'tanggal_akhir', 'no_rm']);
        $model  = new KominfoModel();
        $data   = $model->cpptRequest($params);
        // dd($data);
        // Cek jika API mengembalikan error
        if (isset($data['error'])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengambil data dari API: ' . $data['error'],
            ], 500);
        }

        if (isset($data['response']['data']) && is_array($data['response']['data'])) {
            // Filter data, hanya menampilkan kunjungan hingga hari ini
            $nowDate = Carbon::now()->format('Y-m-d');
            $data    = array_filter($data['response']['data'], fn($item) => $item['tanggal'] <= $nowDate);
        } else {
            $data = [];
        }

        $riwayat = [];
        foreach ($data as $item) {
            $dataLab = LaboratoriumHasilModel::with('pemeriksaan')->where('notrans', $item['no_reg'])->get();
            // (10) Alasan pemeriksan diisi dengan
            // 0 untuk diagnosis
            // 2 atau 3 untuk akhir tahap awal
            // 5 untuk bulan kelima
            // 6 atau 8 untuk akhir pengobatan

            // return $dataLab;
            if (empty($dataLab) || count($dataLab) == 0 || $dataLab == null || $dataLab == []) {
                $hasilLabHtml = "Tidak ada pemeriksaan laboratorium";
            } else {
                $hasilLabHtml = "<table border='1' cellpadding='5' cellspacing='0'>
            <thead>
                <tr>
                    <th>TGL/JAM</th>
                    <th>Pemeriksaan</th>
                    <th>Hasil</th>
                    <th>Nilai Normal</th>
                </tr>
            </thead>
            <tbody>";
                foreach ($dataLab as $lab) {
                    $hasilLabHtml .= "<tr>
                <td>{$lab->updated_at}</td>
                <td>{$lab->pemeriksaan->nmLayanan}</td>
                <td>{$lab->hasil}</td>
                <td>{$lab->pemeriksaan->normal}</td>
            </tr>";
                }

            }

            $hasilLabHtml .= "</tbody></table>";

            $alamat = "{$item['kelurahan_nama']}, {$item['pasien_rt']}/{$item['pasien_rw']}, {$item['kecamatan_nama']}, {$item['kabupaten_nama']}, {$item['provinsi_nama']}";

            $riwayat[] = [
                'tanggal'                  => $item['tanggal'],
                'status_pasien_pulang'     => $item['status_pasien_pulang'],
                'ket_status_pasien_pulang' => $item['ket_status_pasien_pulang'],
                'dokter_nama'              => $item['dokter_nama'],
                'pasien_nama'              => $item['pasien_nama'],
                'pasien_no_rm'             => $item['pasien_no_rm'],
                'pasien_tgl_lahir'         => $item['pasien_tgl_lahir'],
                'umur'                     => $item['umur'],
                'antrean_nomor'            => $item['antrean_nomor'],
                'penjamin_nama'            => $item['penjamin_nama'],
                'jenis_kelamin_nama'       => $item['jenis_kelamin_nama'],
                'alamat'                   => $alamat,
                'dx1'                      => $item['diagnosa'][0]['nama_diagnosa'] ?? '',
                'dx2'                      => $item['diagnosa'][1]['nama_diagnosa'] ?? '',
                'dx3'                      => $item['diagnosa'][2]['nama_diagnosa'] ?? '',
                'ds'                       => $item['subjek'] ?? '',
                'do'                       => $item['objek_data_objektif'] ?? '',
                'td'                       => $item['objek_tekanan_darah'] ?? '',
                'bb'                       => $item['objek_bb'] ?? '',
                'nadi'                     => $item['objek_nadi'] ?? '',
                'suhu'                     => $item['objek_suhu'] ?? '',
                'rr'                       => $item['objek_rr'] ?? '',
                'tindakan'                 => $item['tindakan'],
                'radiologi'                => $item['radiologi'],
                'obat'                     => $item['resep_obat'],
                'laboratorium'             => $item['laboratorium'],
                'hasilLab'                 => $hasilLabHtml,
            ];
        }

        return response()->json($riwayat);
    }

    // public function newCpptRequest(Request $request)
    // {
    //     // Ambil parameter dari req
    //     $params = $request->only(['tanggal_awal', 'tanggal_akhir', 'no_rm', 'ruang']);
    //     $ruang  = $params['ruang'] ?? '';

    //     $model = new KominfoModel();
    //     $data  = $model->cpptRequest($params);
    //     // return $data;

    //     if (isset($data['response']['data']) && is_array($data['response']['data'])) {
    //         $filteredData = array_filter(array_map(function ($d) use ($ruang) {
    //             $d['status']      = 'belum';
    //             $d['status_obat'] = $d['resep_obat'] == [] ? 'Obat Belum' : 'Obat Sudah';

    //             if ($ruang === 'igd') {

    //                 // $ts = IGDTransModel::with('transbmhp')->where('norm', $d['pasien_no_rm'])
    //                 //     ->whereDate('created_at', $d['tanggal'])->first();
    //                 // $d['status'] = !$ts ? 'belum' :
    //                 // ($ts->transbmhp == null ? 'Belum Ada Transaksi BMHP' : 'sudah');

    //                 // foreach ($filteredData as $d) {
    //                 // dd($d);
    //                 $notrans = $d['tanggal'] < date('Y-m-d') ? $d['no_trans'] : $d['no_reg'];
    //                 $check   = KunjunganWaktuSelesai::where('notrans', $notrans)->first();
    //                 // dd($check);
    //                 // jika $check null
    //                 $checkigd = $check->waktu_selesai_igd ?? null;
    //                 // dd($igd);

    //                 $d['igd_selesai'] = $checkigd == null ? 'danger' : 'success';
    //                 // dd($d['igd_selesai']);
    //                 // }

    //                 $jumlahPermintaan = count($d['tindakan']);

    //                 $igd = IGDTransModel::whereDate('created_at', $d['tanggal'])->where('norm', $d['pasien_no_rm'])->get();
    //                 // dd($igd);
    //                 $jumlahIgd = count($igd);
    //                 if ($jumlahIgd < $jumlahPermintaan) {
    //                     $d['status'] = 'belum';
    //                 } else {
    //                     $d['status'] = 'sudah';
    //                 }
    //                 $d['jmlPerminttanIgd'] = $jumlahPermintaan;
    //                 $d['jmlIgd']           = $jumlahIgd;
    //                 if (empty($d['tindakan']) && $jumlahIgd == 0) {
    //                     return null;
    //                 }

    //             } elseif ($ruang === 'dots') {
    //                 // $dots            = DotsTransModel::whereDate('created_at', $d['tanggal'])->where('norm', $d['pasien_no_rm'])->first();
    //                 // $d['status']     = $dots ? 'sudah' : 'belum';
    //                 // $hasTuberculosis = false;
    //                 // if (isset($d['diagnosa'][0])) {
    //                 //     $hasTuberculosis = false;

    //                 //     // Loop hanya untuk dx1 sampai dx5 (maksimal 5 diagnosa)
    //                 //     for ($i = 0; $i < min(5, count($d['diagnosa'])); $i++) {
    //                 //         $dx = $d['diagnosa'][$i];

    //                 //         if (stripos($dx['nama_diagnosa'], 'tb') !== false ||
    //                 //             (stripos($dx['nama_diagnosa'], 'tuberculosis') !== false &&
    //                 //                 stripos($dx['nama_diagnosa'], 'Observation for suspected tuberculosis') === false)) {
    //                 //             $hasTuberculosis = true;
    //                 //             break;
    //                 //         }
    //                 //     }

    //                 //     // Jika tidak ada diagnosis Tuberculosis, kembalikan null
    //                 //     if (! $hasTuberculosis) {
    //                 //         return null;
    //                 //     }

    //                 //     // Cek status di DotsTransModel
    //                 //     $tb          = DotsTransModel::whereDate('created_at', $d['tanggal'])->where('norm', $d['pasien_no_rm'])->first();
    //                 //     $d['status'] = $tb ? 'sudah' : 'belum';

    //                 // } else {
    //                 //     // Handle case where there is no diagnosis
    //                 //     return null;
    //                 // }

    //                 $dots        = DotsTransModel::whereDate('created_at', $d['tanggal'])->where('norm', $d['pasien_no_rm'])->first();
    //                 $d['status'] = $dots ? 'sudah' : 'belum';

    //                 $hasOAT = false;

    //                 if (! empty($d['resep_obat'])) {
    //                     foreach ($d['resep_obat'] as $resep) {
    //                         if (! empty($resep['resep_obat_detail'])) {
    //                             foreach ($resep['resep_obat_detail'] as $obat) {
    //                                 $namaObat = strtolower($obat['nama_obat']);
    //                                 if (strpos($namaObat, 'oat') !== false || strpos($namaObat, 'rifampisin') !== false) {
    //                                     $hasOAT = true;
    //                                     break 2; // keluar dari 2 loop sekaligus
    //                                 }
    //                             }
    //                         }
    //                     }

    //                     if (! $hasOAT) {
    //                         return null; // tidak mengandung OAT atau RIFAMPISIN
    //                     }

    //                     // Jika ada, ambil status DOTS lagi (boleh di-skip kalau sudah di atas)
    //                     $tb          = DotsTransModel::whereDate('created_at', $d['tanggal'])->where('norm', $d['pasien_no_rm'])->first();
    //                     $d['status'] = $tb ? 'sudah' : 'belum';

    //                 } else {
    //                     return null; // tidak ada resep_obat sama sekali
    //                 }

    //             } elseif ($ruang === 'ro') {
    //                 $tsRo = ROTransaksiModel::where('norm', $d['pasien_no_rm'])
    //                     ->whereDate('tgltrans', $d['tanggal'])->first();
    //                 $foto = RoHasilModel::where('norm', $d['pasien_no_rm'])
    //                     ->whereDate('tanggal', $d['tanggal'])->first();
    //                 $d['status'] = ! $tsRo && ! $foto ? 'belum' :
    //                 ($tsRo && ! $foto ? 'Belum Upload Foto Thorax' : 'sudah');
    //                 // Cek di $d['radiologi'] apakah ada layanan "Konsultasi dokter Radiologi"
    //                 $d['permintaan_konsul'] = false;
    //                 if (! empty($d['radiologi'])) {
    //                     foreach ($d['radiologi'] as $radiologi) {
    //                         if (isset($radiologi['layanan']) && $radiologi['layanan'] === 'Konsultasi dokter Radiologi') {
    //                             $d['permintaan_konsul'] = true;
    //                             break;
    //                         }
    //                     }
    //                 }
    //                 $status_konsul = KunjunganWaktuSelesai::where('notrans', $d['no_reg'])->first();

    //                 $d['status_konsul'] = $status_konsul && $status_konsul->konsul_ro ? 'sudah' : 'belum';

    //                 if (empty($d['radiologi'])) {
    //                     return null;
    //                 }

    //             } elseif ($ruang === 'lab') {
    //                 $jumlahPermintaan = count($d['laboratorium']);
    //                 // $lab              = LaboratoriumKunjunganModel::whereDate('created_at', $d['tanggal'])->where('norm', $d['pasien_no_rm'])->first();
    //                 $jumlahLab = count(LaboratoriumHasilModel::whereDate('created_at', $d['tanggal'])
    //                         ->where('norm', $d['pasien_no_rm'])
    //                         ->whereNot('idLayanan', 214)->get());
    //                 // $d['status']      = $lab ? 'sudah' : 'belum';
    //                 if ($jumlahLab < $jumlahPermintaan) {
    //                     $d['status'] = 'belum';
    //                 } else {
    //                     $d['status'] = 'sudah';
    //                 }
    //                 if (empty($d['laboratorium'])) {
    //                     return null;
    //                 }

    //             }

    //             $doctorNipMap = [
    //                 'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
    //                 'dr. Agil Dananjaya, Sp.P'                  => '9',
    //                 'dr. Filly Ulfa Kusumawardani'              => '198907252019022004',
    //                 'dr. Sigit Dwiyanto'                        => '198903142022031005',
    //             ];

    //             $d['nip_dokter'] = $doctorNipMap[$d['dokter_nama']] ?? 'Unknown';

    //             return $d;
    //         }, $data['response']['data']));

    //         if (! empty($filteredData)) {
    //             return response()->json([
    //                 'metadata' => [
    //                     'message' => 'Data Pasien Ditemukan',
    //                     'code'    => 200,
    //                 ],
    //                 'response' => [
    //                     'data' => array_values($filteredData),
    //                 ],
    //             ]);
    //         } else {
    //             $errorMessages = [
    //                 'IGD' => 'Tidak ada data permintaan Tindakan',
    //                 'lab' => 'Tidak ada data permintaan Laboratorium',
    //                 'ro'  => 'Tidak ada data permintaan Radiologi',
    //             ];

    //             return response()->json(['error' => $errorMessages[$ruang] ?? 'Tidak ada data ditemukan'], 404);
    //         }
    //     } else {
    //         if (empty($data['response']['data'])) {
    //             return response()->json([
    //                 'metadata' => [
    //                     'message' => 'Data Tidak Ditemukan',
    //                     'code'    => 404,
    //                 ],
    //             ], 404);
    //         }
    //     }

    //     return response()->json(['error' => 'Internal Server Error'], 500);
    // }

    public function newCpptRequest(Request $request)
    {
        $params = $request->only(['tanggal_awal', 'tanggal_akhir', 'no_rm', 'ruang']);
        $ruang  = $params['ruang'] ?? '';

        $model = new KominfoModel();
        $data  = $model->cpptRequest($params);

        if (! isset($data['response']['data']) || ! is_array($data['response']['data'])) {
            return response()->json([
                'metadata' => ['message' => 'Data Tidak Ditemukan', 'code' => 404],
            ], 404);
        }

        $filteredData = array_filter(array_map(function ($d) use ($ruang) {
            $d['status']      = 'belum';
            $d['status_obat'] = empty($d['resep_obat']) ? 'Obat Belum' : 'Obat Sudah';

            switch ($ruang) {
                case 'igd':
                    return $this->processIGD($d);
                case 'dots':
                    return $this->processDOTS($d);
                case 'ro':
                    return $this->processRO($d);
                case 'lab':
                    return $this->processLab($d);
                default:
                    return null;
            }
        }, $data['response']['data']));

        if (! empty($filteredData)) {
            return response()->json([
                'metadata' => ['message' => 'Data Pasien Ditemukan', 'code' => 200],
                'response' => ['data' => array_values($filteredData)],
            ]);
        }

        $errorMessages = [
            'IGD' => 'Tidak ada data permintaan Tindakan',
            'lab' => 'Tidak ada data permintaan Laboratorium',
            'ro'  => 'Tidak ada data permintaan Radiologi',
        ];

        return response()->json(['error' => $errorMessages[$ruang] ?? 'Tidak ada data ditemukan'], 404);
    }

    protected function processIGD(array $d)
    {
        $notrans          = $d['tanggal'] < date('Y-m-d') ? $d['no_trans'] : $d['no_reg'];
        $check            = KunjunganWaktuSelesai::where('notrans', $notrans)->first();
        $d['igd_selesai'] = $check?->waktu_selesai_igd ? 'success' : 'danger';

        $jumlahPermintaan = count($d['tindakan']);
        $jumlahIgd        = IGDTransModel::whereDate('created_at', $d['tanggal'])
            ->where('norm', $d['pasien_no_rm'])->count();

        $d['status']           = $jumlahIgd < $jumlahPermintaan ? 'belum' : 'sudah';
        $d['jmlPerminttanIgd'] = $jumlahPermintaan;
        $d['jmlIgd']           = $jumlahIgd;

        return ($jumlahIgd === 0 && empty($d['tindakan'])) ? null : $this->attachNip($d);
    }

    protected function processDOTS(array $d)
    {
        if (empty($d['resep_obat']) || ! $this->checkObatOAT($d['resep_obat'])) {
            return null;
        }

        $dots = DotsTransModel::whereDate('created_at', $d['tanggal'])
            ->where('norm', $d['pasien_no_rm'])->first();

        $d['status'] = $dots ? 'sudah' : 'belum';
        return $this->attachNip($d);
    }

    protected function processRO(array $d)
    {
        $tsRo = ROTransaksiModel::where('norm', $d['pasien_no_rm'])
            ->whereDate('tgltrans', $d['tanggal'])->first();

        $foto = RoHasilModel::where('norm', $d['pasien_no_rm'])
            ->whereDate('tanggal', $d['tanggal'])->first();

        $d['status'] = ! $tsRo && ! $foto ? 'belum' :
        ($tsRo && ! $foto ? 'Belum Upload Foto Thorax' : 'sudah');

        $d['permintaan_konsul'] = collect($d['radiologi'] ?? [])
            ->contains(fn($r) => $r['layanan'] === 'Konsultasi dokter Radiologi');

        $konsul             = KunjunganWaktuSelesai::where('notrans', $d['no_reg'])->first();
        $d['status_konsul'] = $konsul?->konsul_ro ? 'sudah' : 'belum';

        return empty($d['radiologi']) ? null : $this->attachNip($d);
    }

    protected function processLab(array $d)
    {
        $jumlahPermintaan = count($d['laboratorium']);
        $jumlahLab        = LaboratoriumHasilModel::whereDate('created_at', $d['tanggal'])
            ->where('norm', $d['pasien_no_rm'])
            ->whereNot('idLayanan', 214)
            ->count();

        $d['status'] = $jumlahLab < $jumlahPermintaan ? 'belum' : 'sudah';

        return empty($d['laboratorium']) ? null : $this->attachNip($d);
    }

    protected function checkObatOAT(array $resepObat): bool
    {
        foreach ($resepObat as $resep) {
            foreach ($resep['resep_obat_detail'] ?? [] as $obat) {
                $nama = strtolower($obat['nama_obat'] ?? '');
                if (str_contains($nama, 'oat') || str_contains($nama, 'rifampisin')) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function attachNip(array $d): array
    {
        $doctorNipMap = [
            'dr. Cempaka Nova Intani, Sp.P, FISR., MM.' => '198311142011012002',
            'dr. Agil Dananjaya, Sp.P'                  => '9',
            'dr. Filly Ulfa Kusumawardani'              => '198907252019022004',
            'dr. Sigit Dwiyanto'                        => '198903142022031005',
        ];

        $d['nip_dokter'] = $doctorNipMap[$d['dokter_nama']] ?? 'Unknown';
        return $d;
    }

    public function rekapPoin(Request $request)
    {
        $params = $request->only(['tanggal_awal', 'tanggal_akhir']);

        $model = new KominfoModel();

        // Ambil data dari model
        $data = $model->poinRequest($params);

        // Step 1: Filter data (hilangkan entri "Ruang Poli")
        $filteredData = array_filter($data['response']['data'], function ($item) {
            return $item['ruang_nama'] !== 'Ruang Poli';
        });
        // dd($filteredData);

        // Step 2: Buat map sementara berdasarkan ruang dan admin
        $grouped = [];
        foreach ($filteredData as $item) {
            $ruang  = $item['ruang_nama'];
            $admin  = $item['admin_nama'];
            $jumlah = (int) $item['jumlah'];

            $grouped[$ruang][$admin] = ($grouped[$ruang][$admin] ?? 0) + $jumlah;
        }

        // Step 3: Tambahkan ruang baru "Anamnesa pasien lama"
        $anamnesaLama = [];
        $tensi        = $grouped['Ruang Tensi 1'] ?? [];
        $awal         = $grouped['Petugas Assessment Awal'] ?? [];

        foreach ($tensi as $admin => $jumlahTensi) {
            if (isset($awal[$admin])) {
                $selisih = $jumlahTensi - $awal[$admin];
                if ($selisih > 0) {
                    $anamnesaLama[] = [
                        'ruang_nama' => 'Anamnesa pasien lama',
                        'admin_nama' => $admin,
                        'jumlah'     => $selisih,
                    ];
                }
            } else {
                // Jika hanya ada di tensi, tetap masukkan
                $anamnesaLama[] = [
                    'ruang_nama' => 'Anamnesa pasien lama',
                    'admin_nama' => $admin,
                    'jumlah'     => $jumlahTensi,
                ];
            }
        }

        // Step 4: Mapping nama ruang yang lain
        $namaBaru = [
            'Ruang Tensi 1'             => 'Timbang dan Tensi',
            'Petugas Assessment Awal'   => 'Anamnesa pasien baru',
            'Ruang Poli (Perawat Poli)' => 'Asisten dokter',
            'Ruang Poli (Dokter CPPT)'  => 'Pemeriksaan dokter',
            // Tambahkan mapping lainnya jika ada
        ];

        $mappedData = array_map(function ($item) use ($namaBaru) {
            if (isset($namaBaru[$item['ruang_nama']])) {
                $item['ruang_nama'] = $namaBaru[$item['ruang_nama']];
            }
            return $item;
        }, $filteredData);

        // Step 5: Gabungkan hasil mapping dengan Anamnesa pasien lama
        $finalData = array_merge($mappedData, $anamnesaLama);

        // Step 6: Replace data asli
        $data['response']['data'] = array_values($finalData);

        return response()->json($data);
    }

    public function rekapPoinPecah(Request $request)
    {

        $params = $request->only(['tanggal_awal', 'tanggal_akhir']);

        $model = new KominfoModel();

        $data = $model->cpptRequest($params);
        $res  = $data['response']['data'];

        return response()->json($res);

    }

    public function waktuLayanan(Request $request)
    {

        $params = $request->all();

        $model = new KominfoModel();

        $data = $model->waktuLayananRequest($params);

        return response()->json($data);
    }

    public function avgWaktuTunggu(Request $request)
    {
        try {
            $params = $request->all();
            $model  = new KominfoModel();

            // Ambil data dari model menggunakan metode waktuLayananRequest
            $data = $model->waktuLayananRequest($params);
            if (empty($data)) {
                return response()->json(['error' => 'Data Tidak Ditemukan'], 404);
            }
            // $data=[
            //     "error" => "cURL error 7: Failed to connect to kkpm.banyumaskab.go.id port 443 after 4399 ms: No route to host (see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://kkpm.banyumaskab.go.id/api_kkpm/v1/pendaftaran/data_pendaftaran"
            //     ];
            if (isset($data['error'])) {
                return response()->json(['error' => $data['error']], 500);
            }
            // Hitung rata-rata dan waktu terlama
            $results = $this->calculateAverages($data);

            // Kembalikan response dalam format JSON
            return response()->json(
                ['data' => $results,
                    'waktu' => $data,
                ]);
        } catch (\Exception $e) {
            // Tangani kesalahan
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function calculateAverages($data)
    {
        // Initialize totals, max values, and counts
        $totals = [
            'tunggu_daftar'    => 0,
            'tunggu_rm'        => 0,
            'tunggu_lab'       => 0,
            'tunggu_hasil_lab' => 0,
            'tunggu_hasil_ro'  => 0,
            'tunggu_ro'        => 0,
            'tunggu_poli'      => 0,
            'durasi_poli'      => 0,
            'tunggu_tensi'     => 0,
            'tunggu_igd'       => 0,
            'lama_igd'         => 0,
            'tunggu_farmasi'   => 0,
            'tunggu_kasir'     => 0,
        ];

        $maxValues = $totals;
        $minValues = array_fill_keys(array_keys($totals), PHP_INT_MAX);

        $counts = [
            'rm'  => 0,
            'ro'  => 0,
            'lab' => 0,
            'igd' => 0,
        ];

        // Initialize above and below 90 minutes counters
        $above90 = $totals;
        $below90 = $totals;

        // Initialize variables for "oke" false
        $totalDurasiPoliOkeFalse = 0;
        $countOkeFalse           = 0;

        // Process each patient's data
        foreach ($data as $message) {
            // Check for valid values and handle them properly
            foreach ($totals as $key => &$total) {
                $value = $message[$key] ?? 0;
                if (is_array($value)) {
                    $value = 0; // Handle array case as needed
                }
                $total += $value;
                $maxValues[$key] = max($maxValues[$key], $value);
                $minValues[$key] = min($minValues[$key], $value);

                // Count values above and below 90 minutes
                if ($value > 90) {
                    $above90[$key]++;
                } else {
                    $below90[$key]++;
                }
            }

            // Update counts for specific categories
            $counts['rm'] += isset($message['rm']) && $message['rm'] === true ? 1 : 0;
            $counts['ro'] += isset($message['rodata']) && $message['rodata'] === true ? 1 : 0;
            $counts['lab'] += isset($message['labdata']) && $message['labdata'] === true ? 1 : 0;
            $counts['igd'] += isset($message['igddata']) && $message['igddata'] === true ? 1 : 0;

            // Accumulate lama_igd if present
            $lamaIgdValue = $message['lama_igd'] ?? 0;
            if ($lamaIgdValue > 0) {
                $totals['lama_igd'] += $lamaIgdValue;
            }

            // If "oke" is false, accumulate durasi_poli and count
            if (isset($message['oke']) && $message['oke'] === false) {
                $totalDurasiPoliOkeFalse += $message['durasi_poli'] ?? 0;
                $countOkeFalse++;
            }
        }

        // Calculate averages and metrics
        $results = [];
        foreach ($totals as $key => $total) {
            // Use appropriate count for lab, ro, igd
            $jml = count($data);
            if (stripos($key, 'lab') !== false) {
                $jml = $counts['lab'] == 0 ? 1 : $counts['lab'];
            } elseif (stripos($key, 'ro') !== false) {
                $jml = $counts['ro'] == 0 ? 1 : $counts['ro'];
            } elseif (stripos($key, 'igd') !== false) {
                $jml = $counts['igd'] == 0 ? 1 : $counts['igd'];
            }

            $results["avg_$key"]           = round($total / $jml, 2);
            $results["max_$key"]           = $maxValues[$key];
            $results["min_$key"]           = $minValues[$key];
            $results["total_$key"]         = round($total, 2);
            $results["lebih_$key"]         = $above90[$key];
            $results["lebih_persen_$key"]  = round(($above90[$key] / $jml) * 100, 2);
            $results["kurang_$key"]        = $below90[$key];
            $results["kurang_persen_$key"] = round(($below90[$key] / $jml) * 100, 2);
        }

        // Special handling for cases where counts are zero
        foreach ($counts as $key => $count) {
            $results["avg_tunggu_{$key}"] = $count > 0 ? round($totals["tunggu_{$key}"] / $count, 2) : 0;
        }

        // Include total counts
        $results = array_merge($results, [
            'total_pasien'         => count($data),
            'total_ro'             => $counts['ro'],
            'total_lab'            => $counts['lab'],
            'total_igd'            => $counts['igd'],
            'total_tanpa_tambahan' => count(array_filter($data, fn($item) => isset($item['oke']) && $item['oke'] === false)),
            'total_rm'             => $counts['rm'],
        ]);

        // Calculate average durasi_poli for "oke" false
        $results['avg_durasi_poli_oke_false'] = $countOkeFalse > 0 ? round($totalDurasiPoliOkeFalse / $countOkeFalse, 2) : 0;

        // Calculate average lama_igd
        $results['avg_lama_igd'] = $counts['igd'] > 0 ? round($totals['lama_igd'] / $counts['igd'], 2) : 0;

        // Calculate average for "tunggu_ro"
        $rodata = array_filter($data, function ($d) {
            return $d['rodata'] === true;
        });
        $jumlahRo                                 = count($rodata);
        $totalWaktuRo                             = array_sum(array_column($rodata, 'tunggu_ro')); // Sum up 'tunggu_ro' values
        $results['avg_tunggu_ro']                 = $jumlahRo > 0 ? round($totalWaktuRo / $jumlahRo, 2) : 0;
        $results['max_tunggu_ro']                 = $jumlahRo > 0 ? max(array_column($rodata, 'tunggu_ro')) : 0;
        $results['min_tunggu_ro']                 = $jumlahRo > 0 ? min(array_column($rodata, 'tunggu_ro')) : 0;
        $results['lebih_tunggu_ro']               = count(array_filter($rodata, fn($d) => $d['tunggu_ro'] > 90));
        $results['lebih_persen_tunggu_ro']        = $jumlahRo > 0 ? round(($results['lebih_tunggu_ro'] / $jumlahRo) * 100, 2) : 0;
        $results['kurang_tunggu_ro']              = $jumlahRo > 0 ? $jumlahRo - $results['lebih_tunggu_ro'] : 0;
        $results['kurang_persen_tunggu_ro']       = $jumlahRo > 0 ? round(($results['kurang_tunggu_ro'] / $jumlahRo) * 100, 2) : 0;
        $totalWaktuRo                             = array_sum(array_column($rodata, 'tunggu_hasil_ro')); // Sum up 'tunggu_ro' values
        $results['avg_tunggu_hasil_ro']           = $jumlahRo > 0 ? round($totalWaktuRo / $jumlahRo, 2) : 0;
        $results['max_tunggu_hasil_ro']           = $jumlahRo > 0 ? max(array_column($rodata, 'tunggu_hasil_ro')) : 0;
        $results['min_tunggu_hasil_ro']           = $jumlahRo > 0 ? min(array_column($rodata, 'tunggu_hasil_ro')) : 0;
        $results['lebih_tunggu_hasil_ro']         = count(array_filter($rodata, fn($d) => $d['tunggu_hasil_ro'] > 90));
        $results['lebih_persen_tunggu_hasil_ro']  = $jumlahRo > 0 ? round(($results['lebih_tunggu_hasil_ro'] / $jumlahRo) * 100, 2) : 0;
        $results['kurang_tunggu_hasil_ro']        = $jumlahRo > 0 ? $jumlahRo - $results['lebih_tunggu_hasil_ro'] : 0;
        $results['kurang_persen_tunggu_hasil_ro'] = $jumlahRo > 0 ? round(($results['kurang_tunggu_hasil_ro'] / $jumlahRo) * 100, 2) : 0;

        // Similar calculations for lab
        $labdata = array_filter($data, function ($d) {
            return $d['labdata'] === true;
        });
        $jumlahLab                                 = count($labdata);
        $totalWaktuLab                             = array_sum(array_column($labdata, 'tunggu_hasil_lab'));
        $results['avg_tunggu_hasil_lab']           = $jumlahLab > 0 ? round($totalWaktuLab / $jumlahLab, 2) : 0;
        $results['max_tunggu_hasil_lab']           = $jumlahLab > 0 ? max(array_column($labdata, 'tunggu_hasil_lab')) : 0;
        $results['min_tunggu_hasil_lab']           = $jumlahLab > 0 ? min(array_column($labdata, 'tunggu_hasil_lab')) : 0;
        $results['lebih_tunggu_hasil_lab']         = count(array_filter($labdata, fn($d) => $d['tunggu_hasil_lab'] > 90));
        $results['lebih_persen_tunggu_hasil_lab']  = $jumlahLab > 0 ? round(($results['lebih_tunggu_hasil_lab'] / $jumlahLab) * 100, 2) : 0;
        $results['kurang_tunggu_hasil_lab']        = $jumlahLab > 0 ? $jumlahLab - $results['lebih_tunggu_hasil_lab'] : 0;
        $results['kurang_persen_tunggu_hasil_lab'] = $jumlahLab > 0 ? round(($results['kurang_tunggu_hasil_lab'] / $jumlahLab) * 100, 2) : 0;
        $totalWaktuLab                             = array_sum(array_column($labdata, 'tunggu_lab'));
        $results['avg_tunggu_lab']                 = $jumlahLab > 0 ? round($totalWaktuLab / $jumlahLab, 2) : 0;
        $results['max_tunggu_lab']                 = $jumlahLab > 0 ? max(array_column($labdata, 'tunggu_lab')) : 0;
        $results['min_tunggu_lab']                 = $jumlahLab > 0 ? min(array_column($labdata, 'tunggu_lab')) : 0;
        $results['lebih_tunggu_lab']               = count(array_filter($labdata, fn($d) => $d['tunggu_lab'] > 90));
        $results['lebih_persen_tunggu_lab']        = $jumlahLab > 0 ? round(($results['lebih_tunggu_lab'] / $jumlahLab) * 100, 2) : 0;
        $results['kurang_tunggu_lab']              = $jumlahLab > 0 ? $jumlahLab - $results['lebih_tunggu_lab'] : 0;
        $results['kurang_persen_tunggu_lab']       = $jumlahLab > 0 ? round(($results['kurang_tunggu_lab'] / $jumlahLab) * 100, 2) : 0;

        // Similar calculations for igd
        $igddata = array_filter($data, function ($d) {
            return $d['igddata'] === true;
        });
        $jumlahIgd                           = count($igddata);
        $totalWaktuIgd                       = array_sum(array_column($igddata, 'tunggu_igd'));
        $results['avg_tunggu_igd']           = $jumlahIgd > 0 ? round($totalWaktuIgd / $jumlahIgd, 2) : 0;
        $results['max_tunggu_igd']           = $jumlahIgd > 0 ? max(array_column($igddata, 'tunggu_igd')) : 0;
        $results['min_tunggu_igd']           = $jumlahIgd > 0 ? min(array_column($igddata, 'tunggu_igd')) : 0;
        $results['lebih_tunggu_igd']         = count(array_filter($igddata, fn($d) => $d['tunggu_igd'] > 90));
        $results['lebih_persen_tunggu_igd']  = $jumlahIgd > 0 ? round(($results['lebih_tunggu_igd'] / $jumlahIgd) * 100, 2) : 0;
        $results['kurang_tunggu_igd']        = $jumlahIgd > 0 ? $jumlahIgd - $results['lebih_tunggu_igd'] : 0;
        $results['kurang_persen_tunggu_igd'] = $jumlahIgd > 0 ? round(($results['kurang_tunggu_igd'] / $jumlahIgd) * 100, 2) : 0;

        return $results;
    }

    public function grafikDokter(Request $request)
    {
        $params = $request->all();
        $model  = new KominfoModel();
        $data   = $model->getGrafikDokter($params)['data'];
        // return $data;
        $pasien = $this->filterData($model->getTungguPoli($params)['data']['data']);
        // return $pasien;

        $formattedData = $this->filterData($data);
        // return [
        //     'data' => $formattedData,
        //     'pasien' => $pasien];
        $hasil = $this->calculatePercentage($formattedData, $pasien);

        return response()->json($hasil, 200, [], JSON_PRETTY_PRINT);
    }

    private function filterData(array $data)
    {
        // return $data;
        // Daftar nama dokter yang ingin dihitung
        $doctors = [
            'dr. Cempaka Nova Intani, Sp.P, FISR., MM.',
            'dr. Agil Dananjaya, Sp.P',
            'dr. Filly Ulfa Kusumawardani',
            'dr. Sigit Dwiyanto',
        ];

        // Filter dan format ulang data
        $result = [];
        foreach ($data as $item) {
            if (in_array($item['dokter_nama'], $doctors)) {
                $tanggal                   = $item['tanggal'];
                $dokter                    = $item['dokter_nama'];
                $result[$tanggal][$dokter] = ($result[$tanggal][$dokter] ?? 0) + 1;
            }
        }

        // Tambahkan dokter yang tidak memiliki data
        foreach ($result as $tanggal => &$dokters) {
            foreach ($doctors as $dokter) {
                $dokters[$dokter] = $dokters[$dokter] ?? 0;
            }
        }

        // Format ulang menjadi array terstruktur
        $formattedResult = [];
        foreach ($result as $tanggal => $dokters) {
            foreach ($dokters as $dokter => $count) {
                $formattedResult[] = [
                    'tanggal'     => $tanggal,
                    'dokter_nama' => $dokter,
                    'jumlah'      => $count,
                ];
            }
        }

        // Urutkan berdasarkan tanggal
        usort($formattedResult, fn($a, $b) => strtotime($a['tanggal']) - strtotime($b['tanggal']));

        return $formattedResult;
    }

    private function calculatePercentage(array $data, array $pasien)
    {
        $hasil = [];
        foreach ($data as $dataItem) {
            foreach ($pasien as $pasienItem) {
                if ($dataItem['dokter_nama'] === $pasienItem['dokter_nama'] && $dataItem['tanggal'] === $pasienItem['tanggal']) {
                    $jumlahData   = $dataItem['jumlah'];
                    $jumlahPasien = $pasienItem['jumlah'];
                    $percentage   = $jumlahData > 0 ? ($jumlahData / $jumlahPasien) * 100 : 0;

                    $hasil[] = [
                        'tanggal'        => $dataItem['tanggal'],
                        'dokter_nama'    => $dataItem['dokter_nama'],
                        'jumlah_farmasi' => $jumlahData,
                        'jumlah_pasien'  => $jumlahPasien,
                        'percentage'     => round($percentage, 2),
                    ];
                }
            }
        }

        return $hasil;
    }

    public function rekapFaskesPerujuk(Request $request)
    {
        $params = [
            'tanggal_sep_awal'  => $request->input('tanggal_awal'),
            'tanggal_sep_akhir' => $request->input('tanggal_akhir'),
            'order_by'          => 'jumlah_rujukan',
            'order_jenis'       => 'desc',
        ];
        $model = new KominfoModel();
        $data  = $model->rekapFaskesPerujuk($params)['response']['data_rujukan'];
        // return $req;
        // return $req['response']['data_rujukan'];
        // $data = $req['data_rujukan'];
        // $data = array_values($data);

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

}
