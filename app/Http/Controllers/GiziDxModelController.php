<?php

namespace App\Http\Controllers;

use App\Models\GiziDxDomainModel;
use App\Models\GiziDxKelasModel;
use App\Models\GiziDxSubKelasModel;
use Illuminate\Http\Request;

class GiziDxModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function subKelas()
    {
        $data = GiziDxSubKelasModel::with('domain', 'kelas')->get();

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function kelas()
    {
        $data = GiziDxKelasModel::with('domain')->get();
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
    public function domain()
    {
        $data = GiziDxDomainModel::get();
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function simpanSubKelas(Request $request)
    {
        $data = GiziDxSubKelasModel::where('id', $request->id)->first();
        if ($data) {
            $data->kode = $request->kode;
            $data->domain = $request->domain;
            $data->kelas = $request->kelas;
            $data->sub_kelas = $request->deskripsi;
            $data->save();
            $msg = ["message" => "Data Sub Kelas Berhasil Diubah"];

            return response()->json($msg, 200, [], JSON_PRETTY_PRINT);
        } else {

            $data = new GiziDxSubKelasModel();
            $data->kode = $request->kode;
            $data->domain = $request->domain;
            $data->kelas = $request->kelas;
            $data->sub_kelas = $request->deskripsi;
            $data->save();
            $msg = ["message" => "Data Sub Kelas Berhasil Ditambahkan"];
        }
        return response()->json($msg, 200, [], JSON_PRETTY_PRINT);
    }

    public function deleteSubKelas(Request $request)
    {
        $data = GiziDxSubKelasModel::find($request->id);
        if ($data) {
            $data->delete();
            $msg = ["message" => "Data Sub Kelas Berhasil Dihapus"];
            return response()->json($msg, 200, [], JSON_PRETTY_PRINT);
        } else {
            return response()->json("Data Tidak Ditemukan", 404, [], JSON_PRETTY_PRINT);
        }
    }

    public function simpanKelas(Request $request)
    {
        $data = GiziDxKelasModel::where('id', $request->id)->first();
        if ($data) {
            $data->kode = $request->kode;
            $data->domain = $request->domain;
            $data->kelas = $request->deskripsi;
            $data->save();
            $msg = ["message" => "Data Kelas Berhasil Diubah"];
            return response()->json($msg, 200, [], JSON_PRETTY_PRINT);
        } else {
            $data = new GiziDxKelasModel();
            $data->kode = $request->kode;
            $data->domain = $request->domain;
            $data->kelas = $request->deskripsi;
            $data->save();
            $msg = ["message" => "Data Kelas Berhasil Ditambahkan"];
        }
        return response()->json($msg, 200, [], JSON_PRETTY_PRINT);
    }
    public function deleteKelas(Request $request)
    {
        $data = GiziDxKelasModel::find($request->id);
        if ($data) {
            $data->delete();
            $msg = ["message" => "Data Kelas Berhasil Dihapus"];
            return response()->json($msg, 200, [], JSON_PRETTY_PRINT);
        } else {
            return response()->json("Data Tidak Ditemukan", 404, [], JSON_PRETTY_PRINT);
        }
    }

    public function simpanDomain(Request $request)
    {
        $data = GiziDxDomainModel::where('id', $request->id)->first();
        if ($data) {
            $data->kode = $request->kode;
            $data->domain = $request->deskripsi;
            $data->save();
            $msg = ["message" => "Data Domain Berhasil Diubah"];
            return response()->json($msg, 200, [], JSON_PRETTY_PRINT);
        } else {
            $data = new GiziDxDomainModel();
            $data->kode = $request->kode;
            $data->domain = $request->deskripsi;
            $data->save();
            $msg = ["message" => "Data Domain Berhasil Ditambahkan"];
        }
        return response()->json($msg, 200, [], JSON_PRETTY_PRINT);
    }
    public function deleteDomain(Request $request)
    {
        $data = GiziDxDomainModel::find($request->id);
        if ($data) {
            $data->delete();
            $msg = ["message" => "Data Domain Berhasil Dihapus"];
            return response()->json($msg, 200, [], JSON_PRETTY_PRINT);
        } else {
            return response()->json("Data Tidak Ditemukan", 404, [], JSON_PRETTY_PRINT);
        }
    }

}
