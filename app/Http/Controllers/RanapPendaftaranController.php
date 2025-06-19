<?php

namespace App\Http\Controllers;

use App\Models\PegawaiModel;
use App\Models\RanapPendaftaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RanapPendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home()
    {
        $title = 'Dashboard Pendaftaran';
        return view('Ranap.Pendaftaran.main', compact('title'));
    }
    public function index()
    {
        $title = 'Ranap Pendaftaran';
        $pegawaiModel = new PegawaiModel();
        $dokter = $pegawaiModel->olahPegawai([1, 7, 8]);
        $dokter = array_map(function ($item) {
            return (object) $item;
        }, $dokter);
        $petugas = $pegawaiModel->olahPegawai([16, 17]);
        $petugas = array_map(function ($item) {
            return (object) $item;
        }, $petugas);
        // return ['dokter' => $dokter, 'petugas' => $petugas];
        return view('Ranap.Pendaftaran.main', compact('title', 'dokter', 'petugas'));
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
        // Validasi awal (optional tapi sangat direkomendasikan)
        $request->validate([
            'pasien_no_rm' => 'required|string|max:6',
            'jaminan' => 'required|string',
            'tgl_masuk' => 'required|date',
            'dokter' => 'required|string',
            'admin' => 'required|string',
            'status_pulang' => 'nullable|string',
            'ruang' => 'required|string',
        ]);

        try {
            $norm = $request->pasien_no_rm;
            $jaminan = $request->jaminan;
            $statusPulang = $request->status_pulang ?? null;
            $tglMasuk = Carbon::parse($request->tgl_masuk)->format('Y-m-d');
            $dpjp = $request->dokter;
            $ruang = $request->ruang;
            $admin = $request->admin;
            $tgl = Carbon::parse($tglMasuk);

            $cekData = RanapPendaftaran::where('norm', $norm)->where('tgl_masuk', $tglMasuk)->first();

            if ($cekData) {
                return response()->json([
                    'status' => 'error',
                    'success' => false,
                    'message' => 'Data pasien dengan No RM ' . $norm . ' dan tanggal masuk ' . $tglMasuk . ' sudah terdaftar.',
                ], 400);
            }

            $jumlahBulanIni = RanapPendaftaran::whereMonth('created_at', $tgl->month)
                ->whereYear('created_at', $tgl->year)
                ->count() + 1;
            $nomorUrut = str_pad($jumlahBulanIni, 4, '0', STR_PAD_LEFT);

            // Buat No Transaksi: RI + tglMasuk (tanpa -) + norm + urutan
            $noTrans = 'RI' . str_replace('-', '', $tglMasuk) . $norm . $nomorUrut;

            // Simpan data ke DB
            $ranapPendaftaran = new RanapPendaftaran();
            $ranapPendaftaran->norm = $norm;
            $ranapPendaftaran->notrans = $noTrans;
            $ranapPendaftaran->jaminan = $jaminan;
            $ranapPendaftaran->status_pulang = $statusPulang;
            $ranapPendaftaran->tgl_masuk = $tglMasuk;
            $ranapPendaftaran->dpjp = $dpjp;
            $ranapPendaftaran->ruang = $ruang;
            $ranapPendaftaran->admin = $admin;
            $ranapPendaftaran->save();

            return response()->json([
                'message' => 'Data berhasil disimpan',
                'success' => true,
                'data' => $ranapPendaftaran,
            ], 200);

        } catch (\Exception $e) {
            // Log error dan kirim response yang aman
            Log::error('Gagal simpan rawat inap: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RanapPendaftaran $ranapPendaftaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RanapPendaftaran $ranapPendaftaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RanapPendaftaran $ranapPendaftaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RanapPendaftaran $ranapPendaftaran)
    {
        //
    }
}
