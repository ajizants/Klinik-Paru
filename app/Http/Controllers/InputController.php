<?php

namespace App\Http\Controllers;

use App\Models\BMHPModel;
use App\Models\DiagnosaModel;
use App\Models\DotsObatModel;
use App\Models\GudangFarmasiModel;
use App\Models\ObatModel;
use App\Models\TindakanModel;
use App\Models\PegawaiModel;
use Illuminate\Http\Request;

class InputController extends Controller
{
    public function JenisTindakan()
    {

        $tind = TindakanModel::on('mysql')->get();
        // $tind = TindakanModel::on('mysql')->get();
        return response()->json($tind, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($tind);
    }

    public function addJenisTindakan(Request $request)
    {
        // Mengambil nilai dari input pengguna
        $nmTindakan = $request->input('nmTindakan');
        $harga = $request->input('harga');

        // Pastikan $kdTind memiliki nilai yang valid sebelum menyimpan data
        if ($nmTindakan !== null) {
            // Membuat instance dari model KunjunganTindakan
            $JenisTindakan = new TindakanModel();
            // Mengatur nilai-nilai kolom
            $JenisTindakan->nmTindakan = $nmTindakan;
            $JenisTindakan->harga = $harga;

            // Simpan data ke dalam tabel
            $JenisTindakan->save();

            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan']);
        } else {
            // Handle case when $kdTind is null, misalnya kirim respon error
            return response()->json(['message' => 'Jenis Tindakan tidak valid'], 400);
        }
    }

    public function deleteJenisTindakan(Request $request)
    {
        $kdTindakan = $request->input('kdTindakan');

        // Cek apakah ID yang diterima adalah ID yang valid dalam database
        $tindakan = TindakanModel::find($kdTindakan);

        if (!$tindakan) {
            return response()->json(['message' => 'Data tindakan tidak ditemukan'], 404);
        }

        // Hapus data tindakan dari database
        $tindakan->delete();

        // Respon sukses
        return response()->json(['message' => 'Data tindakan berhasil dihapus']);
    }

    public function bmhp()
    {

        $data = BMHPModel::with(['supplier', 'pabrikan'])
            ->get();
        foreach ($data as $transaksi) {
            if ($transaksi["jenis"] !== 1) {
                $nmjenis = "Obat";
            } else {
                $nmjenis = "BMHP";
            }
            $transaksi["nmjenis"] = $nmjenis;
        }
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function dxMedis()
    {
        $dxMedis = DiagnosaModel::on('mysql')->get();
        return response()->json($dxMedis, 200, [], JSON_PRETTY_PRINT);
    }

    public function addJenisBmhp(Request $request)
    {
        // Mengambil nilai dari input pengguna
        $nmBmhp = $request->input('nmBmhp');
        $hargaBmhp = $request->input('hargaBmhp');

        // Pastikan $kdTind memiliki nilai yang valid sebelum menyimpan data
        if ($nmBmhp !== null) {
            // Membuat instance dari model KunjunganTindakan
            $JenisBmhp = new BMHPModel();
            // Mengatur nilai-nilai kolom
            $JenisBmhp->nmBmhp = $nmBmhp;
            $JenisBmhp->hargaBmhp = $hargaBmhp;

            // Simpan data ke dalam tabel
            $JenisBmhp->save();

            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan']);
        } else {
            // Handle case when $kdTind is null, misalnya kirim respon error
            return response()->json(['message' => 'Jenis BMHP tidak valid'], 400);
        }
    }

    public function deleteJenisBmhp(Request $request)
    {
        $kdBmhp = $request->input('kdBmhp');

        // Cek apakah ID yang diterima adalah ID yang valid dalam database
        $bmhp = BMHPModel::find($kdBmhp);

        if (!$bmhp) {
            return response()->json(['message' => 'Data tindakan tidak ditemukan'], 404);
        }

        // Hapus data tindakan dari database
        $bmhp->delete();

        // Respon sukses
        return response()->json(['message' => 'Data tindakan berhasil dihapus']);
    }

    public function obat()
    {
        // $jenis = ('2');
        $obat = GudangFarmasiModel::on('mysql')
            // ->where('jenis', $jenis)
            ->whereNotNull('stok_akhir')
            ->where('stok_akhir', '>', 0)
            ->get();
        return response()->json($obat, 200, [], JSON_PRETTY_PRINT);
    }
    public function dokter()
    {
        $kdjab = [1, 7, 8];

        $dokter = PegawaiModel::on('mysql')->whereIn('kd_jab', $kdjab)->get();

        return response()->json($dokter);
    }

    public function perawat()
    {
        $kdjab = [10, 15];

        $perawat = PegawaiModel::on('mysql')->whereIn('kd_jab', $kdjab)->get();

        return response()->json($perawat, 200, [], JSON_PRETTY_PRINT);
    }
    public function apoteker()
    {
        $kdjab = [9];

        $apoteker = PegawaiModel::on('mysql')->whereIn('kd_jab', $kdjab)->get();

        return response()->json($apoteker, 200, [], JSON_PRETTY_PRINT);
    }
}
