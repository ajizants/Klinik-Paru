<?php

namespace App\Http\Controllers;

use App\Models\RoHasilModel;
use App\Models\ROJenisKondisi;
use App\Models\ROTransaksiHasilModel;
use App\Models\ROTransaksiModel;
use App\Models\TransPetugasModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Sesuaikan dengan nama model Anda

class ROTransaksiController extends Controller
{

    public function dataTransaksiRo(Request $request)
    {
        $tglAwal = $request->input('tglAwal');
        $tglAkhir = $request->input('tglAkhir');
        $norm = $request->input('norm');
        $norm = str_pad($norm, 6, '0', STR_PAD_LEFT);
        $data = ROTransaksiModel::with('film', 'foto', 'proyeksi', 'mesin', 'kv', 'ma', 's')
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

    // public function addTransaksiRo(Request $request)
    // {
    //     try {
    //         // Buat instance baru dari model ROTransaksiModel
    //         $transaksi = new ROTransaksiModel();

    //         // Isi properti model dengan data dari permintaan
    //         $transaksi->notrans = $request->input('notrans');
    //         $transaksi->norm = $request->input('norm');
    //         $transaksi->tgltrans = $request->input('tglRo');
    //         $transaksi->noreg = $request->input('noreg');
    //         $transaksi->kdFoto = $request->input('kdFoto');
    //         $transaksi->ma = $request->input('ma');
    //         $transaksi->kv = $request->input('kv');
    //         $transaksi->s = $request->input('s');
    //         $transaksi->jmlExpose = $request->input('jmlExpose');
    //         $transaksi->jmlFilmDipakai = $request->input('jmlFilmDipakai');
    //         $transaksi->jmlFilmRusak = $request->input('jmlFilmRusak');
    //         $transaksi->kdMesin = $request->input('kdMesin');
    //         $transaksi->kdProyeksi = $request->input('kdProyeksi');
    //         $transaksi->layanan = $request->input('layanan');
    //         // $transaksi->p_rontgen = $request->input('p_rontgen');
    //         // $transaksi->dokter = $request->input('dokter');

    //         // Simpan data ke dalam database
    //         $transaksi->save();

    //         // Jika ingin memberikan respons JSON, bisa seperti ini:

    //         // Mengunggah file gambar
    //         if ($request->hasFile('gambar')) {
    //             // Mendapatkan file yang diunggah
    //             $gambar = $request->file('gambar');

    //             // Menyimpan file gambar ke dalam direktori yang ditentukan
    //             // $gambarPath = $gambar->store('172.16.10.88/ro/file', 'public');
    //             $gambarPath = $gambar->store('hasilRo', 'ro_storage');

    //             $roTransaksiHasiFoto = new ROTransaksiHasilModel();
    //             $roTransaksiHasiFoto->norm = $request->input('norm');
    //             $roTransaksiHasiFoto->tanggal = $request->input('tglRo');
    //             $roTransaksiHasiFoto->foto = $gambarPath; // Menyimpan path foto dalam database
    //             $roTransaksiHasiFoto->save();
    //         }

    //         return response()->json(['message' => 'Data berhasil disimpan'], 200);

    //     } catch (\Exception $e) {
    //         // Tangani kesalahan
    //         return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()], 500);
    //     }
    // }
    public function addTransaksiRo(Request $request)
    {
        DB::beginTransaction(); // Mulai transaksi

        try {
            // Cari data berdasarkan notrans
            $transaksi = ROTransaksiModel::where('notrans', $request->input('notrans'))->first();
            if (!$transaksi) {
                // Jika tidak ada, buat entitas baru
                $transaksi = new ROTransaksiModel();
            }

            // Isi properti model dengan data dari permintaan
            $transaksi->notrans = $request->input('notrans');
            $transaksi->norm = $request->input('norm');
            $transaksi->tgltrans = $request->input('tglRo');
            $transaksi->noreg = $request->input('noreg');
            $transaksi->pasienRawat = $request->input('pasienRawat');
            $transaksi->kdFoto = $request->input('kdFoto');
            $transaksi->kdFilm = $request->input('kdFilm');
            $transaksi->ma = $request->input('ma');
            $transaksi->kv = $request->input('kv');
            $transaksi->s = $request->input('s');
            $transaksi->jmlExpose = $request->input('jmlExpose');
            $transaksi->jmlFilmDipakai = $request->input('jmlFilmDipakai');
            $transaksi->jmlFilmRusak = $request->input('jmlFilmRusak');
            $transaksi->kdMesin = $request->input('kdMesin');
            $transaksi->kdProyeksi = $request->input('kdProyeksi');
            $transaksi->catatan = $request->input('catatan');
            $transaksi->layanan = $request->input('layanan');
            $transaksi->selesai = 1;
            $transaksi->kdKondisiRo = 55;

            // Tambahkan informasi debug
            Log::info('Data yang akan disimpan:', $transaksi->toArray());

            // Simpan data ke dalam database
            $transaksi->save();

            // Simpan transaksi petugas, cari data berdasarkan notrans, jika ada update, jika tidak ada create
            $petugas = TransPetugasModel::where('notrans', $request->input('notrans'))->first();
            if (!$petugas) {
                $petugas = new TransPetugasModel();
                $petugas->notrans = $request->input('notrans');
            }

            $petugas->p_dokter_poli = $request->input('dokter');
            $petugas->p_rontgen = $request->input('p_rontgen');
            $petugas->save();

            //if gambar not "" or null
            if ($request->input('gambar') != "" && $request->input('gambar') != null) {
                $upload = ROTransaksiHasilModel::where('norm', $request->input('norm'))
                    ->whereDate('tanggal', $request->input('tglRo'))
                    ->first();
                dd($upload);
                if (!$upload) {
                    // Jika tidak ada data, buat entitas baru
                    $upload = new ROTransaksiHasilModel();
                    $upload->norm = $request->input('norm');
                    $upload->tanggal = $request->input('tglRo');

                    // Upload gambar karena data belum ada
                    if ($request->hasFile('gambar')) {
                        $file = $request->file('gambar');
                        // dd($file);
                        $fileName = $file->getClientOriginalName();
                        $filePath = $file->getPathname();

                        $param = [
                            [
                                'name' => 'norm',
                                'contents' => $request->input('norm'),
                            ],
                            [
                                'name' => 'notrans',
                                'contents' => $request->input('notrans'),
                            ],
                            [
                                'name' => 'tanggal',
                                'contents' => $request->input('tglRo'),
                            ],
                            [
                                'name' => 'nama',
                                'contents' => $request->input('nama'),
                            ],
                            [
                                'name' => 'foto',
                                'contents' => fopen($filePath, 'r'),
                                'filename' => $fileName,
                            ],
                        ];

                        // Simpan foto dengan memanggil metode simpanFoto()
                        $upload->simpanFoto($param);
                    }
                    // Simpan entitas baru ke database
                    $upload->save();
                } else {
                    // Jika data sudah ada, tidak perlu melakukan apapun
                    // Anda bisa menambahkan pesan atau logika tambahan di sini jika diperlukan
                }
            } else {
                // dd("no gambar");
            }
            // Upload gambar

            DB::commit(); // Commit transaksi jika semua berhasil
            return response()->json(['message' => 'Data berhasil disimpan'], 200);

        } catch (\Exception $e) {
            DB::rollback(); // Rollback transaksi jika terjadi kesalahan
            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function hasilRo(Request $request)
    {
        $norm = $request->input('norm');
        $data = ROTransaksiHasilModel::on('mysql')
            ->when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
                return $query->where('norm', $norm);
            })
            ->get();
        if ($data->isEmpty()) {
            $res = [
                'metadata' => [
                    'message' => 'Data foto thorax tidak ditemukan, silahkan menghubungi radiologi',
                    'status' => 404,
                ],
                'data' => [],
            ];
        } else {
            $res = [
                'metadata' => [
                    'message' => 'Data foto thorax ditemukan',
                    'status' => 200,
                ],
                'data' => $data,
            ];
        }
        return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }

    public function logBook(Request $request)
    {
        $norm = $request->input('norm');
        $tglAwal = $request->input('tglAwal');
        $tglAkhir = $request->input('tglAkhir');
        $data = ROTransaksiModel::with('pasien', 'proyeksi', 'mesin', 'kv', 'ma', 's', 'kondisiOld', 'film', 'foto', 'radiografer.radiografer', 'kunjungan')
            ->when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
                return $query->where('norm', $norm);
            })
            ->whereBetween('tglTrans', [$tglAwal, $tglAkhir])
            ->get();

