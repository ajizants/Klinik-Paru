<?php

namespace App\Http\Controllers;

use App\Models\LayananModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KasirController extends Controller
{

    public function Layanan(Request $request)
    {

        $query = LayananModel::on('mysql')->where('status', '1');

        // Mengecek apakah request memiliki parameter kelas
        if ($request->has('kelas')) {
            $kelas = $request->input('kelas');
            $query->where('kelas', $kelas);
        }

        $layanan = $query->get();

        return response()->json($layanan, 200, [], JSON_PRETTY_PRINT);
    }

    public function add(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'nmLayanan' => 'required|string|max:255',
            'tarif' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'status' => 'required',
        ]);

        try {
            // Create a new instance of LayananModel with the validated data
            $layanan = LayananModel::create($validatedData);

            // Return a JSON response indicating success
            return response()->json(['message' => 'Data layanan berhasil ditambahkan', 'data' => $layanan], 201);
        } catch (\Exception $e) {
            // Return a JSON response indicating failure
            return response()->json(['message' => 'Data layanan gagal ditambahkan', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            LayananModel::where('idLayanan', $request->input('id'))->delete();
            return response()->json(['message' => 'Data layanan berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
            return response()->json(['message' => 'Data layanan gagal dihapus']);
        }
    }

    public function updateLayanan(Request $request)
    {
        try {
            $data = LayananModel::where('idLayanan', $request->input('id'))->firstOrFail();

            $data->update($request->only(['nmLayanan', 'tarif', 'kelas', 'status']));

            return response()->json(['message' => 'Data layanan berhasil diperbarui']);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return response()->json(['message' => 'Data layanan gagal diperbarui']);
        }
    }

}
