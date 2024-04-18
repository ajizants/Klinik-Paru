<?php

namespace App\Http\Controllers;

use App\Models\KunjunganModel;
use App\Models\LaboratoriumHasilModel;
use App\Models\LaboratoriumKunjunganModel;
use App\Models\LaboratoriumModel;
use App\Models\LayananModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaboratoriumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function transaksi(Request $request)
    {
        $notrans = $request->input('notrans');
        $tglAwal = $request->input('tglAwal');
        $tglAkhir = $request->input('tglAkhir', now()->toDateString());
        // dd($tglAkhir);
        $data = LaboratoriumModel::with('kunjungan.biodata', 'layanan.kelas', 'petugas.biodata', 'dokter.biodata')
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
                "UmurPasien" => $transaksi["kunjungan"]["umurthn"] ?? null,
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
                        'petugas' => $data['petugas'],
                        'dokter' => $data['dokter'],
                        'idLayanan' => $data['idLayanan'],
                        'ket' => $data['ket'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    return response()->json([
                        'message' => 'Data tidak lengkap',
                    ], 500);
                }
            }

            // Simpan data permintaan laborat ke database
            LaboratoriumModel::insert($dataToInsert);
            DB::commit();

            // Extract notrans and tujuan from the request
            $notrans = $request->input('notrans');
            $norm = $request->input('norm');
            $dokter = $request->input('dokter');
            $petugas = $request->input('petugas');
            $tujuan = $request->input('tujuan');
            if ($notrans !== null) {
                //add t_kunjungan
                $kunjunganLab = new LaboratoriumKunjunganModel();

                $kunjunganLab->norm = $norm;
                $kunjunganLab->notrans = $notrans;
                $kunjunganLab->petugas = $petugas;
                $kunjunganLab->dokter = $dokter;
                $kunjunganLab->save();

                // Respon sukses atau redirect ke halaman lain
                return response()->json(['message' => 'Data berhasil disimpan']);
            } else {
                // Handle case when $kdTind is null, misalnya kirim respon error
                return response()->json(['message' => 'kdTind tidak valid'], 400);
            }
            // Update the KunjunganModel
            $affectedRows = KunjunganModel::where('notrans', $notrans)
                ->update(['ktujuan' => $tujuan]);

            if ($affectedRows > 0) {
                return response()->json([
                    'message' => 'Data berhasil disimpan dan diupdate',
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Tujuan berikutnya sama dengan tujuan sebelumnya',
                ], 200);
            }
        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi kesalahan
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
            ], 500);
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

            // Membuat array untuk menyimpan data yang akan disimpan
            $dataToInsert = [];
            // dd($dataToInsert);

            // Looping untuk mengolah dataTerpilih
            foreach ($dataTerpilih as $data) {
                // Validasi data yang diperlukan pada setiap elemen dataTerpilih
                if (isset($data['idLayanan']) && isset($data['notrans'])) {
                    $dataToInsert[] = [
                        'idLab' => $data['idLab'],
                        'notrans' => $data['notrans'],
                        'norm' => $data['norm'],
                        'idLayanan' => $data['idLayanan'],
                        'hasil' => $data['hasil'],
                        'petugas' => $data['petugas'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    // dd($dataTerpilih);
                } else {
                    return response()->json([
                        'message' => 'Data tidak lengkap',
                    ], 500);
                }
            }

            // Simpan data ke database
            LaboratoriumHasilModel::insert($dataToInsert);

            // Commit transaksi database
            DB::commit();

            return response()->json([
                'message' => 'Data Berhasil Disimpan',
            ], 201);

        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi kesalahan
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(), // tambahkan ini untuk mendapatkan pesan kesalahan
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteLab(Request $request)
    {
        $id = $request->input("idLab");
        // dd($id);
        // Memastikan $id tidak null sebelum memanggil fungsi destroyLab
        if ($id !== null) {
            // Memanggil metode destroyLab dari model
            LaboratoriumModel::destroyLab($id);

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
        $data = LaboratoriumModel::with('kunjungan.biodata', 'layanan.kelas', 'petugas.biodata', 'dokter.biodata')
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
        $notrans = $request->input('notrans');
        $tglAwal = $request->input('tglAwal', now()->toDateString());
        $tglAkhir = $request->input('tglAkhir', now()->toDateString());
        // dd($tglAkhir);
        $data = KunjunganModel::with('kelompok', 'biodata', 'riwayatLab.layanan.kelas', 'riwayatLab.petugas.biodata', 'riwayatLab.dokter.biodata')
            ->where('notrans', 'like', '%' . $notrans . '%')
            ->whereBetween('tgltrans', [
                \Carbon\Carbon::parse($tglAwal)->startOfDay(), // Menambahkan waktu mulai hari
                \Carbon\Carbon::parse($tglAkhir)->endOfDay(), // Menambahkan waktu akhir hari
            ])
            ->whereHas('riwayatLab')
            ->get();

        $lab = json_decode($data, true);
        // // dd($lab);
        $formattedData = [];
        foreach ($lab as $transaksi) {
            $riwayatLabData = [];

            foreach ($transaksi["riwayat_lab"] as $labResult) {
                $riwayatLabData[] = [
                    "IdLab" => $labResult["idLab"] ?? null,
                    "NoTrans" => $labResult["notrans"] ?? null,
                    "Norm" => $labResult["norm"] ?? null,
                    "IdLayanan" => $labResult["idLayanan"] ?? null,
                    "Jumlah" => $labResult["jumlah"] ?? null,
                    "Total" => $labResult["total"] ?? null,
                    "NipPetugas" => $labResult["petugas"]["nip"] ?? null,
                    "NamaPetugas" => ($labResult["petugas"]["gelar_d"] ?? null) . "" . ($labResult["petugas"]["biodata"]["nama"] ?? null) . " " . ($labResult["petugas"]["gelar_b"] ?? null),
                    "NipDokter" => $labResult["dokter"]["nip"] ?? null,
                    "NamaDokter" => ($labResult["dokter"]["gelar_d"] ?? null) . " " . ($labResult["dokter"]["biodata"]["nama"] ?? null) . " " . ($labResult["dokter"]["gelar_b"] ?? null),
                    "Ket" => $labResult["ket"] ?? null,
                    // "CreatedAt" => $labResult["created_at"] ?? null,
                    // "UpdatedAt" => $labResult["updated_at"] ?? null,
                    "Kelas" => $labResult["layanan"]["kelas"]["kelas"] ?? null,
                    "NmKelas" => $labResult["layanan"]["kelas"]["nmKelas"] ?? null,
                    "NmLayanan" => $labResult["layanan"]["nmLayanan"] ?? null,
                    "Tarif" => $labResult["layanan"]["tarif"] ?? null,
                    "Status" => $labResult["layanan"]["status"] ?? null,
                    "CreatedAt" => $labResult["created_at"]?\Carbon\Carbon::parse($labResult["created_at"])->toDateTimeString() : null,
                    "UpdatedAt" => $labResult["updated_at"]?\Carbon\Carbon::parse($labResult["updated_at"])->toDateTimeString() : null,
                ];
            }

            $formattedData[] = [
                "NoTrans" => $transaksi["notrans"] ?? null,
                "Norm" => $transaksi["norm"] ?? null,
                "RMLama" => $transaksi["rmlama"] ?? null,
                "NoUrut" => $transaksi["nourut"] ?? null,
                "TglTrans" => $transaksi["tgltrans"] ?? null,
                "Kunj" => $transaksi["kunj"] ?? null,
                "JenisKelamin" => $transaksi["jeniskel"] ?? null,
                "Jaminan" => $transaksi["kelompok"]["kelompok"] ?? null,
                "NoAsuransi" => $transaksi["noasuransi"] ?? null,
                "Ktujuan" => $transaksi["ktujuan"] ?? null,
                "Kabupaten" => $transaksi["kkabupaten"] ?? null,
                "UmurTahun" => $transaksi["umurthn"] ?? null,
                "Biaya" => $transaksi["biaya"] ?? null,
                "NIKPasien" => $transaksi["biodata"]["noktp"] ?? null,
                "NamaPasien" => $transaksi["biodata"]["nama"] ?? null,
                "Domisili" => $transaksi["biodata"]["alamat"] ?? null,
                "jeniskel" => $transaksi["biodata"]["jeniskel"] ?? null,
                "jkel" => $transaksi["biodata"]["jkel"] ?? null,
                "NoHP" => $transaksi["biodata"]["nohp"] ?? null,
                "Perkawinan" => $transaksi["biodata"]["statKawin"] ?? null,
                "Pekerjaan" => $transaksi["biodata"]["pekerjaan"] ?? null,
                "provinsi" => $transaksi["biodata"]["provinsi"] ?? null,
                "kabupaten" => $transaksi["biodata"]["kabupaten"] ?? null,
                "kecamatan" => $transaksi["biodata"]["kecamatan"] ?? null,
                "kelurahan" => $transaksi["biodata"]["kelurahan"] ?? null,
                "AlamatLengkap" => ($transaksi["biodata"]["kelurahan"] ?? null) . ' ' . ($transaksi["biodata"]["rtrw"] ?? null) . ', ' . ($transaksi["biodata"]["kecamatan"] ?? null) . ', ' . ($transaksi["biodata"]["kabupaten"] ?? null),

                // "RiwayatLab" => [
                //     [
                //         "IdLab" => $transaksi["riwayat_lab"][0]["idLab"] ?? null,
                //         "NoTrans" => $transaksi["riwayat_lab"][0]["notrans"] ?? null,
                //         "Norm" => $transaksi["riwayat_lab"][0]["norm"] ?? null,
                //         "IdLayanan" => $transaksi["riwayat_lab"][0]["idLayanan"] ?? null,
                //         "Jumlah" => $transaksi["riwayat_lab"][0]["jumlah"] ?? null,
                //         "Total" => $transaksi["riwayat_lab"][0]["total"] ?? null,
                //         "NipPetugas" => $transaksi["riwayat_lab"][0]["petugas"]["nip"] ?? null,
                //         "NamaPetugas" => ($transaksi["riwayat_lab"][0]["petugas"]["gelar_d"] ?? null) . "" . ($transaksi["riwayat_lab"][0]["petugas"]["biodata"]["nama"] ?? null) . " " . ($transaksi["riwayat_lab"][0]["petugas"]["gelar_b"] ?? null),
                //         "NipDokter" => $transaksi["riwayat_lab"][0]["dokter"]["nip"] ?? null,
                //         "NamaDokter" => ($transaksi["riwayat_lab"][0]["dokter"]["gelar_d"] ?? null) . " " . ($transaksi["riwayat_lab"][0]["dokter"]["biodata"]["nama"] ?? null) . " " . ($transaksi["riwayat_lab"][0]["dokter"]["gelar_b"] ?? null),
                //         "Ket" => $transaksi["riwayat_lab"][0]["ket"] ?? null,
                //         "CreatedAt" => $transaksi["riwayat_lab"][0]["created_at"] ?? null,
                //         "UpdatedAt" => $transaksi["riwayat_lab"][0]["updated_at"] ?? null,
                //         "IdLayanan" => $transaksi["riwayat_lab"][0]["layanan"]["idLayanan"] ?? null,
                //         "Kelas" => $transaksi["riwayat_lab"][0]["layanan"]["kelas"]["kelas"] ?? null,
                //         "NmKelas" => $transaksi["riwayat_lab"][0]["layanan"]["kelas"]["nmKelas"] ?? null,
                //         "NmLayanan" => $transaksi["riwayat_lab"][0]["layanan"]["nmLayanan"] ?? null,
                //         "Tarif" => $transaksi["riwayat_lab"][0]["layanan"]["tarif"] ?? null,
                //         "Status" => $transaksi["riwayat_lab"][0]["layanan"]["status"] ?? null,
                //         "CreatedAt" => $transaksi["riwayat_lab"][0]["layanan"]["created_at"] ?? null,
                //         "UpdatedAt" => $transaksi["riwayat_lab"][0]["layanan"]["updated_at"] ?? null,
                //     ],
                // ],
                "RiwayatLab" => $riwayatLabData,
            ];
        }
        // dd($formattedData);
        return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($lab, 200, [], JSON_PRETTY_PRINT);
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
