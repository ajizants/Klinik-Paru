<?php

namespace App\Http\Controllers;

use App\Models\KunjunganModel;
use App\Models\LaboratoriumModel;
use App\Models\LayananModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaboratoriumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $notrans = $request->input('notrans');
        $data = LaboratoriumModel::with('layanan', 'petugas.biodata', 'dokter.biodata')
            ->where('notrans', 'like', '%' . $notrans . '%')
            ->get();

        $lab = json_decode($data, true);
        $formattedData = [];
        foreach ($lab as $transaksi) {

            $formattedData[] = [
                "idLab" => $transaksi["idLab"] ?? null,
                "notrans" => $transaksi["notrans"] ?? null,
                "norm" => $transaksi["norm"] ?? null,
                "ket" => $transaksi["ket"] ?? null,
                "idLayanan" => $transaksi["idLayanan"] ?? null,
                "NamaLayanan" => $transaksi["layanan"]["nmLayanan"] ?? null,
                "jumlah" => $transaksi["jumlah"] ?? null,
                "nippetugas" => $transaksi["petugas"]["biodata"]["nip"] ?? null,
                "petugas" => ($transaksi["petugas"]["gelar_d"] ?? null) . ' ' . ($transaksi["petugas"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["petugas"]["gelar_b"] ?? null),
                "nippetugas" => $transaksi["petugas"]["biodata"]["nip"] ?? null,
                "dokter" => ($transaksi["dokter"]["gelar_d"] ?? null) . ' ' . ($transaksi["dokter"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["dokter"]["gelar_b"] ?? null),
                "created_at" => $transaksi["created_at"] ?? null,
                "updated_at" => $transaksi["updated_at"] ?? null,

            ];
        }
        // dd($formattedData);
        return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($lab, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for take resource.
     */
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
        // dd($dataTerpilih);
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
            // dd($dataToInsert);
            // Simpan data ke database
            LaboratoriumModel::insert($dataToInsert);

            // Commit transaksi database
            DB::commit();

            return response()->json([
                'message' => 'Data berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi kesalahan
            DB::rollBack();
            dd($e);
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
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

            $formattedData[] = [
                "idLab" => $transaksi["idLab"] ?? null,
                "notrans" => $transaksi["notrans"] ?? null,
                "norm" => $transaksi["norm"] ?? null,
                "ket" => $transaksi["ket"] ?? null,
                "idLayanan" => $transaksi["idLayanan"] ?? null,
                "NamaLayanan" => $transaksi["layanan"]["nmLayanan"] ?? null,
                "jumlah" => $transaksi["jumlah"] ?? null,
                "nippetugas" => $transaksi["petugas"]["biodata"]["nip"] ?? null,
                "petugas" => ($transaksi["petugas"]["gelar_d"] ?? null) . ' ' . ($transaksi["petugas"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["petugas"]["gelar_b"] ?? null),
                "nippetugas" => $transaksi["petugas"]["biodata"]["nip"] ?? null,
                "dokter" => ($transaksi["dokter"]["gelar_d"] ?? null) . ' ' . ($transaksi["dokter"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["dokter"]["gelar_b"] ?? null),
                "created_at" => $transaksi["created_at"] ?? null,
                "updated_at" => $transaksi["updated_at"] ?? null,
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

}
