<?php
namespace App\Http\Controllers;

use App\Models\IGDTransModel;
use App\Models\SpirometriModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SpirometriModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tgl = Carbon::parse($request->tgl)->format('Y-m-d') ?? date('Y-m-d');
        // dd($tgl);/
        $html = $this->getTableHasil($tgl);
        return response()->json($html);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $savedData = SpirometriModel::create($data);
        $tgl       = Carbon::parse($request->tgl)->format('Y-m-d') ?? date('Y-m-d');
        $res       = [
            'status'  => 'success',
            'message' => 'Data berhasil disimpan',
            'data'    => $savedData,
            'table'   => $this->getTableHasil($tgl),
        ];
        return response()->json($res, 200, [], JSON_PRETTY_PRINT);

    }

    private function getTableHasil($tgl)
    {
        $data = IGDTransModel::with('pasien', 'tindakan', 'pelaksana.biodata', 'dok.biodata', 'hasilSpiro')
            ->where('created_at', 'like', '%' . $tgl . '%')
            ->where('kdTind', '27')
            ->get();
        // return $data;
        // $data=[];
        $html = view('IGD.Trans.antrianSpiro', compact('data'))->render();
        return $html;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SpirometriModel $spirometriModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpirometriModel $spirometriModel)
    {
        // Validasi atau filter kolom yang boleh diupdate
        $data = $request->only([
            'norm', 'notrans', 'pred_fvc', 'value_fvc',
            'pred_fev1', 'value_fev1', 'pred_fev1_fvc', 'value_fev1_fvc',
            'dokter', 'petugas', // dan lainnya sesuai kolom
        ]);

        $spirometriModel->update($data);

        // Tanggal default: hari ini jika tidak ada
        $tgl = $request->filled('tgl')
        ? Carbon::parse($request->tgl)->format('Y-m-d')
        : now()->format('Y-m-d');

        // Ambil ulang data antrian berdasarkan tanggal
        $data = IGDTransModel::with('pasien', 'tindakan', 'pelaksana.biodata', 'dok.biodata', 'hasilSpiro')
            ->whereDate('created_at', $tgl)
            ->where('kdTind', '27')
            ->get();

        // Render HTML table
        $html = view('IGD.Trans.antrianSpiro', compact('data'))->render();

        return response()->json([
            'message' => 'Data berhasil diupdate',
            'status'  => 'success',
            'data'    => $spirometriModel,
            'table'   => $html,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpirometriModel $spirometriModel)
    {
        //
    }
}
