<?php

namespace App\Http\Controllers;

use App\Models\GiziAsesmentModel;
use Illuminate\Http\Request;

class GiziAsesmenAwalController extends Controller
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
            $asesmen = GiziAsesmentModel::where('norm',  $request->norm )
            ->with('kunjungan.dxGizi')->first();

            if ($asesmen == null) {
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
            $asesmen = GiziAsesmentModel::all();
            $response['data'] = $asesmen;
            $response['message'] = 'All data retrieved';
        }

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public function store(Request $request)
    {

        $item = $request->all();
        $norm = $request->norm;
        // dd($item);

        // Cari data yang sudah ada berdasarkan 'notrans' dan 'norm'
        $giziAsesment = GiziAsesmentModel::where('norm', $norm)
            ->first();

        if ($giziAsesment) {
            // Jika data ditemukan, perbarui data
            $giziAsesment->update($item);
        } else {
            // Jika data tidak ditemukan, buat data baru
            GiziAsesmentModel::create($item);
        }

        return response()->json([
            'message' => $giziAsesment ? 'Data Asesment Awal Berhasil diupdate' : 'Data Asesment Awal Berhasil disimpan',
        ], 200);

    }

    public function destroy(Request $request)
    {
        $asesmen = GiziAsesmentModel::where('norm', $request->norm)->delete();
        return response()->json([
            'message' => $asesmen ? 'Data Asesment Berhasil dihapus' : 'Data not found',
        ], 200);
    }
}
