<?php

namespace App\Http\Controllers;

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
        return view('IGD.main')->with('title', $title);
    }
    public function askep()
    {
        $title = 'ASKEP';
        return view('Askep.main')->with('title', $title);
    }
    public function dots()
    {
        $title = 'Dots Center';
        return view('DotsCenter.main')->with('title', $title);
    }
    public function farmasi()
    {
        $title = 'FARMASI';
        return view('Farmasi.main')->with('title', $title);
    }
    public function logFarmasi()
    {
        $title = 'RIWAYAT TRANSAKSI FARMASI';
        return view('Farmasi.log')->with('title', $title);
    }
    public function gudangFarmasi()
    {
        $title = 'Gudang Farmasi';
        return view('Farmasi.gudangFarmasi.main')->with('title', $title);
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
        $title = 'LABORATORIUM';
        return view('Laboratorium.main')->with('title', $title);
    }
    public function riwayatlab()
    {
        $title = 'Riwayat Laboratorium';
        return view('Laboratorium.RiwayatLab.main')->with('title', $title);
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
}
