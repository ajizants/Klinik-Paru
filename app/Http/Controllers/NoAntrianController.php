<?php

namespace App\Http\Controllers;

use App\Models\NoAntrianModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NoAntrianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function lastNoAntri(Request $request)
    {
        // Ambil tanggal dari request atau gunakan tanggal sekarang jika tidak ada
        $tgl = $request->input('tgl', now()->toDateString());
        // dd($tgl);
        // Ambil nomor antrian terakhir berdasarkan tanggal
        $antrian = NoAntrianModel::on('antrian')
            ->whereDate('Tanggal', $tgl)
            ->orderBy('NoAntri', 'desc')
            ->first();

        return response()->json($antrian, 200, [], JSON_PRETTY_PRINT);
    }
    private function noakhir(Request $request)
    {
        // Ambil tanggal dari request atau gunakan tanggal sekarang jika tidak ada
        $tgl = $request->input('tgl', now()->toDateString());
        // dd($tgl);
        // Ambil nomor antrian terakhir berdasarkan tanggal
        $antrian = NoAntrianModel::on('antrian')
            ->whereDate('Tanggal', $tgl)
            ->orderBy('NoAntri', 'desc')
            ->first();

        return response()->json($antrian, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $no = $request->input('noAntri');
        $tgl = Carbon::now();
        $panggil = '0';
        $selesai = '0';
        $loket = '0';
        $lewati = '0';
        $jenis = $request->input('jenis');

        $Antrian = new NoAntrianModel;
        $Antrian->NoAntri = $no;
        $Antrian->Tanggal = $tgl;
        $Antrian->Panggil = $panggil;
        $Antrian->Selesai = $selesai;
        $Antrian->LOKET = $loket;
        $Antrian->LEWATI = $lewati;
        $Antrian->jenis = $jenis;
        $Antrian->save();

        $responseMessage = [
            'message' => 'Data berhasil disimpan',
            'noAntri' => $Antrian->NoAntri,
            'jenis' => $Antrian->jenis,
            'tanggal' => $Antrian->created_at,
        ];

        return response()->json($responseMessage);
        // return response()->json($Antrian, 200, ['message' => 'Data berhasil disimpan'], JSON_PRETTY_PRINT);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $antrian = NoAntrianModel::on('antrian')->get();

        // Format tanggal menggunakan Carbon
        foreach ($antrian as $item) {
            $tanggal = Carbon::parse($item->tanggal)->format('Y-m-d H:i:s');
        }

        return response()->json($antrian, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NoAntrianModel $noAntrianModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NoAntrianModel $noAntrianModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NoAntrianModel $noAntrianModel)
    {
        //
    }
}
