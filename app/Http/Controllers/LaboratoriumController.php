<?php
namespace App\Http\Controllers;

use App\Models\KominfoModel;
use App\Models\LaboratoriumHasilModel;
use App\Models\LaboratoriumKunjunganModel;
use App\Models\LayananModel;
use App\Models\PegawaiModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LaboratoriumController extends Controller
{
    public function lab()
    {
        $title = 'Pendaftaran Laboratorium';
        $lModel = new LayananModel();
        $layananLab = $lModel->layanans([9]);
        // dd($layananLab);
        $pModel = new PegawaiModel();
        $dokter = $pModel->olahPegawai([1, 7, 8]);
        $analis = $pModel->olahPegawai([11]);

        $dokter = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        $analis = array_map(function ($item) {
            return (object) $item;
        }, $analis);
        $layananLab = array_map(function ($item) {
            return (object) $item;
        }, $layananLab);
        return view('Laboratorium.Pendaftaran.main', compact('layananLab', 'dokter', 'analis'))->with('title', $title);
    }
    public function hasilLab()
    {
        $title = 'Input Hasil Laboratorium';
        return view('Laboratorium.Hasil.main')->with('title', $title);
    }
    public function laporan()
    {
        $title = 'Laporan Laboratorium';

        $query = LayananModel::on('mysql')
            ->where('status', '1')
            ->where('kelas', 'like', '%9%')
            ->get();
        $col = [];
        foreach ($query as $d) {
            $col[] = [
                "idLayanan" => $d["idLayanan"] ?? null,
                "nmLayanan" => $d["nmLayanan"] ?? null,
            ];
        }

        return view('Laboratorium.Laporan.main', ['col' => $col])->with('title', $title);
    }
    public function masterlab()
    {
        $title = 'Master Laboratorium';

        $query = LayananModel::on('mysql')
            ->where('status', '1')
            ->where('kelas', 'like', '%9%')
            ->get();
        $col = [];
        foreach ($query as $d) {
            $col[] = [
                "idLayanan" => $d["idLayanan"] ?? null,
                "nmLayanan" => $d["nmLayanan"] ?? null,
            ];
        }

        return view('Laboratorium.MasterLab.main', ['col' => $col])->with('title', $title);
    }

    public function antrianHasil(Request $request)
    {
        $tgl = $request->input('tgl', now()->toDateString());
        try {
            $data = LaboratoriumKunjunganModel::with('pemeriksaan.pemeriksaan')
                ->whereDate('created_at', 'like', '%' . $tgl . '%')->get();

            foreach ($data as $item) {
                $pemeriksaan = $item->pemeriksaan;
                $nonNullHasilCount = 0;

                foreach ($pemeriksaan as $periksa) {
                    if (!is_null($periksa->hasil)) {
                        $nonNullHasilCount++;
                    }
                }

                $item->jmlh = $pemeriksaan->count();

                if ($nonNullHasilCount == 0) {
                    $item->status = 'Belum';
                } else if ($nonNullHasilCount < $item->jmlh) {
                    $item->status = 'Belum Lengkap';
                } else {
                    $item->status = 'Lengkap';
                }

                $doctorNipMap = [
                    '198311142011012002' => 'dr. Cempaka Nova Intani, Sp.P, FISR., MM.',
                    '9' => 'dr. AGIL DANANJAYA, Sp.P',
                    '198907252019022004' => 'dr. FILLY ULFA KUSUMAWARDANI',
                    '198903142022031005' => 'dr. SIGIT DWIYANTO',
                ];
                $item->nama_dokter = $doctorNipMap[$item['dokter']] ?? 'Unknown';
            }

            $lab = $data->toArray(); // Convert the collection to an array
            return response()->json(array_values($lab), 200, [], JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            $res = [
                'message' => $e->getMessage(),
                'code' => 400,
            ];
            return response()->json($res, 400, [], JSON_PRETTY_PRINT);
        }
    }
    public function noSampel()
    {
        //cari kunjunganLab hari ini
        $data = LaboratoriumKunjunganModel::where('created_at', 'like', '%' . now()->toDateString() . '%')->get();
        $jumlah = $data->count();
        // buatkan $noSample format 001
        $noSample = str_pad($jumlah + 1, 3, '0', STR_PAD_LEFT);
        $res = [
            'noSample' => $noSample,
        ];
        return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }

    public function cariTsLab(Request $request)
    {
        try {
            if ($request->input('notrans') == null) {
                $norm = $request->input('norm');
                $tgl = $request->input('tgl');
                // dd($tgl);
                // $data = LaboratoriumKunjunganModel::with('pemeriksaan.pemeriksaan')
                // ->where('created_at', 'like', '%' . $tgl . '%')
                // ->where('norm', 'like', '%' . $norm . '%')
                // ->first();
                $data = LaboratoriumKunjunganModel::where('created_at', 'like', '%' . $tgl . '%')
                    ->where('norm', 'like', '%' . $norm . '%')
                    ->first();

                $pemeriksaan = LaboratoriumHasilModel::with('pemeriksaan')
                    ->where('norm', 'like', '%' . $norm . '%')
                    ->whereDate('created_at', 'like', '%' . $tgl . '%')->get();
                // dd($pemeriksaan);
                if (!empty($pemeriksaan) && $pemeriksaan != "[]") {
                    $data->pemeriksaan = $pemeriksaan;
                }

                $lab = json_decode($data, true);
                if ($data == null) {
                    $res = [
                        'message' => 'Belum ada Transaksi Lab',
                        'code' => 404,
                    ];
                    return response()->json($res, 404, [], JSON_PRETTY_PRINT);
                }

                return response()->json($lab, 200, [], JSON_PRETTY_PRINT);
            } else {
                $notrans = $request->input('notrans');
                $tgl = $request->input('tgl');

                $data = LaboratoriumHasilModel::with('pemeriksaan')
                    ->where('notrans', 'like', '%' . $notrans . '%')
                    ->whereDate('created_at', 'like', '%' . $tgl . '%')->get();
                return response()->json($data, 200, [], JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mencari data: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat mencari data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for take resource.
     */
    public function layanan()
    {
        $data = LayananModel::where('kelas', 'like', '%9%')
            ->get();

        $layanan = [];

        foreach ($data as $d) {
            if ($d['status'] === "0") {
                $status = "Tidak Tersedia";
            } else {
                $status = "Tersedia";
            }
            $layanan[] = [
                'idLayanan' => $d->idLayanan,
                'kelas' => $d->kelas,
                'nmLayanan' => $d->nmLayanan,
                'tarif' => $d->tarif,
                'status' => $d->status,
                'statusTx' => $status,
                'satuan' => $d->satuan,
                'normal' => $d->normal,
                'estimasi' => $d->estimasi,
            ];
        }

        return response()->json($layanan, 200, [], JSON_PRETTY_PRINT);
    }
    public function layananlab(Request $request)
    {
        $kelas = $request->input('kelas');
        $data = LayananModel::where('kelas', 'like', '%' . $kelas . '%')
            ->where('status', 'like', '%1%')
            ->get();

        $layanan = [];

        foreach ($data as $d) {
            $layanan[] = [
                'idLayanan' => $d->idLayanan,
                'kelas' => $d->kelas,
                'nmLayanan' => $d->nmLayanan,
                'tarif' => $d->tarif,
            ];
        }

        return response()->json(['data' => $layanan], 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function addTransaksi(Request $request)
    {
        // dd($request->all());wis
        // Mendapatkan dataTerpilih dari permintaan
        $dataTerpilih = $request->input('dataTerpilih');

        // Validasi bahwa dataTerpilih harus array dan tidak boleh kosong
        if (!is_array($dataTerpilih) || empty($dataTerpilih)) {
            return response()->json([
                'message' => 'Data terpilih tidak valid atau kosong',
            ], 400);
        }
        $notrans = $request->input('notrans');
        $norm = $request->input('norm');
        $nama = $request->input('nama');
        $jk = $request->input('jk');
        $nik = $request->input('nik');
        $noSampel = $request->input('noSampel');
        $umur = $request->input('umur');
        $alamat = $request->input('alamat');
        $jaminan = $request->input('jaminan');
        $dokter = $request->input('dokter');
        $petugas = $request->input('petugas');
        $tglTrans = $request->input('tgltrans'); // Assuming tglTrans is in 'Y-m-d' format
        $currentDateTime = Carbon::now(); // Get current date and time
        $today = $currentDateTime->format('Y-m-d');

        // Check if today's date is not the same as tglTrans
        if ($today !== $tglTrans) {
            // Create a Carbon instance using tglTrans and the current time
            $tanggal = Carbon::createFromFormat('Y-m-d H:i:s', $tglTrans . ' ' . $currentDateTime->format('H:i:s'));
        } else {
            // Use the current date and time
            $tanggal = $currentDateTime;
        }

        try {
            // Memulai transaksi database
            DB::beginTransaction();

            // Membuat array untuk menyimpan data yang akan disimpan
            $dataToInsert = [];

            // Looping untuk mengolah dataTerpilih
            foreach ($dataTerpilih as $data) {
                // Validasi data yang diperlukan pada setiap elemen dataTerpilih
                if (isset($data['idLayanan']) && isset($data['notrans'])) {
                    $dataToInsert[] = [
                        'notrans' => $data['notrans'],
                        'norm' => $data['norm'],
                        'idLayanan' => $data['idLayanan'],
                        'petugas' => $petugas,
                        'dokter' => $dokter,
                        'created_at' => $tanggal,
                        'updated_at' => $tanggal,
                    ];
                } else {
                    return response()->json([
                        'message' => 'Data tidak lengkap',
                    ], 500);
                }
            }
            // Simpan data permintaan laborat ke database
            LaboratoriumHasilModel::insert($dataToInsert);
            //tambahkan log data yang di simpan ke db

            DB::commit();
            Log::info('Transaksi berhasil disimpan: ' . json_encode($dataToInsert));

            // Extract notrans and tujuan from the request

            if ($notrans !== null) {
                $dataKunjungan = LaboratoriumKunjunganModel::where('notrans', $notrans)->whereDate('created_at', $tanggal)->first();

                if ($dataKunjungan == null) {
                    $kunjunganLab = new LaboratoriumKunjunganModel();

                    $kunjunganLab->notrans = $notrans;
                    $kunjunganLab->norm = $norm;
                    $kunjunganLab->nama = $nama;
                    $kunjunganLab->jk = $jk;
                    $kunjunganLab->nik = $nik;
                    $kunjunganLab->umur = $umur;
                    $kunjunganLab->no_sampel = $noSampel;
                    $kunjunganLab->alamat = $alamat;
                    $kunjunganLab->layanan = $jaminan;
                    $kunjunganLab->petugas = $petugas;
                    $kunjunganLab->dokter = $dokter;
                    $kunjunganLab->ket = "Belum";
                    $kunjunganLab->created_at = $tanggal;
                    $kunjunganLab->updated_at = $tanggal;
                    $kunjunganLab->save();
                    return response()->json(['message' => 'Kunjungan berhasil ditambahkan...!!'], 200);
                }

                return response()->json(['message' => 'Kunjungan berhasil di update...!!'], 200);

            } else {
                return response()->json(['message' => 'No Transaksi tidak valid'], 400);
            }
        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi kesalahan
            DB::rollback(); // Rollback transaksi jika terjadi kesalahan
            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function deleteTs(Request $request)
    {
        $notrans = $request->input('notrans');
        if ($notrans !== null) {
            $dataKunjungan = LaboratoriumKunjunganModel::where('notrans', $notrans)->first();
            if ($dataKunjungan !== null) {
                $dataKunjungan->delete();

                LaboratoriumHasilModel::desroyAll($notrans);
                return response()->json(['message' => 'Data berhasil di hapus']);
            } else {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

        } else {
            return response()->json(['message' => 'notrans tidak valid'], 400);
        }
    }
    public function addHasil(Request $request)
    {
        // Mendapatkan dataTerpilih dari permintaan
        $dataTerpilih = $request->input('dataTerpilih');
        $ketStatus = $request->input('keterangan');
        $tglTrans = Carbon::createFromFormat('Y-m-d', $request->input('tglTrans')); // Memastikan format yang benar
        $tglNow = Carbon::now(); // Mengambil waktu sekarang sebagai objek Carbon

        if ($tglTrans < $tglNow) {
            // Jika tglTrans lebih kecil dari hari ini
            $waktuSelesai = $tglTrans->copy()->setTime(now()->hour, now()->minute, now()->second);
            $waktuSelesai = $waktuSelesai->format('Y-m-d H:i:s');
        } else {
            // Jika tglTrans sama atau lebih besar dari hari ini
            $waktuSelesai = now()->format('Y-m-d H:i:s');
        }

        // Validasi bahwa dataTerpilih harus array dan tidak boleh kosong
        if (!is_array($dataTerpilih) || empty($dataTerpilih)) {
            return response()->json([
                'message' => 'Data terpilih tidak valid atau kosong',
            ], 400);
        }

        try {
            // Memulai transaksi database
            DB::beginTransaction();

            // Looping untuk mengolah dataTerpilih
            foreach ($dataTerpilih as $data) {
                // Validasi data yang diperlukan pada setiap elemen dataTerpilih
                if (isset($data['idLab']) && isset($data['notrans'])) {
                    // Update data pada tabel LaboratoriumHasilModel berdasarkan notrans
                    LaboratoriumHasilModel::where('notrans', $data['notrans'])
                        ->where('idLab', $data['idLab'])
                        ->update([
                            'norm' => $data['norm'],
                            'idLayanan' => $data['idLayanan'],
                            'hasil' => $data['hasil'],
                            'petugas' => $data['petugas'],
                            'ket' => $data['ket'],
                            // 'updated_at' => now(), // Jika ada kolom updated_at dan ingin diperbarui
                        ]);
                } else {
                    return response()->json([
                        'message' => 'Data tidak lengkap',
                    ], 400);
                }
            }
            if ($ketStatus == "Selesai") {
                $kunjungan = LaboratoriumKunjunganModel::where('norm', $data['norm'])
                    ->where('created_at', 'like', '%' . $tglTrans->format('Y-m-d') . '%')
                    ->first();
                $kunjungan->update([
                    'waktu_selesai' => $waktuSelesai,
                    'ket' => $ketStatus,
                ]);
            }

            // Commit transaksi database
            DB::commit();

            return response()->json([
                'message' => 'Data Berhasil Diperbarui',
                'waktu_selesai' => $waktuSelesai,
            ], 200);

        } catch (\Exception $e) {
            DB::rollback(); // Rollback transaksi jika terjadi kesalahan
            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function deleteLab(Request $request)
    {
        $id = $request->input("idLab");
        // dd($id);
        // Memastikan $id tidak null sebelum memanggil fungsi destroyLab
        if ($id !== null) {
            // Memanggil metode destroyLab dari model
            LaboratoriumHasilModel::destroyLab($id);

            // ... melakukan tindakan lainnya setelah penghapusan ...

            return response()->json(['message' => 'Data laboratorium berhasil dihapus.']);
        } else {
            // Handle kasus nilai $id null
            return response()->json(['message' => 'ID tidak valid.'], 400);
        }
    }

    public function hasil(Request $request)
    {
        $norm = $request->input('norm');
        // dd($norm);
        try {
            $hasilLab = LaboratoriumHasilModel::with('pasien', 'pemeriksaan', 'petugas.biodata', 'dokter.biodata')
                ->where('norm', $norm) // Filter by norm using a LIKE condition
                ->get();
            if ($hasilLab->isEmpty()) {
                $hasilLab = "Data Hasil Laboratorium pada Pasien dengan Norm: <u><b>" . $norm . "</b></u> tidak ditemukan,
                <br> Jika pasien melakukan Pemeriksaan Lab di KKPM, silahkan Menghubungi Bagian Laboratorium.
                <br> Dengan catatan pemeriksaan dilakukan Setelah Tanggal : <u><b>18 Juli 2024</b></u>, sebelum tanggal tersebut data tidak ada di sistem. Terima Kasih...";
                return response()->json($hasilLab, 404, [], JSON_PRETTY_PRINT);
            }

            // dd($hasilLab); // Debug: Dump and Die
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengakses database Lab. Silahkan hubungi TIM IT.',
                'error' => $e->getMessage(),
                'status' => 500,
            ], 500, [], JSON_PRETTY_PRINT);
        }
        return response()->json($hasilLab, 200, [], JSON_PRETTY_PRINT);
    }

    public function cetak($notrans, $tgl)
    {
        try {

            // $notrans = $request->input('notrans');
            // $tgl = $request->input('tgl');
            // $notrans = "027783";
            // $tgl = "2024-10-01";

            $data = LaboratoriumKunjunganModel::with('pemeriksaan.pemeriksaan')
                ->where('notrans', 'like', '%' . $notrans . '%')
                ->whereDate('created_at', 'like', '%' . $tgl . '%')->get();
            $lab = $data[0];
            $dataAnalis = [
                'SUHARTANTI Amd.AK.',
                'JUNI SUPRAPTI A.Md.AK',
                'TANTI LISTIYOWATI S.Tr.Kes',
            ];
            $nipDokter = $lab->dokter;
            switch ($nipDokter) {
                case "198311142011012002":
                    $dokter = "dr. CEMPAKA NOVA INTANI Sp.P, MM, FISR.";
                    break;
                case "9":
                    $dokter = "dr. AGIL DANARJAYA Sp.P.";
                    break;
                case "198907252019022004":
                    $dokter = "dr. FILLY ULFA KUSUMAWARDANI";
                    break;
                case "198903142022031005":
                    $dokter = "dr. SIGIT DWIYANTO";
                    break;
            }

            $analis = $dataAnalis[rand(0, 2)];
            // return $lab;
            // return $analis;
            // return $dokter;
            return view('Laboratorium.Hasil.cetak', compact('lab', 'analis', 'dokter'));

        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mencari data: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat mencari data: ' . $e->getMessage()], 500);
        }
    }
    public function cetakPermintaan($notrans, $norm, $tgl)
    {
        try {
            $model = new KominfoModel();
            $params = [
                'no_rm' => $norm,
                'tanggal_awal' => $tgl,
                'tanggal_akhir' => $tgl,
            ];
            $cppt = $model->cpptRequest($params);
            $permintaan = $cppt['response']['data'][0]['laboratorium'];
            $tglLahir = $cppt['response']['data'][0]['pasien_tgl_lahir'];
            $dataCppt = $cppt['response']['data'][0];
            // return $dataCppt;
            $lab = LaboratoriumKunjunganModel::with('pemeriksaan.pemeriksaan')
                ->where('notrans', 'like', '%' . $notrans . '%')
            // ->whereDate('created_at', 'like', '%' . $tgl . '%')
                ->first();
            $dataAnalis = [
                'SUHARTANTI Amd.AK.',
                'JUNI SUPRAPTI A.Md.AK',
                'TANTI LISTIYOWATI S.Tr.Kes',
            ];
            $dokter = $cppt['response']['data'][0]['dokter_nama'];

            $analis = $dataAnalis[rand(0, 2)];

            $dataNoSampel = $this->noSampel();
            $noSampel = $dataNoSampel->getData()->noSample; // atau getData(true) untuk array

            return view('Laboratorium.Pendaftaran.order', compact('noSampel', 'dataCppt', 'lab', 'tglLahir', 'permintaan', 'analis', 'dokter'));

        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mencari data: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat mencari data: ' . $e->getMessage()], 500);
        }
    }
    public function rekapKunjungan(Request $request)
    {
        $norm = $request->input('norm');
        $tglAwal = $request->input('tglAwal', now()->toDateString());
        $tglAkhir = $request->input('tglAkhir', now()->toDateString());

        $data = LaboratoriumKunjunganModel::with('pemeriksaan', 'pemeriksaan.petugas.biodata', 'pemeriksaan.pemeriksaan', 'petugas.biodata', 'dokter.biodata')
            ->where('norm', 'like', '%' . $norm . '%')
            ->whereBetween('created_at', [
                \Carbon\Carbon::parse($tglAwal)->startOfDay(), // Menambahkan waktu mulai hari
                \Carbon\Carbon::parse($tglAkhir)->endOfDay(), // Menambahkan waktu akhir hari
            ])
            ->get();
        $lab = json_decode($data, true);
        $pasien = [];

        foreach ($lab as $d) {
            $tanggal = Carbon::parse($d['updated_at'])->format('d-m-Y');
            $dokter = ($d['dokter']['gelar_d'] ?? null) . " " . ($d['dokter']['biodata']['nama'] ?? null) . " " . ($d['dokter']['gelar_b'] ?? null);
            $admin = ($d['petugas']['gelar_d'] ?? null) . " " . ($d['petugas']['biodata']['nama'] ?? null) . " " . ($d['petugas']['gelar_b'] ?? null);

            $pemeriksaanDetails = [];
            foreach ($d['pemeriksaan'] as $pemeriksaan) {
                $petugas = ($pemeriksaan['petugas']['gelar_d'] ?? null) . " " . ($pemeriksaan['petugas']['biodata']['nama'] ?? null) . " " . ($pemeriksaan['petugas']['gelar_b'] ?? null);
                $hasilLab = ($pemeriksaan['hasil'] ?? null) . " " . ($pemeriksaan['ket'] ?? null);
                $pemeriksaanDetails[] = [
                    'idLab' => $pemeriksaan['idLab'] ?? null,
                    'idLayanan' => $pemeriksaan['idLayanan'] ?? null,
                    'nmLayanan' => $pemeriksaan['pemeriksaan']['nmLayanan'] ?? null,
                    'tarif' => $pemeriksaan['pemeriksaan']['tarif'] ?? null,
                    'hasil' => $hasilLab ?? null,
                    'hasil_murni' => $pemeriksaan['hasil'] ?? null,
                    'petugas' => $petugas ?? null,
                ];
            }

            $pasien[] = [
                'id' => $d['id'] ?? null,
                'notrans' => $d['notrans'] ?? null,
                'tgl' => $tanggal ?? null,
                'norm' => $d['norm'] ?? null,
                'jaminan' => $d['layanan'] ?? null,
                'nama' => $d['nama'] ?? null,
                'alamat' => $d['alamat'] ?? null,
                'dokter_nip' => $d['dokter']['nip'] ?? null,
                'dokter_nama' => $dokter ?? null,
                'admin_nip' => $d['petugas']['nip'] ?? null,
                'admin_nama' => $admin ?? null,
                'pemeriksaan' => $pemeriksaanDetails ?? null,
            ];
        }

        return response()->json($pasien, 200, [], JSON_PRETTY_PRINT);
    }

    public function rekapKunjungan1(Request $request)
    {
        $norm = $request->input('norm');
        $tglAwal = $request->input('tglAwal', now()->toDateString());
        $tglAkhir = $request->input('tglAkhir', now()->toDateString());
        // dd($tglAkhir);
        $data = LaboratoriumHasilModel::with('pasien', 'pemeriksaan', 'petugas.biodata', 'dokter.biodata')
            ->where('norm', 'like', '%' . $norm . '%')
            ->whereBetween('created_at', [
                \Carbon\Carbon::parse($tglAwal)->startOfDay(), // Menambahkan waktu mulai hari
                \Carbon\Carbon::parse($tglAkhir)->endOfDay(), // Menambahkan waktu akhir hari
            ])
            ->get();

        $lab = json_decode($data, true);

        $res = [];

        foreach ($lab as $d) {
            $tanggal = Carbon::parse($d['updated_at'])->format('d-m-Y');
            $dokter = ($d['dokter']['gelar_d'] ?? null) . " " . ($d['dokter']['biodata']['nama'] ?? null) . " " . ($d['dokter']['gelar_b'] ?? null);
            $petugas = ($d['petugas']['gelar_d'] ?? null) . " " . ($d['petugas']['biodata']['nama'] ?? null) . " " . ($d['petugas']['gelar_b'] ?? null);
            $res[] = [
                'id' => $d['idLab'],
                'notrans' => $d['notrans'],
                'tgl' => $tanggal,
                'norm' => $d['norm'],
                'jaminan' => $d['pasien']['layanan'],
                'nama' => $d['pasien']['nama'],
                'alamat' => $d['pasien']['alamat'],
                'pemeriksaan' => $d['pemeriksaan']['nmLayanan'],
                'tarif' => $d['pemeriksaan']['tarif'],
                'hasil' => $d['hasil'],
                'petugas' => $d['petugas']['biodata']['nama'],
                'dokter_nip' => $d['dokter']['nip'],
                'dokter_nama' => $dokter,
                'petugas_nip' => $d['petugas']['nip'],
                'petugas_nama' => $petugas,
            ];
        }

        // return response()->json($lab, 200, [], JSON_PRETTY_PRINT);
        return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }

    public function poinPetugas(Request $request)
    {
        $mulaiTgl = $request->input('tglAwal', now()->toDateString());
        $selesaiTgl = $request->input('tglAkhir', now()->toDateString());

        $labHasilPemeriksaan = DB::table('t_kunjungan_lab_hasil')
            ->select(
                DB::raw('COUNT(t_kunjungan_lab_hasil.idLab) AS jml'),
                'peg_m_biodata.nip',
                'peg_m_biodata.nama',
                'kasir_m_layanan.nmLayanan AS tindakan'
            )
            ->join('peg_m_biodata', 't_kunjungan_lab_hasil.petugas', '=', 'peg_m_biodata.nip')
            ->join('kasir_m_layanan', 't_kunjungan_lab_hasil.idLayanan', '=', 'kasir_m_layanan.idLayanan')
            ->whereBetween(DB::raw('DATE_FORMAT(t_kunjungan_lab_hasil.created_at, "%Y-%m-%d")'), [$mulaiTgl, $selesaiTgl])
            ->groupBy('peg_m_biodata.nip', 'peg_m_biodata.nama', 'kasir_m_layanan.idLayanan', 'kasir_m_layanan.nmLayanan');

        $tKunjunganLab = DB::table('t_kunjungan_lab')
            ->select(
                DB::raw('COUNT(t_kunjungan_lab.id) AS jml'),
                'peg_m_biodata.nip',
                'peg_m_biodata.nama',
                DB::raw('"Sampling/Admin Loket" AS tindakan')
            )
            ->join('peg_m_biodata', 't_kunjungan_lab.petugas', '=', 'peg_m_biodata.nip')
            ->whereBetween(DB::raw('DATE_FORMAT(t_kunjungan_lab.created_at, "%Y-%m-%d")'), [$mulaiTgl, $selesaiTgl])
            ->groupBy('peg_m_biodata.nip', 'peg_m_biodata.nama');

        $query = $labHasilPemeriksaan->union($tKunjunganLab)->get();

        return response()->json($query, 200, [], JSON_PRETTY_PRINT);
    }

    public function jumlah_pemeriksaan(Request $request)
    {
        $mulaiTgl = $request->input('tglAwal', now()->toDateString());
        $selesaiTgl = $request->input('tglAkhir', now()->toDateString());

        $labHasilPemeriksaan = DB::table('t_kunjungan_lab_hasil')
            ->select(
                DB::raw('COUNT(t_kunjungan_lab_hasil.idLab) AS jumlah'),
                'kasir_m_layanan.nmLayanan AS nama_layanan',
                'kasir_m_layanan.idLayanan AS kode_layanan',
                't_kunjungan_lab.layanan AS jaminan',
                DB::raw('DATE(t_kunjungan_lab_hasil.created_at) AS tanggal')
            )
            ->join('kasir_m_layanan', 't_kunjungan_lab_hasil.idLayanan', '=', 'kasir_m_layanan.idLayanan')
            ->join('t_kunjungan_lab', 't_kunjungan_lab_hasil.notrans', '=', 't_kunjungan_lab.notrans')
            ->whereBetween(DB::raw('DATE(t_kunjungan_lab_hasil.created_at)'), [$mulaiTgl, $selesaiTgl])
            ->groupBy('tanggal', 'kasir_m_layanan.idLayanan', 'kasir_m_layanan.nmLayanan', 't_kunjungan_lab.layanan')
            ->get();

        return response()->json($labHasilPemeriksaan, 200, [], JSON_PRETTY_PRINT);
    }

    public function waktu_pemeriksaan(Request $request)
    {
        $mulaiTgl = $request->input('tglAwal', now()->toDateString());
        $selesaiTgl = $request->input('tglAkhir', now()->toDateString());

        $labHasilPemeriksaan = LaboratoriumHasilModel::with('pemeriksaan')
            ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$mulaiTgl, $selesaiTgl])
            ->get();
        // return response()->json($labHasilPemeriksaan, 200, [], JSON_PRETTY_PRINT);
        $data = [];
        foreach ($labHasilPemeriksaan as $d) {
            $waktuMulai = date('Y-m-d H:i:s', strtotime($d->created_at));
            $waktuSelesai = date('Y-m-d H:i:s', strtotime($d->updated_at));
            $durasi = max(0, round((strtotime($waktuSelesai) - strtotime($waktuMulai)) / 60, 2));
            $data[] = [
                'waktu_mulai' => $waktuMulai,
                'waktu_selesai' => $waktuSelesai,
                "durasi" => $durasi,
                "idLab" => $d->idLab,
                "notrans" => $d->notrans,
                "norm" => $d->norm,
                "idLayanan" => $d->idLayanan,
                "layanan" => $d->pemeriksaan->nmLayanan,
                "estimasi" => $d->pemeriksaan->estimasi,
                "hasil" => $d->pemeriksaan_fisik,
                "ket" => $d->ket,
                "created_at" => $d->created_at,
                "updated_at" => $d->updated_at,
            ];
        }

        $rataWaktu = $this->rataWaktuPemeriksaan($data);
        $response = [
            'rata_waktu' => $rataWaktu,
            'waktu_pemeriksaan' => $data,
        ];
        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($labHasilPemeriksaan, 200, [], JSON_PRETTY_PRINT);
    }
    public function rataWaktuPemeriksaan($labHasilPemeriksaan)
    {

        $data = [];
        // dd($labHasilPemeriksaan);
        foreach ($labHasilPemeriksaan as $d) {
            $waktuMulai = strtotime($d['created_at']);
            $waktuSelesai = strtotime($d['updated_at']);
            $durasi = max(0, round(($waktuSelesai - $waktuMulai) / 60, 2));

            $idLayanan = $d['idLayanan'];
            $nmLayanan = $d['layanan'];
            $estimasi = $d['estimasi'];

            if (!isset($data[$idLayanan])) {
                $data[$idLayanan] = [
                    'idLayanan' => $idLayanan,
                    'nmLayanan' => $nmLayanan,
                    'estimasi' => $estimasi,
                    'total_waktu' => 0,
                    'jumlah' => 0,
                    'waktu_terlama' => 0,
                    'waktu_tercepat' => PHP_INT_MAX,
                ];
            }

            $data[$idLayanan]['total_waktu'] += $durasi;
            $data[$idLayanan]['jumlah']++;
            $data[$idLayanan]['waktu_terlama'] = max($data[$idLayanan]['waktu_terlama'], $durasi);
            $data[$idLayanan]['waktu_tercepat'] = min($data[$idLayanan]['waktu_tercepat'], $durasi);
        }

        $result = [];
        foreach ($data as $item) {
            $result[] = [
                'idLayanan' => $item['idLayanan'],
                'nmLayanan' => $item['nmLayanan'],
                'estimasi' => $item['estimasi'],
                'rata-rata' => round($item['total_waktu'] / max(1, $item['jumlah']), 2),
                'waktu_terlama' => $item['waktu_terlama'],
                'waktu_tercepat' => ($item['waktu_tercepat'] == PHP_INT_MAX ? 0 : $item['waktu_tercepat']),
            ];
        }

        return $result;
    }

    private function calculateAverages($data)
    {
        $total = count($data);

        $total_tunggu_daftar = 0;
        $total_tunggu_lab = 0;
        $total_tunggu_hasil_lab = 0;
        $total_tunggu_ro = 0;
        $total_tunggu_hasil_ro = 0;
        $total_tunggu_poli = 0;
        $total_durasi_poli = 0;
        $total_tunggu_tensi = 0;
        $total_tunggu_igd = 0;
        $total_tunggu_farmasi = 0;
        $total_tunggu_kasir = 0;

        $max_tunggu_daftar = 0;
        $max_tunggu_lab = 0;
        $max_tunggu_hasil_lab = 0;
        $max_tunggu_hasil_ro = 0;
        $max_tunggu_ro = 0;
        $max_tunggu_poli = 0;
        $max_durasi_poli = 0;
        $max_tunggu_tensi = 0;
        $max_tunggu_igd = 0;
        $max_tunggu_farmasi = 0;
        $max_tunggu_kasir = 0;

        foreach ($data as $message) {
            $total_tunggu_daftar += $message['tunggu_daftar'];
            $total_tunggu_lab += $message['tunggu_lab'];
            $total_tunggu_hasil_lab += $message['tunggu_hasil_lab'];
            $total_tunggu_hasil_ro += $message['tunggu_hasil_ro'];
            $total_tunggu_ro += $message['tunggu_ro'];
            $total_tunggu_poli += $message['tunggu_poli'];
            $total_durasi_poli += $message['durasi_poli'];
            $total_tunggu_tensi += $message['tunggu_tensi'];
            $total_tunggu_igd += $message['tunggu_igd'];
            $total_tunggu_farmasi += $message['tunggu_farmasi'];
            $total_tunggu_kasir += $message['tunggu_kasir'];

            // Update max values
            $max_tunggu_daftar = max($max_tunggu_daftar, $message['tunggu_daftar']);
            $max_tunggu_lab = max($max_tunggu_lab, $message['tunggu_lab']);
            $max_tunggu_hasil_lab = max($max_tunggu_hasil_lab, $message['tunggu_hasil_lab']);
            $max_tunggu_hasil_ro = max($max_tunggu_hasil_ro, $message['tunggu_hasil_ro']);
            $max_tunggu_ro = max($max_tunggu_ro, $message['tunggu_ro']);
            $max_tunggu_poli = max($max_tunggu_poli, $message['tunggu_poli']);
            $max_durasi_poli = max($max_durasi_poli, $message['durasi_poli']);
            $max_tunggu_tensi = max($max_tunggu_tensi, $message['tunggu_tensi']);
            $max_tunggu_igd = max($max_tunggu_igd, $message['tunggu_igd']);
            $max_tunggu_farmasi = max($max_tunggu_farmasi, $message['tunggu_farmasi']);
            $max_tunggu_kasir = max($max_tunggu_kasir, $message['tunggu_kasir']);
        }

        $avg_tunggu_daftar = round($total_tunggu_daftar / $total, 2);
        $avg_tunggu_lab = round($total_tunggu_lab / $total, 2);
        $avg_tunggu_hasil_lab = round($total_tunggu_hasil_lab / $total, 2);
        $avg_tunggu_hasil_ro = round($total_tunggu_hasil_ro / $total, 2);
        $avg_tunggu_ro = round($total_tunggu_ro / $total, 2);
        $avg_tunggu_poli = round($total_tunggu_poli / $total, 2);
        $avg_durasi_poli = round($total_durasi_poli / $total, 2);
        $avg_tunggu_tensi = round($total_tunggu_tensi / $total, 2);
        $avg_tunggu_igd = round($total_tunggu_igd / $total, 2);
        $avg_tunggu_farmasi = round($total_tunggu_farmasi / $total, 2);
        $avg_tunggu_kasir = round($total_tunggu_kasir / $total, 2);

        $results = [
            'avg_tunggu_daftar' => $avg_tunggu_daftar,
            'avg_tunggu_lab' => $avg_tunggu_lab,
            'avg_tunggu_hasil_lab' => $avg_tunggu_hasil_lab,
            'avg_tunggu_hasil_ro' => $avg_tunggu_hasil_ro,
            'avg_tunggu_ro' => $avg_tunggu_ro,
            'avg_tunggu_poli' => $avg_tunggu_poli,
            'avg_durasi_poli' => $avg_durasi_poli,
            'avg_tunggu_tensi' => $avg_tunggu_tensi,
            'avg_tunggu_igd' => $avg_tunggu_igd,
            'avg_tunggu_farmasi' => $avg_tunggu_farmasi,
            'avg_tunggu_kasir' => $avg_tunggu_kasir,

            'max_tunggu_daftar' => $max_tunggu_daftar,
            'max_tunggu_lab' => $max_tunggu_lab,
            'max_tunggu_hasil_lab' => $max_tunggu_hasil_lab,
            'max_tunggu_hasil_ro' => $max_tunggu_hasil_ro,
            'max_tunggu_ro' => $max_tunggu_ro,
            'max_tunggu_poli' => $max_tunggu_poli,
            'max_durasi_poli' => $max_durasi_poli,
            'max_tunggu_tensi' => $max_tunggu_tensi,
            'max_tunggu_igd' => $max_tunggu_igd,
            'max_tunggu_farmasi' => $max_tunggu_farmasi,
            'max_tunggu_kasir' => $max_tunggu_kasir,
        ];

        return $results;
    }

    public function rekapKunjunganLab(Request $request)
    {
        $tglAwal = $request->input('tglAwal') ?? Carbon::now()->startOfYear()->format('Y-m-d');
        $tglAkhir = $request->input('tglAkhir') ?? Carbon::now()->endOfYear()->format('Y-m-d');

        $tglAwal = $tglAwal . ' 00:00:00';
        $tglAkhir = $tglAkhir . ' 23:59:59';

        $data = LaboratoriumKunjunganModel::select(
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

        // Buat HTML tabel
        $html = '<table id="jumlahLabTable" class="table table-bordered table-striped dataTable no-footer dtr-inline"
                    aria-describedby="jumlahLabTable">';
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

        return response()->json([
            'rekap_bulanan' => $data,
            'html' => $html,
        ], 200, [], JSON_PRETTY_PRINT);
    }

    // public function rekapKunjunganLabItem(Request $request)
    // {
    //     $mulaiTgl   = $request->input('tglAwal', now()->startOfYear()->toDateString());
    //     $selesaiTgl = $request->input('tglAkhir', now()->endOfYear()->toDateString());

    //     $labHasilPemeriksaan = DB::table('t_kunjungan_lab_hasil')
    //         ->select(
    //             DB::raw("DATE_FORMAT(t_kunjungan_lab_hasil.created_at, '%Y-%m') as bulan"),
    //             'kasir_m_layanan.nmLayanan AS nama_layanan',
    //             'kasir_m_layanan.idLayanan AS kode_layanan',
    //             't_kunjungan_lab.layanan AS jaminan',
    //             DB::raw('COUNT(t_kunjungan_lab_hasil.idLab) AS jumlah')
    //         )
    //         ->join('kasir_m_layanan', 't_kunjungan_lab_hasil.idLayanan', '=', 'kasir_m_layanan.idLayanan')
    //         ->join('t_kunjungan_lab', 't_kunjungan_lab_hasil.notrans', '=', 't_kunjungan_lab.notrans')
    //         ->whereBetween(DB::raw('DATE(t_kunjungan_lab_hasil.created_at)'), [$mulaiTgl, $selesaiTgl])
    //         ->groupBy('bulan', 'kode_layanan', 'nama_layanan', 'jaminan')
    //         ->orderBy('bulan')
    //         ->get();

    //     // Kelompokkan data
    //     $grouped = [];
    //     foreach ($labHasilPemeriksaan as $item) {
    //         $key = $item->bulan . '|' . $item->kode_layanan;
    //         if (! isset($grouped[$key])) {
    //             $grouped[$key] = [
    //                 'bulan'        => $item->bulan,
    //                 'nama_layanan' => $item->nama_layanan,
    //                 'kode_layanan' => $item->kode_layanan,
    //                 'BPJS'         => 0,
    //                 'UMUM'         => 0,
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
    //     <div class="card-header">
    //         <h3 class="card-title">Rekap Kunjungan Lab per Bulan dan Layanan</h3>
    //     </div>
    //     <div class="card-body">
    //         <table id="jumlahLabItemTable" class="table table-bordered table-striped dataTable no-footer dtr-inline"
    //                 aria-describedby="jumlahLabItemTable">
    //             <thead>
    //                 <tr>
    //                     <th style="width: 10px;">#</th>
    //                     <th>Bulan</th>
    //                     <th>Nama Layanan</th>
    //                     <th>Kode Layanan</th>
    //                     <th>BPJS</th>
    //                     <th>UMUM</th>
    //                 </tr>
    //             </thead>
    //             <tbody>';

    //     if (empty($grouped)) {
    //         $html .= '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>';
    //     } else {
    //         $i = 1;
    //         foreach ($grouped as $data) {
    //             $html .= '<tr>
    //             <td>' . $i++ . '</td>
    //             <td>' . date('F Y', strtotime($data['bulan'] . '-01')) . '</td>
    //             <td>' . $data['nama_layanan'] . '</td>
    //             <td>' . $data['kode_layanan'] . '</td>
    //             <td>' . $data['BPJS'] . '</td>
    //             <td>' . $data['UMUM'] . '</td>
    //         </tr>';
    //         }
    //     }

    //     $html .= '    </tbody>
    //         </table>
    //     </div>
    // </div>';

    //     return response()->json([
    //         'data' => $labHasilPemeriksaan,
    //         'html' => $html,
    //     ], 200, [], JSON_PRETTY_PRINT);
    // }

    public function rekapKunjunganLabItem(Request $request)
    {
        $mulaiTgl = $request->input('tglAwal', now()->startOfYear()->toDateString());
        $selesaiTgl = $request->input('tglAkhir', now()->endOfYear()->toDateString());

        $labHasilPemeriksaan = DB::table('t_kunjungan_lab_hasil')
            ->select(
                DB::raw("DATE_FORMAT(t_kunjungan_lab_hasil.created_at, '%Y-%m') as bulan"),
                'kasir_m_layanan.nmLayanan AS nama_layanan',
                'kasir_m_layanan.idLayanan AS kode_layanan',
                't_kunjungan_lab.layanan AS jaminan',
                DB::raw('COUNT(t_kunjungan_lab_hasil.idLab) AS jumlah')
            )
            ->join('kasir_m_layanan', 't_kunjungan_lab_hasil.idLayanan', '=', 'kasir_m_layanan.idLayanan')
            ->join('t_kunjungan_lab', 't_kunjungan_lab_hasil.notrans', '=', 't_kunjungan_lab.notrans')
            ->whereBetween(DB::raw('DATE(t_kunjungan_lab_hasil.created_at)'), [$mulaiTgl, $selesaiTgl])
            ->groupBy('bulan', 'kode_layanan', 'nama_layanan', 'jaminan')
            ->orderBy('bulan')
            ->get();

        // Ambil semua bulan unik
        $bulanUnik = collect($labHasilPemeriksaan)->pluck('bulan')->unique()->sort()->values();

        // Struktur data per layanan
        $grouped = [];

        foreach ($labHasilPemeriksaan as $item) {
            $key = $item->kode_layanan;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'nama_layanan' => $item->nama_layanan,
                    'kode_layanan' => $item->kode_layanan,
                    'data' => [],
                ];
            }

            $grouped[$key]['data'][$item->bulan][$item->jaminan] = $item->jumlah;
        }

        // Buat HTML
        $html = '<div class="card">
        <div class="card-header">
            <h3 class="card-title">Rekap Kunjungan Lab per Bulan dan Layanan</h3>
        </div>
        <div class="card-body">
            <table id="jumlahLabItemTable" class="table table-bordered table-striped dataTable no-footer dtr-inline"
                aria-describedby="jumlahLabItemTable">
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
            'data' => $labHasilPemeriksaan,
            'html' => $html,
        ], 200, [], JSON_PRETTY_PRINT);
    }

}
