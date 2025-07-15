<?php
namespace App\Http\Controllers;

use App\Imports\HariLiburImport;
use App\Models\HariLibur;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HariLiburController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($tahun = null)
    {
        $hariLiburs = HariLibur::whereYear('tanggal', $tahun)->get();
        // dd($hariLiburs);
        $dataHariLibur = view('TataUsaha.Cuti.tabelHariLibur', compact('hariLiburs'))->render();
        // return $dataHariLibur;
        return response()->json([
            'message' => 'Data cuti ditemukan.',
            'success' => true,
            'html'    => $dataHariLibur,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal'    => 'required|date',
            'keterangan' => 'required|string',
        ]);

        $hariLibur = HariLibur::create($validatedData);

        $hariLiburs = HariLibur::whereYear('tanggal', now()->year)->get();
        // dd($hariLiburs);
        $dataHariLibur = view('TataUsaha.Cuti.tabelHariLibur', compact('hariLiburs'))->render();

        return response()->json([
            'message' => 'Data cuti berhasil ditambahkan.',
            'success' => true,
            'data'    => $hariLibur,
            'html'    => $dataHariLibur,
        ]);
    }

    public function HariLiburKolektif(Request $request)
    {
        $request->validate([
            'file_tambahan_hari_libur' => 'required|file|mimes:xls,xlsx',
        ]);
        // dd($request->all());

        try {
            Excel::import(new HariLiburImport, $request->file('file_tambahan_hari_libur'));

            $hariLiburs = HariLibur::whereYear('tanggal', now()->year)->get();

            $dataHariLibur = view('TataUsaha.Cuti.tabelHariLibur', compact('hariLiburs'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Data tambahan hari libur berhasil diimpor.',
                'data'    => $hariLiburs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimpor file: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(HariLibur $hariLibur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HariLibur $hariLibur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HariLibur $hariLibur)
    {
        //
    }
}
