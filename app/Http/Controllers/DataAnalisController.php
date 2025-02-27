<?php
namespace App\Http\Controllers;

use App\Models\KasirTransModel;
use App\Models\KominfoModel;
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
        $title  = 'Data Analis';
        $params = [
            'tanggal_awal'  => date('Y-m-d'),
            'tanggal_akhir' => date('Y-m-d'),
        ];
        $data = $this->getData($params);
        // dd($data);
        return view('AnalisisData.main', compact('data'))->with('title', $title);
    }

    // public function DataBiayaKunjungan(Request $request)
    // {
    //     $params = $request->all();
    //     $data   = $this->getData($params);

    //     //buatkan html tabel yang mengisi data

    //     return response()->json($data);
    // }

    public function DataBiayaKunjungan(Request $request)
    {
        $params = $request->all();
        $data   = $this->getData($params);
        // return response()->json($data);

        $html = '<table id="kunjunganTable" class="table table-bordered table-striped">';
        $html .= '<thead class="bg-info">
                <tr>
                <th rowspan="2">No RM</th>
                    <th class="align-item-center" rowspan="2">Total Kunjungan</th>
                    <th class="align-item-center" rowspan="2">Tanggal Pertama</th>
                    <th class="align-item-center" rowspan="2">Tanggal Kedua</th>
                    <th class="align-item-center" rowspan="2">Kelurahan</th>
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

    public function getData(array $params)
    {
        $kominfo                 = new KominfoModel();
        $dataPendaftaranResponse = $kominfo->pendaftaran($params);

        // Filter hanya yang memiliki status "SELESAI DIPANGGIL LOKET PENDAFTARAN"
        $filteredData = array_filter($dataPendaftaranResponse, fn($item) =>
            isset($item['keterangan']) && $item['keterangan'] === 'SELESAI DIPANGGIL LOKET PENDAFTARAN'
        );

        // Ambil semua notrans untuk mengurangi query
        $notransList        = array_column($filteredData, 'notrans');
        $dataBiayaKunjungan = KasirTransModel::whereIn('notrans', $notransList)->get()->keyBy('notrans');

        $data = array_map(function ($item) use ($dataBiayaKunjungan) {
            $notrans = $item['notrans'];
            $biaya   = $dataBiayaKunjungan[$notrans] ?? null;

            return [
                'status_pulang'    => $item['status_pulang'],
                'no_reg'           => $item['no_reg'],
                'id'               => $item['id'],
                'tanggal'          => $item['tanggal'],
                'pasien_no_rm'     => $item['pasien_no_rm'],
                'pasien_lama_baru' => $item['pasien_lama_baru'],
                'pasien_nama'      => $item['pasien_nama'],
                'pasien_alamat'    => $item['pasien_alamat'],
                'notrans'          => $item['notrans'],
                'jaminan'          => $item['penjamin_nama'],
                'tagihan'          => $biaya->tagihan ?? "0 - BPJS",
                'bayar'            => $biaya->bayar ?? "0 - BPJS",
                'kembalian'        => $biaya->kembalian ?? "0 - BPJS",
                'kabupaten'        => $item['kabupaten'],
                'kelurahan'        => $item['kelurahan'],
            ];
        }, $filteredData);

        // Hitung jumlah pasien baru dan lama dalam satu iterasi
        $counts = ['baru' => [], 'lama' => [], 'total' => []];

        foreach ($data as $item) {
            $no_rm = $item['pasien_no_rm'];

            if ($item['pasien_lama_baru'] === 'BARU') {
                $counts['baru'][$no_rm]['jumlah_baru']  = ($counts['baru'][$no_rm]['jumlah_baru'] ?? 0) + 1;
                $counts['baru'][$no_rm]['tagihan_baru'] = ($counts['baru'][$no_rm]['tagihan_baru'] ?? 0) + floatval($item['tagihan']);

                if (! isset($counts['baru'][$no_rm]['tanggal_baru']) || $item['tanggal'] < $counts['baru'][$no_rm]['tanggal_baru']) {
                    $counts['baru'][$no_rm]['tanggal_baru'] = $item['tanggal'];
                }

                if (! isset($counts['baru'][$no_rm]['jaminan_baru'])) {
                    $counts['baru'][$no_rm]['jaminan_baru'] = $item['jaminan'];
                }

                if (! isset($counts['baru'][$no_rm]['pasien_nama'])) {
                    $counts['baru'][$no_rm]['pasien_nama'] = $item['pasien_nama'];
                }
                if (! isset($counts['baru'][$no_rm]['kabupaten'])) {
                    $counts['baru'][$no_rm]['kabupaten'] = $item['kabupaten'];
                }
                if (! isset($counts['baru'][$no_rm]['kelurahan'])) {
                    $counts['baru'][$no_rm]['kelurahan'] = $item['kelurahan'];
                }

            }

            if ($item['pasien_lama_baru'] === 'LAMA') {
                $counts['lama'][$no_rm]['jumlah_lama'] = ($counts['lama'][$no_rm]['jumlah_lama'] ?? 0) + 1;
                // Simpan tanggal kontrol pertama
                if (! isset($counts['lama'][$no_rm]['tanggal_kontrol_pertama'])) {
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
            'no_rm'                   => $no_rm,
            'nama'                    => $dataBaru['pasien_nama'],
            'kabupaten'               => $dataBaru['kabupaten'],
            'kelurahan'               => $dataBaru['kelurahan'],
            'jumlah_total_kunjungan'  => $counts['total'][$no_rm],
            'tanggal_kontrol_pertama' => $counts['lama'][$no_rm]['tanggal_kontrol_pertama'] ?? "-",
            'tanggal_baru'            => $dataBaru['tanggal_baru'],
            'tagihan_baru'            => number_format($dataBaru['tagihan_baru'], 2, '.', ''),
            'jaminan_saat_baru'       => $dataBaru['jaminan_baru'],
            'jaminan_lama_umum'       => $counts['lama'][$no_rm]['jaminan_lama_umum'] ?? 0,
            'jaminan_lama_bpjs'       => $counts['lama'][$no_rm]['jaminan_lama_bpjs'] ?? 0,
            'jumlah_baru'             => $dataBaru['jumlah_baru'],
            'datang_lagi'             => $counts['total'][$no_rm] - $dataBaru['jumlah_baru'],
        ], array_keys($counts['baru']), $counts['baru']);

        $totalMuncul = array_sum(array_column($detail, 'jumlah_total_kunjungan'));

        $res =
            [
            'total_pasien_baru' => count($counts['baru']),
            'total_kemunculan'  => $totalMuncul,
            'detail'            => $detail,
        ];
        return $res;
    }

}
