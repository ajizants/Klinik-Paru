<?php

namespace App\Http\Controllers;

use App\Models\KominfoModel;
use App\Models\PegawaiModel;
use App\Models\SuratMedis;

class SuratController extends Controller
{
    private function pegawai($kdjab)
    {
        $data = PegawaiModel::with(['biodata', 'jabatan'])->whereIn('kd_jab', $kdjab)->get();

        $pegawai = [];
        foreach ($data as $peg) {
            $pegawai[] = array_map('strval', [
                "nip" => $peg["nip"] ?? null,
                "status" => $peg["stat_pns"] ?? null,
                "gelar_d" => $peg["gelar_d"] ?? null,
                "gelar_b" => $peg["gelar_b"] ?? null,
                "kd_jab" => $peg["kd_jab"] ?? null,
                "kd_pend" => $peg["kd_pend"] ?? null,
                "kd_jurusan" => $peg["kd_jurusan"] ?? null,
                "tgl_masuk" => $peg["tgl_masuk"] ?? null,
                "nama" => $peg["biodata"]["nama"] ?? null,
                "jeniskel" => $peg["biodata"]["jeniskel"] ?? null,
                "tempat_lahir" => $peg["biodata"]["tempat_lahir"] ?? null,
                "tgl_lahir" => $peg["biodata"]["tgl_lahir"] ?? null,
                "alamat" => $peg["biodata"]["alamat"] ?? null,
                "kd_prov" => $peg["biodata"]["kd_prov"] ?? null,
                "kd_kab" => $peg["biodata"]["kd_kab"] ?? null,
                "kd_kec" => $peg["biodata"]["kd_kec"] ?? null,
                "kd_kel" => $peg["biodata"]["kd_kel"] ?? null,
                "kdAgama" => $peg["biodata"]["kdAgama"] ?? null,
                "status_kawin" => $peg["biodata"]["status_kawin"] ?? null,
                "nm_jabatan" => $peg["jabatan"]["nm_jabatan"] ?? null,
            ]);
        }
        return $pegawai;
    }
    public function listSM()
    {
        $lists = SuratMedis::all();
        $kominfoControler = new KominfoModel();
        $param = [
            'tanggal' => date('Y-m-d'),
            'ruang' => 'surat',
        ];
        $pasien = $kominfoControler->antrianAll($param);
        $jumlahSuratTahunIni = SuratMedis::whereYear('tanggal', date('Y'))->count();
        $dokter = $this->pegawai([1, 7, 8]);
        $petugas = $this->pegawai([10, 15]);
        $dokter = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        $petugas = array_map(function ($item) {
            return (object) $item;
        }, $petugas);

        return view('Laporan.noSuratMedis', compact('pasien', 'lists', 'jumlahSuratTahunIni', 'dokter', 'petugas'));
    }

}
