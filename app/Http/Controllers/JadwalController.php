<?php
namespace App\Http\Controllers;

use App\Imports\JadwalImport;
use App\Models\JadwalModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class JadwalController extends Controller
{
    public function index()
    {
        $title = 'Jadwal Karyawan';
        return view('Jadwal.main')->with('title', $title);
    }

    public function getTemplate()
    {
        $filePath = 'public/templates/format_jadwal_karyawan.xlsx';

        if (Storage::exists($filePath)) {
            return Storage::download($filePath, 'format_jadwal_karyawan.xlsx');
        }

        return response()->json(['error' => 'File tidak ditemukan'], 404);
    }

    public function import(Request $request)
    {
        try {
            // Validasi request
            $request->validate([
                'file'    => 'required|mimes:xlsx,xls',
                'bulan'   => 'required',
                'tahun'   => 'required',
                'jabatan' => 'required',
            ]);

            // Ambil data dari request
            $file       = $request->file('file');
            $bulan      = $request->bulan;
            $tahun      = $request->tahun;
            $jabatan    = $request->jabatan;
            $tahunBulan = "$tahun-$bulan";

            // Cek apakah file valid
            if (! $file->isValid()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'File tidak valid atau rusak.',
                ], 400, [], JSON_PRETTY_PRINT);
            }

            // Proses import data
            Excel::import(new JadwalImport($tahunBulan, $jabatan), $file);

            // Ambil data yang baru diimport
            $data = JadwalModel::where('jabatan', $jabatan)->get();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupload',
                'data'    => $data,
            ], 200, [], JSON_PRETTY_PRINT);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Error jika ada kesalahan validasi di dalam file Excel
            return response()->json([
                'success' => false,
                'message' => 'Format file tidak sesuai. Harap periksa kembali template.',
                'errors'  => $e->failures(),
            ], 422, [], JSON_PRETTY_PRINT);

        } catch (\Throwable $e) {
            // Logging error untuk debugging
            Log::error('Error saat mengimpor file: ' . $e->getMessage());

            // Response jika terjadi error tak terduga
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat mengupload file.',
                'error'   => $e->getMessage(),
            ], 500, [], JSON_PRETTY_PRINT);
        }
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalModel::find($id);
        // dd($jadwal);
        if (! $jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        // Update data jadwal
        $jadwal->update([
            'nama'    => $request->nama,
            'tanggal' => $request->tanggal,
            'shift'   => $request->shift,
            'jabatan' => $request->jabatan,
            'nip'     => $request->nip,
        ]);

        // Ambil semua jadwal terbaru
        $lists = JadwalModel::all();

        return response()->json([
            'message' => 'Jadwal berhasil diperbarui',
            'data'    => $jadwal,
            'lists'   => $lists,
        ]);
    }

    public function destroy($id)
    {
        try {
            $data = JadwalModel::findOrFail($id);

            // Simpan data sebelum dihapus
            $res = [
                'nama'    => $data->nama,
                'tanggal' => $data->tanggal,
                'shift'   => $data->shift,
            ];

            $data->delete(); // Jika menggunakan soft delete
                             // $data->forceDelete(); // Jika ingin menghapus permanen

                                         // Ambil data terbaru setelah penghapusan
            $lists = JadwalModel::all(); // Atau gunakan getJadwal([]) jika ada filter khusus

            return response()->json([
                'message' => 'Data berhasil dihapus',
                'data'    => $res,
                'lists'   => $lists,
            ], 200);

        } catch (\Exception $e) {
            Log::error("Gagal menghapus data: " . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data'], 500);
        }
    }

    public function getJadwal(Request $request)
    {
        $request = $request->all();
        $model   = new JadwalModel();
        $data    = $model->getJadwal($request);
        return response()->json([
            'success' => $data->isNotEmpty(),
            'message' => $data->isNotEmpty() ? 'Data berhasil diambil.' : 'Tidak ada data jadwal.',
            'data'    => $data,
        ], $data->isNotEmpty() ? 200 : 400, [], JSON_PRETTY_PRINT);
    }

}
