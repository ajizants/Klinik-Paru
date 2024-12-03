<?php

namespace App\Http\Controllers;

use App\Models\DotsTransModel;
use App\Models\FarmasiModel;
use App\Models\IGDTransModel;
use App\Models\KasirTransModel;
use App\Models\KominfoModel;
use App\Models\LaboratoriumHasilModel;
use App\Models\LaboratoriumKunjunganModel;
use App\Models\RoHasilModel;
use App\Models\ROTransaksiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PasienKominfoController extends Controller
{
    public function ambilAntrean(Request $request)
    {
        $penjamin_id = $request->input('penjamin_id');
        $koneksi = new KominfoModel();

        $noAtian = $koneksi->ambilNoRequest($penjamin_id);
        // dd($noAtian);
        return response()->json($noAtian);
    }
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
    private function generateQrCodeWithLogo($dokter, $no_rm, $nama)
    {
        // Data untuk QR Code (misalnya tanda tangan)
        $data = 'Dokumen resume medis a.n' . $nama . '(' . $no_rm . ') telah di setujui dan di tandatangani oleh ' . $dokter;

        // $logoPath = public_path('img/LOGO_KKPM.png');
        // $logoPath = str_replace('\\', '/', $logoPath);

        // // dd("Jalur logo: " . $logoPath);
        //         // Periksa apakah file logo ada
        // if (!file_exists($logoPath)) {
        //     dd("Logo tidak ditemukan di: " . $logoPath);
        // }

        // Buat QR Code dengan logo
        $qrCode = QrCode::format('png')
        //     ->merge($logoPath, 0.3) // 0.3 artinya logo 30% dari ukuran QR Code
        //     ->size(300)
        //     ->errorCorrection('H') // Tingkat toleransi tinggi agar QR Code tetap terbaca
            ->generate($data);
        // dd($qrCode);
        return $qrCode;
    }

    public function resumePasien($no_rm, $tgl)
    {
        // $title = 'Laporan Pendaftaran';

        // return view('Template.newPage')->with('title', $title);
        // $params = $request->all();
        $params = [
            'no_rm' => $no_rm,
            'tanggal_awal' => $tgl,
            'tanggal_akhir' => $tgl,
        ];
        $client = new KominfoModel();
        try {
            $data = $client->cpptRequest($params);
            $resumePasienArray = $data['response']['data'];

            // Cek jika $resumePasienArray adalah array
            if (is_array($resumePasienArray) && count($resumePasienArray) > 0) {
                // Ambil objek pertama dari array
                $resumePasien = (object) $resumePasienArray[0];
            } else {
                // Jika tidak ada data, kembalikan sebagai objek kosong
                $resumePasien = new \stdClass();
            }

            // return $resumePasien; // Mengembalikan objek $resumePasien
            $dataObats = $resumePasien->resep_obat;
            $obats = [];
            // return $dataObats;

            foreach ($dataObats as $obat) {
                $obats[] = [
                    'no_resep' => $obat['no_resep'],
                    'aturan' => $obat['signa_1'] . ' X ' . $obat['signa_2'] . ' ' . $obat['aturan_pakai'],
                    'nm_obat' => $obat['resep_obat_detail'],
                ];
            }

            // return $obats;

            // $alamat = $resumePasien->kelurahan_nama . ', ' . $resumePasien->pasien_rt . '/' . $resumePasien->pasien_rw . ', ' . $resumePasien->kecamatan_nama . ', ' . $resumePasien->kabupaten_nama . ', ' . $resumePasien->provinsi_nama;
            $alamat = ucwords(strtolower($resumePasien->kelurahan_nama)) . ', ' .
            $resumePasien->pasien_rt . '/' . $resumePasien->pasien_rw . ', ' .
            ucwords(strtolower($resumePasien->kecamatan_nama)) . ', ' .
            ucwords(strtolower($resumePasien->kabupaten_nama)) . ', ' .
            ucwords(strtolower($resumePasien->provinsi_nama));
            $norm = $no_rm;
            $tanggal = $tgl;
            // $norm = '027820';
            // $tanggal = '2024-10-05';

            //data ro
            $dataRo = ROTransaksiModel::with('film', 'foto', 'proyeksi')
                ->where('norm', $norm)
                ->where('tgltrans', $tanggal)
                ->first();
            // dd($dataRo);
            if (!$dataRo) {
                $ro = [];
            } else {
                $ro = [
                    'noReg' => $dataRo->noreg,
                    'tglRo' => Carbon::parse($dataRo->tgltrans)->format('d-m-Y'),
                    'jenisFoto' => $dataRo->foto->nmFoto,
                    'proyeksi' => $dataRo->proyeksi->proyeksi,
                ];
            }
            // return $ro;

            //data lab
            $dataLab = LaboratoriumHasilModel::with('pemeriksaan')
                ->where('norm', $norm)
                ->where('created_at', 'like', '%' . Carbon::parse($tanggal)->format('Y-m-d') . '%')->get();
            // return $dataLab;
            if (!$dataLab) {
                $lab = [];
            } else {
                $lab = [];
                foreach ($dataLab as $item) {
                    $lab[] = [
                        'idLab' => $item->idLab,
                        'idLayanan' => $item->idLayanan,
                        'tanggal' => Carbon::parse($item->created_at)->format('d-m-Y'),
                        'hasil' => $item->hasil,
                        // Menghapus (stik) dari nama pemeriksaan
                        'pemeriksaan' => str_replace(' (Stik)', '', $item->pemeriksaan->nmLayanan),
                        'satuan' => $item->pemeriksaan->satuan,
                        'normal' => $item->pemeriksaan->normal,
                        'totalItem' => count($dataLab),
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
                        'jumlah' => $key->jml,
                        'nmBmhp' => $key->bmhp->nmObat,
                        'sediaan' => $key->sediaan,
                    ];
                }
                $tindakan[] = [
                    'id' => $item->id,
                    'kdTind' => $item->kdTind,
                    'tanggal' => Carbon::parse($item->created_at)->format('d-m-Y'),
                    // 'tindakan' => $item->tindakan->nmTindakan,
                    'tindakan' => preg_replace('/\s?\(.*?\)/', '', $item->tindakan->nmTindakan),
                    'bmhp' => $bmhp,
                    'totalItem' => count($dataTindakan),
                ];
            }
            // return $tindakan;
            // $lab = [];
            // $ro = [];

            $ttd = $this->generateQrCodeWithLogo($resumePasien->dokter_nama, $no_rm, $resumePasien->pasien_nama);
            return view('Laporan.resume', compact('resumePasien', 'alamat', 'ro', 'lab', 'tindakan', 'obats'));
            // return view('Laporan.resume1', compact('resumePasien', 'alamat', 'ro', 'lab', 'tindakan'));

        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mencari data: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat mencari data: ' . $e->getMessage()], 500);
        }
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
        $tes = $filteredData;

        foreach ($filteredData as &$item) {
            $norm = $item['pasien_no_rm'];
            $dokter_nama = $item['dokter_nama'];

            try {
                switch ($ruang) {
                    case 'ro':
                        $tsRo = ROTransaksiModel::where('norm', $norm)
                            ->whereDate('tgltrans', $tanggal)->first();
                        // $foto = ROTransaksiHasilModel::where('norm', $norm)
                        $foto = RoHasilModel::where('norm', $norm)
                            ->whereDate('tanggal', $tanggal)->first();
                        $item['status'] = !$tsRo && !$foto ? 'Tidak Ada Transaksi' :
                        ($tsRo && !$foto ? 'Belum Upload Foto Thorax' : 'Sudah Selesai');
                        break;

                    case 'igd':
                        $ts = IGDTransModel::with('transbmhp')->where('norm', $norm)
                            ->whereDate('created_at', $tanggal)->first();
                        $item['status'] = !$ts ? 'Tidak Ada Transaksi' :
                        ($ts->transbmhp == null ? 'Belum Ada Transaksi BMHP' : 'Sudah Selesai');
                        break;

                    case 'farmasi':
                        $ts = FarmasiModel::where('norm', $norm)
                            ->whereDate('created_at', $tanggal)->first();
                        $item['status'] = !$ts ? 'Tidak Ada Transaksi' : 'Sudah Selesai';
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
                    case 'kasir':
                        $ts = KasirTransModel::where('norm', $norm)
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
                // 'data' => $tes,
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
        // dd($request->all());
        $norm = $request->input('norm');
        // Jika tgl tidak ada maka gunakan tgl saat ini
        $tanggal = $request->input('tanggal', Carbon::now()->format('Y-m-d'));
        $params = [
            'tanggal_awal' => $tanggal,
            'tanggal_akhir' => $tanggal,
            'no_rm' => $norm ?? '',
        ];
        // dd($params);
        $model = new KominfoModel();
        $data = $model->pendaftaranRequest($params);
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
        // Ambil parameter dari req
        $params = $request->only(['tanggal_awal', 'tanggal_akhir', 'no_rm']);
        $model = new KominfoModel();
        $data = $model->cpptRequest($params);
        if (isset($data['response']['data']) && is_array($data['response']['data'])) {
            // Filter data, jika tindakan kosong maka skip
            $nowDate = Carbon::now()->format('Y-m-d');

            $filteredData = array_filter($data['response']['data'], function ($item) use ($nowDate) {
                return $item['tanggal'] < $nowDate;
            });

            // Update the 'data' key with the filtered data
            $data = $filteredData;
        } else {
            // Handle the case where 'response' or 'data' key is not present
            $data = [];
        }
        $riwayat = [];
        foreach ($data as $item) {
            $riwayat[] = [
                'tanggal' => $item['tanggal'],
                'dokter_nama' => $item['dokter_nama'],
                'pasien_nama' => $item['pasien_nama'],
                'pasien_no_rm' => $item['pasien_no_rm'],
                'dx1' => $item['diagnosa'][0]['nama_diagnosa'] ?? '',
                'dx2' => $item['diagnosa'][1]['nama_diagnosa'] ?? '',
                'dx3' => $item['diagnosa'][2]['nama_diagnosa'] ?? '',
                'ds' => $item['subjek'] ?? '',
                'do' => $item['objek_data_objektif'] ?? '',
                'td' => $item['objek_tekanan_darah'] ?? '',
                'bb' => $item['objek_bb'] ?? '',
                'nadi' => $item['objek_nadi'] ?? '',
                'suhu' => $item['objek_suhu'] ?? '',
                'rr' => $item['objek_rr'] ?? '',

            ];
        }

        // return response()->json($data);
        return response()->json($riwayat);
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
                    if (isset($d['diagnosa'][0])) {
                        $dx1 = $d['diagnosa'][0];

                        $hasTuberculosis = false;
                        if (stripos($dx1['nama_diagnosa'], 'tuberculosis') !== false ||
                            (stripos($dx1['nama_diagnosa'], 'tb') !== false &&
                                stripos($dx1['nama_diagnosa'], 'Observation for suspected tuberculosis') === false)) {
                            $hasTuberculosis = true;
                        }

                        if (!$hasTuberculosis) {
                            return null;
                        }

                        $tb = DotsTransModel::whereDate('created_at', $d['tanggal'])->where('norm', $d['pasien_no_rm'])->first();
                        $d['status'] = $tb ? 'sudah' : 'belum';

                    } else {
                        // Handle case where there is no diagnosis
                        return null;
                    }

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

        // Retrieve the data from the model
        $data = $model->poinRequest($params);

        // Filter out the "Ruang Poli" entries
        $filteredData = array_filter($data['response']['data'], function ($item) {
            return $item['ruang_nama'] !== 'Ruang Poli';
        });

        // Prepare the response
        $response = [
            'metadata' => [
                'code' => 200,
                'message' => 'Data ditemukan!',
            ],
            'response' => [
                'data' => array_values($filteredData), // Re-index the array
            ],
        ];

        return response()->json($response);
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

    public function avgWaktuTunggu(Request $request)
    {
        try {
            $params = $request->all();
            $model = new KominfoModel();

            // Ambil data dari model menggunakan metode waktuLayananRequest
            $data = $model->waktuLayananRequest($params);
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
            'lama_igd' => 0,
            'tunggu_farmasi' => 0,
            'tunggu_kasir' => 0,
        ];

        $maxValues = $totals;
        $minValues = array_fill_keys(array_keys($totals), PHP_INT_MAX);

        $counts = [
            'rm' => 0,
            'ro' => 0,
            'lab' => 0,
            'igd' => 0,
        ];

        // Initialize above and below 90 minutes counters
        $above90 = $totals;
        $below90 = $totals;

        // Initialize variables for "oke" false
        $totalDurasiPoliOkeFalse = 0;
        $countOkeFalse = 0;

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

            $results["avg_$key"] = round($total / $jml, 2);
            $results["max_$key"] = $maxValues[$key];
            $results["min_$key"] = $minValues[$key];
            $results["total_$key"] = round($total, 2);
            $results["lebih_$key"] = $above90[$key];
            $results["lebih_persen_$key"] = round(($above90[$key] / $jml) * 100, 2);
            $results["kurang_$key"] = $below90[$key];
            $results["kurang_persen_$key"] = round(($below90[$key] / $jml) * 100, 2);
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

        // Calculate average durasi_poli for "oke" false
        $results['avg_durasi_poli_oke_false'] = $countOkeFalse > 0 ? round($totalDurasiPoliOkeFalse / $countOkeFalse, 2) : 0;

        // Calculate average lama_igd
        $results['avg_lama_igd'] = $counts['igd'] > 0 ? round($totals['lama_igd'] / $counts['igd'], 2) : 0;

        // Calculate average for "tunggu_ro"
        $rodata = array_filter($data, function ($d) {
            return $d['rodata'] === true;
        });
        $jumlahRo = count($rodata);
        $totalWaktuRo = array_sum(array_column($rodata, 'tunggu_ro')); // Sum up 'tunggu_ro' values
        $results['avg_tunggu_ro'] = $jumlahRo > 0 ? round($totalWaktuRo / $jumlahRo, 2) : 0;
        $results['max_tunggu_ro'] = $jumlahRo > 0 ? max(array_column($rodata, 'tunggu_ro')) : 0;
        $results['min_tunggu_ro'] = $jumlahRo > 0 ? min(array_column($rodata, 'tunggu_ro')) : 0;
        $results['lebih_tunggu_ro'] = count(array_filter($rodata, fn($d) => $d['tunggu_ro'] > 90));
        $results['lebih_persen_tunggu_ro'] = $jumlahRo > 0 ? round(($results['lebih_tunggu_ro'] / $jumlahRo) * 100, 2) : 0;
        $results['kurang_tunggu_ro'] = $jumlahRo > 0 ? $jumlahRo - $results['lebih_tunggu_ro'] : 0;
        $results['kurang_persen_tunggu_ro'] = $jumlahRo > 0 ? round(($results['kurang_tunggu_ro'] / $jumlahRo) * 100, 2) : 0;
        $totalWaktuRo = array_sum(array_column($rodata, 'tunggu_hasil_ro')); // Sum up 'tunggu_ro' values
        $results['avg_tunggu_hasil_ro'] = $jumlahRo > 0 ? round($totalWaktuRo / $jumlahRo, 2) : 0;
        $results['max_tunggu_hasil_ro'] = $jumlahRo > 0 ? max(array_column($rodata, 'tunggu_hasil_ro')) : 0;
        $results['min_tunggu_hasil_ro'] = $jumlahRo > 0 ? min(array_column($rodata, 'tunggu_hasil_ro')) : 0;
        $results['lebih_tunggu_hasil_ro'] = count(array_filter($rodata, fn($d) => $d['tunggu_hasil_ro'] > 90));
        $results['lebih_persen_tunggu_hasil_ro'] = $jumlahRo > 0 ? round(($results['lebih_tunggu_hasil_ro'] / $jumlahRo) * 100, 2) : 0;
        $results['kurang_tunggu_hasil_ro'] = $jumlahRo > 0 ? $jumlahRo - $results['lebih_tunggu_hasil_ro'] : 0;
        $results['kurang_persen_tunggu_hasil_ro'] = $jumlahRo > 0 ? round(($results['kurang_tunggu_hasil_ro'] / $jumlahRo) * 100, 2) : 0;

        // Similar calculations for lab
        $labdata = array_filter($data, function ($d) {
            return $d['labdata'] === true;
        });
        $jumlahLab = count($labdata);
        $totalWaktuLab = array_sum(array_column($labdata, 'tunggu_hasil_lab'));
        $results['avg_tunggu_hasil_lab'] = $jumlahLab > 0 ? round($totalWaktuLab / $jumlahLab, 2) : 0;
        $results['max_tunggu_hasil_lab'] = $jumlahLab > 0 ? max(array_column($labdata, 'tunggu_hasil_lab')) : 0;
        $results['min_tunggu_hasil_lab'] = $jumlahLab > 0 ? min(array_column($labdata, 'tunggu_hasil_lab')) : 0;
        $results['lebih_tunggu_hasil_lab'] = count(array_filter($labdata, fn($d) => $d['tunggu_hasil_lab'] > 90));
        $results['lebih_persen_tunggu_hasil_lab'] = $jumlahLab > 0 ? round(($results['lebih_tunggu_hasil_lab'] / $jumlahLab) * 100, 2) : 0;
        $results['kurang_tunggu_hasil_lab'] = $jumlahLab > 0 ? $jumlahLab - $results['lebih_tunggu_hasil_lab'] : 0;
        $results['kurang_persen_tunggu_hasil_lab'] = $jumlahLab > 0 ? round(($results['kurang_tunggu_hasil_lab'] / $jumlahLab) * 100, 2) : 0;
        $totalWaktuLab = array_sum(array_column($labdata, 'tunggu_lab'));
        $results['avg_tunggu_lab'] = $jumlahLab > 0 ? round($totalWaktuLab / $jumlahLab, 2) : 0;
        $results['max_tunggu_lab'] = $jumlahLab > 0 ? max(array_column($labdata, 'tunggu_lab')) : 0;
        $results['min_tunggu_lab'] = $jumlahLab > 0 ? min(array_column($labdata, 'tunggu_lab')) : 0;
        $results['lebih_tunggu_lab'] = count(array_filter($labdata, fn($d) => $d['tunggu_lab'] > 90));
        $results['lebih_persen_tunggu_lab'] = $jumlahLab > 0 ? round(($results['lebih_tunggu_lab'] / $jumlahLab) * 100, 2) : 0;
        $results['kurang_tunggu_lab'] = $jumlahLab > 0 ? $jumlahLab - $results['lebih_tunggu_lab'] : 0;
        $results['kurang_persen_tunggu_lab'] = $jumlahLab > 0 ? round(($results['kurang_tunggu_lab'] / $jumlahLab) * 100, 2) : 0;

        // Similar calculations for igd
        $igddata = array_filter($data, function ($d) {
            return $d['igddata'] === true;
        });
        $jumlahIgd = count($igddata);
        $totalWaktuIgd = array_sum(array_column($igddata, 'tunggu_igd'));
        $results['avg_tunggu_igd'] = $jumlahIgd > 0 ? round($totalWaktuIgd / $jumlahIgd, 2) : 0;
        $results['max_tunggu_igd'] = $jumlahIgd > 0 ? max(array_column($igddata, 'tunggu_igd')) : 0;
        $results['min_tunggu_igd'] = $jumlahIgd > 0 ? min(array_column($igddata, 'tunggu_igd')) : 0;
        $results['lebih_tunggu_igd'] = count(array_filter($igddata, fn($d) => $d['tunggu_igd'] > 90));
        $results['lebih_persen_tunggu_igd'] = $jumlahIgd > 0 ? round(($results['lebih_tunggu_igd'] / $jumlahIgd) * 100, 2) : 0;
        $results['kurang_tunggu_igd'] = $jumlahIgd > 0 ? $jumlahIgd - $results['lebih_tunggu_igd'] : 0;
        $results['kurang_persen_tunggu_igd'] = $jumlahIgd > 0 ? round(($results['kurang_tunggu_igd'] / $jumlahIgd) * 100, 2) : 0;

        return $results;
    }

    public function grafikDokter(Request $request)
    {
        $params = $request->all();
        $model = new KominfoModel();
        $data = $model->getGrafikDokter($params)['data'];
        // return $data;
        $pasien = $this->filterData($model->getTungguPoli($params)['data']);
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
            'dr. AGIL DANANJAYA, Sp.P',
            'dr. FILLY ULFA KUSUMAWARDANI',
            'dr. SIGIT DWIYANTO',
        ];

        // Filter dan format ulang data
        $result = [];
        foreach ($data as $item) {
            if (in_array($item['dokter_nama'], $doctors)) {
                $tanggal = $item['tanggal'];
                $dokter = $item['dokter_nama'];
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
                    'tanggal' => $tanggal,
                    'dokter_nama' => $dokter,
                    'jumlah' => $count,
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
                    $jumlahData = $dataItem['jumlah'];
                    $jumlahPasien = $pasienItem['jumlah'];
                    $percentage = $jumlahData > 0 ? ($jumlahData / $jumlahPasien) * 100 : 0;

                    $hasil[] = [
                        'tanggal' => $dataItem['tanggal'],
                        'dokter_nama' => $dataItem['dokter_nama'],
                        'jumlah_farmasi' => $jumlahData,
                        'jumlah_pasien' => $jumlahPasien,
                        'percentage' => round($percentage, 2),
                    ];
                }
            }
        }

        return $hasil;
    }

    // public function grafikDokter(Request $request)
    // {
    //     $params = $request->all();
    //     $model = new KominfoModel();
    //     $data = $model->getGrafikDokter($params)['data'];
    //     $pasien = $this->filterData($model->getTungguPoli($params)['data']);

    //     $formattedData = $this->filterData($data);
    //     $hasil = $this->calculatePercentage($formattedData, $pasien);

    //     return response()->json($hasil, 200, [], JSON_PRETTY_PRINT);
    // }

    // private function filterData(array $data)
    // {
    //     // Daftar nama dokter yang ingin dihitung
    //     $doctors = [
    //         'dr. Cempaka Nova Intani, Sp.P, FISR., MM.',
    //         'dr. AGIL DANANJAYA, Sp.P',
    //         'dr. FILLY ULFA KUSUMAWARDANI',
    //         'dr. SIGIT DWIYANTO',
    //     ];

    //     // Filter dan format ulang data
    //     $result = [];
    //     foreach ($data as $item) {
    //         if (in_array($item['dokter_nama'], $doctors)) {
    //             $tanggal = $item['tanggal'];
    //             $dokter = $item['dokter_nama'];
    //             $result[$tanggal][$dokter] = ($result[$tanggal][$dokter] ?? 0) + 1;
    //         }
    //     }

    //     // Tambahkan dokter yang tidak memiliki data
    //     foreach ($result as $tanggal => &$dokters) {
    //         foreach ($doctors as $dokter) {
    //             $dokters[$dokter] = $dokters[$dokter] ?? 0;
    //         }
    //     }

    //     // Format ulang menjadi array terstruktur
    //     $formattedResult = [];
    //     foreach ($result as $tanggal => $dokters) {
    //         foreach ($dokters as $dokter => $count) {
    //             $formattedResult[] = [
    //                 'tanggal' => $tanggal,
    //                 'dokter_nama' => $dokter,
    //                 'jumlah' => $count,
    //             ];
    //         }
    //     }

    //     // Urutkan berdasarkan tanggal
    //     usort($formattedResult, fn($a, $b) => strtotime($a['tanggal']) - strtotime($b['tanggal']));

    //     return $formattedResult;
    // }

    // private function calculatePercentage(array $data, array $pasien)
    // {
    //     $hasil = [];
    //     foreach ($data as $dataItem) {
    //         foreach ($pasien as $pasienItem) {
    //             if ($dataItem['dokter_nama'] === $pasienItem['dokter_nama'] && $dataItem['tanggal'] === $pasienItem['tanggal']) {
    //                 $jumlahData = $dataItem['jumlah'];
    //                 $jumlahPasien = $pasienItem['jumlah'];
    //                 $percentage = $jumlahData > 0 ? ($jumlahData / $jumlahPasien) * 100 : 0;

    //                 $hasil[] = [
    //                     'tanggal' => $dataItem['tanggal'],
    //                     'dokter_nama' => $dataItem['dokter_nama'],
    //                     'jumlah_farmasi' => $jumlahData,
    //                     'jumlah_pasien' => $jumlahPasien,
    //                     'percentage' => round($percentage, 2),
    //                 ];
    //             }
    //         }
    //     }

    //     return $hasil;
    // }

}
