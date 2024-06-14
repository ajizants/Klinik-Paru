<?php

namespace App\Http\Controllers;

use App\Models\RoHasilModel;
use App\Models\ROTransaksiHasilModel;
use App\Models\ROTransaksiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Sesuaikan dengan nama model Anda

class ROTransaksiController extends Controller
{

    public function dataTransaksiRo(Request $request)
    {
        $tglAwal = $request->input('tglAwal');
        $tglAkhir = $request->input('tglAkhir');
        $norm = $request->input('norm');
        $norm = str_pad($norm, 6, '0', STR_PAD_LEFT);
        $data = ROTransaksiModel::with('film', 'foto', 'proyeksi', 'mesin', 'kv', 'ma', 's')
            ->when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
                return $query->where('norm', $norm);
            })
            ->whereBetween('tgltrans', [$tglAwal, $tglAkhir])
            ->get();
        if ($data->isEmpty()) {
            return response()->json(['message' => 'Data pasien tidak ditemukan'], 404, [], JSON_PRETTY_PRINT);
        } else {
            return response()->json(['data' => $data], 200, [], JSON_PRETTY_PRINT);
        }

    }

    // public function addTransaksiRo(Request $request)
    // {
    //     try {
    //         // Buat instance baru dari model ROTransaksiModel
    //         $transaksi = new ROTransaksiModel();

    //         // Isi properti model dengan data dari permintaan
    //         $transaksi->notrans = $request->input('notrans');
    //         $transaksi->norm = $request->input('norm');
    //         $transaksi->tgltrans = $request->input('tglRo');
    //         $transaksi->noreg = $request->input('noreg');
    //         $transaksi->kdFoto = $request->input('kdFoto');
    //         $transaksi->ma = $request->input('ma');
    //         $transaksi->kv = $request->input('kv');
    //         $transaksi->s = $request->input('s');
    //         $transaksi->jmlExpose = $request->input('jmlExpose');
    //         $transaksi->jmlFilmDipakai = $request->input('jmlFilmDipakai');
    //         $transaksi->jmlFilmRusak = $request->input('jmlFilmRusak');
    //         $transaksi->kdMesin = $request->input('kdMesin');
    //         $transaksi->kdProyeksi = $request->input('kdProyeksi');
    //         $transaksi->layanan = $request->input('layanan');
    //         // $transaksi->p_rontgen = $request->input('p_rontgen');
    //         // $transaksi->dokter = $request->input('dokter');

    //         // Simpan data ke dalam database
    //         $transaksi->save();

    //         // Jika ingin memberikan respons JSON, bisa seperti ini:

    //         // Mengunggah file gambar
    //         if ($request->hasFile('gambar')) {
    //             // Mendapatkan file yang diunggah
    //             $gambar = $request->file('gambar');

    //             // Menyimpan file gambar ke dalam direktori yang ditentukan
    //             // $gambarPath = $gambar->store('172.16.10.88/ro/file', 'public');
    //             $gambarPath = $gambar->store('hasilRo', 'ro_storage');

    //             $roTransaksiHasiFoto = new ROTransaksiHasilModel();
    //             $roTransaksiHasiFoto->norm = $request->input('norm');
    //             $roTransaksiHasiFoto->tanggal = $request->input('tglRo');
    //             $roTransaksiHasiFoto->foto = $gambarPath; // Menyimpan path foto dalam database
    //             $roTransaksiHasiFoto->save();
    //         }

    //         return response()->json(['message' => 'Data berhasil disimpan'], 200);

    //     } catch (\Exception $e) {
    //         // Tangani kesalahan
    //         return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()], 500);
    //     }
    // }
    public function addTransaksiRo(Request $request)
    {
        try {
            // Buat instance baru dari model ROTransaksiModel
            $transaksi = new ROTransaksiModel();

            // Isi properti model dengan data dari permintaan
            $transaksi->notrans = $request->input('notrans');
            $transaksi->norm = $request->input('norm');
            $transaksi->tgltrans = $request->input('tglRo');
            $transaksi->noreg = $request->input('noreg');
            $transaksi->pasienRawat = $request->input('pasienRawat');
            $transaksi->kdFoto = $request->input('kdFoto');
            $transaksi->ma = $request->input('ma');
            $transaksi->kv = $request->input('kv');
            $transaksi->s = $request->input('s');
            $transaksi->jmlExpose = $request->input('jmlExpose');
            $transaksi->jmlFilmDipakai = $request->input('jmlFilmDipakai');
            $transaksi->jmlFilmRusak = $request->input('jmlFilmRusak');
            $transaksi->kdMesin = $request->input('kdMesin');
            $transaksi->kdProyeksi = $request->input('kdProyeksi');
            $transaksi->layanan = $request->input('layanan');

            // Tambahkan informasi debug
            Log::info('Data yang akan disimpan:', $transaksi->toArray());

            // Simpan data ke dalam database
            $transaksi->save();

            // Jika ingin memberikan respons JSON, bisa seperti ini:

            // Mengunggah file gambar
            if ($request->hasFile('gambar')) {
                // Mendapatkan file yang diunggah
                $gambar = $request->file('gambar');

                // Tambahkan informasi debug
                Log::info('Informasi file gambar:', [
                    'nama' => $gambar->getClientOriginalName(),
                    'ukuran' => $gambar->getSize(),
                    'mime_type' => $gambar->getMimeType(),
                ]);

                // Menyimpan file gambar ke dalam direktori yang ditentukan
                $gambarPath = $gambar->store('hasilRo', 'ro_storage');

                // Tambahkan informasi debug
                Log::info('Path gambar yang disimpan:', ['path' => $gambarPath]);

                $roTransaksiHasiFoto = new ROTransaksiHasilModel();
                $roTransaksiHasiFoto->norm = $request->input('norm');
                $roTransaksiHasiFoto->tanggal = $request->input('tglRo');
                $roTransaksiHasiFoto->foto = $gambarPath; // Menyimpan path foto dalam database

                // Tambahkan informasi debug
                Log::info('Data yang akan disimpan ke dalam tabel ROTransaksiHasilModel:', $roTransaksiHasiFoto->toArray());

                $roTransaksiHasiFoto->save();
            }

            return response()->json(['message' => 'Data berhasil disimpan'], 200);

        } catch (\Exception $e) {
            // Tangani kesalahan
            Log::error('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function hasilRo(Request $request)
    {
        $tglAwal = $request->input('tglAwal');
        $tglAkhir = $request->input('tglAkhir');
        $norm = $request->input('norm');
        $data = RoHasilModel::on('rontgen')
            ->when($norm !== null && $norm !== '' && $norm !== '000000', function ($query) use ($norm) {
                return $query->where('norm', $norm);
            })
            ->whereBetween('tanggal', [$tglAwal, $tglAkhir])
            ->get();
        return response()->json(['data' => $data], 200, [], JSON_PRETTY_PRINT);
    }
    // public function addHasilRo(Request $request){
    //     $fotoPath = $request->file('foto')->store('public/foto');

    //     // Buat entri baru dalam tabel ROTransaksiHasiFoto
    //     $roTransaksiHasiFoto = new ROTransaksiHasiFoto();
    //     $roTransaksiHasiFoto->norm = $validatedData['norm'];
    //     $roTransaksiHasiFoto->tanggal = $validatedData['tanggal'];
    //     $roTransaksiHasiFoto->foto = $fotoPath; // Menyimpan path foto dalam database
    //     $roTransaksiHasiFoto->save();

    //     return response()->json(['message' => 'Data berhasil disimpan'], 201);
    // }
}
