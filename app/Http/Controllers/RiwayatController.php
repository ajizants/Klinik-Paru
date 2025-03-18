<?php
namespace App\Http\Controllers;

use App\Models\KominfoModel;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $params   = $request->only('tanggal_awal', 'tanggal_akhir');
        $diagnosa = $request->input('diagnosa');
        // dd($diagnosa);
        $model = new KominfoModel();
        $data  = $model->cpptRequestAll($params);
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
                        if (! $match) {
                            return null;
                        }
                    }
                }, $data['response']['data']));
            } else {
                return response()->json([
                    'metadata' => [
                        'message' => 'Data Tidak Ditemukan',
                        'code'    => 404,
                    ],
                ], 200);
            }
            return response()->json($filteredData);
        }
        return response()->json($data);
    }
    public function CountDxMedis(Request $request)
    {
        $params   = $request->only('tanggal_awal', 'tanggal_akhir');
        $diagnosa = $request->input('diagnosa');
        $model    = new KominfoModel();
        $data     = $model->cpptRequestAll($params);
        // dd($data);
        if (isset($data['error'])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengambil data dari API: ' . $data['error'],
            ], 500);
        }

        // Initialize arrays to hold diagnosis counts for each insurance type
        $diagnosisCounts = [
            'BPJS'  => [],
            'UMUM'  => [],
            'Total' => [],
        ];

        if (isset($data['response']['data']) && is_array($data['response']['data'])) {
            foreach ($data['response']['data'] as $item) {
                $insuranceType = $item['penjamin_nama'] ?? 'UMUM'; // Default to UMUM if not specified

                // Count diagnoses based on insurance type
                foreach ($item['diagnosa'] as $diagnosis) {
                    $diagnosisCode = $diagnosis['kode_diagnosa'];
                    $diagnosisName = $diagnosis['nama_diagnosa'];

                    // Check if the insurance type is valid
                    if (! in_array($insuranceType, ['BPJS', 'UMUM'])) {
                        continue; // Skip if it's not BPJS or UMUM
                    }

                    $key = $diagnosisCode . ' - ' . $diagnosisName; // Combine code and name

                    // Count for the specific insurance type
                    if (array_key_exists($key, $diagnosisCounts[$insuranceType])) {
                        $diagnosisCounts[$insuranceType][$key]++;
                        // $diagnosisCounts[$insuranceType][$diagnosisCode]++;
                    } else {
                        $diagnosisCounts[$insuranceType][$key] = 1;
                        // $diagnosisCounts[$insuranceType][$diagnosisCode] = 1;
                    }

                    // Count for the Total diagnoses
                    if (array_key_exists($key, $diagnosisCounts['Total'])) {
                        $diagnosisCounts['Total'][$key]++;
                        // $diagnosisCounts['Total'][$diagnosisCode]++;
                    } else {
                        $diagnosisCounts['Total'][$key] = 1;
                        // $diagnosisCounts['Total'][$diagnosisCode] = 1;
                    }
                }
            }
        }

        // Return the counts as a JSON response
        return response()->json([
            'metadata'         => [
                'message' => 'Diagnosis counts retrieved successfully.',
                'code'    => 200,
            ],
            'diagnosis_counts' => $diagnosisCounts,
        ]);
    }
}
