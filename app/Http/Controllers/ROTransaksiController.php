<?php

namespace App\Http\Controllers;

use App\Models\RoHasilModel;
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
            $transaksi->layanan = $request->input('layanan');

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

            // Upload gambar
            $upload = ROTransaksiHasilModel::where('norm', $request->input('norm'))
                ->whereDate('tanggal', $request->input('tglRo'))
                ->first();

            if (!$upload) {
                // Jika tidak ada data, buat entitas baru
                $upload = new ROTransaksiHasilModel();
                $upload->norm = $request->input('norm');
                $upload->tanggal = $request->input('tglRo');

                // Upload gambar karena data belum ada
                if ($request->hasFile('gambar')) {
                    $file = $request->file('gambar');
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
        $data = RoHasilModel::on('rontgen')
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
