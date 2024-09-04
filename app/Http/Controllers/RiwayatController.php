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
    public function CountDxMedis(Request $request)
    {
        $params = $request->only('tanggal_awal', 'tanggal_akhir');
        $diagnosa = $request->input('diagnosa');
        $model = new KominfoModel();
        $data = $model->cpptRequestAll($params);
        // $data = [
        //     [
        //         "metadata" => [
        //             "code" => 200,
        //             "message" => "Data CPPT ditemukan!",
        //         ],
        //         "response" => [
        //             "data" => [
        //                 [
        //                     "pendaftaran_id" => "107674",
        //                     "id_cppt" => "3023",
        //                     "no_trans" => "02731908202401",
        //                     "no_reg" => "2024081000064",
        //                     "tanggal" => "2024-08-10",
        //                     "antrean_huruf" => null,
        //                     "antrean_angka" => "064",
        //                     "antrean_nomor" => "064",
        //                     "pasien_no_rm" => "027319",
        //                     "pasien_nama" => "MOHAMMAD ZULFIKAR",
        //                     "pasien_alamat" => "JALAN SAWO RT 02 RW 08 KESUGIHAN CILACAP",
        //                     "pasien_rt" => "003",
        //                     "pasien_rw" => "004",
        //                     "pasien_kode_pos" => "",
        //                     "provinsi_nama" => "JAWA TENGAH",
        //                     "kabupaten_nama" => "KENDAL",
        //                     "kecamatan_nama" => "GEMUH",
        //                     "kelurahan_nama" => "Poncorejo",
        //                     "penjamin_nama" => "UMUM",
        //                     "dokter_username" => "198311142011012002",
        //                     "dokter_nama" => "dr. Cempaka Nova Intani, Sp.P, FISR., MM.",
        //                     "tindakan" => [],
        //                     "diagnosa" => [
        //                         [
        //                             "kode_diagnosa" => "Z02",
        //                             "nama_diagnosa" => "Examination and encounter for administrative purposes",
        //                         ],
        //                     ],
        //                     "radiologi" => [
        //                         [
        //                             "layanan" => "Thorax (dada) AP / PA",
        //                             "keterangan" => "",
        //                             "tarif" => "100000",
        //                             "kategori_layanan" => "LAYANAN RADIOLOGI",
        //                             "subkategori_layanan" => "",
        //                         ],
        //                     ],
        //                     "laboratorium" => [
        //                         [
        //                             "layanan" => "Hematologi Analizer 5 DIFF",
        //                             "keterangan" => "",
        //                             "tarif" => "90000",
        //                             "kategori_layanan" => "LAYANAN LABORATORIUM",
        //                             "subkategori_layanan" => "HEMATOLOGI",
        //                         ],
        //                         [
        //                             "layanan" => "TCM",
        //                             "keterangan" => "",
        //                             "tarif" => "0",
        //                             "kategori_layanan" => "LAYANAN LABORATORIUM",
        //                             "subkategori_layanan" => "BAKTERIOLOGI",
        //                         ],
        //                         [
        //                             "layanan" => "Glukosa darah",
        //                             "keterangan" => "",
        //                             "tarif" => "35000",
        //                             "kategori_layanan" => "LAYANAN LABORATORIUM",
        //                             "subkategori_layanan" => "KIMIA DARAH",
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     "pendaftaran_id" => "107673",
        //                     "id_cppt" => "3024",
        //                     "no_trans" => "02731808202401",
        //                     "no_reg" => "2024081000063",
        //                     "tanggal" => "2024-08-10",
        //                     "antrean_huruf" => null,
        //                     "antrean_angka" => "063",
        //                     "antrean_nomor" => "063",
        //                     "pasien_no_rm" => "027318",
        //                     "pasien_nama" => "ELI MARYATI",
        //                     "pasien_alamat" => "",
        //                     "pasien_rt" => "005",
        //                     "pasien_rw" => "003",
        //                     "pasien_kode_pos" => "",
        //                     "provinsi_nama" => "JAWA TENGAH",
        //                     "kabupaten_nama" => "BANYUMAS",
        //                     "kecamatan_nama" => "GUMELAR",
        //                     "kelurahan_nama" => "Karangkemojing",
        //                     "penjamin_nama" => "UMUM",
        //                     "dokter_username" => "198907252019022004",
        //                     "dokter_nama" => "dr. FILLY ULFA KUSUMAWARDANI",
        //                     "tindakan" => [],
        //                     "diagnosa" => [
        //                         [
        //                             "kode_diagnosa" => "Z03.0",
        //                             "nama_diagnosa" => "Observation for suspected tuberculosis",
        //                         ],
        //                     ],
        //                     "radiologi" => [
        //                         [
        //                             "layanan" => "Thorax (dada) AP / PA",
        //                             "keterangan" => "",
        //                             "tarif" => "100000",
        //                             "kategori_layanan" => "LAYANAN RADIOLOGI",
        //                             "subkategori_layanan" => "",
        //                         ],
        //                         [
        //                             "layanan" => "Konsultasi dokter Radiologi",
        //                             "keterangan" => "",
        //                             "tarif" => "50000",
        //                             "kategori_layanan" => "LAYANAN RADIOLOGI",
        //                             "subkategori_layanan" => "",
        //                         ],
        //                     ],
        //                     "laboratorium" => [
        //                         [
        //                             "layanan" => "Hematologi Analizer 5 DIFF",
        //                             "keterangan" => "",
        //                             "tarif" => "90000",
        //                             "kategori_layanan" => "LAYANAN LABORATORIUM",
        //                             "subkategori_layanan" => "HEMATOLOGI",
        //                         ],
        //                         [
        //                             "layanan" => "Asam urat darah",
        //                             "keterangan" => "",
        //                             "tarif" => "40000",
        //                             "kategori_layanan" => "LAYANAN LABORATORIUM",
        //                             "subkategori_layanan" => "KIMIA DARAH",
        //                         ],
        //                         [
        //                             "layanan" => "SGOT",
        //                             "keterangan" => "",
        //                             "tarif" => "35000",
        //                             "kategori_layanan" => "LAYANAN LABORATORIUM",
        //                             "subkategori_layanan" => "KIMIA DARAH",
        //                         ],
        //                         [
        //                             "layanan" => "SGPT",
        //                             "keterangan" => "",
        //                             "tarif" => "35000",
        //                             "kategori_layanan" => "LAYANAN LABORATORIUM",
        //                             "subkategori_layanan" => "KIMIA DARAH",
        //                         ],
        //                         [
        //                             "layanan" => "Glukosa darah",
        //                             "keterangan" => "",
        //                             "tarif" => "35000",
        //                             "kategori_layanan" => "LAYANAN LABORATORIUM",
        //                             "subkategori_layanan" => "KIMIA DARAH",
        //                         ],
        //                         [
        //                             "layanan" => "Cholesterol",
        //                             "keterangan" => "",
        //                             "tarif" => "40000",
        //                             "kategori_layanan" => "LAYANAN LABORATORIUM",
        //                             "subkategori_layanan" => "KIMIA DARAH",
        //                         ],
        //                     ],
        //                 ],
        //                 [
        //                     "pendaftaran_id" => "107672",
        //                     "id_cppt" => null,
        //                     "no_trans" => null,
        //                     "no_reg" => "2024081000062",
        //                     "tanggal" => "2024-08-10",
        //                     "antrean_huruf" => null,
        //                     "antrean_angka" => "062",
        //                     "antrean_nomor" => "062",
        //                     "pasien_no_rm" => null,
        //                     "pasien_nama" => null,
        //                     "pasien_alamat" => null,
        //                     "pasien_rt" => null,
        //                     "pasien_rw" => null,
        //                     "pasien_kode_pos" => null,
        //                     "provinsi_nama" => null,
        //                     "kabupaten_nama" => null,
        //                     "kecamatan_nama" => null,
        //                     "kelurahan_nama" => null,
        //                     "penjamin_nama" => "UMUM",
        //                     "dokter_username" => null,
        //                     "dokter_nama" => null,
        //                     "tindakan" => [],
        //                     "diagnosa" => [],
        //                     "radiologi" => [],
        //                     "laboratorium" => [],
        //                 ],
        //                 [
        //                     "pendaftaran_id" => "107671",
        //                     "id_cppt" => "3022",
        //                     "no_trans" => "02731708202401",
        //                     "no_reg" => "2024081000061",
        //                     "tanggal" => "2024-08-10",
        //                     "antrean_huruf" => null,
        //                     "antrean_angka" => "061",
        //                     "antrean_nomor" => "061",
        //                     "pasien_no_rm" => "027317",
        //                     "pasien_nama" => "AMIN NURYANTO",
        //                     "pasien_alamat" => "",
        //                     "pasien_rt" => "005",
        //                     "pasien_rw" => "004",
        //                     "pasien_kode_pos" => "",
        //                     "provinsi_nama" => "JAWA TENGAH",
        //                     "kabupaten_nama" => "BANYUMAS",
        //                     "kecamatan_nama" => "BATURRADEN",
        //                     "kelurahan_nama" => "Rempoah",
        //                     "penjamin_nama" => "BPJS",
        //                     "dokter_username" => "198311142011012002",
        //                     "dokter_nama" => "dr. Cempaka Nova Intani, Sp.P, FISR., MM.",
        //                     "tindakan" => [],
        //                     "diagnosa" => [
        //                         [
        //                             "kode_diagnosa" => "A09",
        //                             "nama_diagnosa" => "Infectious gastroenteritis and colitis, unspecified",
        //                         ],
        //                     ],
        //                     "radiologi" => [],
        //                     "laboratorium" => [],
        //                 ],
        //             ],
        //         ],
        //     ],
        // ];
        // dd($data);

        // Initialize arrays to hold diagnosis counts for each insurance type
        $diagnosisCounts = [
            'BPJS' => [],
            'UMUM' => [],
            'Total' => [],
        ];

        if (isset($data['response']['data']) && is_array($data['response']['data'])) {
            foreach ($data['response']['data'] as $item) {
                // Get the insurance type
                $insuranceType = $item['penjamin_nama'] ?? 'UMUM'; // Default to UMUM if not specified

                // Count diagnoses based on insurance type
                foreach ($item['diagnosa'] as $diagnosis) {
                    $diagnosisCode = $diagnosis['kode_diagnosa'];
                    $diagnosisName = $diagnosis['nama_diagnosa'];

                    // Check if the insurance type is valid
                    if (!in_array($insuranceType, ['BPJS', 'UMUM'])) {
                        continue; // Skip if it's not BPJS or UMUM
                    }

                    // Count the occurrences for the specific insurance type
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
            'metadata' => [
                'message' => 'Diagnosis counts retrieved successfully.',
                'code' => 200,
            ],
            'diagnosis_counts' => $diagnosisCounts,
        ]);
    }
}
