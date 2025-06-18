<?php
namespace App\Http\Controllers;

use App\Models\KasirTransModel;
use App\Models\KominfoModel;
use App\Models\KunjunganWaktuSelesai;
use App\Models\LaboratoriumHasilModel;
use App\Models\LaboratoriumKunjunganModel;
use App\Models\PoliModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DataAnalisController extends Controller
{

    // public function DataBiayaKunjungan(Request $request)
    // {
    //     $params = $request->all();

    //     $kominfo                 = new KominfoModel();
    //     $dataPendaftaranResponse = $kominfo->pendaftaran($params);

    //     // Filter data dengan keterangan "SELESAI DOPANGGIL PENDAFTARAN"
    //     $filteredData = array_values(array_filter($dataPendaftaranResponse, function ($item) {
    //         return isset($item['keterangan']) && $item['keterangan'] === 'SELESAI DIPANGGIL LOKET PENDAFTARAN';
    //     }));

    //     $data = [];

    //     foreach ($filteredData as &$item) {
    //         $notrans            = $item['notrans'];
    //         $dataBiayaKunjungan = KasirTransModel::where('notrans', $notrans)->first();

    //         $data[] = [
    //             'status_pulang'    => $item['status_pulang'],
    //             'no_reg'           => $item['no_reg'],
    //             'id'               => $item['id'],
    //             'tanggal'          => $item['tanggal'],
    //             'pasien_no_rm'     => $item['pasien_no_rm'],
    //             'pasien_lama_baru' => $item['pasien_lama_baru'],
    //             'pasien_nama'      => $item['pasien_nama'],
    //             'pasien_alamat'    => $item['pasien_alamat'],
    //             'notrans'          => $item['notrans'],
    //             'jaminan'          => $item['penjamin_nama'],
    //             'tagihan'          => $dataBiayaKunjungan->tagihan ?? "0 - BPJS",
    //             'bayar'            => $dataBiayaKunjungan->bayar ?? "0 - BPJS",
    //             'kembalian'        => $dataBiayaKunjungan->kembalian ?? "0 - BPJS",

    //         ];
    //     }

    //     $counts = array_reduce($data, function ($carry, $item) {
    //         $no_rm = $item['pasien_no_rm'];

    //         // Hitung jumlah pasien baru
    //         if ($item['pasien_lama_baru'] === 'BARU') {
    //             $carry['baru'][$no_rm]['jumlah_baru']  = ($carry['baru'][$no_rm]['jumlah_baru'] ?? 0) + 1;
    //             $carry['baru'][$no_rm]['tagihan_baru'] = ($carry['baru'][$no_rm]['tagihan_baru'] ?? 0) + floatval($item['tagihan']);

    //             // Simpan tanggal pertama kali pasien berstatus "BARU"
    //             if (! isset($carry['baru'][$no_rm]['tanggal_baru']) || $item['tanggal'] < $carry['baru'][$no_rm]['tanggal_baru']) {
    //                 $carry['baru'][$no_rm]['tanggal_baru'] = $item['tanggal'];
    //             }

    //             // Simpan jaminan pertama kali pasien berstatus "BARU"
    //             if (! isset($carry['baru'][$no_rm]['jaminan_baru'])) {
    //                 $carry['baru'][$no_rm]['jaminan_baru'] = $item['jaminan'];
    //             }
    //         }

    //         // Hitung jumlah pasien lama
    //         if ($item['pasien_lama_baru'] === 'LAMA') {
    //             $carry['lama'][$no_rm]['jumlah_lama'] = ($carry['lama'][$no_rm]['jumlah_lama'] ?? 0) + 1;

    //             // Hitung jumlah berdasarkan jenis jaminan
    //             if ($item['jaminan'] === 'UMUM') {
    //                 $carry['lama'][$no_rm]['jaminan_lama_umum'] = ($carry['lama'][$no_rm]['jaminan_lama_umum'] ?? 0) + 1;
    //             } elseif ($item['jaminan'] === 'BPJS') {
    //                 $carry['lama'][$no_rm]['jaminan_lama_bpjs'] = ($carry['lama'][$no_rm]['jaminan_lama_bpjs'] ?? 0) + 1;
    //             }
    //         }

    //         // Hitung total kemunculan pasien (baik "BARU" maupun "LAMA")
    //         $carry['total'][$no_rm] = ($carry['total'][$no_rm] ?? 0) + 1;

    //         return $carry;
    //     }, ['baru' => [], 'lama' => [], 'total' => []]);

    //     // Gabungkan data ke dalam detail
    //     $detail = [];
    //     foreach ($counts['baru'] as $no_rm => $dataBaru) {
    //         $jumlahBaru  = $dataBaru['jumlah_baru'];
    //         $tagihanBaru = $dataBaru['tagihan_baru'];

    //         $detail[$no_rm] = [
    //             'jumlah_total_kunjungan' => $counts['total'][$no_rm], // Berapa kali muncul di semua data
    //             'tanggal_baru'           => $dataBaru['tanggal_baru'],
    //             'tagihan_baru'           => number_format($tagihanBaru, 2, '.', ''),
    //             'jaminan_saat_baru'      => $dataBaru['jaminan_baru'],
    //             'jaminan_lama_umum'      => $counts['lama'][$no_rm]['jaminan_lama_umum'] ?? 0,
    //             'jaminan_lama_bpjs'      => $counts['lama'][$no_rm]['jaminan_lama_bpjs'] ?? 0,
    //             'jumlah_baru'            => $jumlahBaru,                            // Berapa kali muncul dengan status "BARU"
    //             'datang_lagi'            => $counts['total'][$no_rm] - $jumlahBaru, // Berapa kali datang setelah pertama kali "BARU"
    //         ];
    //     }

    //     // Hitung total kemunculan semua pasien yang pernah menjadi "BARU"
    //     $totalMuncul = array_sum(array_column($detail, 'jumlah_total'));

    //     // Response JSON
    //     return response()->json([
    //         'total_pasien_baru' => count($counts['baru']),
    //         'total_kemunculan'  => $totalMuncul,
    //         'detail'            => $detail, // Detail pasien yang awalnya "BARU" dan kemunculan totalnya
    //     ]);

    // }

    public function index()
    {
        $title = 'Data Analis';
        $params = [
            'tanggal_awal' => date('Y-m-d'),
            'tanggal_akhir' => date('Y-m-d'),
        ];
        // $data = $this->getData($params);
        // dd($data);
        return view('PusatData.main')->with('title', $title);
        return view('PusatData.main', compact('data'))->with('title', $title);
    }

    public function DataBiayaKunjungan(Request $request)
    {
        $params = $request->all();
        $data = $this->getData($params);
        // return response()->json($data);

        $html = '<table id="kunjunganTable" class="table table-bordered table-striped">';
        $html .= '<thead class="bg-info">
                <tr>
                <th rowspan="2">No RM</th>
                    <th class="align-item-center" rowspan="2">Total Kunjungan</th>
                    <th class="align-item-center" rowspan="2">Tanggal Pertama</th>
                    <th class="align-item-center" rowspan="2">Tanggal Kedua</th>
                    <th class="align-item-center" rowspan="2">Kelurahan</th>
                    <th class="align-item-center" rowspan="2">Kecamatan</th>
                    <th class="align-item-center" rowspan="2">Kabupaten</th>
                    <th class="align-item-center" rowspan="2">Tagihan Baru</th>
                    <th class="text-center" colspan="3">Jaminan</th>
                    <th class="align-item-center" rowspan="2">Datang Lagi</th>
                </tr>
                <tr>

                    <th>Saat Baru</th>
                    <th>Kontrol - Umum</th>
                    <th>Kontrol - BPJS</th>

                </tr>
              </thead>';
        $html .= '<tbody>';

        foreach ($data['detail'] as $item) {
            $html .= '<tr>';
            $html .= '<td>' . $item['no_rm'] . '</td>';
            $html .= '<td>' . $item['jumlah_total_kunjungan'] . '</td>';
            $html .= '<td>' . $item['tanggal_baru'] . '</td>';
            $html .= '<td>' . $item['tanggal_kontrol_pertama'] . '</td>';
            $html .= '<td>' . $item['kelurahan'] . '</td>';
            $html .= '<td>' . $item['kecamatan'] . '</td>';
            $html .= '<td>' . $item['kabupaten'] . '</td>';
            $html .= '<td>' . $item['tagihan_baru'] . '</td>';
            $html .= '<td>' . $item['jaminan_saat_baru'] . '</td>';
            $html .= '<td>' . $item['jaminan_lama_umum'] . '</td>';
            $html .= '<td>' . $item['jaminan_lama_bpjs'] . '</td>';
            $html .= '<td>' . $item['datang_lagi'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        return response()->json(['html' => $html]);
    }
    public function faskesPerujuk(Request $request)
    {
        $params = [
            'tanggal_sep_awal' => $request->input('tanggal_awal'),
            'tanggal_sep_akhir' => $request->input('tanggal_akhir'),
            'order_by' => 'jumlah_rujukan',
            'order_jenis' => 'desc',
        ];
        $model = new KominfoModel();
        $data = $model->rekapFaskesPerujuk($params)['response']['data_rujukan'];
        // return $data;

        $html = '<table id="faskesPerujukTable" class="table table-bordered table-striped">';
        $html .= '<thead class="bg bg-orange table-bordered">
                     <tr>
                        <th>NO</th>
                        <th>Nama Faskes</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>';
        $html .= '<tbody>';

        foreach ($data as $index => $item) {
            $html .= '<tr>';
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . $item['ppk_rujukan_nama'] . '</td>';
            $html .= '<td>' . $item['jumlah_rujukan'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        return response()->json(['html' => $html]);
    }

    public function getData(array $params)
    {
        $kominfo = new KominfoModel();
        $dataPendaftaranResponse = $kominfo->pendaftaran($params);
        // dd($dataPendaftaranResponse);

        // Filter hanya yang memiliki status "SELESAI DIPANGGIL LOKET PENDAFTARAN"
        $filteredData = array_filter($dataPendaftaranResponse, fn($item) =>
            isset($item['keterangan']) && $item['keterangan'] === 'SELESAI DIPANGGIL LOKET PENDAFTARAN'
        );

        // Ambil semua notrans untuk mengurangi query
        $notransList = array_column($filteredData, 'notrans');
        $dataBiayaKunjungan = KasirTransModel::whereIn('notrans', $notransList)->get()->keyBy('notrans');

        $data = array_map(function ($item) use ($dataBiayaKunjungan) {
            $notrans = $item['notrans'];
            $biaya = $dataBiayaKunjungan[$notrans] ?? null;

            return [
                'status_pulang' => $item['status_pulang'],
                'no_reg' => $item['no_reg'],
                'id' => $item['id'],
                'tanggal' => $item['tanggal'],
                'pasien_no_rm' => $item['pasien_no_rm'],
                'pasien_lama_baru' => $item['pasien_lama_baru'],
                'pasien_nama' => $item['pasien_nama'],
                'pasien_alamat' => $item['pasien_alamat'],
                'notrans' => $item['notrans'],
                'jaminan' => $item['penjamin_nama'],
                'tagihan' => $biaya->tagihan ?? "0 - BPJS",
                'bayar' => $biaya->bayar ?? "0 - BPJS",
                'kembalian' => $biaya->kembalian ?? "0 - BPJS",
                'kabupaten' => $item['kabupaten'],
                'kecamatan' => $item['kecamatan'],
                'kelurahan' => $item['kelurahan'],
            ];
        }, $filteredData);

        // Hitung jumlah pasien baru dan lama dalam satu iterasi
        $counts = ['baru' => [], 'lama' => [], 'total' => []];

        foreach ($data as $item) {
            $no_rm = $item['pasien_no_rm'];

            if ($item['pasien_lama_baru'] === 'BARU') {
                $counts['baru'][$no_rm]['jumlah_baru'] = ($counts['baru'][$no_rm]['jumlah_baru'] ?? 0) + 1;
                $counts['baru'][$no_rm]['tagihan_baru'] = ($counts['baru'][$no_rm]['tagihan_baru'] ?? 0) + floatval($item['tagihan']);

                if (!isset($counts['baru'][$no_rm]['tanggal_baru']) || $item['tanggal'] < $counts['baru'][$no_rm]['tanggal_baru']) {
                    $counts['baru'][$no_rm]['tanggal_baru'] = $item['tanggal'];
                }

                if (!isset($counts['baru'][$no_rm]['jaminan_baru'])) {
                    $counts['baru'][$no_rm]['jaminan_baru'] = $item['jaminan'];
                }

                if (!isset($counts['baru'][$no_rm]['pasien_nama'])) {
                    $counts['baru'][$no_rm]['pasien_nama'] = $item['pasien_nama'];
                }
                if (!isset($counts['baru'][$no_rm]['kabupaten'])) {
                    $counts['baru'][$no_rm]['kabupaten'] = $item['kabupaten'];
                }
                if (!isset($counts['baru'][$no_rm]['kelurahan'])) {
                    $counts['baru'][$no_rm]['kelurahan'] = $item['kelurahan'];
                }
                if (!isset($counts['baru'][$no_rm]['kecamatan'])) {
                    $counts['baru'][$no_rm]['kecamatan'] = $item['kecamatan'];
                }

            }

            if ($item['pasien_lama_baru'] === 'LAMA') {
                $counts['lama'][$no_rm]['jumlah_lama'] = ($counts['lama'][$no_rm]['jumlah_lama'] ?? 0) + 1;
                // Simpan tanggal kontrol pertama
                if (!isset($counts['lama'][$no_rm]['tanggal_kontrol_pertama'])) {
                    $counts['lama'][$no_rm]['tanggal_kontrol_pertama'] = $item['tanggal'];
                } else {
                    $counts['lama'][$no_rm]['tanggal_kontrol_pertama'] = min(
                        $counts['lama'][$no_rm]['tanggal_kontrol_pertama'],
                        $item['tanggal']
                    );
                }

                // Hitung jaminan
                if ($item['jaminan'] === 'UMUM') {
                    $counts['lama'][$no_rm]['jaminan_lama_umum'] = ($counts['lama'][$no_rm]['jaminan_lama_umum'] ?? 0) + 1;
                } elseif ($item['jaminan'] === 'BPJS') {
                    $counts['lama'][$no_rm]['jaminan_lama_bpjs'] = ($counts['lama'][$no_rm]['jaminan_lama_bpjs'] ?? 0) + 1;
                }
            }

            $counts['total'][$no_rm] = ($counts['total'][$no_rm] ?? 0) + 1;
        }

        // Gabungkan hasil ke dalam detail
        $detail = array_map(fn($no_rm, $dataBaru) => [
            'no_rm' => $no_rm,
            'nama' => $dataBaru['pasien_nama'],
            'kabupaten' => $dataBaru['kabupaten'],
            'kecamatan' => $dataBaru['kecamatan'],
            'kelurahan' => $dataBaru['kelurahan'],
            'jumlah_total_kunjungan' => $counts['total'][$no_rm],
            'tanggal_kontrol_pertama' => $counts['lama'][$no_rm]['tanggal_kontrol_pertama'] ?? "-",
            'tanggal_baru' => $dataBaru['tanggal_baru'],
            'tagihan_baru' => $dataBaru['tagihan_baru'],
            // 'tagihan_baru' => number_format($dataBaru['tagihan_baru'], 2, '.', ''),
            'jaminan_saat_baru' => $dataBaru['jaminan_baru'],
            'jaminan_lama_umum' => $counts['lama'][$no_rm]['jaminan_lama_umum'] ?? 0,
            'jaminan_lama_bpjs' => $counts['lama'][$no_rm]['jaminan_lama_bpjs'] ?? 0,
            'jumlah_baru' => $dataBaru['jumlah_baru'],
            'datang_lagi' => $counts['total'][$no_rm] - $dataBaru['jumlah_baru'],
        ], array_keys($counts['baru']), $counts['baru']);

        $totalMuncul = array_sum(array_column($detail, 'jumlah_total_kunjungan'));

        $res =
            [
            'total_pasien_baru' => count($counts['baru']),
            'total_kemunculan' => $totalMuncul,
            'detail' => $detail,
        ];
        return $res;
    }

    private function jumlahKunjungan(Request $request)
    {
        $tglAwal = Carbon::parse($request->input('tglAwal'))->startOfDay(); // 00:00:00
        $tglAkhir = Carbon::parse($request->input('tglAkhir'))->endOfDay(); // 23:59:59

        $data = KunjunganWaktuSelesai::whereBetween('created_at', [$tglAwal, $tglAkhir])->get();

        return response()->json($data, 200);
    }

    public function kunjunganLab(Request $request)
    {
        $tglAkhir = Carbon::parse($request->input('tglAkhir'))->endOfDay(); // 23:59:59
        $tglAwal = Carbon::parse($request->input('tglAwal'))->startOfDay(); // 00:00:00
        $dataKunjunganLab = LaboratoriumKunjunganModel::whereBetween('created_at', [$tglAwal, $tglAkhir])->get();
        $dataHasilLab = LaboratoriumHasilModel::whereBetween('created_at', [$tglAwal, $tglAkhir])->get();

        return response()->json([
            'kunjungan' => $dataKunjunganLab,
            'hasil' => $dataHasilLab,
        ], 200);
    }

    public function jumlahDiagnosa($tahun)
    {
        if ($tahun >= 2025) {
            $thisYear = date('Y');

            if ($tahun == 2025) {
                // $startDate = Carbon::parse('2024-07-01');
                $startDate = Carbon::parse('2025-01-01');
            } else {
                $startDate = Carbon::parse($tahun . '-01-01');
            }

            if ($tahun == $thisYear) {
                $endDate = Carbon::now();
            } else {
                $endDate = Carbon::parse($tahun . '-02-31');
            }

            $model = new KominfoModel();
            $allData = [];

            $current = $startDate->copy();
            while ($current->lte($endDate)) {
                $startOfMonth = $current->copy()->startOfMonth()->toDateString();
                $endOfMonth = $current->copy()->endOfMonth()->toDateString();

                // Hindari ambil data lebih dari endDate
                if ($endOfMonth > $endDate->toDateString()) {
                    $endOfMonth = $endDate->toDateString();
                }

                $params = [
                    'tanggal_awal' => $startOfMonth,
                    'tanggal_akhir' => $endOfMonth,
                ];

                $result = $model->cpptRequestAll($params);
                if (isset($result['response']['data'])) {
                    $allData = array_merge($allData, $result['response']['data']);
                }

                // Naik ke bulan berikutnya
                $current->addMonth();
            }

            return $allData;
        } else {
            $data = PoliModel::with('dx1', 'dx2', 'dx3')
                ->whereYear('tgltrans', $tahun)
                ->get();
        }

        $data = $this->hitungDx2024($data);
        // return $data['perBulan'];
        // return $data['perTahun'];
        $tablePerbulan = $this->generateDiagnosaTablePerbulan($data['perBulan']);
        $tablePertahun = $this->generateDiagnosaTable($data['perTahun']);
        // return $tablePerbulan;
        return response()->json([
            'tablePerbulan' => $tablePerbulan,
            'tablePertahun' => $tablePertahun,
            'perBulan' => $data['perBulan'],
            'perTahun' => $data['perTahun'],
        ], 200);
    }

    private function hitungDx2024($data)
    {
        $perBulan = [];
        $perTahun = [];

        foreach ($data as $item) {
            $tgl = \Carbon\Carbon::parse($item['tgltrans']);
            $bulan = $tgl->format('Y-m'); // contoh: "2024-01"
            $tahun = $tgl->format('Y'); // contoh: "2024"

            // Ambil ketiga diagnosa
            $diagnosaList = [
                ['kode' => $item['diagnosa1'], 'detail' => $item['dx1'] ?? null],
                ['kode' => $item['diagnosa2'], 'detail' => $item['dx2'] ?? null],
                ['kode' => $item['diagnosa3'], 'detail' => $item['dx3'] ?? null],
            ];

            foreach ($diagnosaList as $dx) {
                if (!empty($dx['kode'])) {
                    $kode = $dx['kode'];
                    $nama = $dx['detail']['diagnosa'] ?? 'Tidak diketahui';

                    // === Hitung per bulan ===
                    if (!isset($perBulan[$bulan])) {
                        $perBulan[$bulan] = [];
                    }

                    // Cari apakah kode sudah ada
                    $found = false;
                    foreach ($perBulan[$bulan] as &$entry) {
                        if ($entry['kddx'] === $kode) {
                            $entry['jumlah']++;
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $perBulan[$bulan][] = ['kddx' => $kode, 'namadx' => $nama, 'jumlah' => 1];
                    }

                    // === Hitung per tahun ===
                    if (!isset($perTahun[$tahun])) {
                        $perTahun[$tahun] = [];
                    }

                    $found = false;
                    foreach ($perTahun[$tahun] as &$entry) {
                        if ($entry['kddx'] === $kode) {
                            $entry['jumlah']++;
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $perTahun[$tahun][] = ['kddx' => $kode, 'namadx' => $nama, 'jumlah' => 1];
                    }
                }
            }
        }

        return [
            'perBulan' => $perBulan,
            'perTahun' => $perTahun,
        ];
    }

    private function generateDiagnosaTablePerbulan($perbulan)
    {
        // Kumpulkan semua bulan
        $allMonths = [];
        foreach ($perbulan as $bulan => $dxList) {
            $allMonths[$bulan] = \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('M Y'); // contoh: Jan 2024
        }

        // Urutkan bulan secara kronologis (Y-m)
        ksort($allMonths); // <-- INI penting agar urut dari Jan ke Des

        // Kumpulkan semua kode diagnosa
        $diagnosaList = [];
        foreach ($perbulan as $bulan => $dxs) {
            foreach ($dxs as $kode => $item) {
                if (!isset($diagnosaList[$kode])) {
                    $diagnosaList[$kode] = [
                        'kddx' => $item['kddx'],
                        'namadx' => $item['namadx'],
                    ];
                }
                $diagnosaList[$kode][$bulan] = $item['jumlah'];
            }
        }

        // Susun data ke dalam format tabel
        $table = [];
        foreach ($diagnosaList as $kode => $dx) {
            $row = [
                'kddx' => $dx['kddx'],
                'namadx' => $dx['namadx'],
            ];

            // Tambahkan kolom per bulan (yang sudah terurut)
            foreach (array_keys($allMonths) as $bulan) {
                $row[$bulan] = $dx[$bulan] ?? 0;
            }

            $table[] = $row;
        }

        // Optional: urutkan berdasarkan jumlah total
        usort($table, function ($a, $b) use ($allMonths) {
            $sumA = array_sum(array_intersect_key($a, array_flip(array_keys($allMonths))));
            $sumB = array_sum(array_intersect_key($b, array_flip(array_keys($allMonths))));
            return $sumB <=> $sumA;
        });

        // Header kolom
        $headers = array_merge(['kddx', 'namadx'], array_values($allMonths));

        // Generate HTML
        $html = '<table id="jumlahDxTable" class="table table-bordered table-striped">';
        $html .= '<thead class="bg bg-orange table-bordered"><tr>';
        foreach ($headers as $header) {
            $html .= '<th>' . $header . '</th>';
        }
        $html .= '</tr></thead><tbody>';

        foreach ($table as $row) {
            $html .= '<tr>';
            foreach (array_keys($row) as $key) {
                $html .= '<td>' . $row[$key] . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }
    private function generateDiagnosaTable($pertahun)
    {
        $html = '';

        foreach ($pertahun as $tahun => $diagnosas) {
            // Header tahun
            // $html .= '<h4>Diagnosa Tahun ' . $tahun . '</h4>';
            $html .= '<table id="jumlahDxPerTahunTable" class="table table-bordered table-striped">';
            $html .= '<thead class="bg bg-primary text-white">';
            $html .= '<tr>';
            $html .= '<th>Kode Dx</th>';
            $html .= '<th>Nama Diagnosa</th>';
            $html .= '<th>Jumlah Tahun ' . $tahun . '</th>';
            $html .= '</tr>';
            $html .= '</thead><tbody>';

            // Sort diagnosa berdasarkan jumlah terbanyak
            uasort($diagnosas, function ($a, $b) {
                return $b['jumlah'] <=> $a['jumlah'];
            });

            foreach ($diagnosas as $dx) {
                $html .= '<tr>';
                $html .= '<td>' . $dx['kddx'] . '</td>';
                $html .= '<td>' . $dx['namadx'] . '</td>';
                $html .= '<td class="text-end">' . number_format($dx['jumlah']) . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody></table><br>';
        }

        return $html;
    }

}
