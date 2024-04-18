<?php

namespace App\Http\Controllers;

use App\Models\KunjunganModel;
use App\Models\PasienModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

            if (count($transaksi["tindakan"]) === 0) {
                $status = "belum";
            } else {
                $status = "sudah";
            }

            // $transaksi["status"] = $status;

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
        $norm = $request->input("norm");
        $date = $request->input('date', now()->toDateString());
        // dd($date);
        $data = KunjunganModel::with(['poli', 'biodata', 'lab', 'kelompok', 'petugas.pegawai.biodata'])
            ->whereDate('tgltrans', $date)
            ->where('norm', 'LIKE', '%' . $norm . '%')
            ->whereHas('poli', function ($query) {
                $query->where(function ($q) {
                    $q->where('tcm', 1)
                    // ->where('tcm', 'NOT LIKE', '%-%')
                        ->orWhere('bta', 1)
                    // ->where('bta', 'NOT LIKE', '%-%')
                        ->orWhere('hematologi', 1)
                    // ->where('hematologi', 'NOT LIKE', '%-%')
                        ->orWhere('kimiaDarah', 1)
                    // ->where('kimiaDarah', 'NOT LIKE', '%-%')
                        ->orWhere('imunoSerologi', 1);
                    // ->where('imunoSerologi', 'NOT LIKE', '%-%');
                });
            })
            ->orWhere('ktujuan', 5)
            ->get();

            $formattedData = [];
        // dd($data);
        foreach ($data as $transaksi) {

            if (count($transaksi["lab"]) === 0) {
                $status = "belum";
            } else {
                $status = "sudah";
            }
            // dd($status);
            if ($transaksi["umurthn"] <= 15) {
                $pang = "anak";
            } elseif ($transaksi["umurthn"] >= 16 && $transaksi["umurthn"] <= 30) {
                if ($transaksi["biodata"]["jeniskel"] == "Laki-Laki") {
                    $pang = "saudara";
                } else {
                    $pang = "nona";
                }
            } elseif ($transaksi["umurthn"] >= 31) {
                if ($transaksi["biodata"]["jeniskel"] == "Laki-Laki") {
                    $pang = "bapak";
                } else {
                    $pang = "ibu";
                }
            }

            $transaksi["status"] = $status;

            $formattedData[] = [
                "notrans" => $transaksi["notrans"] ?? null,
                "norm" => $transaksi["norm"] ?? null,
                "nourut" => $transaksi["nourut"] ?? null,
                "noasuransi" => $transaksi["noasuransi"] ?? null,
                "ktujuan" => $transaksi["ktujuan"] ?? null,
                "tgltrans" => $transaksi["tgltrans"] ?? null,
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

                "rontgen" => $transaksi["poli"]["rontgen"] ?? null,
                "konsul" => $transaksi["poli"]["konsul"] ?? null,
                "tcm" => $transaksi["poli"]["tcm"] ?? null,
                "bta" => $transaksi["poli"]["bta"] ?? null,
                "hematologi" => $transaksi["poli"]["hematologi"] ?? null,
                "kimiaDarah" => $transaksi["poli"]["kimiaDarah"] ?? null,
                "imunoSerologi" => $transaksi["poli"]["imunoSerologi"] ?? null,
                "terapi" => $transaksi["poli"]["terapi"] ?? null,

                "status" => $status,
                "pang" => $pang,

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
    public function antrianFarmasi(Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());

        $data = KunjunganModel::with(['biodata', 'kelompok', 'poli', 'tindakan', 'farmasi', 'petugas.pegawai.biodata'])
            ->whereDate('tgltrans', $date)
            ->whereHas('poli', function ($query) {
                $query->whereNotNull('notrans');
            })
            ->get();
        $formattedData = [];
        foreach ($data as $transaksi) {

            if (isset($transaksi["farmasi"]) && isset($transaksi["farmasi"]["idAptk"]) && $transaksi["farmasi"]["idAptk"] !== null) {
                $status = "sudah";
            } else {
                $status = "belum";
            }

            if ($transaksi["umurthn"] <= 15) {
                $pang = "anak";
            } elseif ($transaksi["umurthn"] >= 16 && $transaksi["umurthn"] <= 30) {
                if ($transaksi["biodata"]["jeniskel"] == "Laki-Laki") {
                    $pang = "saudara";
                } else {
                    $pang = "nona";
                }
            } elseif ($transaksi["umurthn"] >= 31) {
                if ($transaksi["biodata"]["jeniskel"] == "Laki-Laki") {
                    $pang = "bapak";
                } else {
                    $pang = "ibu";
                }
            }
            if (
                isset($transaksi["petugas"]) &&
                is_array($transaksi["petugas"]) &&
                isset($transaksi["petugas"]["p_dokter_poli_konsul"]) &&
                $transaksi["petugas"]["p_dokter_poli_konsul"] !== null
            ) {
                $dokter = $transaksi["petugas"]["p_dokter_poli_konsul"];
            } else {
                $dokter = isset($transaksi["petugas"]["p_dokter_poli"]) ? $transaksi["petugas"]["p_dokter_poli"] : null;
            }

            $transaksi["status"] = $status;

            $formattedData[] = [
                "notrans" => $transaksi["notrans"] ?? "null",
                "norm" => $transaksi["norm"] ?? "null",
                "nourut" => $transaksi["nourut"] ?? "null",
                "noasuransi" => $transaksi["noasuransi"] ?? "null",
                "layanan" => $transaksi["kelompok"]["kelompok"] ?? "null",
                "biaya" => $transaksi["kelompok"]["biaya"] ?? "null",
                "noktp" => $transaksi["biodata"]["noktp"] ?? "null",
                "pang" => $pang ?? "null",
                "namapasien" => $transaksi["biodata"]["nama"] ?? "null",
                "alamatpasien" => $transaksi["biodata"]["alamat"] ?? "null",
                "rtrwpasien" => $transaksi["biodata"]["rtrw"] ?? "null",
                "kelaminpasien" => $transaksi["biodata"]["jeniskel"] ?? "null",
                "tgllahir" => $transaksi["biodata"]["tgllahir"] ?? "null",
                "umurpasien" => $transaksi["biodata"]["umur"] ?? "null",
                "nohppasien" => $transaksi["biodata"]["nohp"] ?? "null",
                "provinsi" => $transaksi["biodata"]["provinsi"] ?? "null",
                "kabupaten" => $transaksi["biodata"]["kabupaten"] ?? "null",
                "kecamatan" => $transaksi["biodata"]["kecamatan"] ?? "null",
                "kelurahan" => $transaksi["biodata"]["kelurahan"] ?? "null",
                "rtrw" => $transaksi["biodata"]["rtrw"] ?? "null",
                "agama" => $transaksi["biodata"]["agama"] ?? "null",
                "pendidikan" => $transaksi["biodata"]["pendidikan"] ?? "null",

                "status" => $status,

                "tgltrans" => $transaksi["poli"]["tgltrans"] ?? "null",
                "rontgen" => $transaksi["poli"]["rontgen"] ?? "null",
                "konsul" => $transaksi["poli"]["konsul"] ?? "null",
                "tcm" => $transaksi["poli"]["tcm"] ?? "null",
                "bta" => $transaksi["poli"]["bta"] ?? "null",
                "hematologi" => $transaksi["poli"]["hematologi"] ?? "null",
                "kimiaDarah" => $transaksi["poli"]["kimiaDarah"] ?? "null",
                "imunoSerologi" => $transaksi["poli"]["imunoSerologi"] ?? "null",
                "mantoux" => $transaksi["poli"]["mantoux"] ?? "null",
                "ekg" => $transaksi["poli"]["ekg"] ?? "null",
                "mikroCo" => $transaksi["poli"]["mikroCo"] ?? "null",
                "spirometri" => $transaksi["poli"]["spirometri"] ?? "null",
                "spo2" => $transaksi["poli"]["spo2"] ?? "null",
                "diagnosa1" => $transaksi["poli"]["diagnosa1"] ?? "null",
                "diagnosa2" => $transaksi["poli"]["diagnosa2"] ?? "null",
                "diagnosa3" => $transaksi["poli"]["diagnosa3"] ?? "null",
                "nebulizer" => $transaksi["poli"]["nebulizer"] ?? "null",
                "infus" => $transaksi["poli"]["infus"] ?? "null",
                "oksigenasi" => $transaksi["poli"]["oksigenasi"] ?? "null",
                "injeksi" => $transaksi["poli"]["injeksi"] ?? "null",
                "terapi" => $transaksi["poli"]["terapi"] ?? "null",
                "dokterpoli" => ($transaksi["petugas"]["pegawai"]["gelar_d"] ?? "null") . ' ' . ($transaksi["petugas"]["pegawai"]["biodata"]["nama"] ?? "null") . ' ' . ($transaksi["petugas"]["pegawai"]["gelar_b"] ?? "null"),
                "kddokter" => $dokter ?? "null",
                "idtindakan" => $transaksi["tindakan"]["id"] ?? "null",
                "kdTind" => $transaksi["tindakan"]["kdTind"] ?? "null",
                "petugastindakan" => $transaksi["tindakan"]["petugas"] ?? "null",
                "doktertindakan" => $transaksi["tindakan"]["dokter"] ?? "null",
                "jabatan" => $transaksi["petugas"]["pegawai"]["nm_jabatan"] ?? "null",
                "farmasi" => $transaksi["farmasi"] ?? "null",

            ];
        }

        return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function antrianKasir(Request $request)
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
    public function all(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $ruang = $request->input('ruang');
        $data = KunjunganModel::with(['poli', 'tujuan', 'biodata', 'lab', 'tindakan', 'kelompok', 'petugas.pegawai.biodata'])
            ->whereDate('tgltrans', $date)
            ->where('ktujuan', '<>', $ruang)
            ->get();

        $formattedData = [];
        foreach ($data as $transaksi) {
            if (isset($transaksi["kunj"]) && $transaksi["kunj"] == 'B') {
                $status = "BARU"; // Set $status to "Baru" if $transaksi["kunj"] is equal to 'B'
            } else {
                $status = "LAMA"; // Set $status to "Lama" for all other cases
            }

            if (count($transaksi["lab"]) === 0) {
                $lab = "belum";
            } else {
                $lab = "sudah";
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
                "laborat" => $lab,

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
            $item["notrans"] = $item["norm"] . $kode;
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
