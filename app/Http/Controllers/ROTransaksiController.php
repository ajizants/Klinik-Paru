<?php
namespace App\Http\Controllers;

use App\Models\KunjunganWaktuSelesai;
use App\Models\PasienModel;
use App\Models\RoHasilModel;
use App\Models\ROJenisKondisi;
use App\Models\ROTransaksiHasilModel;
use App\Models\ROTransaksiModel;
use App\Models\TransPetugasModel;
use Carbon\Carbon;
use function PHPUnit\Framework\isEmpty;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

// Sesuaikan dengan nama model Anda

class ROTransaksiController extends Controller
{

    public function dataTransaksiRo(Request $request)
    {
        $tglAwal  = $request->input('tglAwal');
        $tglAkhir = $request->input('tglAkhir');
        $norm     = $request->input('norm');
        $norm     = str_pad($norm, 6, '0', STR_PAD_LEFT);
        $data     = ROTransaksiModel::with('film', 'foto', 'proyeksi', 'mesin', 'kv', 'ma', 's')
            ->when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
                return $query->where('norm', $norm);
            })
            ->whereBetween('tgltrans', [$tglAwal, $tglAkhir])
            ->get();
        if ($data->isEmpty()) {
            return response()->json(['message' => 'Data pasien tidak ditemukan'], 404, [], JSON_PRETTY_PRINT);
        } else {
            return response()->json(['data' => $data], 200, [], JSON_PRETTY_PRINT);
        }

    }
    public function addTransaksiRo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notrans'        => 'required',
            'norm'           => 'required',
            'nama'           => 'required',
            'alamat'         => 'required',
            'tgltrans'       => 'required|date_format:Y-m-d',
            'noreg'          => 'required',
            'pasienRawat'    => 'required',
            'kdFoto'         => 'required',
            'kdFilm'         => 'required',
            'ma'             => 'required',
            'kv'             => 'required',
            's'              => 'required',
            'jmlExpose'      => 'required',
            'jmlFilmDipakai' => 'required',
            'jmlFilmRusak'   => 'required',
            'kdMesin'        => 'required',
            'kdProyeksi'     => 'required',
            'layanan'        => 'required',
            'p_rontgen'      => 'required',
            'dokter'         => 'required',
            'jk'             => 'required',
        ]);

        // Jika validasi gagal, kembalikan respons dengan pesan error
        if ($validator->fails()) {
            return response()->json([
                'errors'  => $validator->errors(),
                'message' => 'Data belum lengkap. Mohon lengkapi semua data yang diperlukan.',
            ], 400);
        }

        DB::beginTransaction(); // Mulai transaksi

        try {
            $massage = '';
            $msgFile = '';
            // Cari data berdasarkan notrans
            $transaksi = ROTransaksiModel::where('notrans', $request->input('notrans'))->first();
            if (! $transaksi) {
                // Jika tidak ada, buat entitas baru
                $transaksi          = new ROTransaksiModel();
                $transaksi->notrans = $request->input('notrans');
                $massage            = 'Transaksi Baru...!!';
            } else {
                $massage = 'Transaksi Update...!!';
            }
            $tglTrans        = $request->input('tgltrans'); // Assuming tglTrans is in 'Y-m-d' format
            $currentDateTime = Carbon::now();               // Get current date and time
            $today           = $currentDateTime->format('Y-m-d');

            // Check if today's date is not the same as tglTrans
            if ($today !== $tglTrans) {
                // Create a Carbon instance using tglTrans and the current time
                $tanggal = Carbon::createFromFormat('Y-m-d H:i:s', $tglTrans . ' ' . $currentDateTime->format('H:i:s'));
            } else {
                // Use the current date and time
                $tanggal = $currentDateTime;
            }

            // Isi properti model dengan data dari permintaan
            $transaksi->norm           = $request->input('norm');
            $transaksi->nama           = $request->input('nama');
            $transaksi->alamat         = $request->input('alamat');
            $transaksi->jk             = $request->input('jk');
            $transaksi->tgltrans       = $request->input('tgltrans');
            $transaksi->noreg          = $request->input('noreg');
            $transaksi->pasienRawat    = $request->input('pasienRawat');
            $transaksi->kdFoto         = $request->input('kdFoto');
            $transaksi->kdFilm         = $request->input('kdFilm');
            $transaksi->ma             = $request->input('ma');
            $transaksi->kv             = $request->input('kv');
            $transaksi->s              = $request->input('s');
            $transaksi->jmlExpose      = $request->input('jmlExpose');
            $transaksi->jmlFilmDipakai = $request->input('jmlFilmDipakai');
            $transaksi->jmlFilmRusak   = $request->input('jmlFilmRusak');
            $transaksi->kdMesin        = $request->input('kdMesin');
            $transaksi->kdProyeksi     = $request->input('kdProyeksi');
            $transaksi->catatan        = $request->input('catatan');
            $transaksi->layanan        = $request->input('layanan');
            $transaksi->selesai        = 1;
            $transaksi->kdKondisiRo    = 55;
            $transaksi->created_at     = $tanggal;
            $transaksi->updated_at     = $tanggal;
            $id                        = "";

            if ($request->hasFile('gambar')) {
                if ($request->input('ket_foto') == '') {
                    $ket_foto = 'PA';
                } else {
                    $ket_foto = $request->input('ket_foto');
                }
                $tanggalBersih = preg_replace("/[^0-9]/", "", $request->input('tgltrans'));

                // $id = $tanggalBersih . '_' . $request->input('norm') . '_' . $ket_foto;
                $dataRO = ROTransaksiHasilModel::orderBy('id', 'desc')->first();
                $id     = $dataRO->id + 1;
                // dd($id);
                $namaFile = $tanggalBersih . '_' . $request->input('norm') . '_' . $ket_foto . $request->input('foto') . '.' . pathinfo($request->file('gambar')->getClientOriginalName(), PATHINFO_EXTENSION);
            } else {
                $namaFile = null;
            }

            $transaksi->save();

            // Simpan transaksi petugas, cari data berdasarkan notrans, jika ada update, jika tidak ada create
            $petugas = TransPetugasModel::where('notrans', $request->input('notrans'))->first();
            if (! $petugas) {
                $petugas          = new TransPetugasModel();
                $petugas->notrans = $request->input('notrans');
            }

            $petugas->p_dokter_poli = $request->input('dokter');
            $petugas->p_rontgen     = $request->input('p_rontgen');

            // Simpan data petugas ke dalam database
            $petugas->save();

            // Commit transaksi data utama
            DB::commit();

            // Mulai transaksi untuk upload gambar
            DB::beginTransaction();
            try {
                if ($request->hasFile('gambar')) {
                    // dd($namaFile);
                    $upload = ROTransaksiHasilModel::where('norm', $request->input('norm'))
                        ->where('foto', $namaFile)
                        ->whereDate('tanggal', $request->input('tgltrans'))
                        ->first();
                    // dd($upload);

                    if (! $upload) {
                        // Jika tidak ada data, buat entitas baru
                        $upload          = new ROTransaksiHasilModel();
                        $upload->id      = $id;
                        $upload->norm    = $request->input('norm');
                        $upload->tanggal = $request->input('tgltrans');
                        $upload->nama    = $request->input('nama');
                        $upload->foto    = $namaFile;

                        // Upload gambar karena data belum ada
                        $file     = $request->file('gambar');
                        $fileName = $file->getClientOriginalName();
                        $filePath = $file->getPathname();
                        $jenis    = $request->input('ket_foto');
                        // dd($jenis);

                        $param = [
                            [
                                'name'     => 'id',
                                'contents' => $id,
                            ],
                            [
                                'name'     => 'norm',
                                'contents' => $request->input('norm'),
                            ],
                            [
                                'name'     => 'notrans',
                                'contents' => $request->input('notrans'),
                            ],
                            [
                                'name'     => 'tanggal',
                                'contents' => $request->input('tgltrans'),
                            ],
                            [
                                'name'     => 'nama',
                                'contents' => $request->input('nama'),
                            ],
                            [
                                'name'     => 'jenis',
                                'contents' => $jenis,
                            ],
                            [
                                'name'     => 'foto',
                                'contents' => fopen($filePath, 'r'),
                                'filename' => $fileName,
                            ],
                        ];

                        // Simpan foto db rontgen dengan memanggil metode simpanFoto()
                        $keterangan_upload = $upload->simpanFoto($param);
                        //ambil message dari respon
                        $ket_upload = $keterangan_upload['message'];
                        $upload->save();
                        DB::commit(); // Commit transaksi jika semua berhasil
                    } else {
                        $ket_upload = 'Sudah ada foto thorax yang diupload';
                    }
                } else {
                    $upload = ROTransaksiHasilModel::where('norm', $request->input('norm'))
                        ->whereDate('tanggal', $request->input('tgltrans'))
                        ->where('foto', $namaFile)
                        ->first();

                    if (! $upload) {
                        $ket_upload = 'Tidak ada foto thorax yang dipilih untuk di upload';
                    } else {
                        $ket_upload = 'Sudah ada foto thorax yang diupload';
                    }
                }

                $resMsg = [
                    'metadata' => [
                        'message' => 'Data berhasil disimpan',
                        'status'  => 200,
                    ],
                    'data'     => [
                        'transaksi'   => $massage,
                        'foto_thorax' => $ket_upload,
                    ],
                ];

                return response()->json($resMsg, 200, [], JSON_PRETTY_PRINT);
            } catch (\Exception $e) {
                DB::rollback(); // Rollback transaksi jika terjadi kesalahan
                Log::error('Terjadi kesalahan saat mengupload gambar: ' . $e->getMessage());
                return response()->json(['message' => 'Terjadi kesalahan saat mengupload gambar: ' . $e->getMessage()], 500);
            }

        } catch (\Exception $e) {
            DB::rollback(); // Rollback transaksi jika terjadi kesalahan
            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function deleteTransaksiRo(Request $request)
    {
        $notrans = $request->input('notrans');
        $tanggal = $request->input('tanggal');
        return [
            'notrans' => $notrans,
            'tanggal' => $tanggal,
        ];

        try {
            DB::beginTransaction();

            // Ambil data transaksi
            $transaksi = ROTransaksiModel::where('notrans', $notrans)
                ->whereDate('tgltrans', $tanggal)
                ->first();

            if (! $transaksi) {
                return response()->json(['message' => 'Data transaksi tidak ditemukan'], 404);
            }

            $norm = $transaksi->norm;
            $tgl  = $transaksi->tgltrans;

            // Ambil data foto thorax
            $hasilFoto = ROTransaksiHasilModel::where('norm', $norm)
                ->whereDate('tanggal', $tgl)
                ->get();

            if ($hasilFoto->isEmpty()) {
                return response()->json(['message' => 'Data foto thorax tidak ditemukan'], 404);
            }

            // Hapus semua foto yang terkait
            foreach ($hasilFoto as $foto) {
                $this->hapusGambar(['id' => $foto->id]);
            }

            // Hapus data transaksi
            $transaksi->delete();

            DB::commit();
            return response()->json(['message' => 'Transaksi berhasil dihapus'], 200);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Kesalahan saat menghapus transaksi: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function deleteGambar(Request $request)
    {
        $idFoto = $request->input('id');
        return $this->hapusGambar(['id' => $idFoto]);
    }

    private function hapusGambar(array $params)
    {
        $msg = [];
        DB::beginTransaction();
        $client = new Client();

        try {
            $gambar = ROTransaksiHasilModel::find($params['id']);

            if (! $gambar) {
                return response()->json([
                    'metadata' => [
                        'message' => 'Data gambar tidak ditemukan',
                        'status'  => 404,
                    ],
                ], 404);
            }

            // URL untuk menghapus gambar di server eksternal
            $urlDelete = env('APP_URL_DELETE') . '?id=' . $params['id'];

            // Kirim permintaan ke server eksternal
            $response = $client->get($urlDelete);

            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Gagal menghapus gambar dari server eksternal.');
            }

            $res = json_decode($response->getBody()->getContents(), true);

            // Hapus data gambar dari database lokal
            $gambar->delete();

            $msg = [
                'metadata' => [
                    'message' => $res['message'] ?? 'Gambar berhasil dihapus',
                    'status'  => 200,
                ],
            ];

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Kesalahan saat menghapus gambar: ' . $e->getMessage());
            $msg = [
                'metadata' => [
                    'message' => 'Terjadi kesalahan saat menghapus gambar: ' . $e->getMessage(),
                    'status'  => 500,
                ],
            ];
        }

        return response()->json($msg, 200, [], JSON_PRETTY_PRINT);
    }

    public function updateGambar(Request $request)
    {
        // Sanitize the date by removing non-numeric characters
        $tanggalBersih = preg_replace("/[^0-9]/", "", $request->input('tgltrans'));
        $dataRO        = ROTransaksiHasilModel::orderBy('id', 'desc')->first();
        $id            = $dataRO->id + 1;
        // Construct the new file name
        $namaFile = $tanggalBersih . '_' . $request->input('norm') . '_' . $request->input('ket_foto') . $request->input('foto') . '.' . $request->file('gambar')->getClientOriginalExtension();
        $key      = pathinfo($namaFile, PATHINFO_FILENAME);
        // dd($namaFile);
        // Find the existing record by ID
        $dataFoto = ROTransaksiHasilModel::where('norm', $request->input('norm'))
            ->where('foto', 'like', '%' . $key . '%')
            ->first();

        // dd($dataFoto);

        if ($dataFoto) {
            // Update the record with new data
            $dataFoto->norm    = $request->input('norm');
            $dataFoto->tanggal = $request->input('tgltrans');
            $dataFoto->nama    = $request->input('nama');
            $dataFoto->foto    = $namaFile;

            // Upload gambar karena data belum ada
            $file     = $request->file('gambar');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->getPathname();
            $jenis    = $request->input('ket_foto');
            // dd($jenis);

            $param = [
                [
                    'name'     => 'id',
                    'contents' => $id,
                ],
                [
                    'name'     => 'norm',
                    'contents' => $request->input('norm'),
                ],
                [
                    'name'     => 'notrans',
                    'contents' => $request->input('notrans'),
                ],
                [
                    'name'     => 'tanggal',
                    'contents' => $request->input('tgltrans'),
                ],
                [
                    'name'     => 'nama',
                    'contents' => $request->input('nama'),
                ],
                [
                    'name'     => 'jenis',
                    'contents' => $jenis,
                ],
                [
                    'name'     => 'foto',
                    'contents' => fopen($filePath, 'r'),
                    'filename' => $fileName,
                ],
            ];

            // Update foto di db rontgen dengan memanggil metode simpanFoto()
            $keterangan_upload = $dataFoto->simpanFoto($param);
            //ambil message dari respon
            $resMsg = [
                // 'metadata' => [
                'message' => $keterangan_upload['message'],
                'status'  => 200,
                // ],
            ];
            // Save the updated record to the database RS Paru
            $dataFoto->save();

            return response()->json($resMsg, 200, [], JSON_PRETTY_PRINT);
        } else {
            return response()->json(['message' => 'Data not found'], 404);
        }

    }

    public function hasilRo(Request $request)
    {
        try {
            $norm = $request->input('norm');
            $tgl  = $request->input('tgltrans');
            $data = RoHasilModel::when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
                return $query->where('norm', $norm);
            })
                ->where('tanggal', 'like', '%' . $tgl . '%')
                ->get();

            if ($data->isEmpty()) {
                $res = [
                    'message' => 'Data Foto Thorax pada Pasien dengan Norm: <u><b>' . $norm . '</b></u> tidak ditemukan,<br> Jika pasien melakukan Foto Thorax di KKPM, silahkan Menghubungi Bagian Radiologi. Terima Kasih..."',
                    'status'  => 404,
                ];
                return response()->json($res, 404, [], JSON_PRETTY_PRINT);
            } else {
                $res = [
                    'metadata' => [
                        'message' => 'Data foto thorax ditemukan',
                        'status'  => 200,
                    ],
                    'data'     => $data,
                ];
            }
            return response()->json($res, 200, [], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            // Handle the exception if the database is down or any other error occurs

            return response()->json([
                'message' => 'Terjadi kesalahan saat mengakses database. Silahkan hubungi radiologi untuk menghidupkan server.',
                'status'  => 500,
            ], 500, [], JSON_PRETTY_PRINT);

        }

    }

    // public function logBook(Request $request)
    // {
    //     // dd($request->all());
    //     $norm = $request->input('norm');
    //     $tglAwal = $request->input('tglAwal');
    //     $tglAkhir = $request->input('tglAkhir');
    //     $data = ROTransaksiModel::with('proyeksi', 'mesin', 'kv', 'ma', 's', 'kondisiOld', 'film', 'foto', 'radiografer.radiografer', 'kunjungan')
    //         ->when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
    //             return $query->where('norm', $norm);
    //         })
    //         ->whereBetween('tgltrans', [$tglAwal, $tglAkhir])
    //         ->orderBy('tgltrans', 'asc') // Sort by tglTrans ascending
    //         ->orderBy('noreg', 'asc') // Sort by noreg ascending
    //         ->get();

    //     $res = [];
    //     $jumlah = [];

    //     // $kominfoModel = new KominfoModel();
    //     foreach ($data as $d) {
    //         // Cari Layanan
    //         if ($d['layanan'] === "" || $d['layanan'] === null) {
    //             if ($d['kunjungan']['kkelompok'] == "1") {
    //                 $d['layanan'] = "UMUM";
    //             } elseif ($d['kunjungan']['kkelompok'] == "2") {
    //                 $d['layanan'] = "BPJS";
    //             } else {
    //                 $d['layanan'] = "JAMKESDA";
    //             }
    //         }

    //         //Cari Kondisi RO
    //         if ($d['ma'] === null && $d['s'] === null && $d['kv'] === null) {
    //             $d['kondisiRo'] = $d['kondisiOld']['kondisiRo'] ?? null;
    //         } else {
    //             $kv = ROJenisKondisi::where('kdKondisiRo', $d['kv'])->first();
    //             $ma = ROJenisKondisi::where('kdKondisiRo', $d['ma'])->first();
    //             $s = ROJenisKondisi::where('kdKondisiRo', $d['s'])->first();

    //             if ($kv && $ma && $s) {
    //                 $d['kondisiRo'] = $kv->nmKondisi . "  " . $ma->nmKondisi . "  " . $s->nmKondisi;
    //             } else {
    //                 // Handle jika ada yang tidak ditemukan atau null
    //                 $d['kondisiRo'] = "Tidak ditemukan kondisi yang sesuai";
    //             }
    //         }

    //         //Cari Proyeksi
    //         $kdProy = [];
    //         if ($d['kdProyeksi'] === null) {
    //             if ($d['pa'] === 1) {
    //                 $kdProy[] = 'pa';
    //             }
    //             if ($d['ap'] === 1) {
    //                 $kdProy[] = 'ap';
    //             }
    //             if ($d['lateral'] === 1) {
    //                 $kdProy[] = 'lateral';
    //             }
    //             if ($d['obliq'] === 1) {
    //                 $kdProy[] = 'obliq';
    //             }

    //             // Join the array elements into a string separated by commas
    //             $proy = !empty($kdProy) ? implode(', ', $kdProy) : null;
    //             $kdProyeksi = $d['proyeksi']['kdProyeksi'] ?? null;
    //         } else {
    //             $proy = $d['proyeksi']['proyeksi'] ?? null;
    //             $kdProyeksi = $d['proyeksi']['kdProyeksi'] ?? null;
    //         }

    //         //Cari Alamat
    //         $alamatFix = [];
    //         if ($d['nama'] === null || $d['alamat'] === "" || $d['alamat'] === null || $d['jk'] === null) {
    //             // dd("alamat null");
    //             $pasien = PasienModel::where('norm', $d['norm'])->first();
    //             // dd($pasien);
    //             $alamatFix = ($pasien['kelurahan'] ?? null) . ", " . ($pasien['rtrw'] ?? null) . ", " . ($pasien['kecamatan'] ?? null) . ", " . ($pasien['kabupaten'] ?? null);
    //             $namaPasien = $pasien['nama'] ?? null;
    //             $jkPasien = $pasien['jkel'] ?? null;
    //         } else {
    //             $alamatFix = $d['alamat'];
    //             $namaPasien = $d['nama'];
    //             $jkPasien = $d['jk'];
    //         }

    //         $res[] = [
    //             "notrans" => $d['notrans'],
    //             "norm" => $d['norm'],

    //             "nama" => $namaPasien,
    //             "alamatDbOld" => $alamatFix,
    //             "jkel" => $jkPasien,

    //             "tgltrans" => $d['tgltrans'],
    //             "ktujuan" => $d['ktujuan'],
    //             "pasienRawat" => $d['pasienRawat'],
    //             "noreg" => $d['noreg'],

    //             "jmlExpose" => $d['jmlExpose'],
    //             "jmlFilmDipakai" => $d['jmlFilmDipakai'],
    //             "jmlFilmRusak" => $d['jmlFilmRusak'],

    //             "catatan" => $d['catatan'],
    //             "selesai" => $d['selesai'],

    //             "layanan" => $d['layanan'],
    //             "kdLayanan" => $d['kunjungan']['kkelompok'] ?? null,
    //             "file" => $d['file'],

    //             "norm" => $d['pasien']['norm'] ?? null,
    //             "noktp" => $d['pasien']['noktp'] ?? null,
    //             // "nama" => $d['pasien']['nama'] ?? null,
    //             "domisili" => $d['pasien']['alamat'] ?? null,
    //             "jeniskel" => $d['pasien']['jeniskel'] ?? null,
    //             // "jkel" => $d['pasien']['jkel'] ?? null,
    //             "tmptlahir" => $d['pasien']['tmptlahir'] ?? null,
    //             "tgllahir" => $d['pasien']['tgllahir'] ?? null,
    //             "umur" => $d['pasien']['umur'] ?? null,
    //             "nohp" => $d['pasien']['nohp'] ?? null,
    //             // "alamatDbOld" => ($d['pasien']['kelurahan'] ?? null) . ", " . ($d['pasien']['rtrw'] ?? null) . ", " . ($d['pasien']['kecamatan'] ?? null) . ", " . ($d['pasien']['kabupaten'] ?? null),
    //             "rtrw" => $d['pasien']['rtrw'] ?? null,
    //             "provinsi" => $d['pasien']['provinsi'] ?? null,
    //             "kabupaten" => $d['pasien']['kabupaten'] ?? null,
    //             "kecamatan" => $d['pasien']['kecamatan'] ?? null,
    //             "kelurahan" => $d['pasien']['kelurahan'] ?? null,

    //             "kdFoto" => $d['kdFoto'],
    //             "kdFoto" => $d['foto']['kdFoto'] ?? null,
    //             "nmFoto" => $d['foto']['nmFoto'] ?? null,

    //             "kdKv" => $d['kv'],
    //             "kdMa" => $d['ma'],
    //             "KdS" => $d['s'],
    //             "kdKondisiRo" => $d['kdKondisiRo'],
    //             "kondisiRo" => $d['kondisiRo'],

    //             "kdFilm" => $d['kdFilm'],
    //             "kdFilm" => $d['film']['kdFilm'] ?? null,
    //             "ukuranFilm" => $d['film']['ukuranFilm'] ?? null,

    //             "kdProyeksi" => $kdProyeksi,
    //             "proyeksi" => $proy,
    //             "pa" => $d['pa'],
    //             "ap" => $d['ap'],
    //             "lateral" => $d['lateral'],
    //             "obliq" => $d['obliq'],

    //             "kdMesin" => $d['kdMesin'],
    //             "kdMesin" => $d['mesin']['kdMesin'] ?? null,
    //             "nmMesin" => $d['mesin']['nmMesin'] ?? null,

    //             "p_rontgen" => $d['radiografer']['p_rontgen'] ?? null,
    //             "radiografer_nama" => ($d['radiografer']['radiografer']['nama'] ?? null) . ", Amd.Rad.",
    //         ];
    //     }

    //     $ambar = count(array_filter($res, function ($item) {
    //         return isset($item['p_rontgen']) && $item['p_rontgen'] === '197404231998032006';
    //     }));

    //     $nofi = count(array_filter($res, function ($item) {
    //         return isset($item['p_rontgen']) && $item['p_rontgen'] === '199009202011012001';
    //     }));

    //     $jumlah = [
    //         [
    //             "nama" => "AMBARSARI, Amd.Rad.",
    //             "nip" => "197404231998032006",
    //             "jml" => $ambar,
    //         ],
    //         [
    //             "nama" => "NOFI INDRIYANI, Amd.Rad.",
    //             "nip" => "199009202011012001",
    //             "jml" => $nofi,
    //         ],
    //     ];

    //     $response = [
    //         "jumlah" => $jumlah,
    //         "data" => $res,

    //     ];

    //     if ($data->isEmpty()) {
    //         return response()->json(['message' => 'Data transaksi tidak ditemukan'], 404, [], JSON_PRETTY_PRINT);
    //     } else {
    //         // return response()->json(['data' => $res], 200, [], JSON_PRETTY_PRINT);
    //         return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    //     }
    // }

    public function logBook(Request $request)
    {
        $norm     = $request->input('norm');
        $tglAwal  = $request->input('tglAwal');
        $tglAkhir = $request->input('tglAkhir');

        $data = ROTransaksiModel::with([
            'proyeksi', 'mesin', 'kv', 'ma', 's',
            'kondisiOld', 'film', 'foto',
            'radiografer.radiografer', 'kunjungan',
        ])
            ->when($norm && $norm !== '000000', fn($query) => $query->where('norm', $norm))
            ->whereBetween('tgltrans', [$tglAwal, $tglAkhir])
            ->orderBy('tgltrans')->orderBy('noreg')
            ->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Data transaksi tidak ditemukan'], 404, [], JSON_PRETTY_PRINT);
        }

        // Ambil semua kode kondisi sekaligus untuk mengurangi query berulang
        $kodeKondisi  = collect($data)->pluck('kv')->merge($data->pluck('ma'))->merge($data->pluck('s'))->unique()->filter();
        $jenisKondisi = ROJenisKondisi::whereIn('kdKondisiRo', $kodeKondisi)->get()->keyBy('kdKondisiRo');

        // Ambil semua pasien berdasarkan norm agar tidak query berulang dalam loop
        $norms      = $data->pluck('norm')->unique()->filter();
        $pasienData = PasienModel::whereIn('norm', $norms)->get()->keyBy('norm');

        $res    = [];
        $jumlah = [
            ["nama" => "AMBARSARI, Amd.Rad.", "nip" => "197404231998032006", "jml" => 0],
            ["nama" => "NOFI INDRIYANI, Amd.Rad.", "nip" => "199009202011012001", "jml" => 0],
        ];

        foreach ($data as $d) {
            // Tentukan layanan
            $d['layanan'] = $d['layanan'] ?? match (optional($d->kunjungan)->kkelompok) {
                "1" => "UMUM", "2" => "BPJS",     default => "JAMKESDA"
            };

            // Tentukan kondisi RO
            if ($d['ma'] === null && $d['s'] === null && $d['kv'] === null) {
                $d['kondisiRo'] = optional($d->kondisiOld)->kondisiRo;
            } else {
                $kondisiRo = collect([$d['kv'], $d['ma'], $d['s']])
                    ->map(fn($kd) => $jenisKondisi[$kd]->nmKondisi ?? null)
                    ->filter()
                    ->implode("  ");
                $d['kondisiRo'] = $kondisiRo ?: "Tidak ditemukan kondisi yang sesuai";
            }

            // Tentukan proyeksi
            $kdProyeksi = optional($d->proyeksi)->kdProyeksi;
            $proy       = $kdProyeksi ? optional($d->proyeksi)->proyeksi : implode(', ', array_filter([
                $d['pa'] ? 'pa' : null,
                $d['ap'] ? 'ap' : null,
                $d['lateral'] ? 'lateral' : null,
                $d['obliq'] ? 'obliq' : null,
            ]));

            // Tentukan alamat
            $pasien     = $pasienData[$d['norm']] ?? null;
            $alamatFix  = $pasien ? "{$pasien->kelurahan}, {$pasien->rtrw}, {$pasien->kecamatan}, {$pasien->kabupaten}" : $d['alamat'];
            $namaPasien = $pasien->nama ?? $d['nama'];
            $jkPasien   = $pasien->jkel ?? $d['jk'];

            $res[] = [
                "notrans"          => $d['notrans'],
                "norm"             => $d['norm'],
                "nama"             => $namaPasien,
                "alamatDbOld"      => $alamatFix,
                "jkel"             => $jkPasien,
                "tgltrans"         => $d['tgltrans'],
                "ktujuan"          => $d['ktujuan'],
                "pasienRawat"      => $d['pasienRawat'],
                "noreg"            => $d['noreg'],
                "jmlExpose"        => $d['jmlExpose'],
                "jmlFilmDipakai"   => $d['jmlFilmDipakai'],
                "jmlFilmRusak"     => $d['jmlFilmRusak'],
                "catatan"          => $d['catatan'],
                "selesai"          => $d['selesai'],
                "layanan"          => $d['layanan'],
                "kdLayanan"        => optional($d->kunjungan)->kkelompok,
                "file"             => $d['file'],
                "noktp"            => optional($d->pasien)->noktp,
                "domisili"         => optional($d->pasien)->alamat,
                "jeniskel"         => optional($d->pasien)->jeniskel,
                "tmptlahir"        => optional($d->pasien)->tmptlahir,
                "tgllahir"         => optional($d->pasien)->tgllahir,
                "umur"             => optional($d->pasien)->umur,
                "nohp"             => optional($d->pasien)->nohp,
                "rtrw"             => optional($d->pasien)->rtrw,
                "provinsi"         => optional($d->pasien)->provinsi,
                "kabupaten"        => optional($d->pasien)->kabupaten,
                "kecamatan"        => optional($d->pasien)->kecamatan,
                "kelurahan"        => optional($d->pasien)->kelurahan,
                "kdFoto"           => optional($d->foto)->kdFoto,
                "nmFoto"           => optional($d->foto)->nmFoto,
                "kdKv"             => $d['kv'],
                "kdMa"             => $d['ma'],
                "KdS"              => $d['s'],
                "kdKondisiRo"      => $d['kdKondisiRo'],
                "kondisiRo"        => $d['kondisiRo'],
                "kdFilm"           => optional($d->film)->kdFilm,
                "ukuranFilm"       => optional($d->film)->ukuranFilm,
                "kdProyeksi"       => $kdProyeksi,
                "proyeksi"         => $proy,
                "kdMesin"          => optional($d->mesin)->kdMesin,
                "nmMesin"          => optional($d->mesin)->nmMesin,
                "p_rontgen"        => optional($d->radiografer)->p_rontgen,
                "radiografer_nama" => optional($d->radiografer->radiografer)->nama ? optional($d->radiografer->radiografer)->nama . ", Amd.Rad." : null,
            ];

            foreach ($jumlah as &$j) {
                if ($j['nip'] === $res[count($res) - 1]['p_rontgen']) {
                    $j['jml']++;
                }
            }
        }
        $table = $this->table(collect($res));

        return response()->json(["jumlah" => $jumlah, "data" => $table], 200, [], JSON_PRETTY_PRINT);
    }

    public function table($data)
    {
        if ($data->isEmpty()) {
            return response()->make('<tr><td colspan="10" class="text-center">Data tidak ditemukan</td></tr>', 200);
        }

        // Ambil semua pasien untuk mengurangi query dalam loop
        $norms      = $data->pluck('norm')->unique()->filter();
        $pasienData = PasienModel::whereIn('norm', $norms)->get()->keyBy('norm');

        // Bangun tabel HTML
        $html = '<table class="table table-bordered" id="logBookTable">
        <thead class="bg bg-secondary">
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">No Reg</th>
                <th rowspan="2">Layanan</th>
                <th rowspan="2">Tanggal</th>
                <th rowspan="2">No RM</th>
                <th rowspan="2">Nama</th>
                <th rowspan="2">JK</th>
                <th rowspan="2">Alamat</th>
                <th rowspan="2">Nama Foto</th>
                <th rowspan="2">Ukuran Film</th>
                <th rowspan="2">Kondisi</th>
                <th class="text-center" colspan="3">Jumlah</th>
                <th rowspan="2">Proyeksi</th>
                <th rowspan="2">Mesin</th>
                <th rowspan="2">Catatan</th>
                <th rowspan="2">Radiografer</th>
            </tr>
            <tr>
                <th>Film</th>
                <th>Expose</th>
                <th>Rusak</th>
            </tr>
        </thead>
        <tbody>';

        // Inisialisasi total
        $totalFilmDipakai = 0;
        $totalExpose      = 0;
        $totalRusak       = 0;

        foreach ($data as $index => $d) {
            $html .= "<tr>
            <td>" . ($index + 1) . "</td>
            <td>{$d['noreg']}</td>
            <td>{$d['layanan']}</td>
            <td>{$d['tgltrans']}</td>
            <td>{$d['norm']}</td>
            <td>{$d['nama']}</td>
            <td>{$d['jkel']}</td>
            <td>{$d['alamatDbOld']}</td>
            <td>{$d['nmFoto']}</td>
            <td>{$d['ukuranFilm']}</td>
            <td>{$d['kondisiRo']}</td>
            <td>{$d['jmlFilmDipakai']}</td>
            <td>{$d['jmlExpose']}</td>
            <td>{$d['jmlFilmRusak']}</td>
            <td>{$d['proyeksi']}</td>
            <td>{$d['nmMesin']}</td>
            <td>{$d['catatan']}</td>
            <td>{$d['radiografer_nama']}</td>
            </tr>";

            // Hitung total
            $totalFilmDipakai += $d['jmlFilmDipakai'];
            $totalExpose += $d['jmlExpose'];
            $totalRusak += $d['jmlFilmRusak'];
        }

        // Hitung jumlah PA & AP
        $jumlahPA     = collect($data)->where('proyeksi', 'PA')->count();
        $jumlahAP     = collect($data)->where('proyeksi', 'AP')->count();
        $jumlahLat    = collect($data)->where('proyeksi', 'Lateral')->count();
        $jumlahOB     = collect($data)->where('proyeksi', 'Obliq')->count();
        $jmlIndoray_1 = collect($data)->where('nmMesin', 'Indoray 1')->count();
        $jmlIndoray_2 = collect($data)->where('nmMesin', 'Indoray 2')->count();
        $petugas_1    = collect($data)->where('p_rontgen', '197404231998032006')->count();
        $petugas_2    = collect($data)->where('p_rontgen', '199009202011012001')->count();

        $html .= '
            <tr class="bg bg-secondary font-weight-bold">
                <th class="text-center" >' . $data->count() + 1 . '</th>
                <th class="text-center" ></th>
                <th class="text-center" ></th>
                <th class="text-center" ></th>
                <th class="text-center" ></th>
                <th class="text-center" ></th>
                <th class="text-center" ></th>
                <th class="text-center" >Total</th>
                <th class="text-center" ></th>
                <th class="text-center" ></th>
                <th class="text-center" ></th>
                <th>' . $totalFilmDipakai . '</th>
                <th>' . $totalExpose . '</th>
                <th>' . $totalRusak . '</th>
                <th>PA: ' . $jumlahPA . '<br> AP: ' . $jumlahAP . '<br> Lat: ' . $jumlahLat . '<br> Obliq: ' . $jumlahOB . '</th>
                <th>Indoray 1: ' . $jmlIndoray_1 . '<br> Indoray 2: ' . $jmlIndoray_2 . '</th>
                <th></th>
                <th>AMBARSARI, Amd.Rad.: ' . $petugas_1 . '<br> NOFI INDRIYANI, Amd.Rad.: ' . $petugas_2 . '</th>
            </tr>
        </tbody>
        </table>';

        return $html;
    }

    public function cariTransaksiRo(Request $request)
    {
        $tgl  = $request->input('tgl', date('Y-m-d'));
        $norm = $request->input('norm');

        // Query untuk mendapatkan data transaksi berdasarkan tanggal dan norm
        $data = ROTransaksiModel::with('pasien')
            ->when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
                return $query->where('norm', $norm);
            })
            ->where('tglTrans', $tgl)
            ->first();

        if (! $data) {
            return response()->json([
                'metadata' => [
                    'message' => 'Data transaksi tidak ditemukan',
                    'status'  => 404,
                ],
            ], 404, [], JSON_PRETTY_PRINT);
        }

        $notrans = $data->notrans;

        // Query untuk mendapatkan data petugas berdasarkan nilai 'notrans'
        $data_petugas = TransPetugasModel::where('notrans', $notrans)->first();

        if (! $data_petugas) {
            $petugas = [
                'metadata' => [
                    'message' => 'Data petugas tidak ditemukan',
                    'status'  => 404,
                ],
            ];
        } else {
            $petugas = $data_petugas;
        }
        // dd($petugas);
        //Query untuk mendapatkan foto thorax
        try {
            $data_foto = RoHasilModel::on('rontgen')
                ->when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
                    return $query->where('norm', $norm);
                })
                ->whereDate('tanggal', $tgl)
                ->get();

            if (! $data_foto) {
                $foto = [
                    'metadata' => [
                        'message' => 'Data foto thorax tidak ditemukan',
                        'status'  => 404,
                    ],
                ];
            } else {
                $foto = $data_foto;
            }
        } catch (\Exception $e) {
            $foto = [
                'metadata' => [
                    'message' => 'Terjadi kesalahan pada koneksi database',
                    'status'  => 500,
                    'error'   => $e->getMessage(),
                ],
            ];
        }

        // dd($foto);

        $response = [
            'metadata' => [
                'message' => 'Data Transaksi Ditemukan',
                'status'  => 200,
            ],
            'data'     => [
                'transaksi_ro' => $data,
                'petugas'      => $petugas, // This will now be an object instead of an array
                'foto_thorax'  => $foto,    // This will now be an object instead of an array
            ],
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public function konsulRo(Request $request)
    {
        $notrans = $request->input('notrans');

        try {
            DB::beginTransaction();
            $data = KunjunganWaktuSelesai::where('notrans', $notrans)->first();
            if ($data) {
                $data->update([
                    'konsul_ro' => 1,
                ]);
                DB::commit();
                return response()->json([
                    'metadata' => [
                        'message' => 'Pasien a.n.' . $request->input('nama') . ' - ' . $request->input('norm') . ' berhasil di konsulkan ke dokter Sp. Rad.',
                        'status'  => 200,
                    ],
                ], 200, [], JSON_PRETTY_PRINT);
            }
            DB::rollBack();
            return response()->json([
                'metadata' => [
                    'message' => 'Data transaksi tidak ditemukan',
                    'status'  => 404,
                ],
            ], 404, [], JSON_PRETTY_PRINT);

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'metadata' => [
                    'message' => 'Terjadi kesalahan pada koneksi database',
                    'status'  => 500,
                    'error'   => $th->getMessage(),
                ],
            ], 500, [], JSON_PRETTY_PRINT);
        }
    }
}
