<?php

namespace App\Http\Controllers;

use App\Models\KominfoModel;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->only('tanggal_awal', 'tanggal_akhir');
        $diagnosa = $request->input('diagnosa');
        // dd($diagnosa);
        $model = new KominfoModel();
        $data = $model->cpptRequestAll($params);
        if ($diagnosa !== null) {
            if (isset($data['response']['data']) && is_array($data['response']['data'])) {
                $filteredData = array_filter(array_map(function ($d) use ($diagnosa) {
                    foreach ($d['diagnosa'] as $item) {
                        $match = false;
                        if (stripos($item['nama_diagnosa'], $diagnosa) === false) {
                            $match = true;
                            break;
                        }
                        // dd($match);
                        if (!$match) {
                            return null;
                        }
                    }
                }, $data['response']['data']));
            } else {
                return response()->json([
                    'metadata' => [
                        'message' => 'Data Tidak Ditemukan',
                        'code' => 404,
                    ],
                ], 200);
            }
            return response()->json($filteredData);
        }
        return response()->json($data);
    }
}
