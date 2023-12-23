<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\LayananModel;
use App\Models\KunjunganModel;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());

        $data = KunjunganModel::with(['poli', 'biodata', 'tindakan', 'farmasi', 'kelompok', 'petugas.pegawai'])
            ->whereDate('tgltrans', $date)
            ->whereHas('poli', function ($query) {
                $query->whereNotNull('notrans');
            })
            ->get();

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function Layanan(Request $request)
    {

        $query = LayananModel::on('mysql')->where('status', '1');

        // Mengecek apakah request memiliki parameter kelas
        if ($request->has('kelas')) {
            $kelas = $request->input('kelas');
            $query->where('kelas', $kelas);
        }

        $layanan = $query->get();

        return response()->json($layanan, 200, [], JSON_PRETTY_PRINT);
    }

    // public function add(Request $request)
    // {
    //     // Mengambil nilai dari input pengguna
    //     $nmTindakan = $request->input('nmTindakan');
    //     $harga = $request->input('harga');

    //     // Pastikan $kdTind memiliki nilai yang valid sebelum menyimpan data
    //     if ($nmTindakan !== null) {
    //         // Membuat instance dari model KunjunganTindakan
    //         $JenisTindakan = new TindakanModel();
    //         // Mengatur nilai-nilai kolom
    //         $JenisTindakan->nmTindakan = $nmTindakan;
    //         $JenisTindakan->harga = $harga;

    //         // Simpan data ke dalam tabel
    //         $JenisTindakan->save();

    //         // Respon sukses atau redirect ke halaman lain
    //         return response()->json(['message' => 'Data berhasil disimpan']);
    //     } else {
    //         // Handle case when $kdTind is null, misalnya kirim respon error
    //         return response()->json(['message' => 'Jenis Tindakan tidak valid'], 400);
    //     }
    // }

    // public function delete(Request $request)
    // {
    //     $kdTindakan = $request->input('kdTindakan');

    //     // Cek apakah ID yang diterima adalah ID yang valid dalam database
    //     $tindakan = TindakanModel::find($kdTindakan);

    //     if (!$tindakan) {
    //         return response()->json(['message' => 'Data tindakan tidak ditemukan'], 404);
    //     }

    //     // Hapus data tindakan dari database
    //     $tindakan->delete();

    //     // Respon sukses
    //     return response()->json(['message' => 'Data tindakan berhasil dihapus']);
    // }

    // public function edit()
    // {

    //     $bmhp = BMHPModel::on('mysql')->get();
    //     // $bmhp = BMHPModel::on('mysql')->get();
    //     return response()->json($bmhp, 200, [], JSON_PRETTY_PRINT);
    //     // return response()->json($bmhp);
    // }

}
