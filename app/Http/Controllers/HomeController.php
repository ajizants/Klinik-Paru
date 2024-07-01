<?php

namespace App\Http\Controllers;

use App\Models\LayananModel;
use App\Models\ROJenisFoto;

class HomeController extends Controller
{
    public function lte()
    {
        $title = 'Dashboard';
        return view('Template.lte')->with('title', $title);
    }
    public function home()
    {
        $title = 'Dashboard';
        return view('dashboard')->with('title', $title);
    }

    public function igd()
    {
        $title = 'IGD';
        return view('IGD.Trans.main')->with('title', $title);
    }
    public function askep()
    {
        $title = 'ASKEP';
        return view('Askep.main')->with('title', $title);
    }
    public function dots()
    {
        $title = 'Dots Center';
        return view('DotsCenter.Trans.main')->with('title', $title);
    }
    public function farmasi()
    {
        $title = 'FARMASI';
        return view('Farmasi.Transaksi.main')->with('title', $title);
    }
    public function logFarmasi()
    {
        $title = 'RIWAYAT TRANSAKSI FARMASI';
        return view('Farmasi.log')->with('title', $title);
    }
    public function gudangFarmasi()
    {
        $title = 'Gudang Farmasi';
        return view('Farmasi.GudangFarmasi.main')->with('title', $title);
    }
    public function gudangIGD()
    {
        $title = 'Gudang IGD';
        return view('IGD.GudangIGD.main')->with('title', $title);
    }
    public function kasir()
    {
        $title = 'KASIR';
        return view('Kasir.main')->with('title', $title);
    }
    public function lab()
    {
        $title = 'Pendaftaran Laboratorium';
        return view('Laboratorium.Pendaftaran.main')->with('title', $title);
    }
    public function hasilLab()
    {
        $title = 'Input Hasil Laboratorium';
        return view('Laboratorium.Hasil.main')->with('title', $title);
    }
    public function riwayatlab()
    {
        $title = 'Riwayat Laboratorium';

        $query = LayananModel::on('mysql')
            ->where('status', '1')
            ->where('kelas', 'like', '%9%')
            ->get();
        $col = [];
        foreach ($query as $d) {
            $col[] = [
                "idLayanan" => $d["idLayanan"] ?? null,
                "nmLayanan" => $d["nmLayanan"] ?? null,
            ];
        }

        return view('Laboratorium.RiwayatLab.main', ['col' => $col])->with('title', $title);
    }
    public function masterlab()
    {
        $title = 'Master Laboratorium';

        $query = LayananModel::on('mysql')
            ->where('status', '1')
            ->where('kelas', 'like', '%9%')
            ->get();
        $col = [];
        foreach ($query as $d) {
            $col[] = [
                "idLayanan" => $d["idLayanan"] ?? null,
                "nmLayanan" => $d["nmLayanan"] ?? null,
            ];
        }

        return view('Laboratorium.MasterLab.main', ['col' => $col])->with('title', $title);
    }
    public function report()
    {
        $title = 'Report IGD';

        return view('IGD.report')->with('title', $title);
    }
    public function dispenser()
    {
        $title = 'Ambil Antrian';

        return view('Dispenser.main')->with('title', $title);
    }
    public function displayAntrian()
    {
        $title = 'Daftar Tunggu';

        return view('Display.main')->with('title', $title);
    }
    public function masterRo()
    {
        $title = 'Master Radiologi';
        $dataROJenisFoto = ROJenisFoto::all(); // Mengambil semua data ROJenisFoto
        // dd($dataROJenisFoto);
        return view('RO.Master.main', compact('title', 'dataROJenisFoto'));
    }
    public function ro()
    {
        $title = 'Radiologi';
        $appUrlRo = env('APP_URLRO');
        // dd($appUrlRo);
        return view('RO.Trans.main', compact('appUrlRo'))->with([
            'title' => $title,

        ]);
    }

    public function riwayatRo()
    {
        $title = 'Riwayat Rontgen';

        return view('RO.LogBook.main')->with('title', $title);
    }
}