        $res = [];
        // $kominfoModel = new KominfoModel();
        foreach ($data as $d) {
            // $pasien = $kominfoModel->pasienRequest($d['norm']);
            if ($d['layanan'] === "" || $d['layanan'] === null) {
                if ($d['kunjungan']['kkelompok'] == "1") {
                    $d['layanan'] = "UMUM";
                } elseif ($d['kunjungan']['kkelompok'] == "2") {
                    $d['layanan'] = "BPJS";
                } else {
                    $d['layanan'] = "JAMKESDA";
                }
            }

            if ($d['ma'] === null && $d['s'] === null && $d['kv'] === null) {
                $d['kondisiRo'] = $d['kondisiOld']['kondisiRo'] ?? null;
            } else {
                $kv = ROJenisKondisi::where('kdKondisiRo', $d['kv'])->first();
                $ma = ROJenisKondisi::where('kdKondisiRo', $d['ma'])->first();
                $s = ROJenisKondisi::where('kdKondisiRo', $d['s'])->first();

                if ($kv && $ma && $s) {
                    $d['kondisiRo'] = $kv->nmKondisi . "  " . $ma->nmKondisi . "  " . $s->nmKondisi;
                } else {
                    // Handle jika ada yang tidak ditemukan atau null
                    $d['kondisiRo'] = "Tidak ditemukan kondisi yang sesuai";
                }
            }

            $kdProy = [];

            if ($d['kdProyeksi'] === null) {
                if ($d['pa'] === 1) {
                    $kdProy[] = 'pa';
                }
                if ($d['ap'] === 1) {
                    $kdProy[] = 'ap';
                }
                if ($d['lateral'] === 1) {
                    $kdProy[] = 'lateral';
                }
                if ($d['obliq'] === 1) {
                    $kdProy[] = 'obliq';
                }

                // Join the array elements into a string separated by commas
                $proy = !empty($kdProy) ? implode(', ', $kdProy) : null;
                // $d['proyeksi'] = $proy;
            } else {
                $proy = $d['proyeksi']['proyeksi'] ?? null;
                $kdProyeksi = $d['proyeksi']['kdProyeksi'] ?? null;
            }

            $res[] = [
                "notrans" => $d['notrans'],
                "norm" => $d['norm'],
                "tgltrans" => $d['tgltrans'],
                "ktujuan" => $d['ktujuan'],
                "pasienRawat" => $d['pasienRawat'],
                "noreg" => $d['noreg'],

                "jmlExpose" => $d['jmlExpose'],
                "jmlFilmDipakai" => $d['jmlFilmDipakai'],
                "jmlFilmRusak" => $d['jmlFilmRusak'],

                "catatan" => $d['catatan'],
                "selesai" => $d['selesai'],

                "layanan" => $d['layanan'],
                "kdLayanan" => $d['kunjungan']['kkelompok'] ?? null,
                "file" => $d['file'],

                // "pasien_nik" => $pasien['response']['data']['pasien_nik'],
                // "pasien_no_kk" => $pasien['response']['data']['pasien_no_kk'],
                // "pasien_nama" => $pasien['response']['data']['pasien_nama'],
                // "pasien_no_rm" => $pasien['response']['data']['pasien_no_rm'],
                // "jenis_kelamin_id" => $pasien['response']['data']['jenis_kelamin_id'],
                // "jenis_kelamin_nama" => $pasien['response']['data']['jenis_kelamin_nama'],
                // "pasien_tempat_lahir" => $pasien['response']['data']['pasien_tempat_lahir'],
                // "pasien_tgl_lahir" => $pasien['response']['data']['pasien_tgl_lahir'],
                // "pasien_no_hp" => $pasien['response']['data']['pasien_no_hp'],
                // "pasien_domisili" => $pasien['response']['data']['pasien_alamat'],
                // "pasien_alamat" => $pasien['response']['data']['kelurahan_nama'] . ", " . $pasien['response']['data']['pasien_rt'] . "/" . $pasien['response']['data']['pasien_rw'] . ", " . $pasien['response']['data']['kecamatan_nama'] . ", " . $pasien['response']['data']['kabupaten_nama'] . ", " . $pasien['response']['data']['provinsi_nama'],
                // "provinsi_nama" => $pasien['response']['data']['provinsi_nama'],
                // "kabupaten_nama" => $pasien['response']['data']['kabupaten_nama'],
                // "kecamatan_nama" => $pasien['response']['data']['kecamatan_nama'],
                // "kelurahan_nama" => $pasien['response']['data']['kelurahan_nama'],
                // "pasien_rt" => $pasien['response']['data']['pasien_rt'],
                // "pasien_rw" => $pasien['response']['data']['pasien_rw'],
                // "penjamin_nama" => $pasien['response']['data']['penjamin_nama'],

                "norm" => $d['pasien']['norm'] ?? null,
                "noktp" => $d['pasien']['noktp'] ?? null,
                "nama" => $d['pasien']['nama'] ?? null,
                "domisili" => $d['pasien']['alamat'] ?? null,
                "rtrw" => $d['pasien']['rtrw'] ?? null,
                "jeniskel" => $d['pasien']['jeniskel'] ?? null,
                "jkel" => $d['pasien']['jkel'] ?? null,
                "tmptlahir" => $d['pasien']['tmptlahir'] ?? null,
                "tgllahir" => $d['pasien']['tgllahir'] ?? null,
                "umur" => $d['pasien']['umur'] ?? null,
                "nohp" => $d['pasien']['nohp'] ?? null,
                "provinsi" => $d['pasien']['provinsi'] ?? null,
                "kabupaten" => $d['pasien']['kabupaten'] ?? null,
                "kecamatan" => $d['pasien']['kecamatan'] ?? null,
                "kelurahan" => $d['pasien']['kelurahan'] ?? null,

                "kdFoto" => $d['kdFoto'],
                "kdFoto" => $d['foto']['kdFoto'] ?? null,
                "nmFoto" => $d['foto']['nmFoto'] ?? null,

                "kdKv" => $d['kv'],
                "kdMa" => $d['ma'],
                "KdS" => $d['s'],
                "kdKondisiRo" => $d['kdKondisiRo'],
                "kondisiRo" => $d['kondisiRo'],
                // "ma" => [
                //     "kdKondisiRo" => $ma['kdKondisiRo'] ?? null,
                //     "nmKondisiMa" => $ma['nmKondisi'] ?? null,
                //     "grupMa" => $ma['grup'] ?? null,
                //     "statusMa" => $ma['status'] ?? null,
                // ],
                // "kv" => [
                //     "kdKondisiRo" => $kv['kdKondisiRo'] ?? null,
                //     "nmKondisiKv" => $kv['nmKondisi'] ?? null,
                //     "grupKv" => $kv['grup'] ?? null,
                //     "statusKv" => $kv['status'] ?? null,
                // ],
                // "s" => [
                //     "kdKondisiRo" => $s['kdKondisiRo'] ?? null,
                //     "nmKondisiS" => $s['nmKondisi'] ?? null,
                //     "grupS" => $s['grup'] ?? null,
                //     "statusS" => $s['status'] ?? null,
                // ],

                "kdFilm" => $d['kdFilm'],
                "kdFilm" => $d['film']['kdFilm'] ?? null,
                "ukuranFilm" => $d['film']['ukuranFilm'] ?? null,

                "kdProyeksi" => $kdProyeksi,
                "proyeksi" => $proy,
                "pa" => $d['pa'],
                "ap" => $d['ap'],
                "lateral" => $d['lateral'],
                "obliq" => $d['obliq'],

                "kdMesin" => $d['kdMesin'],
                "kdMesin" => $d['mesin']['kdMesin'] ?? null,
                "nmMesin" => $d['mesin']['nmMesin'] ?? null,

                "p_rontgen" => $d['radiografer']['p_rontgen'] ?? null,
                "radiografer_nama" => ($d['radiografer']['radiografer']['nama'] ?? null) . ", Amd.Rad.",
            ];

        }

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Data transaksi tidak ditemukan'], 404, [], JSON_PRETTY_PRINT);
        } else {
            return response()->json(['data' => $res], 200, [], JSON_PRETTY_PRINT);
            // return response()->json(['data' => $data], 200, [], JSON_PRETTY_PRINT);
        }
    }

    public function cariTransaksiRo(Request $request)
    {
        $tgl = $request->input('tgl', date('Y-m-d'));
        $norm = $request->input('norm');

        // Query untuk mendapatkan data transaksi berdasarkan tanggal dan norm
        $data = ROTransaksiModel::with('pasien')
            ->when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
                return $query->where('norm', $norm);
            })
            ->where('tglTrans', $tgl)
            ->first();

        if (!$data) {
            return response()->json([
                'metadata' => [
                    'message' => 'Data transaksi tidak ditemukan',
                    'status' => 404,
                ],
            ], 404, [], JSON_PRETTY_PRINT);
        }

        $notrans = $data->notrans;

        // Query untuk mendapatkan data petugas berdasarkan nilai 'notrans'
        $data_petugas = TransPetugasModel::where('notrans', $notrans)->first();

        if (!$data_petugas) {
            $petugas = [
                'metadata' => [
                    'message' => 'Data petugas tidak ditemukan',
                    'status' => 404,
                ],
            ];
        } else {
            $petugas = $data_petugas;
        }
        // dd($petugas);
        //Query untuk mendapatkan foto thorax
        $data_foto = RoHasilModel::on('rontgen')
            ->when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
                return $query->where('norm', $norm);
            })
            ->whereDate('tanggal', $tgl)
            ->first();
        if (!$data_foto) {
            $foto = [
                'metadata' => [
                    'message' => 'Data foto thorax tidak ditemukan',
                    'status' => 404,
                ]];
        } else {
            $foto = $data_foto;
        }
        // dd($foto);

        $response = [
            'metadata' => [
                'message' => 'Data Transaksi Ditemukan',
                'status' => 200,
            ],
            'data' => [
                'transaksi_ro' => $data,
                'petugas' => $petugas, // This will now be an object instead of an array
                'foto_thorax' => $foto, // This will now be an object instead of an array
            ],
        ];

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    // public function addHasilRo(Request $request){
    //     $fotoPath = $request->file('foto')->store('public/foto');

    //     // Buat entri baru dalam tabel ROTransaksiHasiFoto
    //     $roTransaksiHasiFoto = new ROTransaksiHasiFoto();
    //     $roTransaksiHasiFoto->norm = $validatedData['norm'];
    //     $roTransaksiHasiFoto->tanggal = $validatedData['tanggal'];
    //     $roTransaksiHasiFoto->foto = $fotoPath; // Menyimpan path foto dalam database
    //     $roTransaksiHasiFoto->save();

    //     return response()->json(['message' => 'Data berhasil disimpan'], 201);
    // }
}
