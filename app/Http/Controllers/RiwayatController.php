<?php

namespace App\Http\Controllers;


use App\Models\RiwayatModel;
use App\Models\KunjunganModel;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $norm = $request->input('norm');
        $data = KunjunganModel::with(['poli', 'poli.dx1', 'poli.dx2', 'tujuan', 'biodata', 'tindakan', 'kelompok', 'petugas.pegawai'])
            ->where('norm', $norm)
            ->get();

        $formattedData = [];
        foreach ($data as $transaksi) {
            $formattedData[] = [
                "notrans" => $transaksi["notrans"] ?? "null",
                "norm" => $transaksi["norm"] ?? "null",
                "nourut" => $transaksi["nourut"] ?? "null",
                "noktp" => $transaksi["biodata"]["noktp"] ?? "null",
                "namapasien" => $transaksi["biodata"]["nama"] ?? "null",
                "alamatpasien" => ($transaksi["biodata"]["alamat"] ?? "null") . ' ' . ($transaksi["biodata"]["kelurahan"] ?? "null") . ' ' . ($transaksi["biodata"]["kecamatan"] ?? "null") . ' ' . ($transaksi["biodata"]["kabupaten"] ?? "null") . ' ' . ($transaksi["biodata"]["provinsi"] ?? "null"),
                "tgllahir" => $transaksi["biodata"]["tgllahir"] ?? "null",
                "umurpasien" => $transaksi["biodata"]["umur"] ?? "null",
                "nohppasien" => $transaksi["biodata"]["nohp"] ?? "null",

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

                "idtindakan" => $transaksi["tindakan"]["id"] ?? "null",
                "kdTind" => $transaksi["tindakan"]["kdTind"] ?? "null",
                "petugastindakan" => $transaksi["tindakan"]["petugas"] ?? "null",
                "doktertindakan" => $transaksi["tindakan"]["dokter"] ?? "null",
                "created_at" => $transaksi["tindakan"]["created_at"] ?? "null",
                "updated_at" => $transaksi["tindakan"]["updated_at"] ?? "null",
                "nip" => $transaksi["petugas"]["pegawai"]["nip"] ?? "null",
                "dokterpoli" => ($transaksi["petugas"]["pegawai"]["gelar_d"] ?? "null") . ' ' . ($transaksi["petugas"]["pegawai"]["nama"] ?? "null") . ' ' . ($transaksi["petugas"]["pegawai"]["gelar_b"] ?? "null"),
                "jabatan" => $transaksi["petugas"]["pegawai"]["nm_jabatan"] ?? "null",
            ];
        }

        // return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
}
