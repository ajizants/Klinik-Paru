<?php

namespace App\Http\Controllers;

use App\Models\GiziKunjungan;
use Illuminate\Http\Request;

class GiziKunjunganController extends Controller
{
    public function search(Request $request)
    {
        // Inisialisasi respon dengan status default
        $response = [
            'status' => 'success',
            'message' => '',
            'data' => null,
        ];

        if ($request->has('norm')) {
            // Mencari data berdasarkan 'norm'
            $asesmen = GiziKunjungan::where('norm', $request->norm)
                ->with('dxGizi', 'dxMedis')
                ->get();

            if (count($asesmen) == 0) {
                // Jika tidak ada data ditemukan
                $response['status'] = 'error';
                $response['message'] = 'Data not found';
                return response()->json($response, 404);
            }

            // Menyimpan data ke objek respon
            $response['data'] = $asesmen;
            $response['message'] = 'Data found';
        } else {
            // Mengambil semua data jika 'norm' tidak ada di request
            $asesmen = GiziKunjungan::all();
            $response['data'] = $asesmen;
            $response['message'] = 'All data retrieved';
        }

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public function store(Request $request)
    {

        $item = $request->all();
        $response = [
            'status' => 'success',
            'message' => '',
            'data' => null,
        ];

        // Validasi input
        $validatedData = $request->validate([
            'norm' => 'required|string',
            'notrans' => 'required|string',
            'dokter' => 'required|string',
            'ahli_gizi' => 'required|string',
            'bb' => 'required|numeric',
            'tb' => 'required|numeric',
            'imt' => 'required|numeric',
            'keluhan' => 'required|string',
            'parameter' => 'required|string',
            'dxMedis' => 'required|string',
            'dxGizi' => 'required|string',
            'etiologi' => 'required|string',
            'evaluasi' => 'required|string',
        ]);

        // Menambahkan data baru
        $asesmen = GiziKunjungan::create($validatedData);

        $response['data'] = $asesmen;
        $response['message'] = 'Data added successfully';

        return response()->json($response, 201, [], JSON_PRETTY_PRINT);

    }

    public function update(Request $request, $id)
{
    $response = [
        'status' => 'success',
        'message' => '',
        'data' => null,
    ];

    // Validasi input
    $validatedData = $request->validate([
        'norm' => 'required|string',
        'notrans' => 'required|string',
        'dokter' => 'required|string',
        'ahli_gizi' => 'required|string',
        'bb' => 'required|numeric',
        'tb' => 'required|numeric',
        'imt' => 'required|numeric',
        'keluhan' => 'required|string',
        'parameter' => 'required|string',
        'dxMedis' => 'required|string',
        'dxGizi' => 'required|string',
        'etiologi' => 'required|string',
        'evaluasi' => 'required|string',
    ]);

    // Mencari data berdasarkan ID
    $asesmen = GiziKunjungan::find($id);

    if (!$asesmen) {
        $response['status'] = 'error';
        $response['message'] = 'Data not found';
        return response()->json($response, 404);
    }

    // Memperbarui data
    $asesmen->update($validatedData);

    $response['data'] = $asesmen;
    $response['message'] = 'Data Berhasil diupdate';

    return response()->json($response, 200, [], JSON_PRETTY_PRINT);
}


    public function destroy(Request $request)
    {
        $data = GiziKunjungan::where('id', $request->id)->delete();
        return response()->json([
            'message' => $data ? 'Data Kunjungan Berhasil dihapus' : 'Data not found',
        ], 200);
    }
}
