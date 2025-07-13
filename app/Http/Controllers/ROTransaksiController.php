<?php
namespace App\Http\Controllers;

use App\Models\KunjunganWaktuSelesai;
use App\Models\LaboratoriumHasilModel;
use App\Models\PegawaiModel;
use App\Models\ROBacaan;
use App\Models\RoHasilModel;
use App\Models\ROJenisFilm;
use App\Models\ROJenisFoto;
use App\Models\ROJenisKondisi;
use App\Models\ROJenisMesin;
use App\Models\RoProyeksiModel;
use App\Models\ROTransaksiHasilModel;
use App\Models\ROTransaksiModel;
use App\Models\SpirometriModel;
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

    public function ro()
    {
        $title       = 'Radiologi';
        $appUrlRo    = env('APP_URLRO');
        $proyeksi    = RoProyeksiModel::all();
        $kondisi     = ROJenisKondisi::all();
        $mesin       = ROJenisMesin::all();
        $foto        = ROJenisFoto::all();
        $film        = ROJenisFilm::all();
        $pModel      = new PegawaiModel();
        $dokter      = $pModel->olahPegawai([1, 7, 8]);
        $radiografer = $pModel->olahPegawai([12]);

        $kv = array_filter($kondisi->toArray(), function ($p) {
            return $p['grup'] === 'KV';
        });

        $ma = array_filter($kondisi->toArray(), function ($p) {
            return $p['grup'] === 'mA';
        });

        $s = array_filter($kondisi->toArray(), function ($p) {
            return $p['grup'] === 's';
        });

        $kv = array_map(function ($item) {
            return (object) $item;
        }, $kv);

        $ma = array_map(function ($item) {
            return (object) $item;
        }, $ma);

        $s = array_map(function ($item) {
            return (object) $item;
        }, $s);
        $dokter = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        $radiografer = array_map(function ($item) {
            return (object) $item;
        }, $radiografer);

        return view('RO.Trans.main', compact('appUrlRo', 'proyeksi', 'mesin', 'foto', 'film', 'kv', 'ma', 's', 'dokter', 'radiografer'))->with([
            'title' => $title,
        ]);
    }
    public function ro2()
    {
        $title       = 'Radiologi';
        $appUrlRo    = env('APP_URLRO');
        $proyeksi    = RoProyeksiModel::all();
        $kondisi     = ROJenisKondisi::all();
        $mesin       = ROJenisMesin::all();
        $foto        = ROJenisFoto::all();
        $film        = ROJenisFilm::all();
        $pModel      = new PegawaiModel();
        $dokter      = $pModel->olahPegawai([1, 7, 8]);
        $radiografer = $pModel->olahPegawai([12]);

        $kv = array_filter($kondisi->toArray(), function ($p) {
            return $p['grup'] === 'KV';
        });

        $ma = array_filter($kondisi->toArray(), function ($p) {
            return $p['grup'] === 'mA';
        });

        $s = array_filter($kondisi->toArray(), function ($p) {
            return $p['grup'] === 's';
        });

        $kv = array_map(function ($item) {
            return (object) $item;
        }, $kv);

        $ma = array_map(function ($item) {
            return (object) $item;
        }, $ma);

        $s = array_map(function ($item) {
            return (object) $item;
        }, $s);
        $dokter = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        $radiografer = array_map(function ($item) {
            return (object) $item;
        }, $radiografer);

        return view('RO.Trans.main2', compact('appUrlRo', 'proyeksi', 'mesin', 'foto', 'film', 'kv', 'ma', 's', 'dokter', 'radiografer'))->with([
            'title' => $title,
        ]);
    }

    public function masterRo()
    {
        $title           = 'Master Radiologi';
        $dataROJenisFoto = ROJenisFoto::all();
        return view('RO.Master.main', compact('title', 'dataROJenisFoto'));
    }
    public function laporanRo()
    {
        $title = 'Riwayat Rontgen';

        $pModel = new PegawaiModel();

        $radiografer = $pModel->olahPegawai([12]);

        $radiografer = array_map(function ($item) {
            return (object) $item;
        }, $radiografer);

        return view('RO.LogBook.main', compact('radiografer'))->with('title', $title);
    }
    public function rontgenHasil($id)
    {
        $title = 'Hasil Penunjang';

        $appUrlRo = env('APP_URLRO');
        $norm     = str_pad($id, 6, '0', STR_PAD_LEFT); // Normalize ID to 6 digits

        $hasilRo = "";
        try {
            $hasilRo = RoHasilModel::when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
                return $query->where('norm', $norm); // Filter by norm if valid
            })
                ->get();
            // $hasilRo = ROTransaksiHasilModel::where('norm', $norm)->get();
            if ($hasilRo->isEmpty()) {
                $hasilRo = "Data Foto Thorax pada Pasien dengan Norm: <u><b>" . $norm . "</b></u> tidak ditemukan,<br> Jika pasien melakukan Foto Thorax di KKPM, silahkan Menghubungi Bagian Radiologi. Terima Kasih...";
            } else {
                foreach ($hasilRo as $item) {
                    $norm = $item->norm;
                    $tgl  = $item->tanggal;
                    // dd($item->tanggal);
                    $item['hasilBacaan'] = 'RO Tidak Dibacakan';

                    $bacaan = ROBacaan::where('norm', $norm)
                        ->where('tanggal_ro', $tgl)
                        ->first();
                    // dd($bacaan);
                    if ($bacaan) {
                        $item['hasilBacaan'] = $bacaan->bacaan_radiolog;
                    }
                }
            }

        } catch (\Exception $e) {
            $hasilRo = "Terjadi kesalahan saat mengakses database. Silahkan hubungi radiologi untuk menghidupkan server.";
            // return response()->json([
            //     'message' => 'Terjadi kesalahan saat mengakses database. Silahkan hubungi radiologi untuk menghidupkan server.',
            //     'status' => 500,
            // ], 500, [], JSON_PRETTY_PRINT);
        }
        // $hasilRo = "Terjadi kesalahan saat mengakses database. Silahkan hubungi radiologi untuk menghidupkan server.";

        try {
            $hasilLab = LaboratoriumHasilModel::with('pasien', 'pemeriksaan', 'petugas.biodata', 'dokter.biodata')
                ->where('norm', $norm) // Filter by norm using a LIKE condition
                ->get();
            // return response()->json($hasilLab, 200, [], JSON_PRETTY_PRINT);
            if ($hasilLab->isEmpty() || $hasilLab == null || $hasilLab == []) {
                $hasilLab = "Data Hasil Laboratorium pada Pasien dengan Norm: <u><b>" . $norm . "</b></u> tidak ditemukan,
                    <br> Jika pasien melakukan Pemeriksaan Lab di KKPM, silahkan Menghubungi Bagian Laboratorium.
                    <br> Dengan catatan pemeriksaan dilakukan Setelah Tanggal : <u><b>18 Juli 2024</b></u>, sebelum tanggal tersebut data tidak ada di sistem. Terima Kasih...";
            } else {
                // dd($hasilLab); // Debug: Dump and Die
                foreach ($hasilLab as $item) {
                    if (in_array($item->idLayanan, [130, 131, 214])) {
                        // dd($item->hasil);
                        if ($item->no_iden_sediaan !== null) {
                            $item['hasil'] = $item->hasil . " <br> " .
                            substr($item->tgl_hasil, 2, 2) . "/K3302730/" .
                            $item->kode_tcm . "/" . $item->no_iden_sediaan;
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            $hasilLab = "Terjadi kesalahan saat mengakses database Lab. Silahkan hubungi TIM IT.";
            // return response()->json([
            //     'message' => 'Terjadi kesalahan saat mengakses database Lab. Silahkan hubungi TIM IT.',
            //     'error'   => $e->getMessage(),
            //     'status'  => 500,
            // ], 500, [], JSON_PRETTY_PRINT);
        }

        $dataSpirometri = SpirometriModel::with('biodataPetugas', 'biodataDokter')
            ->where('norm', $norm)
            ->get();
        $hasilSpiro = view('IGD.Trans.tabelHasilSpiro', compact('dataSpirometri'))->render();

        return view('RO.Hasil.main', compact('appUrlRo', 'hasilRo', 'hasilLab', 'hasilSpiro'))->with([
            'title' => $title,
        ]);
    }

    public function roHasil()
    {
        $title          = 'Hasil Penunjang';
        $appUrlRo       = env('APP_URLRO');
        $hasilRo        = "Silahkan Ketikan No RM dan tekan Enter/Klik Tombol Cari";
        $hasilLab       = "Silahkan Ketikan No RM dan tekan Enter/Klik Tombol Cari";
        $dataSpirometri = [];
        $hasilSpiro     = view('IGD.Trans.tabelHasilSpiro', compact('dataSpirometri'))->render();
        return view('RO.Hasil.main', compact('appUrlRo', 'hasilRo', 'hasilLab', 'hasilSpiro'))->with([
            'title' => $title,

        ]);
    }

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

            $petugas->p_dokter_poli       = $request->input('dokter');
            $petugas->p_rontgen           = $request->input('p_rontgen');
            $petugas->p_rontgen_evaluator = $request->input('p_rontgen_evaluator');

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
            // $data = ROTransaksiHasilModel::when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
            //     return $query->where('norm', $norm);
            // })
                ->where('tanggal', 'like', '%' . $tgl . '%')
                ->get();

            if ($data->isEmpty()) {
                $res = [
                    'message' => 'Data Foto Thorax pada Pasien dengan Norm: <u><b>' . $norm . '</b></u> tidak ditemukan,<br> Jika pasien melakukan Foto Thorax di KKPM, silahkan Menghubungi Bagian Radiologi. Terima Kasih..."',
                    'status'  => 404,
                ];
                return response()->json($res, 404, [], JSON_PRETTY_PRINT);
            } else {
                foreach ($data as $item) {
                    $norm = $item->norm;
                    $tgl  = $item->tanggal;
                    // dd($item->tanggal);
                    $item['hasilBacaan'] = 'RO Tidak Dibacakan';

                    $bacaan = ROBacaan::where('norm', $norm)
                        ->where('tanggal_ro', $tgl)
                        ->first();
                    // dd($bacaan);
                    if ($bacaan) {
                        $item['hasilBacaan'] = $bacaan->bacaan_radiolog;
                    }

                }
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

    public function logBook(Request $request)
    {
        // dd($request->all());
        $norm     = $request->input('norm');
        $tglAwal  = $request->input('tglAwal');
        $tglAkhir = $request->input('tglAkhir');
        $cetak    = $request->input('cetak');
        $petugas  = $request->input('petugas');

        if (Carbon::parse($tglAwal)->lessThanOrEqualTo(Carbon::parse('2024-06-01'))) {
            return response()->json(['message' => 'Data transaksi sebelum 2024-06-24 tidak ditemukan, cari di Aplikasi lama : RSPARU'], 404, [], JSON_PRETTY_PRINT);
        }

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
                "nama"             => $d['nama'],
                "alamatDbOld"      => $d['alamat'],
                "jkel"             => $d['jk'],
                // "nama"             => $namaPasien,
                // "alamatDbOld"      => $alamatFix,
                // "jkel"             => $jkPasien,
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

        if ($cetak === "cetak") {
            $title = 'Log Book';
            $data  = array_filter($res, function ($value) use ($petugas) {
                return $value['p_rontgen'] === $petugas;
            });
            $data = array_values($data);

            if (empty($data)) {
                $petugasNipMap = [
                    '197404231998032006' => 'AMBARSARI, Amd.Rad.',
                    '199009202011012001' => 'NOFI INDRIYANI, Amd.Rad.',
                ];
                // $namaPetugas = //ambil berdasarkan nip
                $petugas = $petugasNipMap[$petugas];
                $message = "Data Log Book a.n. $petugas pada tanggal $tglAwal sampai $tglAkhir tidak ditemukan";
                return view('Template.404', compact('title', 'message'));
            }
            $table = $this->table(collect($data), $petugas);
            return view('RO.LogBook.logBook', compact('table', 'title', 'tglAwal', 'tglAkhir'));
        }
        $table = $this->table(collect($res));

        return response()->json(["jumlah" => $jumlah, "data" => $table], 200, [], JSON_PRETTY_PRINT);
    }

    public function table($data, $rgrafer = null)
    {
        if ($data->isEmpty()) {
            return response()->make('<tr><td colspan="10" class="text-center">Data tidak ditemukan</td></tr>', 200);
        }

        $radiografer_nama = $data->first()['radiografer_nama'];

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
        if ($rgrafer != null) {
            $jumlah       = collect($data)->where('p_rontgen', $rgrafer)->count();
            $isianPetugas = "Jumlah Petugas Melakukan Rontgen <br> $radiografer_nama = $jumlah";
        } else {
            $isianPetugas = "Jumlah Petugas Melakukan Rontgen <br> AMBARSARI, Amd.Rad. = $petugas_1<br>NOFI INDRIYANI, Amd.Rad. = $petugas_2";
        }

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
                <th>' . $isianPetugas . '</th>
            </tr>
        </tbody>
        </table>';

        return $html;
    }

    public function cariTransaksiRo(Request $request)
    {
        $tgl  = $request->input('tgl', date('Y-m-d'));
        $norm = $request->input('norm');

        // Query untuk mendapatkan data transaksi berdasarkan tanggal dan norm, 2024-10-04 mulai notran baru
        $data = ROTransaksiModel::with('pasien', 'hasilBacaan')
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
            $data_foto = ROTransaksiHasilModel::when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
                return $query->where('norm', $norm);
            })
                ->whereDate('tanggal', $tgl)
                ->get();
            // $data_foto = RoHasilModel::on('rontgen')
            //     ->when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
            //         return $query->where('norm', $norm);
            //     })
            //     ->whereDate('tanggal', $tgl)
            //     ->get();

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

    public function rekapKegiatan($tglAwal, $tglAkhir)
    {
        $tglAwal  = $tglAwal ?? Carbon::now()->format('Y-m-d');
        $tglAkhir = $tglAkhir ?? Carbon::now()->format('Y-m-d');

        if (Carbon::parse($tglAwal)->lessThanOrEqualTo(Carbon::parse('2024-06-01'))) {
            return response()->json(['message' => 'Data transaksi sebelum 2024-06-24 tidak ditemukan, cari di Aplikasi lama : RSPARU'], 404, [], JSON_PRETTY_PRINT);
        }

        $data = ROTransaksiModel::with([
            'radiografer.radiografer', 'evaluator.evaluator',
        ])
            ->whereBetween('tgltrans', [$tglAwal, $tglAkhir])
            ->orderBy('tgltrans')->orderBy('notrans', 'desc')
            ->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Data transaksi tidak ditemukan'], 404, [], JSON_PRETTY_PRINT);
        }

        $dataRadiografers = $this->getRadiografer();
        $prosesJumlah     = $this->prosesJumlah($data);
        // return [$prosesJumlah, $dataRadiografers];

        $html = '<table class="min-w-full table-auto border-collapse border border-black mb-8">
            <thead class="bg-lime-400">
                <tr>
                    <th rowspan="2" class="px-2 py-2 text-center border border-black">Tanggal</th>';

        // Header nama radiografer dengan colspan 3
        foreach ($dataRadiografers as $radiografer) {
            $html .= '<th colspan="3" class="px-2 py-2 text-center border border-black">' . $radiografer['nama'] . '</th>';
        }

        $html .= '
                    <th rowspan="2" class="px-2 py-2 text-center border border-black">Jumlah Pasien</th>
                    <th rowspan="2" class="px-2 py-2 text-center border border-black">Catatan</th>
                </tr>
                <tr>';

        // Sub kolom tetap untuk setiap radiografer
        foreach ($dataRadiografers as $radiografer) {
            $html .= '<th class="px-2 py-2 text-center border border-black">Mutu CR</th>
                      <th class="px-2 py-2 text-center border border-black">Persiapan RO</th>
                      <th class="px-2 py-2 text-center border border-black">Pelaksanaan RO</th>';
        }

        $html .= '
                </tr>
            </thead>
            <tbody>';

        $totals = [];

        foreach ($dataRadiografers as $radiografer) {
            $nip          = $radiografer['nip'];
            $totals[$nip] = [
                'mutu_cr'        => 0,
                'persiapan_ro'   => 0,
                'pelaksanaan_ro' => 0,
            ];
        }

        $totalPasienSemua = 0;

        // Menambahkan baris per tanggal
        foreach ($prosesJumlah['persiapan_ro'] as $tanggal => $radiograferData) {

            $html .= '<tr>';
            $html .= '<td class="px-2 py-1 text-center border border-black">' . date('d', strtotime($tanggal)) . '</td>';

            // Menambahkan data radiografer per tanggal
            foreach ($dataRadiografers as $radiografer) {
                $radiograferNip = $radiografer['nip'];

                // Mencari jumlah persiapan
                $persiapan = '-';
                if (isset($prosesJumlah['persiapan_ro'][$tanggal])) {
                    foreach ($prosesJumlah['persiapan_ro'][$tanggal] as $item) {
                        if ($item['nip'] == $radiograferNip) {
                            $persiapan = $item['jumlah'];
                            break;
                        }
                    }
                }

                // Mencari jumlah pelaksanaan
                $pelaksanaan = '-';
                if (isset($prosesJumlah['pelaksanaan_ro'][$tanggal])) {
                    foreach ($prosesJumlah['pelaksanaan_ro'][$tanggal] as $item) {
                        if ($item['nip'] == $radiograferNip) {
                            $pelaksanaan = $item['jumlah'];
                            break;
                        }
                    }
                }

                               // Mencari mutu CR (saat ini kosong, jadi nilai tetap 0)
                $mutuCr = '-'; // Seperti yang terlihat, 'mutu_cr' tidak ada datanya pada contoh yang diberikan
                if (isset($prosesJumlah['mutu_cr'][$tanggal])) {
                    foreach ($prosesJumlah['mutu_cr'][$tanggal] as $item) {
                        if ($item['nip'] == $radiograferNip) {
                            $mutuCr = $item['jumlah'];
                            break;
                        }
                    }
                }

                // Menambahkan kolom jumlah untuk radiografer
                $html .= '<td class="px-2 py-1 text-center border border-black"><input class="text-center w-16" value="' . $mutuCr . '"></td>';
                $html .= '<td class="px-2 py-1 text-center border border-black"><input class="text-center w-16" value="' . $persiapan . '"></td>';
                $html .= '<td class="px-2 py-1 text-center border border-black"><input class="text-center w-16" value="' . $pelaksanaan . '"></td>';

                $totals[$radiograferNip]['mutu_cr'] += is_numeric($mutuCr) ? $mutuCr : 0;
                $totals[$radiograferNip]['persiapan_ro'] += is_numeric($persiapan) ? $persiapan : 0;
                $totals[$radiograferNip]['pelaksanaan_ro'] += is_numeric($pelaksanaan) ? $pelaksanaan : 0;
            }

                                                                                // Menambahkan kolom jumlah pasien (jumlah evaluator) dan catatan
            $totalPasien = array_sum(array_column($radiograferData, 'jumlah')); // Jumlah pasien untuk evaluator
            $html .= '<td class="px-2 py-1 text-center border border-black"><input class="text-center w-16" value="' . $totalPasien . '"></td>';
            $html .= '<td class="px-2 py-1 text-center border border-black"><input class="text-center w-32" value="-"></td>'; // Kolom catatan kosong

            $html .= '</tr>';

            $totalPasienSemua += $totalPasien;
        }

        // $html .= '
        //     </tbody>
        // </table>';

        $html .= '
            </tbody>';
        $html .= '<tfoot><tr>';
        $html .= '<th class="px-2 py-2 text-center border border-black">Jumlah</th>';

        foreach ($dataRadiografers as $radiografer) {
            $nip = $radiografer['nip'];
            $html .= '<th class="px-2 py-2 text-center border border-black">' . $totals[$nip]['mutu_cr'] . '</th>';
            $html .= '<th class="px-2 py-2 text-center border border-black">' . $totals[$nip]['persiapan_ro'] . '</th>';
            $html .= '<th class="px-2 py-2 text-center border border-black">' . $totals[$nip]['pelaksanaan_ro'] . '</th>';
        }

        $html .= '<th class="px-2 py-2 text-center border border-black">' . $totalPasienSemua . '</th>';
        $html .= '<th class="px-2 py-2 text-center border border-black">-</th>';
        $html .= '</tr></tfoot> ';
        $html .= '</table>';

        $blnTahun = Carbon::parse($tglAkhir)->locale('id')->isoFormat('MMMM YYYY');

        return view('RO.LogBook.laporanKegiatan', compact('html', 'blnTahun', 'tglAkhir'));

    }

    public function getRadiografer()
    {
        $pegawai         = PegawaiModel::with(['biodata', 'jabatan'])->where('kd_jab', 12)->get();
        $dataRadiografer = [];
        foreach ($pegawai as $index => $d) {
            $dataRadiografer[] = [
                'nip'     => $d->nip,
                'nama'    => $d->gelar_d
                ? $d->gelar_d . ' ' . $d->biodata->nama . ' ' . $d->gelar_b
                : $d->biodata->nama . ' ' . $d->gelar_b,
                'jabatan' => $d->jabatan->nm_jabatan,
            ];
        }
        return $dataRadiografer;
    }

    public function prosesJumlah($data)
    {
        // Inisialisasi array penampung per tanggal
        $radiograferCount = [];
        $evaluatorCount   = [];

        foreach ($data as $item) {
            // Ambil tanggal transaksi
            $tanggal = $item->tgltrans;

            // Hitung Radiografer per tanggal
            $nipRadiografer = $item->radiografer->radiografer->nip ?? null;
            if ($nipRadiografer) {
                if (! isset($radiograferCount[$tanggal])) {
                    $radiograferCount[$tanggal] = [];
                }
                if (! isset($radiograferCount[$tanggal][$nipRadiografer])) {
                    $radiograferCount[$tanggal][$nipRadiografer] = [
                        'nip'    => $nipRadiografer,
                        'nama'   => $item->radiografer->radiografer->nama ?? '-',
                        'jumlah' => 0,
                    ];
                }
                $radiograferCount[$tanggal][$nipRadiografer]['jumlah']++;
            }

            // Hitung Evaluator per tanggal
            $nipEvaluator = $item->evaluator->evaluator->nip ?? null;
            if ($nipEvaluator) {
                if (! isset($evaluatorCount[$tanggal])) {
                    $evaluatorCount[$tanggal] = [];
                }
                if (! isset($evaluatorCount[$tanggal][$nipEvaluator])) {
                    $evaluatorCount[$tanggal][$nipEvaluator] = [
                        'nip'    => $nipEvaluator,
                        'nama'   => $item->evaluator->evaluator->nama ?? '-',
                        'jumlah' => 0,
                    ];
                }
                $evaluatorCount[$tanggal][$nipEvaluator]['jumlah']++;
            }
        }

        // Convert array associative tanggal ke array biasa untuk output
        return [
            'persiapan_ro'   => $this->convertToDateWise($radiograferCount),
            'pelaksanaan_ro' => $this->convertToDateWise($radiograferCount),
            'mutu_cr'        => $this->convertToDateWise($evaluatorCount),
        ];
    }

    private function convertToDateWise($countArray)
    {
        $result = [];
        foreach ($countArray as $tanggal => $items) {
            $result[$tanggal] = array_values($items); // Convert to indexed array
        }
        return $result;
    }

    // public function rekapKunjunganRo(Request $request)
    // {
    //     $tglAwal  = $request->input('tglAwal') ?? Carbon::now()->startOfYear()->format('Y-m-d');
    //     $tglAkhir = $request->input('tglAkhir') ?? Carbon::now()->endOfYear()->format('Y-m-d');

    //     $tglAwal  = $tglAwal . ' 00:00:00';
    //     $tglAkhir = $tglAkhir . ' 23:59:59';

    //     $data = ROTransaksiModel::select(
    //         DB::raw("DATE_FORMAT(updated_at, '%Y-%m') as bulan"),
    //         DB::raw("SUM(CASE WHEN layanan = 'BPJS' THEN 1 ELSE 0 END) as jumlah_bpjs"),
    //         DB::raw("SUM(CASE WHEN layanan = 'UMUM' THEN 1 ELSE 0 END) as jumlah_umum"),
    //         DB::raw("COUNT(*) as total")
    //     )
    //         ->whereBetween('updated_at', [$tglAwal, $tglAkhir])
    //         ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y-%m')"))
    //         ->orderBy(DB::raw("DATE_FORMAT(updated_at, '%Y-%m')"))
    //         ->get();

    //     if ($data->isEmpty()) {
    //         return response()->json(['message' => 'Data transaksi tidak ditemukan'], 404, [], JSON_PRETTY_PRINT);
    //     }

    //     // Buat HTML tabel
    //     $html = '<table id="jumlahRoTable" class="table table-bordered table-striped dataTable no-footer dtr-inline"
    //                 aria-describedby="jumlahRoTable">';
    //     $html .= '
    //     <thead class="thead-dark">
    //         <tr>
    //             <th>#</th>
    //             <th>Bulan</th>
    //             <th>Jumlah BPJS</th>
    //             <th>Jumlah Umum</th>
    //             <th>Total</th>
    //         </tr>
    //     </thead>
    //     <tbody>';
    //     $i = 1;
    //     foreach ($data as $row) {
    //         $html .= '<tr>';
    //         $html .= '<td>' . $i++ . '</td>';
    //         $html .= '<td>' . date('F Y', strtotime($row->bulan . '-01')) . '</td>';
    //         $html .= '<td>' . $row->jumlah_bpjs . '</td>';
    //         $html .= '<td>' . $row->jumlah_umum . '</td>';
    //         $html .= '<td>' . $row->total . '</td>';
    //         $html .= '</tr>';
    //     }

    //     $html .= '</tbody></table>';

    //     return response()->json([
    //         'rekap_bulanan' => $data,
    //         'html'          => $html,
    //     ], 200, [], JSON_PRETTY_PRINT);
    // }

    public function rekapKunjunganRo(Request $request)
    {
        $tglAwal  = $request->input('tglAwal') ?? Carbon::now()->startOfYear()->format('Y-m-d');
        $tglAkhir = $request->input('tglAkhir') ?? Carbon::now()->endOfYear()->format('Y-m-d');

        $tglAwal  = $tglAwal . ' 00:00:00';
        $tglAkhir = $tglAkhir . ' 23:59:59';

        $data = ROTransaksiModel::select(
            DB::raw("DATE_FORMAT(updated_at, '%Y-%m') as bulan"),
            DB::raw("SUM(CASE WHEN layanan = 'BPJS' THEN 1 ELSE 0 END) as jumlah_bpjs"),
            DB::raw("SUM(CASE WHEN layanan = 'UMUM' THEN 1 ELSE 0 END) as jumlah_umum"),
            DB::raw("COUNT(*) as total")
        )
            ->whereBetween('updated_at', [$tglAwal, $tglAkhir])
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(updated_at, '%Y-%m')"))
            ->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Data transaksi tidak ditemukan'], 404, [], JSON_PRETTY_PRINT);
        }

        // === Buat Tabel HTML ===
        $html = '<table id="jumlahRoTable" class="table table-bordered table-striped dataTable no-footer dtr-inline"
                aria-describedby="jumlahRoTable">';
        $html .= '
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Bulan</th>
            <th>Jumlah BPJS</th>
            <th>Jumlah Umum</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>';
        $i = 1;
        foreach ($data as $row) {
            $html .= '<tr>';
            $html .= '<td>' . $i++ . '</td>';
            $html .= '<td>' . date('F Y', strtotime($row->bulan . '-01')) . '</td>';
            $html .= '<td>' . $row->jumlah_bpjs . '</td>';
            $html .= '<td>' . $row->jumlah_umum . '</td>';
            $html .= '<td>' . $row->total . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        // === Siapkan data untuk chart ===
        $labels   = [];
        $dataBpjs = [];
        $dataUmum = [];

        foreach ($data as $row) {
            $labels[]   = date('F Y', strtotime($row->bulan . '-01'));
            $dataBpjs[] = (int) $row->jumlah_bpjs;
            $dataUmum[] = (int) $row->jumlah_umum;
        }

        $chart = [
            'labels'   => $labels,
            'datasets' => [
                [
                    'label'           => 'BPJS',
                    'backgroundColor' => '#007bff',
                    'data'            => $dataBpjs,
                ],
                [
                    'label'           => 'Umum',
                    'backgroundColor' => '#28a745',
                    'data'            => $dataUmum,
                ],
            ],
        ];

        return response()->json([
            'rekap_bulanan' => $data,
            'html'          => $html,
            'chart'         => $chart,
        ], 200, [], JSON_PRETTY_PRINT);
    }

    // public function rekapKunjunganRoItem(Request $request)
    // {
    //     $mulaiTgl = $request->input('tglAwal', now()->startOfYear()->toDateString());
    //     $selesaiTgl = $request->input('tglAkhir', now()->endOfYear()->toDateString());

    //     $data = DB::table('t_rontgen')
    //         ->select(
    //             DB::raw("DATE_FORMAT(t_rontgen.created_at, '%Y-%m') as bulan"),
    //             'kasir_m_layanan.nmLayanan AS nama_layanan',
    //             'kasir_m_layanan.kdFoto AS kode_layanan',
    //             't_rontgen.layanan AS jaminan',
    //             DB::raw('COUNT(*) AS jumlah')
    //         )
    //         ->join('kasir_m_layanan', 't_rontgen.kdFoto', '=', 'kasir_m_layanan.kdFoto')
    //         ->whereBetween(DB::raw('DATE(t_rontgen.created_at)'), [$mulaiTgl, $selesaiTgl])
    //         ->groupBy('bulan', 'kode_layanan', 'nama_layanan', 'jaminan')
    //         ->orderBy('bulan')
    //         ->get();

    //     // Hapus return $data; dari sini

    //     // Kelompokkan data
    //     $grouped = [];
    //     foreach ($data as $item) {
    //         $key = $item->bulan . '|' . $item->kode_layanan;
    //         if (!isset($grouped[$key])) {
    //             $grouped[$key] = [
    //                 'bulan' => $item->bulan,
    //                 'nama_layanan' => $item->nama_layanan,
    //                 'kode_layanan' => $item->kode_layanan,
    //                 'BPJS' => 0,
    //                 'UMUM' => 0,
    //             ];
    //         }

    //         if ($item->jaminan === 'BPJS') {
    //             $grouped[$key]['BPJS'] += $item->jumlah;
    //         } elseif ($item->jaminan === 'UMUM') {
    //             $grouped[$key]['UMUM'] += $item->jumlah;
    //         }
    //     }

    //     // Susun HTML
    //     $html = '<div class="card">
    //             <div class="card-header">
    //                 <h3 class="card-title">Rekap Kunjungan Radiologi per Bulan dan Layanan</h3>
    //             </div>
    //             <div class="card-body">
    //                 <table id="jumlahRoItemTable" class="table table-bordered table-striped dataTable no-footer dtr-inline" aria-describedby="jumlahRoItemTable">
    //                     <thead>
    //                         <tr>
    //                             <th style="width: 10px;">#</th>
    //                             <th>Bulan</th>
    //                             <th>Nama Layanan</th>
    //                             <th>Kode Layanan</th>
    //                             <th>BPJS</th>
    //                             <th>UMUM</th>
    //                         </tr>
    //                     </thead>
    //                     <tbody>';

    //     if (empty($grouped)) {
    //         $html .= '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>';
    //     } else {
    //         $i = 1;
    //         foreach ($grouped as $data) {
    //             $html .= '<tr>
    //         <td>' . $i++ . '</td>
    //         <td>' . date('F Y', strtotime($data['bulan'] . '-01')) . '</td>
    //         <td>' . $data['nama_layanan'] . '</td>
    //         <td>' . $data['kode_layanan'] . '</td>
    //         <td>' . $data['BPJS'] . '</td>
    //         <td>' . $data['UMUM'] . '</td>
    //     </tr>';
    //         }
    //     }

    //     $html .= '</tbody>
    //             </table>
    //         </div>
    //         </div>';

    //     return response()->json([
    //         'data' => array_values($grouped), // <- gunakan grouped, bukan $data
    //         'html' => $html,
    //     ], 200, [], JSON_PRETTY_PRINT);

    // }

    public function rekapKunjunganRoItem(Request $request)
    {
        $mulaiTgl   = $request->input('tglAwal', now()->startOfYear()->toDateString());
        $selesaiTgl = $request->input('tglAkhir', now()->endOfYear()->toDateString());

        $roHasilPemeriksaan = DB::table('t_rontgen')
            ->select(
                DB::raw("DATE_FORMAT(t_rontgen.created_at, '%Y-%m') as bulan"),
                'kasir_m_layanan.nmLayanan AS nama_layanan',
                'kasir_m_layanan.kdFoto AS kode_layanan',
                't_rontgen.layanan AS jaminan',
                DB::raw('COUNT(t_rontgen.notrans) AS jumlah')
            )
            ->join('kasir_m_layanan', 't_rontgen.kdFoto', '=', 'kasir_m_layanan.kdFoto')
            ->whereBetween(DB::raw('DATE(t_rontgen.created_at)'), [$mulaiTgl, $selesaiTgl])
            ->groupBy('bulan', 'kode_layanan', 'nama_layanan', 'jaminan')
            ->orderBy('bulan')
            ->get();

        $bulanUnik = collect($roHasilPemeriksaan)->pluck('bulan')->unique()->sort()->values();
        $grouped   = [];
        $datasets  = [];

        foreach ($roHasilPemeriksaan as $item) {
            $key = $item->kode_layanan;
            if (! isset($grouped[$key])) {
                $grouped[$key] = [
                    'nama_layanan' => $item->nama_layanan,
                    'kode_layanan' => $item->kode_layanan,
                    'data'         => [],
                ];
            }
            $grouped[$key]['data'][$item->bulan][$item->jaminan] = $item->jumlah;

            $chartKey = $item->kode_layanan . ' - ' . $item->nama_layanan . ' (' . $item->jaminan . ')';
            if (! isset($datasets[$chartKey])) {
                $datasets[$chartKey] = [
                    'label' => $chartKey,
                    'data'  => array_fill_keys($bulanUnik->all(), 0),
                ];
            }
            $datasets[$chartKey]['data'][$item->bulan] = $item->jumlah;
        }

        $formattedDatasets = [];
        foreach ($datasets as $ds) {
            $formattedDatasets[] = [
                'label' => $ds['label'],
                'data'  => array_values($ds['data']),
            ];
        }

        // Build HTML
        $html = '<div class="card">
        <div class="card-header">
            <h3 class="card-title">Rekap Kunjungan RO per Bulan dan Layanan</h3>
        </div>
        <div class="card-body">
            <table id="jumlahRoItemTable" class="table table-bordered table-striped dataTable no-footer dtr-inline"
                aria-describedby="jumlahRoItemTable">
                <thead>
                    <tr>
                        <th style="width: 10px;">#</th>
                        <th>Nama Layanan</th>
                        <th>Kode Layanan</th>';

        foreach ($bulanUnik as $bulan) {
            $shortMonth = strtoupper(date('M', strtotime($bulan . '-01')));
            $html .= "<th>BPJS ($shortMonth)</th><th>UMUM ($shortMonth)</th>";
        }

        $html .= '</tr>
                </thead>
                <tbody>';

        if (empty($grouped)) {
            $html .= '<tr><td colspan="' . (3 + count($bulanUnik) * 2) . '" class="text-center">Tidak ada data</td></tr>';
        } else {
            $i = 1;
            foreach ($grouped as $data) {
                $html .= '<tr>';
                $html .= '<td>' . $i++ . '</td>';
                $html .= '<td>' . $data['nama_layanan'] . '</td>';
                $html .= '<td>' . $data['kode_layanan'] . '</td>';

                foreach ($bulanUnik as $bulan) {
                    $bpjs = $data['data'][$bulan]['BPJS'] ?? 0;
                    $umum = $data['data'][$bulan]['UMUM'] ?? 0;
                    $html .= "<td>$bpjs</td><td>$umum</td>";
                }

                $html .= '</tr>';
            }
        }

        $html .= '    </tbody>
            </table>
        </div>
    </div>';

        return response()->json([
            'html'  => $html,
            'chart' => [
                'labels'   => $bulanUnik,
                'datasets' => $formattedDatasets,
            ],
        ]);
    }

}
