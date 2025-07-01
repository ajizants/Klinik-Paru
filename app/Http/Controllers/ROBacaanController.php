<?php
namespace App\Http\Controllers;

use App\Models\KunjunganWaktuSelesai;
use App\Models\ROBacaan;
use Illuminate\Http\Request;

class ROBacaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Bacaan RO";
        return view('RO.Bacaan.main')->with('title', $title);
    }

    private function dataKonsulRo()
    {
        $data = KunjunganWaktuSelesai::with('pasienRo', 'hasilBacaan', 'pasienRo.dokter.pegawai')
            ->where('konsul_ro', '1')
            ->get();

        foreach ($data as $item) {
            $item['hasilKonsul'] = $item->hasil_bacaan === null ? "Belum Selesai" : "Sudah Selesai";
        }

        // Filter hanya data yang memiliki pasienRo
        $data = $data->filter(function ($item) {
            return $item->pasienRo !== null;
        })->values(); // reset index agar hasilnya rapi

        return $data;
    }

    public function getListBacaan()
    {
        $data = $this->dataKonsulRo();
        return $data;
    }
    public function listBacaan()
    {
        $data = $this->dataKonsulRo();
        return view('RO.Bacaan.list')->with('data', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ROBacaan $rOBacaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ROBacaan $rOBacaan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ROBacaan $rOBacaan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ROBacaan $rOBacaan)
    {
        //
    }
}
