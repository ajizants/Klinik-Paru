<?php

namespace App\Http\Controllers;

use App\Models\ROJenisFilm;
use App\Models\ROJenisFoto;
use App\Models\ROJenisKondisi;
use App\Models\ROJenisMesin;
use App\Models\RoProyeksiModel;
use Illuminate\Http\Request;

class RoMasterController extends Controller
{

    //layanan Ro
    public function fotoRo()
    {
        $data = ROJenisFoto::all();

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);

    }
    public function editfotoRo(Request $request)
    {
        $kdFoto = $request->kdFoto;

        $data = ROJenisFoto::where('kdFoto', $kdFoto)->first();
        // dd($data);
        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $data->nmFoto = $request->nmFoto;
        $data->tarif = $request->tarif;
        $data->save();

        // Kirim data yang sudah diupdate sebagai respons
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    public function deletefotoRo(Request $request)
    {
        $data = ROJenisFoto::all();

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);

    }

    //Ukuran Film
    public function filmRo()
    {
        $data = ROJenisFilm::all();

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);

    }
    public function editfilmRo(Request $request)
    {
        $kdFilm = $request->kdFilm;

        $data = ROJenisFilm::where('kdFilm', $kdFilm)->first();
        // dd($data);
        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $data->ukuranFilm = $request->ukuranFilm;
        $data->save();

        // Kirim data yang sudah diupdate sebagai respons
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    //Kondisi Pemotretan
    public function kondisiRo(Request $request)
    {
        $grup = $request->input('grup');
        $status = $request->input('status');
        $query = ROJenisKondisi::query();
        if (!empty($status)) {
            $query->where('status', $status);
        }
        if (!empty($grup)) {
            $query->where('grup', $grup);
        }

        $data = $query->get();
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    public function editKondisiRo(Request $request)
    {
        $kdKondisiRo = $request->kdKondisi;
        // dd($kdKondisiRo);
        $data = ROJenisKondisi::where('kdKondisiRo', $kdKondisiRo)->first();
        // dd($data);
        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $data->nmKondisi = $request->nmKondisi;
        $data->grup = $request->grup;
        $data->status = $request->status;
        $data->save();

        // Kirim data yang sudah diupdate sebagai respons
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    //Proyeksi
    public function proyeksiRo()
    {
        $data = RoProyeksiModel::all();

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);

    }
    public function editProyeksiRo(Request $request)
    {
        $kdProyeksi = $request->kdProyeksi;
        // dd($kdProyeksi);
        $data = RoProyeksiModel::where('kdProyeksi', $kdProyeksi)->first();
        // dd($data);
        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $data->proyeksi = $request->nmProyeksi;
        $data->save();

        // Kirim data yang sudah diupdate sebagai respons
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    //Mesin RO
    public function mesinRo()
    {
        $data = ROJenisMesin::all();

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);

    }

}
