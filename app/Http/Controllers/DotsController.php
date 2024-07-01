<?php

namespace App\Http\Controllers;

use App\Models\DiagnosaModel;
use App\Models\DotsBlnModel;
use App\Models\DotsModel;
use App\Models\DotsObatModel;
use App\Models\DotsTransModel;
use App\Models\KominfoModel;
use App\Models\KunjunganModel;
use Carbon\Carbon;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DotsController extends Controller
{
    public function Ptb(Request $request)
    {
        $norm = $request->input('norm');
        $ptbData = [];

        if ($norm) {
            $Ptb = DotsModel::with('dokter.biodata')->where('norm', $norm)->get();

        } else {
            $Ptb = DotsModel::with('dokter.biodata')->get();
        }

        foreach ($Ptb as $d) {
            $kdDiag = $d['kdDx'];
            $no_rm = $d['norm'];

            $dx = DiagnosaModel::where('kdDiag', $kdDiag)->get();
            $item['diagnosa'] = $dx;

            $kominfo = new KominfoModel();
            $pasien = $kominfo->pasienFilter($no_rm);

            $params = $request->only(['tanggal_awal', 'tanggal_akhir', 'no_rm']);
            $pendaftaran = $kominfo->waktuLayananRequest($params);
            // dd($pendaftaran);

            // Collect the data for each record
            $ptbData[] = [
                // 'pendaftaran' => $pendaftaran,
                'pasien' => $pasien,
                'ptb' => $d, // include the DotsModel record
                'diagnosa' => $dx,
            ];
        }

        if ($Ptb->isEmpty()) {
            $ptbData[] = [
                'pendaftaran' => $pendaftaran,
                'pasien' => $pasien,
                // 'ptb' => $d, // include the DotsModel record
                'diagnosa' => $dx,
            ];
            $res = [
                'exist' => false,
                'metadata' => [
                    'code' => 204,
                    'message' => 'Belum Terdaftar Sebagai Paien TBC...!!',
                ],
                'data' => $ptbData,
            ];
            return response()->json($res, 200, [], JSON_PRETTY_PRINT);
        } else {
            $res = [
                'exist' => true,
                'metadata' => [
                    'code' => 200,
                    'message' => 'Pasien Ditemukan...!!',
                ],
                'data' => $ptbData,
            ];

            return response()->json($res, 200, [], JSON_PRETTY_PRINT);
        }

    }

    public function cariPasienTB(Request $request)
    {
        $no_rm = $request->input('norm');

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
        $data = DotsTransModel::with('petugas.biodata', 'dokter.biodata')
            ->where('norm', $norm)
            ->get();

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function blnKeDots()
    {
        $obat = DotsBlnModel::on('mysql')
            ->get();
        return response()->json($obat, 200, [], JSON_PRETTY_PRINT);
    }

    public function simpanKunjungan(Request $request)
    {
        // Ambil data dari permintaan Ajax
        $norm = $request->input('norm');
        $notrans = $request->input('notrans');
        $tgltrans = $request->input('tgltrans');
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
            $addPTB->created_at = $tgltrans;
            $addPTB->bta = $bta;
            $addPTB->blnKe = $blnKe;
            $addPTB->bb = $bb;
            $addPTB->nxKontrol = $nxKontrol;
            $addPTB->terapi = $terapi;
            $addPTB->petugas = $petugas;
            $addPTB->dokter = $dokter;

            // Simpan data ke dalam tabel
            $addPTB->save();

            $msgUpdate = "";
            if ($blnKe == 99) {
                $update = DotsModel::where('norm', $norm)->first();
                $update->hasilBerobat = "Pengobatan Selesai";
                $update->save();
                // Respon sukses atau redirect ke halaman lain
                $msgUpdate = ' Status Pengobatan berhasil di Update';
            }

            $res = "Kunjungan pasien TBC Berhasil disimpan" . $msgUpdate;
            // Respon sukses atau redirect ke halaman lain
            return response()->json(['message' => $res]);
        } else {
            // Handle case when $norm is null, misalnya kirim respon error
            return response()->json(['message' => 'Kode tidak valid'], 400);
        }
    }

    public function addPasienTb(Request $request)
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

    public function updatePengobatanPasien(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        $pasien = DotsModel::where('id', $id)->first();
        $pasien->status = $status;
        $pasien->save();
        return response()->json(['message' => 'Data berhasil diupdate']);
    }
}
