<?php

namespace App\Http\Controllers;

use App\Models\PasienModel;
use App\Models\KunjunganModel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AntrianController extends Controller
{

    public function antrianIGD(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $data = KunjunganModel::with(['poli', 'biodata', 'tindakan', 'kelompok', 'petugas.pegawai.biodata'])
            ->whereHas('poli', function ($query) {
                $query->where(function ($q) {
                    $q->where('oksigenasi', '<>', '')
                        ->where('oksigenasi', 'NOT LIKE', '%-%')
                        ->orWhere('nebulizer', '<>', '')
                        ->where('nebulizer', 'NOT LIKE', '%-%')
                        ->orWhere('ekg', '<>', '')
                        ->where('ekg', 'NOT LIKE', '%-%')
                        ->orWhere('mantoux', '<>', '')
                        ->where('mantoux', 'NOT LIKE', '%-%')
                        ->orWhere('spirometri', '<>', '')
                        ->where('spirometri', 'NOT LIKE', '%-%')
                        ->orWhere('injeksi', '<>', '')
                        ->where('injeksi', 'NOT LIKE', '%-%')
                        ->orWhere('infus', '<>', '')
                        ->where('infus', 'NOT LIKE', '%-%');
                });
            })
            ->whereDate('tgltrans', $date)
            ->get();


        $formattedData = [];
        foreach ($data as $transaksi) {

            if (isset($transaksi["tindakan"]) && isset($transaksi["tindakan"]["id"]) && $transaksi["tindakan"]["id"] !== null) {
                $status = "sudah";
            } else {
                $status = "belum";
            }

            $transaksi["status"] = $status;

            $formattedData[] = [
                "notrans" => $transaksi["notrans"] ?? null,
                "norm" => $transaksi["norm"] ?? null,
                "nourut" => $transaksi["nourut"] ?? null,
                "noasuransi" => $transaksi["noasuransi"] ?? null,
                "biaya" => $transaksi["kelompok"]["biaya"] ?? null,
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
                "kelompok" => $transaksi["biodata"]["kelompok"] ?? null,
                "provinsi" => $transaksi["biodata"]["provinsi"] ?? null,
                "kabupaten" => $transaksi["biodata"]["kabupaten"] ?? null,
                "kecamatan" => $transaksi["biodata"]["kecamatan"] ?? null,
                "kelurahan" => $transaksi["biodata"]["kelurahan"] ?? null,
                "rtrw" => $transaksi["biodata"]["rtrw"] ?? null,
                "agama" => $transaksi["biodata"]["agama"] ?? null,
                "pendidikan" => $transaksi["biodata"]["pendidikan"] ?? null,

                "tgltrans" => $transaksi["poli"]["tgltrans"] ?? null,
                "rontgen" => $transaksi["poli"]["rontgen"] ?? null,
                "konsul" => $transaksi["poli"]["konsul"] ?? null,
                "tcm" => $transaksi["poli"]["tcm"] ?? null,
                "bta" => $transaksi["poli"]["bta"] ?? null,
                "hematologi" => $transaksi["poli"]["hematologi"] ?? null,
                "kimiaDarah" => $transaksi["poli"]["kimiaDarah"] ?? null,
                "imunoSerologi" => $transaksi["poli"]["imunoSerologi"] ?? null,
                "mantoux" => $transaksi["poli"]["mantoux"] ?? null,
                "ekg" => $transaksi["poli"]["ekg"] ?? null,
                "mikroCo" => $transaksi["poli"]["mikroCo"] ?? null,
                "spirometri" => $transaksi["poli"]["spirometri"] ?? null,
                "spo2" => $transaksi["poli"]["spo2"] ?? null,
                "diagnosa1" => $transaksi["poli"]["diagnosa1"] ?? null,
                "diagnosa2" => $transaksi["poli"]["diagnosa2"] ?? null,
                "diagnosa3" => $transaksi["poli"]["diagnosa3"] ?? null,
                "nebulizer" => $transaksi["poli"]["nebulizer"] ?? null,
                "infus" => $transaksi["poli"]["infus"] ?? null,
                "oksigenasi" => $transaksi["poli"]["oksigenasi"] ?? null,
                "injeksi" => $transaksi["poli"]["injeksi"] ?? null,
                "terapi" => $transaksi["poli"]["terapi"] ?? null,

                "status" => $status,

                "idtindakan" => $transaksi["tindakan"]["id"] ?? null,
                "kdTind" => $transaksi["tindakan"]["kdTind"] ?? null,
                "petugastindakan" => $transaksi["tindakan"]["petugas"] ?? null,
                "doktertindakan" => $transaksi["tindakan"]["dokter"] ?? null,
                "created_at" => $transaksi["tindakan"]["created_at"] ?? null,
                "updated_at" => $transaksi["tindakan"]["updated_at"] ?? null,
                "nip" => $transaksi["petugas"]["pegawai"]["nip"] ?? null,
                "dokterpoli" => ($transaksi["petugas"]["pegawai"]["gelar_d"] ?? null) . ' ' . ($transaksi["petugas"]["pegawai"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["petugas"]["pegawai"]["gelar_b"] ?? null),
                "jabatan" => $transaksi["petugas"]["pegawai"]["nm_jabatan"] ?? null,
            ];
        }

        return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
    }
    public function antrianLaboratorium(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $data = KunjunganModel::with(['poli', 'biodata', 'tindakan', 'kelompok', 'petugas.pegawai.biodata'])
            ->whereHas('poli', function ($query) {
                $query->where(function ($q) {
                    $q->where('oksigenasi', '<>', '')
                        ->where('oksigenasi', 'NOT LIKE', '%-%')
                        ->orWhere('nebulizer', '<>', '')
                        ->where('nebulizer', 'NOT LIKE', '%-%')
                        ->orWhere('ekg', '<>', '')
                        ->where('ekg', 'NOT LIKE', '%-%')
                        ->orWhere('mantoux', '<>', '')
                        ->where('mantoux', 'NOT LIKE', '%-%')
                        ->orWhere('spirometri', '<>', '')
                        ->where('spirometri', 'NOT LIKE', '%-%')
                        ->orWhere('injeksi', '<>', '')
                        ->where('injeksi', 'NOT LIKE', '%-%')
                        ->orWhere('infus', '<>', '')
                        ->where('infus', 'NOT LIKE', '%-%');
                });
            })
            ->whereDate('tgltrans', $date)
            ->get();


        $formattedData = [];
        foreach ($data as $transaksi) {

            if (isset($transaksi["tindakan"]) && isset($transaksi["tindakan"]["id"]) && $transaksi["tindakan"]["id"] !== null) {
                $status = "sudah";
            } else {
                $status = "belum";
            }

            $transaksi["status"] = $status;

            $formattedData[] = [
                "notrans" => $transaksi["notrans"] ?? null,
                "norm" => $transaksi["norm"] ?? null,
                "nourut" => $transaksi["nourut"] ?? null,
                "noasuransi" => $transaksi["noasuransi"] ?? null,
                "biaya" => $transaksi["kelompok"]["biaya"] ?? null,
                "noktp" => $transaksi["biodata"]["noktp"] ?? null,
                "namapasien" => $transaksi["biodata"]["nama"] ?? null,
                "alamatpasien" => $transaksi["biodata"]["alamat"] ?? null,
                "rtrwpasien" => $transaksi["biodata"]["rtrw"] ?? null,
                "kelaminpasien" => $transaksi["biodata"]["jeniskel"] ?? null,
                "kelompok" => $transaksi["biodata"]["kelompok"] ?? null,
                "provinsi" => $transaksi["biodata"]["provinsi"] ?? null,
                "kabupaten" => $transaksi["biodata"]["kabupaten"] ?? null,
                "kecamatan" => $transaksi["biodata"]["kecamatan"] ?? null,
                "kelurahan" => $transaksi["biodata"]["kelurahan"] ?? null,
                "rtrw" => $transaksi["biodata"]["rtrw"] ?? null,
                "agama" => $transaksi["biodata"]["agama"] ?? null,
                "pendidikan" => $transaksi["biodata"]["pendidikan"] ?? null,

                "tgltrans" => $transaksi["poli"]["tgltrans"] ?? null,
                "rontgen" => $transaksi["poli"]["rontgen"] ?? null,
                "konsul" => $transaksi["poli"]["konsul"] ?? null,
                "tcm" => $transaksi["poli"]["tcm"] ?? null,
                "bta" => $transaksi["poli"]["bta"] ?? null,
                "hematologi" => $transaksi["poli"]["hematologi"] ?? null,
                "kimiaDarah" => $transaksi["poli"]["kimiaDarah"] ?? null,
                "imunoSerologi" => $transaksi["poli"]["imunoSerologi"] ?? null,
                "terapi" => $transaksi["poli"]["terapi"] ?? null,

                "status" => $status,

                "idtindakan" => $transaksi["tindakan"]["id"] ?? null,
                "kdTind" => $transaksi["tindakan"]["kdTind"] ?? null,
                "petugastindakan" => $transaksi["tindakan"]["petugas"] ?? null,
                "doktertindakan" => $transaksi["tindakan"]["dokter"] ?? null,
                "created_at" => $transaksi["tindakan"]["created_at"] ?? null,
                "updated_at" => $transaksi["tindakan"]["updated_at"] ?? null,
                "nip" => $transaksi["petugas"]["pegawai"]["nip"] ?? null,
                "dokterpoli" => ($transaksi["petugas"]["pegawai"]["gelar_d"] ?? null) . ' ' . ($transaksi["petugas"]["pegawai"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["petugas"]["pegawai"]["gelar_b"] ?? null),
                "jabatan" => $transaksi["petugas"]["pegawai"]["nm_jabatan"] ?? null,
            ];
        }

        return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
    }
    public function all(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $data = KunjunganModel::with(['poli', 'tujuan', 'biodata', 'tindakan', 'kelompok', 'petugas.pegawai.biodata'])
            ->whereDate('tgltrans', $date)
            ->get();


        $formattedData = [];
        foreach ($data as $transaksi) {
            if (isset($transaksi["kunj"]) && $transaksi["kunj"] == 'B') {
                $status = "BARU"; // Set $status to "Baru" if $transaksi["kunj"] is equal to 'B'
            } else {
                $status = "LAMA"; // Set $status to "Lama" for all other cases
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

                "tgltrans" => $transaksi["poli"]["tgltrans"] ?? null,
                "rontgen" => $transaksi["poli"]["rontgen"] ?? null,
                "konsul" => $transaksi["poli"]["konsul"] ?? null,
                "tcm" => $transaksi["poli"]["tcm"] ?? null,
                "bta" => $transaksi["poli"]["bta"] ?? null,
                "hematologi" => $transaksi["poli"]["hematologi"] ?? null,
                "kimiaDarah" => $transaksi["poli"]["kimiaDarah"] ?? null,
                "imunoSerologi" => $transaksi["poli"]["imunoSerologi"] ?? null,
                "mantoux" => $transaksi["poli"]["mantoux"] ?? null,
                "ekg" => $transaksi["poli"]["ekg"] ?? null,
                "mikroCo" => $transaksi["poli"]["mikroCo"] ?? null,
                "spirometri" => $transaksi["poli"]["spirometri"] ?? null,
                "spo2" => $transaksi["poli"]["spo2"] ?? null,
                "diagnosa1" => $transaksi["poli"]["diagnosa1"] ?? null,
                "diagnosa2" => $transaksi["poli"]["diagnosa2"] ?? null,
                "diagnosa3" => $transaksi["poli"]["diagnosa3"] ?? null,
                "nebulizer" => $transaksi["poli"]["nebulizer"] ?? null,
                "infus" => $transaksi["poli"]["infus"] ?? null,
                "oksigenasi" => $transaksi["poli"]["oksigenasi"] ?? null,
                "injeksi" => $transaksi["poli"]["injeksi"] ?? null,
                "terapi" => $transaksi["poli"]["terapi"] ?? null,

                "idtindakan" => $transaksi["tindakan"]["id"] ?? null,
                "kdTind" => $transaksi["tindakan"]["kdTind"] ?? null,
                "petugastindakan" => $transaksi["tindakan"]["petugas"] ?? null,
                "doktertindakan" => $transaksi["tindakan"]["dokter"] ?? null,
                "created_at" => $transaksi["tindakan"]["created_at"] ?? null,
                "updated_at" => $transaksi["tindakan"]["updated_at"] ?? null,
                "nip" => $transaksi["petugas"]["pegawai"]["nip"] ?? null,
                "dokterpoli" => ($transaksi["petugas"]["pegawai"]["gelar_d"] ?? null) . ' ' . ($transaksi["petugas"]["pegawai"]["biodata"]["nama"] ?? null) . ' ' . ($transaksi["petugas"]["pegawai"]["gelar_b"] ?? null),
                "jabatan" => $transaksi["petugas"]["pegawai"]["nm_jabatan"] ?? null,
            ];
        }

        return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    public function cariTgl(Request $request)
    {
        $date = $request->input('date', now()->toDateString());

        $data = KunjunganModel::with(['poli', 'biodata', 'tindakan', 'kelompok', 'petugas.pegawai.biodata'])
            ->whereHas('poli', function ($query) {
                $query->where(function ($q) {
                    $q->where('oksigenasi', '<>', '')
                        ->where('oksigenasi', 'NOT LIKE', '%-%')
                        ->orWhere('nebulizer', '<>', '')
                        ->where('nebulizer', 'NOT LIKE', '%-%')
                        ->orWhere('ekg', '<>', '')
                        ->where('ekg', 'NOT LIKE', '%-%')
                        ->orWhere('spirometri', '<>', '')
                        ->where('spirometri', 'NOT LIKE', '%-%')
                        ->orWhere('injeksi', '<>', '')
                        ->where('injeksi', 'NOT LIKE', '%-%')
                        ->orWhere('infus', '<>', '')
                        ->where('infus', 'NOT LIKE', '%-%');
                });
            })
            ->whereDate('tgltrans', $date)
            ->get();

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    public function cariRM(Request $request)
    {
        $norm = $request->input('norm');
        $date = $request->input('date', now()->toDateString());

        $data = KunjunganModel::with(['poli', 'biodata', 'kelompok', 'petugas.pegawai.biodata'])
            ->where('t_kunjungan.norm', 'LIKE', '%' . $norm . '%')
            ->whereDate('tgltrans', $date)
            ->get();

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function cariRMObat(Request $request)
    {
        $norm = $request->input('norm');
        $date = $request->input('date', now()->toDateString());
        $kode = str_replace('-', '', $date);

        $data = PasienModel::on('mysql')
            ->where('norm', 'LIKE', '%' . $norm . '%')
            ->get();
        $res = [];
        foreach ($data as $item) {
            $item["notrans"] =  $item["norm"] . $kode;
            $res[] = [
                "norm" => $item->norm,
                "noktp" => $item->noktp,
                "nama" => $item->nama,
                "tglLahir" => $item->tglLahir,
                "umur" => $item->umur,
                "gender" => $item->gender,
                "alamat" => $item->alamat,
                "provinsi" => $item->provinsi,
                "kabupaten" => $item->kabupaten,
                "kecamatan" => $item->kecamatan,
                "kelurahan" => $item->kelurahan,
                "rtrw" => $item->rtrw,
                "agama" => $item->agama,
                "pendidikan" => $item->pendidikan,
                "pekerjaan" => $item->pekerjaan,
                "notrans" => $item->notrans,
            ];
        }
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }
}
