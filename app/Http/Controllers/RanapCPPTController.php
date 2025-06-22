<?php
namespace App\Http\Controllers;

use App\Models\BMHPModel;
use App\Models\DiagnosaModel;
use App\Models\GiziDxSubKelasModel;
use App\Models\LayananModel;
use App\Models\PegawaiModel;
use App\Models\RanapCPPT;
use App\Models\RanapPendaftaran;
use Illuminate\Http\Request;

class RanapCPPTController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title        = 'Ranap CPPT';
        $pegawaiModel = new PegawaiModel();
        $dokter       = $pegawaiModel->olahPegawai([1, 7, 8]);
        $dokter       = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        $petugas = $pegawaiModel->olahPegawai([1, 7, 8, 9, 10, 12, 14, 15, 23]);
        $petugas = array_map(function ($item) {
            return (object) $item;
        }, $petugas);

        $sub                   = GiziDxSubKelasModel::with('domain')->get();
        $dxMed                 = DiagnosaModel::get();
        $modelRanapPendaftaran = new RanapPendaftaran();

        $lModel   = new LayananModel();
        $tindakan = $lModel->layanans([2, 3, 5, 6]);
        $bmhp     = BMHPModel::all();

        $dataPasien = $modelRanapPendaftaran->getPasienRanap();
        return view('Ranap.Cppt.main', compact('title', 'dokter', 'petugas', 'sub', 'dxMed', 'dataPasien', 'tindakan', 'bmhp'));
    }

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
    public function show(RanapCPPT $ranapCPPT)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RanapCPPT $ranapCPPT)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RanapCPPT $ranapCPPT)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RanapCPPT $ranapCPPT)
    {
        //
    }

    public function simpan(Request $request)
    {
        $obat_ids   = $request->input('obat_id');
        $signa1     = $request->input('signa_1');
        $signa2     = $request->input('signa_2');
        $keterangan = $request->input('keterangan');

        foreach ($obat_ids as $index => $obat_id) {
            ObatTindakan::create([
                'obat_id'    => $obat_id,
                'signa_1'    => $signa1[$index],
                'signa_2'    => $signa2[$index],
                'keterangan' => $keterangan[$index],
            ]);
        }

        return back()->with('success', 'Data tindakan berhasil disimpan.');
    }

}
