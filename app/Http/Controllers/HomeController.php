<?php
namespace App\Http\Controllers;

use App\Models\DiagnosaMapModel;
use App\Models\DiagnosaModel;
use App\Models\GiziDxDomainModel;
use App\Models\GiziDxKelasModel;
use App\Models\GiziDxSubKelasModel;
use App\Models\PegawaiModel;

class HomeController extends Controller
{

    private function pegawai($kdjab)
    {
        $nip = [4, 5, 9999];
        if ($kdjab == []) {
            $data = PegawaiModel::with(['biodata', 'jabatan'])
                ->whereNotIn('nip', $nip)->get();
        } else {

            $data = PegawaiModel::with(['biodata', 'jabatan'])->whereIn('kd_jab', $kdjab)
                ->whereNotIn('nip', $nip)->get();
        }

        $pegawai = [];
        foreach ($data as $peg) {
            $pegawai[] = array_map('strval', [
                "nip"          => $peg["nip"] ?? null,
                "status"       => $peg["stat_pns"] ?? null,
                "gelar_d"      => $peg["gelar_d"] ?? null,
                "gelar_b"      => $peg["gelar_b"] ?? null,
                "kd_jab"       => $peg["kd_jab"] ?? null,
                "kd_pend"      => $peg["kd_pend"] ?? null,
                "kd_jurusan"   => $peg["kd_jurusan"] ?? null,
                "tgl_masuk"    => $peg["tgl_masuk"] ?? null,
                "nama"         => $peg["biodata"]["nama"] ?? null,
                "jeniskel"     => $peg["biodata"]["jeniskel"] ?? null,
                "tempat_lahir" => $peg["biodata"]["tempat_lahir"] ?? null,
                "tgl_lahir"    => $peg["biodata"]["tgl_lahir"] ?? null,
                "alamat"       => $peg["biodata"]["alamat"] ?? null,
                "kd_prov"      => $peg["biodata"]["kd_prov"] ?? null,
                "kd_kab"       => $peg["biodata"]["kd_kab"] ?? null,
                "kd_kec"       => $peg["biodata"]["kd_kec"] ?? null,
                "kd_kel"       => $peg["biodata"]["kd_kel"] ?? null,
                "kdAgama"      => $peg["biodata"]["kdAgama"] ?? null,
                "status_kawin" => $peg["biodata"]["status_kawin"] ?? null,
                "nm_jabatan"   => $peg["jabatan"]["nm_jabatan"] ?? null,
            ]);
        }
        return $pegawai;
    }
    public function lte()
    {
        $title = 'RaJal';
        return view('Template.lte')->with('title', $title);
    }
    public function home()
    {
        $title = 'RaJal';
        return view('dashboard')->with('title', $title);
    }
    public function forbidden()
    {
        $title = 'Forbidden';
        return view('Template.403')->with('title', $title);
    }

    public function gizi()
    {
        $title  = 'Gizi';
        $sub    = GiziDxSubKelasModel::with('domain')->get();
        $dxMed  = DiagnosaModel::get();
        $dokter = $this->pegawai([1, 7, 8]);

        $dokter = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        // dd($sub);
        return view('Gizi.Trans.main', compact('title', 'sub', "dxMed", "dokter"));
    }
    public function riwayatGizi()
    {
        $title = 'Riwayat Gizi';
        return view('Gizi.Riwayat.main')->with('title', $title);
    }
    public function masterGizi()
    {
        $title  = 'Master Gizi';
        $domain = GiziDxDomainModel::all();
        $kelas  = GiziDxKelasModel::all();

        return view('Gizi.Master.main', compact('title', 'domain', 'kelas'));
    }

    public function riwayatKunjungan()
    {
        $title = 'Riwayat Kunjungan';
        return view('Laporan.Pasien.main')->with('title', $title);
    }

    public function mappingDx()
    {
        $title = 'Mapping Diagnosa';
        $data  = DiagnosaMapModel::orderBy('updated_at', 'desc')->get();
        return view('Diagnosa.main', compact('data'))->with('title', $title);
    }

}
