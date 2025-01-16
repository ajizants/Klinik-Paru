<?php

namespace App\Http\Controllers;

use App\Models\KasirPenutupanKasModel;
use App\Models\KasirSetoranModel;
use Illuminate\Http\Request;

class KasirPenutupanKasController extends Controller
{
    public function data(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $totalPendapatan = 0;
        $totalPengeluaran = 0;
        $saldo_bku = 0;

        $model = new KasirSetoranModel();
        $penerimaan = $model->penerimaan($tahun, $bulan);
        $pengeluaran = $model->pengeluaran($tahun, $bulan);
        $dataPenutupan = KasirPenutupanKasModel::where('tanggal_sekarang', 'like', '%' . $tahun . '%')->get();
        // return $data;
        foreach ($penerimaan as $d) {
            $totalPendapatan += $d->pendapatan;
        }
        foreach ($pengeluaran as $d) {
            $totalPengeluaran += $d->pendapatan;
        }

        $saldo_bku = $totalPendapatan - $totalPengeluaran;

        $res = [
            'total_penerimaan' => $totalPendapatan,
            'total_pengeluaran' => $totalPengeluaran,
            'saldo_bku' => $saldo_bku,
            'penerimaan' => $penerimaan,
            'pengeluaran' => $pengeluaran,
            'data' => $dataPenutupan,
        ];

        return $res;
    }

    public function store(Request $request)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'tanggal_sekarang' => 'required|date',
            'tanggal_lalu' => 'required|date',
            'petugas' => 'required|string|max:255',
            'total_penerimaan' => 'required|string', // To handle formatted currency input
            'total_pengeluaran' => 'required|string', // To handle formatted currency input
            'saldo_bku' => 'required|string', // To handle formatted currency input
            'saldo_kas' => 'required|string', // To handle formatted currency input
            'selisih_saldo' => 'required|string', // To handle formatted currency input
            'kertas100k' => 'nullable|integer',
            'kertas50k' => 'nullable|integer',
            'kertas20k' => 'nullable|integer',
            'kertas10k' => 'nullable|integer',
            'kertas5k' => 'nullable|integer',
            'kertas2k' => 'nullable|integer',
            'kertas1k' => 'nullable|integer',
            'logam1k' => 'nullable|integer',
            'logam500' => 'nullable|integer',
            'logam200' => 'nullable|integer',
            'logam100' => 'nullable|integer',
        ]);

        // Convert formatted currency values to numbers
        $total_penerimaan = $this->convertCurrencyToNumber($request->input('total_penerimaan'));
        $total_pengeluaran = $this->convertCurrencyToNumber($request->input('total_pengeluaran'));
        $saldo_bku = $this->convertCurrencyToNumber($request->input('saldo_bku'));
        $saldo_kas = $this->convertCurrencyToNumber($request->input('saldo_kas'));
        $selisih_saldo = abs($saldo_bku - $saldo_kas);
        try {
            // Simpan data ke database
            $model = new KasirPenutupanKasModel();

            // Fill the validated data
            $model->fill($validatedData);

            // Assign converted currency values
            $model->total_penerimaan = $total_penerimaan;
            $model->total_pengeluaran = $total_pengeluaran;
            $model->saldo_bku = $saldo_bku;
            $model->saldo_kas = $saldo_kas;
            $model->selisih_saldo = $selisih_saldo;

            // Save the model
            $model->save();
            $data = $model->all();
            // Kembalikan respons sukses
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan.',
                'data' => $data,
            ], 201);
        } catch (\Exception $e) {
            // Handle exception if something goes wrong
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Helper function to convert currency formatted strings to numbers
    private function convertCurrencyToNumber($currency)
    {
        // Remove 'Rp.' and replace dots (thousand separators) with nothing
        return floatval(str_replace(['Rp.', '.', ','], '', $currency));
    }

    public function update(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'id' => 'required|exists:t_kasir_penutupanKas,id',
            'tanggal_sekarang' => 'required|date',
            'tanggal_lalu' => 'required|date',
            'petugas' => 'required|string|max:255',
            'total_penerimaan' => 'required|numeric',
            'total_pengeluaran' => 'required|numeric',
            'saldo_bku' => 'required|numeric',
            'saldo_kas' => 'required|numeric',
            'selisih_saldo' => 'required|numeric',
            'kertas100k' => 'nullable|integer',
            'kertas50k' => 'nullable|integer',
            'kertas20k' => 'nullable|integer',
            'kertas10k' => 'nullable|integer',
            'kertas5k' => 'nullable|integer',
            'kertas2k' => 'nullable|integer',
            'kertas1k' => 'nullable|integer',
            'logam1k' => 'nullable|integer',
            'logam500' => 'nullable|integer',
            'logam200' => 'nullable|integer',
            'logam100' => 'nullable|integer',
        ]);

        try {
            // Cari data berdasarkan ID
            $model = KasirPenutupanKasModel::findOrFail($validatedData['id']);

            // Update data
            $model->update($validatedData);

            // Kembalikan respons sukses
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diperbarui.',
                'data' => $model,
            ], 200);
        } catch (\Exception $e) {
            // Tangani kesalahan dan kembalikan respons gagal
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'id' => 'required|exists:t_kasir_penutupanKas,id',
            'tahun' => 'required|numeric',
        ]);
        $tahun = $validatedData['tahun'];
        // return $validatedData;
        try {
            // Cari data berdasarkan ID
            $model = KasirPenutupanKasModel::findOrFail($validatedData['id']);

            // Hapus data
            $model->delete();
            $data = KasirPenutupanKasModel::where('tanggal_sekarang', 'like', '%' . $tahun . '%')->get();

            // Kembalikan respons sukses
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus.',
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            // Tangani kesalahan dan kembalikan respons gagal
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
