<?php
namespace App\Http\Controllers;

use App\Models\DiagnosaMapModel;
use Illuminate\Http\Request;

class DiagnosaMappingController extends Controller
{
    public function index()
    {
        $dxMaps = DiagnosaMapModel::orderBy('updated_at', 'desc')->get();
        return response()->json($dxMaps, 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'kdDx'     => 'required|string|max:10', // Contoh validasi: maksimal 10 karakter
            'diagnosa' => 'required|string|max:255',
            'mapping'  => 'nullable|string|max:255',
        ]);

        // Simpan data ke model DiagnosaMapModel
        $diagnosaMap           = new DiagnosaMapModel();
        $diagnosaMap->kdDx     = $validatedData['kdDx'];
        $diagnosaMap->diagnosa = $validatedData['diagnosa'];
        $diagnosaMap->mapping  = $validatedData['mapping'] ?? null; // Nilai default jika mapping tidak ada
        $diagnosaMap->save();
        $dxMaps = DiagnosaMapModel::orderBy('updated_at', 'desc')->get();

        // Berikan respon (misalnya redirect atau JSON response)
        return response()->json([
            'success' => true,
            'message' => 'Data diagnosa berhasil disimpan',
            'data'    => $diagnosaMap,
            'dxMaps'  => $dxMaps,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'kdDx'     => 'required|string|max:20',
                'diagnosa' => 'required|string|max:255',
                'mapping'  => 'nullable|string|max:255',
            ]);
            $kdDx = $validatedData['kdDx'];

            // Cari data berdasarkan kdDx
            $diagnosaMap = DiagnosaMapModel::where('kdDx', $kdDx)->firstOrFail();

            // Perbarui data
            $diagnosaMap->update($validatedData);
            $dxMaps = DiagnosaMapModel::orderBy('updated_at', 'desc')->get();

            // Berikan JSON response jika berhasil
            return response()->json([
                'success' => true,
                'message' => 'Data diagnosa berhasil diperbarui',
                'data'    => $diagnosaMap,
                'dxMaps'  => $dxMaps,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani kesalahan validasi
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Tangani jika data tidak ditemukan
            return response()->json([
                'success' => false,
                'message' => 'Data diagnosa tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            // Tangani kesalahan umum lainnya
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $kdDx)
    {
        try {
            // Cari data berdasarkan kdDx
            $diagnosaMap = DiagnosaMapModel::where('kdDx', $kdDx)->firstOrFail();

            // Hapus data
            $diagnosaMap->delete();
            $dxMaps = DiagnosaMapModel::orderBy('updated_at', 'desc')->get();

            // Berikan respon (redirect atau JSON response)
            // Berikan JSON response jika berhasil
            return response()->json([
                'success' => true,
                'message' => 'Data diagnosa berhasil dihapus',
                'data'    => $diagnosaMap,
                'dxMaps'  => $dxMaps,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Tangani jika data tidak ditemukan
            return response()->json([
                'success' => false,
                'message' => 'Data diagnosa tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            // Tangani kesalahan umum lainnya
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

}
