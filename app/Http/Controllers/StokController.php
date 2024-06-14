<?php

namespace App\Http\Controllers;

use App\Models\BmhpAddStokModel;
use App\Models\GudangFarmasiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function addstokbmhp(Request $request)
    {
        $kdBmhp = $request->input('kdBmhp');
        $jml = $request->input('jml');
        $tglED = $request->input('tglED');

        if ($kdBmhp !== null) {
            $transaksibmhp = new BmhpAddStokModel();
            $transaksibmhp->kdBmhp = $kdBmhp;
            $transaksibmhp->masuk = $jml;
            $transaksibmhp->tglED = $tglED;
            $transaksibmhp->save();

            return response()->json(['message' => 'Data berhasil disimpan']);
        } else {
            return response()->json(['message' => 'kdBmhp tidak valid'], 400);
        }
    }

    public function stokbmhp()
    {

        $tind = BmhpAddStokModel::on('mysql')
        // ->whereNotNull('stok_akhir')
        // ->where('stok_akhir', '>', 0)
            ->get();
        return response()->json($tind, 200, [], JSON_PRETTY_PRINT);
    }

    public function stokOpnameGudang()
    {
        // Mengambil semua data dari GudangFarmasiModel
        $gudangData = GudangFarmasiModel::all();

        // Mulai transaksi database untuk memastikan keamanan data
        DB::beginTransaction();

        try {
            // Iterasi melalui setiap baris data dan memperbarui stok_awal dan mengosongkan masuk, keluar, stok_akhir
            foreach ($gudangData as $gudangRow) {
                $gudangRow->update([
                    'stok_awal' => $gudangRow->stok_akhir,
                    'masuk' => 0,
                    'keluar' => 0,

                ]);
            }

            // Commit transaksi jika berhasil
            DB::commit();

            return response()->json(['message' => 'Data stok berhasil diperbarui'], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui data stok'], 500);
        }
    }

    // public function moveDataToLogGudang()
    // {
    //     // Mengambil semua data dari GudangFarmasiModel
    //     $gudangData = GudangFarmasiModel::on('mysql')->get();

    //     // Mulai transaksi database untuk memastikan keamanan data
    //     DB::beginTransaction();

    //     try {
    //         // Mengubah data ke dalam bentuk array
    //         $dataToInsert = $gudangData->map(function ($row) {
    //             return [
    //                 'barcode' => $row->barcode,
    //                 'nmObat' => $row->nmObat,
    //                 'sumber' => $row->sumber,
    //                 'jenis' => $row->jenis,
    //                 'stok_awal' => $row->stok_awal,
    //                 'masuk' => $row->masuk,
    //                 'keluar' => $row->keluar,
    //                 'stok_akhir' => $row->stok_akhir,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ];
    //         })->toArray();

    //         // Menjalankan operasi bulk insert
    //         loggu::insert($dataToInsert);

    //         // Opsional: Hapus data dari GudangFarmasiModel setelah pemindahan
    //         // GudangFarmasiModel::truncate();

    //         // Commit transaksi jika berhasil
    //         DB::commit();

    //         return response()->json(['message' => 'Data berhasil dipindahkan ke LogStokFarmasiModel'], 200);
    //     } catch (\Exception $e) {
    //         // Rollback transaksi jika terjadi kesalahan
    //         DB::rollBack();

    //         return response()->json(['message' => 'Terjadi kesalahan saat memindahkan data'], 500);
    //     }
    // }
}
