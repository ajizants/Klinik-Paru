<?php

namespace App\Http\Controllers;

use App\Models\KunjunganModel;
use App\Models\LaboratoriumHasilModel;
use App\Models\LaboratoriumKunjunganModel;
use App\Models\LayananModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LaboratoriumController extends Controller
{
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
                    $item->status = 'Belum Input Hasil';
                } else if ($nonNullHasilCount < $item->jmlh) {
                    $item->status = 'Input Hasil Belum Lengkap';
                } else {
                    $item->status = 'Input Hasil Lengkap';
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


    public function cariTsLab(Request $request)
    {
        try {
            if ($request->input('notrans') == null) {
                $norm = $request->input('norm');
                $tgl = $request->input('tgl');
                $data = LaboratoriumKunjunganModel::with('pemeriksaan.pemeriksaan')
                    ->where('norm', 'like', '%' . $norm . '%')
                    ->whereDate('created_at', 'like', '%' . $tgl . '%')
                    ->first();

                $lab = json_decode($data, true);
                // dd($lab);
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
                $data = LaboratoriumHasilModel::with('pemeriksaan')
                    ->where('notrans', 'like', '%' . $notrans . '%')->get();
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
        $nik = $request->input('nik');
        $alamat = $request->input('alamat');
        $jaminan = $request->input('jaminan');
        $dokter = $request->input('dokter');
        $petugas = $request->input('petugas');

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
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    return response()->json([
                        'message' => 'Data tidak lengkap',
                    ], 500);
                }
            }
            // dd($dataToInsert);
            // Simpan data permintaan laborat ke database
            LaboratoriumHasilModel::insert($dataToInsert);
            //tambahkan log data yang di simpan ke db

            DB::commit();
            Log::info('Transaksi berhasil disimpan: ' . json_encode($dataToInsert));

            // Extract notrans and tujuan from the request

            if ($notrans !== null) {
                $dataKunjungan = LaboratoriumKunjunganModel::where('notrans', $notrans)->first();

                if ($dataKunjungan == null) {
                    $kunjunganLab = new LaboratoriumKunjunganModel();

                    $kunjunganLab->notrans = $notrans;
                    $kunjunganLab->norm = $norm;
                    $kunjunganLab->nama = $nama;
                    $kunjunganLab->nik = $nik;
                    $kunjunganLab->alamat = $alamat;
                    $kunjunganLab->layanan = $jaminan;
                    $kunjunganLab->petugas = $petugas;
                    $kunjunganLab->dokter = $dokter;
                    $kunjunganLab->save();
                    return response()->json(['message' => 'Transaksi berhasil disimpan...!!'], 200);
                }

                return response()->json(['message' => 'Transaksi berhasil di update...!!'], 200);

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
                            // 'updated_at' => now(), // Jika ada kolom updated_at dan ingin diperbarui
                        ]);
                } else {
                    return response()->json([
                        'message' => 'Data tidak lengkap',
                    ], 400);
                }
            }
            $kunjungan=LaboratoriumKunjunganModel::where('notrans',$data['notrans'])->first();
            $kunjungan->update([
                'updated_at' => now(), // Jika ada kolom updated_at dan ingin diperbarui
            ]);

            // Commit transaksi database
            DB::commit();

            return response()->json([
                'message' => 'Data Berhasil Diperbarui',
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

    public function riwayat(Request $request)
    {
        $tglAwal = $request->input('tglAwal', now()->toDateString());
        $tglAkhir = $request->input('tglAkhir', now()->toDateString());
        // dd($tglAkhir);
        $notrans = $request->input('notrans');
        $data = KunjunganModel::with('riwayatLab', 'riwayatLab.layanan', 'riwayatLab.petugas.biodata', 'riwayatLab.dokter.biodata')
            ->where('notrans', 'like', '%' . $notrans . '%')
            ->whereBetween(DB::raw('DATE(tglTrans)'), [$tglAwal, $tglAkhir])
            ->whereHas('riwayatLab')
            ->get();
        // dd($data);
        $lab = json_decode($data, true);
        $formattedData = [];
        foreach ($lab as $transaksi) {
            if ($transaksi["kkelompok"] === 1) {
                $jaminan = "UMUM";
            } elseif ($transaksi["kkelompok"] === 1) {
                $jaminan = "BPJS";
            } else {
                $jaminan = "";
            }

            $formattedData[] = [
                "notrans" => $transaksi["notrans"] ?? null,
                "norm" => $transaksi["norm"] ?? null,
                "nourut" => $transaksi["nourut"] ?? null,
                "tgltrans" => $transaksi["tgltrans"] ?? null,
                "janiman" => $jaminan,

                "idLab" => $transaksi["riwayat_lab"]["idLab"] ?? null,
                "ket" => $transaksi["ket"] ?? null,
                "idLayanan" => $transaksi["riwayat_lab"]["idLayanan"] ?? null,
                // "NamaLayanan" => $transaksi["layanan"]["nmLayanan"] ?? null,
                // "jumlah" => $transaksi["jumlah"] ?? null,
                // "nippetugas" => $transaksi["petugas"]["biodata"]["nip"] ?? null,
                // "petugas" => ($transaksi["petugas"]["gelar_d"] ?? null) . ' ' . ($transaksi["petugas"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["petugas"]["gelar_b"] ?? null),
                // "nippetugas" => $transaksi["petugas"]["biodata"]["nip"] ?? null,
                // "dokter" => ($transaksi["dokter"]["gelar_d"] ?? null) . ' ' . ($transaksi["dokter"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["dokter"]["gelar_b"] ?? null),
                // "created_at" => $transaksi["created_at"] ?? null,
                // "updated_at" => $transaksi["updated_at"] ?? null,
            ];
        }
        // dd($formattedData);
        // return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
        return response()->json($lab, 200, [], JSON_PRETTY_PRINT);
    }
    public function rekapBpjsUmum(Request $request)
    {
        $tglAwal = $request->input('tglAwal', now()->toDateString());
        $tglAkhir = $request->input('tglAkhir', now()->toDateString());

        // dd($tglAkhir);
        $riwayatLab = DB::table('t_kunjungan_laboratorium')
            ->join('kasir_m_layanan', 't_kunjungan_laboratorium.idLayanan', '=', 'kasir_m_layanan.idLayanan')
            ->join('t_kunjungan', 't_kunjungan_laboratorium.notrans', '=', 't_kunjungan.notrans')
            ->join('m_kelompok', 't_kunjungan.kkelompok', '=', 'm_kelompok.kkelompok')
            ->select(
                'm_kelompok.kelompok',
                'kasir_m_layanan.nmLayanan',
                't_kunjungan_laboratorium.created_at',
                DB::raw('COUNT(0) AS Jumlah')
            )
            ->groupBy('m_kelompok.kelompok', 'kasir_m_layanan.nmLayanan', 't_kunjungan_laboratorium.created_at')
            ->whereBetween(DB::raw('DATE(t_kunjungan_laboratorium.created_at)'), [$tglAwal, $tglAkhir])
            ->get();

        // return view('riwayat_lab.index', compact('riwayatLab'));
        return response()->json($riwayatLab, 200, [], JSON_PRETTY_PRINT);
    }
    public function rekapReagen(Request $request)
    {
        $tglAwal = $request->input('tglAwal', now()->toDateString());
        $tglAkhir = $request->input('tglAkhir', now()->toDateString());

        // dd($tglAkhir);
        $riwayatLab = DB::table('t_kunjungan_laboratorium')
            ->join('kasir_m_layanan', 't_kunjungan_laboratorium.idLayanan', '=', 'kasir_m_layanan.idLayanan')
            ->join('t_kunjungan', 't_kunjungan_laboratorium.notrans', '=', 't_kunjungan.notrans')
            ->join('m_kelompok', 't_kunjungan.kkelompok', '=', 'm_kelompok.kkelompok')
            ->select(

                'kasir_m_layanan.nmLayanan',
                't_kunjungan_laboratorium.created_at',
                DB::raw('COUNT(0) AS Jumlah')
            )
            ->groupBy('kasir_m_layanan.nmLayanan', 't_kunjungan_laboratorium.created_at')
            ->whereBetween(DB::raw('DATE(t_kunjungan_laboratorium.created_at)'), [$tglAwal, $tglAkhir])
            ->get();

        // return view('riwayat_lab.index', compact('riwayatLab'));
        return response()->json($riwayatLab, 200, [], JSON_PRETTY_PRINT);
    }
    public function rekapReagenBln(Request $request)
    {
        $tglAwal = $request->input('tglAwal', now()->toDateString());
        $tglAkhir = $request->input('tglAkhir', now()->toDateString());

        $riwayatLab = DB::table('t_kunjungan_laboratorium')
            ->join('kasir_m_layanan', 't_kunjungan_laboratorium.idLayanan', '=', 'kasir_m_layanan.idLayanan')
            ->join('t_kunjungan', 't_kunjungan_laboratorium.notrans', '=', 't_kunjungan.notrans')
            ->join('m_kelompok', 't_kunjungan.kkelompok', '=', 'm_kelompok.kkelompok')
            ->select(
                'kasir_m_layanan.nmLayanan',
                DB::raw('CONCAT(MONTH(t_kunjungan_laboratorium.created_at), "-", YEAR(t_kunjungan_laboratorium.created_at)) as created_at'),
                DB::raw('COUNT(0) AS Jumlah')
            )
            ->groupBy('kasir_m_layanan.nmLayanan', 'created_at')
            ->whereBetween(DB::raw('DATE(t_kunjungan_laboratorium.created_at)'), [$tglAwal, $tglAkhir])
            ->get();

        return response()->json($riwayatLab, 200, [], JSON_PRETTY_PRINT);
    }

    public function rekapKunjungan2(Request $request)
    {
        $notrans = $request->input('notrans');
        $tglAwal = $request->input('tglAwal', now()->toDateString());
        $tglAkhir = $request->input('tglAkhir', now()->toDateString());
        // dd($tglAkhir);
        $data = LaboratoriumHasilModel::with('kunjungan.biodata', 'layanan.kelas', 'petugas.biodata', 'dokter.biodata')
            ->where('notrans', 'like', '%' . $notrans . '%')
            ->whereBetween('created_at', [
                \Carbon\Carbon::parse($tglAwal)->startOfDay(), // Menambahkan waktu mulai hari
                \Carbon\Carbon::parse($tglAkhir)->endOfDay(), // Menambahkan waktu akhir hari
            ])
            ->get();

        $lab = json_decode($data, true);
        // dd($lab);
        $formattedData = [];
        foreach ($lab as $transaksi) {

            $formattedData[] = [
                "IdLab" => $transaksi["idLab"] ?? null,
                "NoTrans" => $transaksi["notrans"] ?? null,
                "NORM" => $transaksi["norm"] ?? null,
                "IdLayanan" => $transaksi["idLayanan"] ?? null,
                "Jumlah" => $transaksi["jumlah"] ?? null,
                "Tagihan" => $transaksi["total"] ?? null,

                "NipPetugas" => $transaksi["petugas"]["nip"] ?? null,
                "NamaPetugas" => ($transaksi["petugas"]["gelar_d"] ?? null) . ' ' . ($transaksi["petugas"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["petugas"]["gelar_b"] ?? null),
                "NipDokter" => $transaksi["dokter"]["nip"] ?? null,
                "NamaDokter" => ($transaksi["dokter"]["gelar_d"] ?? null) . ' ' . ($transaksi["dokter"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["dokter"]["gelar_b"] ?? null),

                "Ket" => $transaksi["ket"] ?? null,
                "TglTrans" => $transaksi["created_at"]?\Carbon\Carbon::parse($transaksi["created_at"])->toDateTimeString() : null,

                // "kunjungan" => [
                "NoUrut" => $transaksi["kunjungan"]["nourut"] ?? null,
                "JenisKunjungan" => $transaksi["kunjungan"]["kunj"] ?? null,
                "JenisKelaminPasien" => $transaksi["kunjungan"]["jeniskel"] ?? null,
                "UmutPasien" => $transaksi["kunjungan"]["umurthn"] ?? null,
                "NIKPasien" => $transaksi["kunjungan"]["biodata"]["noktp"] ?? null,
                "NamaPasien" => $transaksi["kunjungan"]["biodata"]["nama"] ?? null,
                "Domisili" => $transaksi["kunjungan"]["biodata"]["alamat"] ?? null,
                "rtrw" => $transaksi["kunjungan"]["biodata"]["rtrw"] ?? null,
                "jeniskel" => $transaksi["kunjungan"]["biodata"]["jeniskel"] ?? null,
                "jkel" => $transaksi["kunjungan"]["biodata"]["jkel"] ?? null,
                "NoHP" => $transaksi["kunjungan"]["biodata"]["nohp"] ?? null,
                "Perkawinan" => $transaksi["kunjungan"]["biodata"]["statKawin"] ?? null,
                "Pekerjaan" => $transaksi["kunjungan"]["biodata"]["pekerjaan"] ?? null,
                "Jaminan" => $transaksi["kunjungan"]["biodata"]["kelompok"] ?? null,
                "provinsi" => $transaksi["kunjungan"]["biodata"]["provinsi"] ?? null,
                "kabupaten" => $transaksi["kunjungan"]["biodata"]["kabupaten"] ?? null,
                "kecamatan" => $transaksi["kunjungan"]["biodata"]["kecamatan"] ?? null,
                "kelurahan" => $transaksi["kunjungan"]["biodata"]["kelurahan"] ?? null,
                "AlamatLengkap" => ($transaksi["kunjungan"]["biodata"]["kelurahan"] ?? null) . ' ' . ($transaksi["kunjungan"]["biodata"]["rtrw"] ?? null) . ', ' . ($transaksi["kunjungan"]["biodata"]["kecamatan"] ?? null) . ', ' . ($transaksi["kunjungan"]["biodata"]["kabupaten"] ?? null),

                // ],
                // "layanan" => [
                "NamaPemeriksaan" => $transaksi["layanan"]["nmLayanan"] ?? null,
                "Tarif" => $transaksi["layanan"]["tarif"] ?? null,
                // ],
            ];
        }
        // dd($formattedData);
        return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($lab, 200, [], JSON_PRETTY_PRINT);
    }
    public function rekapKunjungan(Request $request)
    {
        $norm = $request->input('norm');
        $tglAwal = $request->input('tglAwal', now()->toDateString());
        $tglAkhir = $request->input('tglAkhir', now()->toDateString());
        // dd($tglAkhir);
        $data = LaboratoriumKunjunganModel::with('pemeriksaan', 'pemeriksaan.petugas.biodata', 'pemeriksaan.pemeriksaan', 'petugas.biodata', 'dokter.biodata')
            ->where('norm', 'like', '%' . $norm . '%')
            ->whereBetween('created_at', [
                \Carbon\Carbon::parse($tglAwal)->startOfDay(), // Menambahkan waktu mulai hari
                \Carbon\Carbon::parse($tglAkhir)->endOfDay(), // Menambahkan waktu akhir hari
            ])
            ->get();

        $lab = json_decode($data, true);

        return response()->json($lab, 200, [], JSON_PRETTY_PRINT);
    }

    public function poinPetugas(Request $request)
    {
        $mulaiTgl = $request->input('tglAwal', now()->toDateString());
        $selesaiTgl = $request->input('tglAkhir', now()->toDateString());

        $labHasilPemeriksaan = DB::table('lab_hasil_pemeriksaan')
            ->select(
                DB::raw('COUNT(lab_hasil_pemeriksaan.id) AS jml'),
                'peg_m_biodata.nip',
                'peg_m_biodata.nama',
                'kasir_m_layanan.nmLayanan AS tindakan'
            )
            ->join('peg_m_biodata', 'lab_hasil_pemeriksaan.petugas', '=', 'peg_m_biodata.nip')
            ->join('kasir_m_layanan', 'lab_hasil_pemeriksaan.idLayanan', '=', 'kasir_m_layanan.idLayanan')
            ->whereBetween(DB::raw('DATE_FORMAT(lab_hasil_pemeriksaan.created_at, "%Y-%m-%d")'), [$mulaiTgl, $selesaiTgl])
            ->groupBy('peg_m_biodata.nip', 'peg_m_biodata.nama', 'kasir_m_layanan.idLayanan', 'kasir_m_layanan.nmLayanan');

        $tKunjunganLab = DB::table('t_kunjungan_lab')
            ->select(
                DB::raw('COUNT(t_kunjungan_lab.id) AS jml'),
                'peg_m_biodata.nip',
                'peg_m_biodata.nama',
                DB::raw('"Sampling" AS tindakan')
            )
            ->join('peg_m_biodata', 't_kunjungan_lab.petugas', '=', 'peg_m_biodata.nip')
            ->whereBetween(DB::raw('DATE_FORMAT(t_kunjungan_lab.created_at, "%Y-%m-%d")'), [$mulaiTgl, $selesaiTgl])
            ->groupBy('peg_m_biodata.nip', 'peg_m_biodata.nama');

        $query = $labHasilPemeriksaan->union($tKunjunganLab)->get();

        return response()->json($query, 200, [], JSON_PRETTY_PRINT);
    }

}
