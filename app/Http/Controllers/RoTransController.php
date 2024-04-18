<?php

namespace App\Http\Controllers;

use App\Models\ROTransaksi;
use Illuminate\Http\Request;

class RoTransController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $data = ROTransaksi::whereDate('tgltrans', $date)->get();

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
        // return response()->json($formattedData, 200, [], JSON_PRETTY_PRINT);
    }

    // Metode untuk menampilkan form tambah data
    public function create()
    {
        return view('RO.Master.create');
    }

    // Metode untuk menyimpan data baru
    public function store(Request $request)
    {
        $norm = $request->input('norm');
        $notrans = $request->input('notrans');
        $kdTind = $request->input('kdTind');
        $petugas = $request->input('petugas');
        $dokter = $request->input('dokter');

    }

    // Metode untuk menampilkan data yang akan diedit
    public function edit($id)
    {
        // Logika untuk menampilkan data yang akan diedit
    }

    // Metode untuk menyimpan data yang sudah diedit
    public function update(Request $request, $id)
    {
        // Logika validasi dan penyimpanan data yang sudah diedit disini
    }

    // Metode untuk menghapus data
    public function destroy($id)
    {
        // Logika penghapusan data disini
    }
}
