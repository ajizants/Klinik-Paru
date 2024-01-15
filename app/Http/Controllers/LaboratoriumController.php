<?php

namespace App\Http\Controllers;

use App\Models\LaboratoriumModel;
use App\Models\LayananModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaboratoriumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $notrans = $request->input('notrans');
        $lab = LaboratoriumModel::with('layanan', 'petugas.biodata', 'dokter.biodata')
            ->where('notrans', 'like', '%' . $notrans . '%')
            ->get();
        return response()->json($lab, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for take resource.
     */
    public function layananlab(Request $request)
    {
        $kelas = $request->input('kelas');
        $data = LayananModel::where('kelas', 'like', '%' . $kelas . '%')
            ->where('status', 'like', '%1%')
            ->get();

        $layanan = [];

        foreach ($data as $d) {
            $layanan[] = [
                'idLayanan' => $d->idLayanan,
                'kelas' => $d->kelas,
                'nmLayanan' => $d->nmLayanan,
                'tarif' => $d->tarif,
            ];
        }

        return response()->json(['data' => $layanan], 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function addTransaksi(Request $request)
    {
        // Mendapatkan dataTerpilih dari permintaan
        $dataTerpilih = $request->input('dataTerpilih');

        // Validasi bahwa dataTerpilih harus array dan tidak boleh kosong
        if (!is_array($dataTerpilih) || empty($dataTerpilih)) {
            return response()->json([
                'message' => 'Data terpilih tidak valid atau kosong'
            ], 400);
        }
        // dd($dataTerpilih);
        try {
            // Memulai transaksi database
            DB::beginTransaction();

            // Membuat array untuk menyimpan data yang akan disimpan
            $dataToInsert = [];

            // Looping untuk mengolah dataTerpilih
            foreach ($dataTerpilih as $data) {
                // Validasi data yang diperlukan pada setiap elemen dataTerpilih
                if (isset($data['idLayanan']) && isset($data['notrans'])) {
                    $dataToInsert[] = [
                        'notrans' => $data['notrans'],
                        'norm' => $data['norm'],
                        'petugas' => $data['petugas'],
                        'dokter' => $data['dokter'],
                        'idLayanan' => $data['idLayanan'],
                        'ket' => $data['ket'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                } else {
                    return response()->json([
                        'message' => 'Data tidak lengkap'
                    ], 500);
                }
            }
            // dd($dataToInsert);
            // Simpan data ke database
            LaboratoriumModel::insert($dataToInsert);

            // Commit transaksi database
            DB::commit();

            return response()->json([
                'message' => 'Data berhasil disimpan'
            ], 201);
        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi kesalahan
            DB::rollBack();

            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data'
            ], 500);
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
