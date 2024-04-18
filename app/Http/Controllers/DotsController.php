<?php

namespace App\Http\Controllers;

use App\Models\DotsBlnModel;
use App\Models\DotsModel;
use App\Models\DotsObatModel;
use App\Models\DotsTransModel;
use App\Models\KunjunganModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DotsController extends Controller
{
    public function Ptb(Request $request)
    {
        $norm = $request->input('norm');

        $PtbQuery = DotsModel::with(['biodata', 'diagnosa', 'dokter.biodata']);

        // If 'norm' parameter is provided, filter the results based on it
        if ($norm) {
            $PtbQuery->where('norm', $norm);
        }

        $Ptb = $PtbQuery->get();

        // Check if any records exist based on the 'norm' parameter
        $exists = $Ptb->isNotEmpty();

        return response()->json([
            'exists' => $exists,
            'data' => $Ptb, // Include the data for further processing if needed
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function do(Request $request)
    {
        // Langkah pertama: Ambil norm dan tanggal nxKontrol terbaru untuk setiap norm
        $latestControls = DotsTransModel::select('norm', DB::raw('MAX(nxKontrol) as latest_nxKontrol'))
            ->whereDate('nxKontrol', '>', Carbon::now()->subDays(7))
            ->groupBy('norm')
            ->get();

        // Langkah kedua: Ambil data lengkap berdasarkan norm dan tanggal nxKontrol terbaru
        $Pkontrol = DotsTransModel::whereIn(DB::raw('(norm, nxKontrol)'), function ($query) use ($latestControls) {
            $query->select('norm', 'latest_nxKontrol')
                ->fromSub(function ($subquery) {
                    $subquery->from('t_kunjungan_dots')
                        ->whereDate('nxKontrol', '<', Carbon::now()->subDays(7))
                        ->groupBy('norm')
                        ->select('norm', DB::raw('MAX(nxKontrol) as latest_nxKontrol'));
                }, 'latest_controls');
        })
            ->with(['biodata', 'pasien', 'dokter'])
            ->get();

        return response()->json($Pkontrol, 200, [], JSON_PRETTY_PRINT);
    }

    public function telat(Request $request)
    {
        // Langkah pertama: Ambil norm dan tanggal nxKontrol terbaru untuk setiap norm
        $latestControls = DotsTransModel::select('norm', DB::raw('MAX(nxKontrol) as latest_nxKontrol'))
            ->whereBetween('nxKontrol', [Carbon::now()->subDays(28), Carbon::now()->subDays(1)])
            ->groupBy('norm')
            ->get();

        // Langkah kedua: Ambil data lengkap berdasarkan norm dan tanggal nxKontrol terbaru
        $Pkontrol = DotsTransModel::whereIn(DB::raw('(norm, nxKontrol)'), function ($query) use ($latestControls) {
            $query->select('norm', 'latest_nxKontrol')
                ->fromSub(function ($subquery) {
                    $subquery->from('t_kunjungan_dots')
                        ->whereBetween('nxKontrol', [Carbon::now()->subDays(28), Carbon::now()->subDays(1)])
                        ->groupBy('norm')
                        ->select('norm', DB::raw('MAX(nxKontrol) as latest_nxKontrol'));
                }, 'latest_controls');
        })
            ->with(['biodata', 'pasien', 'dokter'])
            ->get();

        return response()->json($Pkontrol, 200, [], JSON_PRETTY_PRINT);
    }
    public function kontrol(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $data = KunjunganModel::with(['poli.dx1', 'poli.dx2', 'poli.dx3', 'tujuan', 'dots', 'biodata', 'kelompok', 'petugas.pegawai.biodata'])
            ->whereDate('tgltrans', $date)
            ->where('ktujuan', 7)
            ->get();

        $formattedData = [];
        foreach ($data as $transaksi) {
            if (isset($transaksi["dots"]) && isset($transaksi["dots"]["id"]) && $transaksi["dots"]["id"] !== null) {
                $status = "sudah";
            } else {
                $status = "belum";
            }

            $transaksi["kunjungan"] = $status;

            $formattedData[] = [
                "notrans" => $transaksi["notrans"] ?? null,
                "norm" => $transaksi["norm"] ?? null,
                "nourut" => $transaksi["nourut"] ?? null,
                "noasuransi" => $transaksi["noasuransi"] ?? null,
                "kunj" => $transaksi["kunj"] ?? null,
                "kunjungan" => $status,
                "biaya" => $transaksi["kelompok"]["biaya"] ?? null,
                "layanan" => $transaksi["kelompok"]["kelompok"] ?? null,
                "noktp" => $transaksi["biodata"]["noktp"] ?? null,
                "namapasien" => $transaksi["biodata"]["nama"] ?? null,
                "alamatpasien" => $transaksi["biodata"]["alamat"] ?? null,
                "rtrwpasien" => $transaksi["biodata"]["rtrw"] ?? null,
                "kelaminpasien" => $transaksi["biodata"]["jeniskel"] ?? null,
                "tmptlahir" => $transaksi["biodata"]["tmptlahir"] ?? null,
                "tgllahir" => $transaksi["biodata"]["tgllahir"] ?? null,
                "umurpasien" => $transaksi["biodata"]["umur"] ?? null,
                "nohppasien" => $transaksi["biodata"]["nohp"] ?? null,
                "statKawinpasien" => $transaksi["biodata"]["statKawin"] ?? null,
                "provinsi" => $transaksi["biodata"]["provinsi"] ?? null,
                "kabupaten" => $transaksi["biodata"]["kabupaten"] ?? null,
                "kecamatan" => $transaksi["biodata"]["kecamatan"] ?? null,
                "kelurahan" => $transaksi["biodata"]["kelurahan"] ?? null,
                "rtrw" => $transaksi["biodata"]["rtrw"] ?? null,
                "agama" => $transaksi["biodata"]["agama"] ?? null,
                "pendidikan" => $transaksi["biodata"]["pendidikan"] ?? null,
                "lokasi" => $transaksi["tujuan"]["tujuan"] ?? null,

                "idKunjunganDots" => $transaksi["dots"]["id"] ?? null,

                "tgltrans" => $transaksi["poli"]["tgltrans"] ?? null,
                "rontgen" => $transaksi["poli"]["rontgen"] ?? null,
                "konsul" => $transaksi["poli"]["konsul"] ?? null,
                "kdDx1" => $transaksi["poli"]["diagnosa1"] ?? null,
                "diagnosa1" => $transaksi["poli"]["dx1"]["diagnosa"] ?? "",
                "kdDx2" => $transaksi["poli"]["diagnosa2"] ?? "",
                "diagnosa2" => $transaksi["poli"]["dx2"]["diagnosa"] ?? "",
                "kdDx3" => $transaksi["poli"]["diagnosa3"] ?? "",
                "diagnosa3" => $transaksi["poli"]["dx3"]["diagnosa"] ?? "",

                "dokterpoli" => ($transaksi["petugas"]["pegawai"]["gelar_d"] ?? null) . ' ' . ($transaksi["petugas"]["pegawai"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["petugas"]["pegawai"]["gelar_b"] ?? null),
            ];
        }

        return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    public function obatDots()
    {
        $obat = DotsObatModel::on('mysql')
            ->get();
        return response()->json($obat, 200, [], JSON_PRETTY_PRINT);
    }
    public function kunjunganDots(Request $request)
    {
        $norm = $request->input('norm');
        $data = DotsTransModel::with('obat', 'petugas.biodata', 'dokter.biodata')
            ->where('norm', $norm)
            ->get();

        $formattedData = [];
        foreach ($data as $transaksi) {

            $formattedData[] = [
                "id" => $transaksi["id"] ?? null,
                "norm" => $transaksi["norm"] ?? null,
                "notrans" => $transaksi["notrans"] ?? null,
                "bta" => $transaksi["bta"] ?? null,
                "bb" => $transaksi["bb"] ?? null,
                // "kunjungan" => $status,
                // "biaya" => $transaksi["kelompok"]["biaya"] ?? null,
                // "layanan" => $transaksi["kelompok"]["kelompok"] ?? null,
                // "noktp" => $transaksi["biodata"]["noktp"] ?? null,
                // "namapasien" => $transaksi["biodata"]["nama"] ?? null,
                // "alamatpasien" => $transaksi["biodata"]["alamat"] ?? null,
                // "rtrwpasien" => $transaksi["biodata"]["rtrw"] ?? null,
                // "kelaminpasien" => $transaksi["biodata"]["jeniskel"] ?? null,
                // "tmptlahir" => $transaksi["biodata"]["tmptlahir"] ?? null,
                // "tgllahir" => $transaksi["biodata"]["tgllahir"] ?? null,
                // "umurpasien" => $transaksi["biodata"]["umur"] ?? null,
                // "nohppasien" => $transaksi["biodata"]["nohp"] ?? null,
                // "statKawinpasien" => $transaksi["biodata"]["statKawin"] ?? null,
                // "provinsi" => $transaksi["biodata"]["provinsi"] ?? null,
                // "kabupaten" => $transaksi["biodata"]["kabupaten"] ?? null,
                // "kecamatan" => $transaksi["biodata"]["kecamatan"] ?? null,
                // "kelurahan" => $transaksi["biodata"]["kelurahan"] ?? null,
                // "rtrw" => $transaksi["biodata"]["rtrw"] ?? null,
                // "agama" => $transaksi["biodata"]["agama"] ?? null,
                // "pendidikan" => $transaksi["biodata"]["pendidikan"] ?? null,
                // "lokasi" => $transaksi["tujuan"]["tujuan"] ?? null,

                "idTerapi" => $transaksi["obat"]["id"] ?? null,
                "terapi" => $transaksi["obat"]["nmPengobatan"] ?? null,
                "dok" => $transaksi["dokter"]["biodata"]["nama"] ?? null,

                "tgltrans" => $transaksi["created_at"] ?? null,

                "petugas" => ($transaksi["petugas"]["gelar_d"] ?? null) . ' ' . ($transaksi["petugas"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["petugas"]["gelar_b"] ?? null),
                "dokter" => ($transaksi["dokter"]["gelar_d"] ?? null) . ' ' . ($transaksi["dokter"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["dokter"]["gelar_b"] ?? null),
            ];
        }
        // return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function blnKeDots()
    {
        $obat = DotsBlnModel::on('mysql')
            ->get();
        return response()->json($obat, 200, [], JSON_PRETTY_PRINT);
    }

    public function simpanDots(Request $request)
    {
        // Ambil data dari permintaan Ajax
        $norm = $request->input('norm');
        $notrans = $request->input('notrans');
        $bta = $request->input('bta');
        $bb = $request->input('bb');
        $blnKe = $request->input('blnKe');
        $nxKontrol = $request->input('nxKontrol');
        $terapi = $request->input('terapi');
        $petugas = $request->input('petugas');
        $dokter = $request->input('dokter');

        if ($norm !== null) {
            // Membuat instance dari model KunjunganTindakan
            $addPTB = new DotsTransModel();
            // Mengatur nilai-nilai kolom
            $addPTB->norm = $norm;
            $addPTB->notrans = $notrans;
            $addPTB->bta = $bta;
            $addPTB->blnKe = $blnKe;
            $addPTB->bb = $bb;
            $addPTB->nxKontrol = $nxKontrol;
            $addPTB->terapi = $terapi;
            $addPTB->petugas = $petugas;
            $addPTB->dokter = $dokter;

            // Simpan data ke dalam tabel
            $addPTB->save();

            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan']);
        } else {
            // Handle case when $kdTind is null, misalnya kirim respon error
            return response()->json(['message' => 'kode tidak valid'], 400);
        }
    }
    public function addPTB(Request $request)
    {
        // Ambil data dari permintaan Ajax
        $norm = $request->input('norm');
        $hp = $request->input('hp');
        $tcm = $request->input('tcm');
        $dx = $request->input('dx');
        $mulai = $request->input('mulai');
        $bb = $request->input('bb');
        $terapi = $request->input('terapi');

        $hiv = $request->input('hiv');
        $dm = $request->input('dm');
        $ket = $request->input('ket');
        $status = $request->input('status');
        $petugas = $request->input('petugas');
        $dokter = $request->input('dokter');

        if ($norm !== null) {
            // Membuat instance dari model KunjunganTindakan
            $addPTB = new DotsModel();
            // Mengatur nilai-nilai kolom
            $addPTB->norm = $norm;
            $addPTB->noHp = $hp;
            $addPTB->tcm = $tcm;
            $addPTB->kdDx = $dx;
            $addPTB->tglMulai = $mulai;
            $addPTB->bb = $bb;
            $addPTB->obat = $terapi;
            $addPTB->hiv = $hiv;
            $addPTB->dm = $dm;
            $addPTB->ket = $ket;
            $addPTB->hasilBerobat = $status;
            $addPTB->petugas = $petugas;
            $addPTB->dokter = $dokter;

            // Simpan data ke dalam tabel
            $addPTB->save();

            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => 'Data berhasil disimpan']);
        } else {
            // Handle case when $kdTind is null, misalnya kirim respon error
            return response()->json(['message' => 'kdBmhp tidak valid'], 400);
        }
    }
}
