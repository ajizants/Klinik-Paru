<?php

namespace App\Http\Controllers;

use App\Models\PegawaiModel;
use App\Models\PromkesModel;
use Illuminate\Http\Request;

class PromkesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Promkes';

        $nip = [4, 5, 9999];
        $data = PegawaiModel::with(['biodata', 'jabatan'])
            ->whereNotIn('nip', $nip)->get();

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

        $hasilKegiatan = PromkesModel::all();
        return view('Promkes.main', compact('hasilKegiatan', 'pegawai'))->with('title', $title);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(PromkesModel $promkesModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PromkesModel $promkesModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PromkesModel $promkesModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PromkesModel $promkesModel)
    {
        //
    }
}
