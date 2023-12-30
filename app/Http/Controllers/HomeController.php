<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function lte()
    {
        $title = 'Dashboard';
        return view('tamplate.lte')->with('title', $title);
    }
    public function home()
    {
        $title = 'Dashboard';
        return view('dashboard')->with('title', $title);
    }

    public function igd()
    {
        $title = 'IGD';
        return view('igd.main')->with('title', $title);
    }
    public function askep()
    {
        $title = 'ASKEP';
        return view('askep.main')->with('title', $title);
    }
    public function dots()
    {
        $title = 'Dots Center';
        return view('dotscenter.main')->with('title', $title);
    }
    public function farmasi()
    {
        $title = 'FARMASI';
        return view('farmasi.main')->with('title', $title);
    }
    public function logFarmasi()
    {
        $title = 'RIWAYAT TRANSAKSI FARMASI';
        return view('farmasi.log')->with('title', $title);
    }
    public function gudangFarmasi()
    {
        $title = 'Gudang Farmasi';
        return view('farmasi.gudangFarmasi.main')->with('title', $title);
    }
    public function gudangIGD()
    {
        $title = 'Gudang IGD';
        return view('igd.gudangIGD.main')->with('title', $title);
    }
    public function kasir()
    {
        $title = 'KASIR';
        return view('kasir.main')->with('title', $title);
    }
    public function report()
    {
        $title = 'Report IGD';

        return view('report.main')->with('title', $title);
    }
    public function dispenser()
    {
        $title = 'Ambil Antrian';

        return view('dispenser.main')->with('title', $title);
    }
}
