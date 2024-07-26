<?php

namespace App\Http\Controllers;

use App\Models\KunjunganModel;
use App\Models\LaboratoriumHasilModel;
use App\Models\LaboratoriumKunjunganModel;
use App\Models\LayananModel;
use Carbon\Carbon;
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
                            'ket' => $data['ket'],
                            // 'updated_at' => now(), // Jika ada kolom updated_at dan ingin diperbarui
                        ]);
                } else {
                    return response()->json([
                        'message' => 'Data tidak lengkap',
                    ], 400);
                }
            }
            $kunjungan = LaboratoriumKunjunganModel::where('notrans', $data['notrans'])->first();
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

        $labHasilPemeriksaan = LaboratoriumHasilModel::whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$mulaiTgl, $selesaiTgl])->get();
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
                "hasil" => $d->pemeriksaan_fisik,
                "ket" => $d->ket,
                "created_at" => $d->created_at,
                "updated_at" => $d->updated_at,

            ];
        }

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($labHasilPemeriksaan, 200, [], JSON_PRETTY_PRINT);
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

}
