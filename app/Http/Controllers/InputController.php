<?php
namespace App\Http\Controllers;

use App\Models\BMHPModel;
use App\Models\DiagnosaModel;
use App\Models\GudangFarmasiModel;
use App\Models\KelompokModel;
use App\Models\KominfoModel;
use App\Models\KunjunganModel;
use App\Models\TindakanModel;
use App\Models\TujuanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InputController extends Controller
{

    public function tes(Request $request)
    {

        $model  = new KominfoModel();
        $cookie = null;
        $tgl    = 2024 - 12 - 25;
        $data   = $model->getTungguFaramsi($tgl, $cookie);
        return response()->json($data);
    }
    public function jaminan()
    {

        $tind = KelompokModel::on('mysql')->get();
        // $tind = TindakanModel::on('mysql')->get();
        return response()->json($tind, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($tind);
    }
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
        $harga      = $request->input('harga');

        // Pastikan $kdTind memiliki nilai yang valid sebelum menyimpan data
        if ($nmTindakan !== null) {
            // Membuat instance dari model KunjunganTindakan
            $JenisTindakan = new TindakanModel();
            // Mengatur nilai-nilai kolom
            $JenisTindakan->nmTindakan = $nmTindakan;
            $JenisTindakan->harga      = $harga;

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

        if (! $tindakan) {
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
        $formattedData = [];
        foreach ($data as $transaksi) {
            if ($transaksi["jenis"] !== 1) {
                $nmjenis = "Obat";
            } else {
                $nmjenis = "BMHP";
            }
            $transaksi["nmjenis"] = $nmjenis;

            $formattedData[] = [
                "id"           => $transaksi["id"],
                "product_id"   => $transaksi["product_id"],
                "idObat"       => $transaksi["idObat"],
                "nmObat"       => $transaksi["nmObat"],
                "jenis"        => $transaksi["jenis"],
                "nmPabrikan"   => isset($transaksi["pabrikan"]) && is_array($transaksi["pabrikan"]) ? $transaksi["pabrikan"]["nmPabrikan"] : "Tidak Ditentukan",
                "pabrikan"     => isset($transaksi["pabrikan"]) && is_array($transaksi["pabrikan"]) ? $transaksi["pabrikan"]["pabrikan"] : "Tidak Ditentukan",
                "nmSupplier"   => isset($transaksi["supplier"]) && is_array($transaksi["supplier"]) ? $transaksi["supplier"]["nmSupplier"] : "Tidak Ditentukan",
                "supplier"     => isset($transaksi["supplier"]) && is_array($transaksi["supplier"]) ? $transaksi["supplier"]["id"] : "Tidak Ditentukan",
                "sediaan"      => $transaksi["sediaan"],
                "sumber"       => $transaksi["sumber"],
                "tglPembelian" => $transaksi["tglPembelian"],
                "ed"           => $transaksi["ed"],
                "hargaBeli"    => $transaksi["hargaBeli"],
                "hargaJual"    => $transaksi["hargaJual"],
                "stokBaru"     => $transaksi["stokBaru"],
                "keluar"       => $transaksi["keluar"],
                "sisa"         => $transaksi["sisa"],
                "masuk"        => $transaksi["masuk"],
                "created_at"   => $transaksi["created_at"],
                "updated_at"   => $transaksi["updated_at"],
                "nmjenis"      => $transaksi["nmjenis"],
            ];
        }
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
    }
    public function dxMedis()
    {
        $dxMedis = DiagnosaModel::on('mysql')->get();
        return response()->json($dxMedis, 200, [], JSON_PRETTY_PRINT);
    }

    public function addJenisBmhp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idObat'       => 'required',
            'nmObat'       => 'required',
            'stokBaru'     => 'required|numeric|min:1',
            'hargaBeli'    => 'required|numeric|min:0',
            'hargaJual'    => 'required|numeric|min:0',
            'jenis'        => 'required',
            'supplier'     => 'required|string',
            'pabrikan'     => 'required|string',
            'sediaan'      => 'required|string',
            'product_id'   => 'required|string',
            'sumber'       => 'required|string',
            'tglPembelian' => 'required|date',
            'ed'           => 'required|date|after_or_equal:tglPembelian',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Simpan ke database
        $data = BMHPModel::create($request->only([
            'idObat', 'nmObat', 'stokBaru', 'hargaBeli', 'hargaJual',
            'jenis', 'supplier', 'pabrikan', 'sediaan', 'product_id',
            'sumber', 'tglPembelian', 'ed',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Data BMHP berhasil ditambahkan.',
            'data'    => $data,
        ]);
    }

    public function deleteJenisBmhp(Request $request)
    {
        $kdBmhp = $request->input('kdBmhp');

        // Cek apakah ID yang diterima adalah ID yang valid dalam database
        $bmhp = BMHPModel::find($kdBmhp);

        if (! $bmhp) {
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

    public function tujuan()
    {
        $tujuan = TujuanModel::on()
            ->where('stat', 1)
            ->get();
        return response()->json($tujuan, 200, [], JSON_PRETTY_PRINT);
    }
    public function waktuLayanan(Request $request)
    {
        // Ambil nilai input dari permintaan
        $mulaiTgl   = $request->input('mulaiTgl', now());
        $selesaiTgl = $request->input('selesaiTgl', now());

        // Ubah format tanggal menjadi format yang sesuai dengan database
        $mulaiTgl   = date('Y-m-d', strtotime($mulaiTgl)) . ' 00:00:00';
        $selesaiTgl = date('Y-m-d', strtotime($selesaiTgl)) . ' 23:59:59';

        // Query menggunakan whereBetween dengan raw SQL untuk memformat tanggal
        $data = KunjunganModel::with(['biodata', 'tensi', 'poli', 'poli.dx1', 'poli.dx2', 'poli.dx3'])
            ->whereBetween(DB::raw('DATE_FORMAT(t_kunjungan.tgltrans, "%Y-%m-%d %H:%i:%s")'), [$mulaiTgl, $selesaiTgl])
            ->get();

        $formattedData = [];
        foreach ($data as $transaksi) {
            $alamatpasien = ($transaksi["biodata"]["kelurahan"] ?? null) . ' ' . ($transaksi["biodata"]["rtrw"] ?? null) . ' ' . ($transaksi["biodata"]["kecamatan"] ?? null) . ' ' . ($transaksi["biodata"]["kabupaten"] ?? null);

            if ($transaksi["kkelompok"] === 2) {
                $jaminan = "BPJS";
            } else {
                $jaminan = "UMUM";
            }

            $formattedData[] = [
                "notrans"         => $transaksi["notrans"] ?? null,
                "norm"            => $transaksi["norm"] ?? null,
                "noktp"           => $transaksi["biodata"]["noktp"] ?? null,
                "namapasien"      => $transaksi["biodata"]["nama"] ?? null,
                "alamatpasien"    => $alamatpasien ?? null,
                "kelaminpasien"   => $transaksi["biodata"]["jeniskel"] ?? null,
                "tmptlahir"       => $transaksi["biodata"]["tmptlahir"] ?? null,
                "tgllahir"        => $transaksi["biodata"]["tgllahir"] ?? null,
                "umurpasien"      => $transaksi["biodata"]["umur"] ?? null,
                "nohppasien"      => $transaksi["biodata"]["nohp"] ?? null,
                "statKawinpasien" => $transaksi["biodata"]["statKawin"] ?? null,
                "agama"           => $transaksi["biodata"]["agama"] ?? null,
                "pendidikan"      => $transaksi["biodata"]["pendidikan"] ?? null,
                "jaminan"         => $jaminan ?? null,

                "waktuDaftar"     => $transaksi["tgltrans"] ?? null,
                "waktuTensi"      => $transaksi["tensi"]["tgltrans"] ?? null,
                "waktuPoli"       => $transaksi["poli"]["tgltrans"] ?? null,
            ];
        }
        // return response()->json($data, 200, [], JSON_PRETTY_PRINT);
        return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
    }
}
