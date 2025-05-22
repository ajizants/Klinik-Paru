<?php
namespace App\Http\Controllers;

use App\Models\ApiKominfo;
use App\Models\KasirTransModel;
use App\Models\KominfoModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ApiKominfoController extends Controller
{

    // public function cetakSEP(string $no_sep)
    // {
    //     $model = new KominfoModel();
    //     $data = $model->getDetailSEP($no_sep);
    //     $detailSEP = $data['data'];

    //     // Generate QR Code in SVG format
    //     $qrCode = QrCode::format('svg')->size(100)->generate($detailSEP['peserta']['noKartu']);

    //     $qrCodePath = 'data:image/png;base64,' . base64_encode($qrCode);

    //     // Generate the PDF with the converted PNG QR code
    //     $pdf = PDF::loadView('Laporan.Pasien.sepPdf', compact('detailSEP', 'qrCodePath'));

    //     return $pdf->stream('sep.pdf');
    // }

    public function data_rencana_kontrol(Request $request)
    {
        $model = new ApiKominfo();
        $data = $model->data_pasien_kontrol($request->all());

        // Cek jika data kosong atau tidak valid
        if (!$data || count($data) == 0) {
            return response()->json([
                'html' => '<p class="text-center text-danger">Tidak ada data tersedia</p>',
                'data' => [],
            ]);
        }

        $html = '<table class="table table-bordered table-hover dataTable dtr-inline" id="rencanaKontrolTable">
            <thead class="bg bg-info">
                <tr>
                    <th>No</th>
                    <th>Kontrol Selanjutnya</th>
                    <th>Jaminan</th>
                    <th>No RM</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No HP Pasien</th>
                    <th>Penanggung Jawab</th>
                    <th>No Hp Penanggung Jawab</th>
                    <th>Dokter</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($data as $index => $d) {
            $alamatFull = ($d['kelurahan_nama'] ?? '') . ', ' .
                ($d['pasien_rt'] ?? '') . '/' . ($d['pasien_rw'] ?? '') . ', ' .
                ($d['kecamatan_nama'] ?? '') . ', ' . ($d['kabupaten_nama'] ?? '');

            $html .= "<tr>
                <td>" . ($index + 1) . "</td>
                <td>" . ($d['tanggal_kontrol_selanjutnya'] ?? '-') . "</td>
                <td>" . ($d['penjamin_nama'] ?? '-') . "</td>
                <td>" . ($d['pasien_no_rm'] ?? '-') . "</td>
                <td>" . ($d['pasien_nama'] ?? '-') . "</td>
                <td>" . ($alamatFull ?: '-') . "</td>
                <td>" . ($d['pasien_no_hp'] ?? '-') . "</td>
                <td>" . ($d['pasien_penanggung_jawab_nama'] ?? '-') . "</td>
                <td>" . ($d['pasien_penanggung_jawab_no_hp'] ?? '-') . "</td>
                <td>" . ($d['dokter_nama'] ?? '-') . "</td>
            </tr>";
        }

        $html .= '</tbody> </table>';

        return response()->json([
            'html' => $html,
            'data' => $data,
        ]);
    }

    public function poliDokter()
    {
        $model = new KominfoModel();
        $res = $model->getAkssLoket();
        $data = $res['data'];
        $jadwalDokter = array_filter($data, function ($item) {
            return stripos($item['admin_nama'], 'dr. ') !== false;
        });
        $jadwalDokter = array_values($jadwalDokter);
        return $jadwalDokter;

        $html = '<table id="jadwal_ruang_dokter" class="table table-bordered table-striped">';
        $html .= '<thead class="bg bg-orange table-bordered">
                     <tr>
                        <th>NO</th>
                        <th>Nama Dokter</th>
                        <th>Jadwal</th>
                    </tr>
                </thead>';
        $html .= '<tbody>';

        foreach ($jadwalDokter as $index => $item) {
            $html .= '<tr>';
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . $item['admin_nama'] . '</td>';
            $html .= '<td>' . $item['loket_nama'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        return response()->json(['html' => $html]);
    }

    public function getDataSEP(Request $request)
    {
        $model = new KominfoModel();
        $params = [
            'tanggal_awal' => $request->input('tanggal_awal') ?? date('Y-m-d'),
            'tanggal_akhir' => $request->input('tanggal_akhir') ?? date('Y-m-d'),
        ];
        // dd($params);
        $data = $model->getDataSEP($params);
        $res = $data['data'];
        // tambahkan aksi di $res
        foreach ($res as &$item) {
            $item['aksi'] = '
                <a href="' . url('api/sep/cetak/' . $item['no_sep']) . '" target="_blank" class="btn btn-sm btn-primary">Cetak SEP</a>
            ';
        }

        return response()->json($res);
    }
    public function getDataSEPSK(Request $request)
    {
        $model = new KominfoModel();
        $params = [
            'tanggal_awal' => $request->input('tanggal_awal') ?? date('Y-m-d'),
            'tanggal_akhir' => $request->input('tanggal_akhir') ?? date('Y-m-d'),
        ];

        // Ambil data SEP
        $data = $model->getDataSEP($params);
        $res = $data['data'];

        // Ambil data Surat Kontrol
        $dataSurat = $model->getDataSuratKontrol($params);
        $resSurat = $dataSurat['data'];

        // Index data surat kontrol berdasarkan pasien_nik untuk efisiensi pencarian
        $suratKontrolMap = [];
        foreach ($resSurat as $surat) {
            $nik = $surat['pasien_nik'];
            $suratKontrolMap[$nik] = $surat; // Jika 1 pasien hanya 1 surat. Kalau lebih, bisa ubah jadi array.
        }

        // Tambahkan aksi ke data SEP
        foreach ($res as &$item) {
            $nik = $item['pasien_nik'];

            $aksi = '<a href="' . url('api/sep/cetak/' . $item['no_sep']) . '" target="_blank" class="btn btn-sm btn-primary mt-2 col">SEP</a>
            <a href="' . url('api/sep/billing/cetak/' . $item['no_sep']) . '" target="_blank" class="btn btn-sm btn-warning mt-2 col">SEP & Billing</a> ';

            // Cek apakah ada surat kontrol untuk NIK tersebut
            if (isset($suratKontrolMap[$nik])) {
                $noSurat = $suratKontrolMap[$nik]['no_surat_kontrol']; // Atau 'no_surat_kontrol' kalau kamu pakai itu
                $aksi .= '<a href="' . url('api/SuratKontrol/cetak/' . $noSurat) . '" target="_blank" class="btn btn-sm btn-success mt-2 col">S.Kontrol</a>';
                // Tambahkan data Surat Kontrol ke dalam data SEP
                $item['no_surat_kontrol'] = $surat['no_surat_kontrol'] ?? null;
                $item['tanggal_rencana_kontrol'] = $surat['tanggal_rencana_kontrol'] ?? null;
                $item['tanggal_tampil'] = $surat['tanggal_tampil'] ?? null;
                $item['tanggal_rencana_kontrol_tampil'] = $surat['tanggal_rencana_kontrol_tampil'] ?? null;
                $item['detail_surat_kontrol'] = '
                            <p>No Surat Kontrol: <br>' . ($surat['no_surat_kontrol'] ?? '-') . '</p>

                            <p>Rencana Kontrol: <br>' . ($surat['tanggal_rencana_kontrol_tampil'] ?? '-') . '</p>
                        ';

            } else {
                // Kalau tidak ada surat kontrol, tetap null
                $item['no_surat_kontrol'] = null;
                $item['tanggal_rencana_kontrol'] = null;
                $item['tanggal_tampil'] = null;
                $item['tanggal_rencana_kontrol_tampil'] = null;

                // Tampilkan info kosong di detail
                $item['detail_surat_kontrol'] = '
                                                    <p>No Surat Kontrol: -</p>

                                                    <p>Tanggal Rencana Kontrol: -</p>
                                                ';
            }

            $sepTanggal = $item['tanggal_sep'] ?? null;
            $sepTanggalTampil = $item['tanggal_sep_tampil'] ?? '-';
            $tanggalTampil = $item['tanggal_tampil'] ?? '-';

            $item['detail_sep'] = '
                                        <p>No SEP: <br>' . ($item['no_sep'] ?? '-') . '</p>

                                        <p>Tanggal SEP: <br>' . $sepTanggalTampil . '</p>
                                    ';

            $item['aksi'] = $aksi;
        }

        return response()->json($res);
    }

    public function getDetailSEP(Request $request)
    {
        $model = new KominfoModel();
        $no_sep = $request->input('no_sep');
        // dd($params);
        $data = $model->getDetailSEP($no_sep);
        return response()->json($data);
    }
    public function cetakSEP(string $no_sep)
    {
        $model = new KominfoModel();
        // dd($params);
        $data = $model->getDetailSEP($no_sep);
        $detailSEP = $data['data'];
        return response()->json($detailSEP);

        //     // Buat QR Code dengan logo
        //     $qrCode = QrCode::format('svg') // atau svg
        //         ->size(100)
        //         ->errorCorrection('H')
        //         ->generate($detailSEP['peserta']['noKartu']);

        //     // dd($qrCode);
        //     $base64QrCode = base64_encode($qrCode);
        //     $qrCodeImage = 'data:image/png;base64,' . $base64QrCode;

        $noKartu = $detailSEP['peserta']['noKartu'];
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($noKartu) . '&size=100x100';

        $qrCodeBase64 = base64_encode(file_get_contents($qrCodeUrl));
        //buat judul, yaitu 6 digit terakhir dari noSEP
        $judul = substr($detailSEP['noSep'], -6);

        // return view('Laporan.Pasien.sepPdf', compact('detailSEP', 'qrCodeBase64'));
        // Generate the PDF with the converted PNG QR code
        $pdf = PDF::loadView('Laporan.Pasien.sepPdf', compact('detailSEP', 'qrCodeBase64'));

        return $pdf->stream($judul . '.pdf'); // Generate the PDF with the converted PNG QR code

    }
    public function cetakSEPBilling(string $no_sep)
    {
        $model = new KominfoModel();
        // dd($params);
        $data = $model->getDetailSEP($no_sep);
        $detailSEP = $data['data'];
        // return response()->json($detailSEP);

        $norm = $detailSEP['peserta']['noMr'];
        // $norm = '029762';
        $tglKunjungan = $detailSEP['tglSep'];

        $dataTagihan = KasirTransModel::with('item.layanan')
            ->where('norm', $norm)
            ->whereBetween('created_at', [
                $tglKunjungan . ' 00:00:00',
                $tglKunjungan . ' 23:59:59',
            ])->first();
        // return response()->json($dataTagihan);
        if (!isset($dataTagihan)) {
            $lab = null;
            $totalLab = 0;
            $ro = null;
            $totalRo = 0;
            $tindakan = null;
            $totalTindakan = 0;
            $obat = null;
            $totalObat = 0;
            $obatKronis = null;
            $totalObatKronis = 0;
            $bmhp = null;
            $totalbmhp = 0;
        } else {
            $rincian = array_values($dataTagihan->toArray()['item']);

            $lab = array_filter($rincian, function ($item) {
                return stripos($item['layanan']['kelas'], 9) !== false && $item['layanan']['idLayanan'] !== 131 && $item['layanan']['idLayanan'] !== 214;
            });
            if (count($lab) == 0) {
                $lab = null;
                $totalLab = 0;
            } else {
                $lab = array_values($lab);
                $totalLab = 0;

                foreach ($lab as &$item) {
                    // Hapus teks dalam tanda kurung dari nmLayanan
                    $item['layanan']['nmLayanan'] = preg_replace('/\s*\(.*?\)/', '', $item['layanan']['nmLayanan']);

                    // Hitung total harga
                    $totalLab += $item['totalHarga'];
                }
                unset($item); // Hindari referensi yang tidak disengaja
            }
            // return $lab;

            $ro = array_filter($rincian, function ($item) {
                return stripos($item['layanan']['kelas'], 8) !== false;
            });
            if (count($ro) == 0) {
                $ro = null;
                $totalRo = 0;
            } else {
                $ro = array_values($ro);
                $totalRo = 0;
                foreach ($ro as $item) {
                    $totalRo += $item['totalHarga'];
                }
            }

            $tindakan = array_filter($rincian, function ($item) {
                // Casting ke int untuk memastikan tipe
                return in_array((int) $item['layanan']['kelas'], [5, 6, 7], true);
            });
            if (count($tindakan) == 0) {
                $tindakan = null;
                $totalTindakan = 0;
            } else {

                $tindakan = array_values($tindakan);
                $totalTindakan = 0;
                foreach ($tindakan as $item) {
                    $totalTindakan += $item['totalHarga'];
                }
                // return $ro;
            }

            $obat = array_filter($rincian, function ($item) {
                return $item['layanan']['idLayanan'] == 2;
            });
            // return $obat;

            if (count($obat) == 0) {
                $obat = null;
                $totalObat = 0;
            } else {
                // return $obat;
                $obat = array_values($obat);
                $totalObat = $obat[0]['totalHarga'];
            }
            $obatKronis = array_filter($rincian, function ($item) {
                return $item['layanan']['idLayanan'] == 228;
            });

            if (count($obatKronis) == 0) {
                $obatKronis = null;
                $totalObatKronis = 0;
            } else {
                $obatKronis = array_values($obatKronis);
                $totalObatKronis = $obatKronis[0]['totalHarga'];
                // return $obatKronis;
            }
            $bmhp = array_filter($rincian, function ($item) {
                return $item['layanan']['idLayanan'] == 229;
            });

            if (count($bmhp) == 0) {
                $bmhp = null;
                $totalbmhp = 0;
            } else {
                // return $bmhp;
                $bmhp = array_values($bmhp);
                $totalbmhp = $bmhp[0]['totalHarga'];
            }
        }

        $noKartu = $detailSEP['peserta']['noKartu'];
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($noKartu) . '&size=100x100';

        $qrCodeBase64 = base64_encode(file_get_contents($qrCodeUrl));
        //buat judul, yaitu 6 digit terakhir dari noSEP
        $judul = substr($detailSEP['noSep'], -6);

        return view('Laporan.Pasien.sepBilling',
            compact('detailSEP', 'qrCodeBase64', 'dataTagihan', 'lab',
                'totalLab', 'totalRo', 'ro', 'totalTindakan', 'tindakan',
                'totalObat', 'obat', 'totalObatKronis', 'totalbmhp'
            ));
        $pdf = PDF::loadView('Laporan.Pasien.sepBilling',
            compact('detailSEP', 'qrCodeBase64', 'dataTagihan', 'lab',
                'totalLab', 'totalRo', 'ro', 'totalTindakan', 'tindakan',
                'totalObat', 'obat', 'totalObatKronis', 'totalbmhp'
            ));

        return $pdf->stream($judul . '.pdf'); // Generate the PDF with the converted PNG QR code

    }

    public function getDataSuratKontrol(Request $request)
    {
        $model = new KominfoModel();
        $params = [
            'tanggal_awal' => $request->input('tanggal_awal') ?? date('Y-m-d'),
            'tanggal_akhir' => $request->input('tanggal_akhir') ?? date('Y-m-d'),
        ];
        // dd($params);
        $data = $model->getDataSuratKontrol($params);
        $res = $data['data'];
        // tambahkan aksi di $res
        foreach ($res as &$item) {
            $item['aksi'] = '
                <a href="' . url('api/SuratKontrol/cetak/' . $item['no_surat_kontrol']) . '" target="_blank" class="btn btn-sm btn-primary">Cetak SuratKontrol</a>
            ';
        }

        return response()->json($res);
    }
    public function getDetailSuratKontrol(Request $request)
    {
        $model = new KominfoModel();
        $no_SuratKontrol = $request->input('no_SuratKontrol');
        // dd($params);
        $data = $model->getDetailSuratKontrol($no_SuratKontrol);
        return response()->json($data);
    }
    public function cetakSuratKontrol(string $no_SuratKontrol)
    {
        $model = new KominfoModel();
        // dd($params);
        $data = $model->getDetailSuratKontrol($no_SuratKontrol);
        $detailSuratKontrol = $data['data'];
        // return response()->json($detailSuratKontrol);

        return view('Laporan.Pasien.SuratKontrol', compact('detailSuratKontrol'));
    }

    public function getJumlahPemeriksaanDokter($tahun, $bln)
    {
        $kominfo = new KominfoModel();
        $result = [];

        for ($bulan = 1; $bulan <= $bln; $bulan++) {
            $bulanFormatted = str_pad($bulan, 2, '0', STR_PAD_LEFT);
            $tanggal_awal = "$tahun-$bulanFormatted-01";
            $tanggal_akhir = date("Y-m-t", strtotime($tanggal_awal));

            $params = [
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
                'no_rm' => '',
            ];
            // $params = [
            //     'tanggal_awal' => '2025-07-01',
            //     'tanggal_akhir' => '2025-07-31',
            //     'no_rm' => '',
            // ];

            $response = $kominfo->poinRequest($params);
            // dd($response);

            if ($response['metadata']['code'] == 201) {
                // Tidak ada data, buat dummy entri dengan jumlah 0
                $res = [[
                    "ruang_nama" => "Ruang Poli (Dokter CPPT)",
                    "admin_nama" => "dr. Agil Dananjaya, Sp.P",
                    "jumlah" => 0,
                ], [
                    "ruang_nama" => "Ruang Poli (Dokter CPPT)",
                    "admin_nama" => "dr. Cempaka Nova Intani, Sp.P, FISR., MM.",
                    "jumlah" => 0,
                ], [
                    "ruang_nama" => "Ruang Poli (Dokter CPPT)",
                    "admin_nama" => "dr. Filly Ulfa Kusumawardani",
                    "jumlah" => 0,
                ], [
                    "ruang_nama" => "Ruang Poli (Dokter CPPT)",
                    "admin_nama" => "dr. Sigit Dwiyanto",
                    "jumlah" => 0,
                ]];
            } else {
                // Ambil dan filter data yang sesuai
                $data = $response['response']['data'];
                $res = array_filter($data, function ($item) {
                    return
                    ($item['ruang_nama'] ?? '') === 'Ruang Poli (Dokter CPPT)' &&
                    strtoupper($item['admin_nama'] ?? '') !== 'AJI SANTOSO';
                });

                // Jika setelah filter kosong, tetap tambahkan entri default
                if (empty($res)) {
                    $res = [[
                        "ruang_nama" => "Ruang Poli (Dokter CPPT)",
                        "admin_nama" => "dr. Agil Dananjaya, Sp.P",
                        "jumlah" => 0,
                    ], [
                        "ruang_nama" => "Ruang Poli (Dokter CPPT)",
                        "admin_nama" => "dr. Cempaka Nova Intani, Sp.P, FISR., MM.",
                        "jumlah" => 0,
                    ], [
                        "ruang_nama" => "Ruang Poli (Dokter CPPT)",
                        "admin_nama" => "dr. Filly Ulfa Kusumawardani",
                        "jumlah" => 0,
                    ], [
                        "ruang_nama" => "Ruang Poli (Dokter CPPT)",
                        "admin_nama" => "dr. Sigit Dwiyanto",
                        "jumlah" => 0,
                    ]];
                }
            }

            // Simpan hasil
            $result[$bulanFormatted] = array_values($res); // reset key numerik
        }

        $html = $this->generatejmlDokterPeriksaTable($result, $tahun);
        $chart = $this->generatejmlDokterPeriksaChart($result, $tahun);
        $res = [
            'html' => $html,
            'chart' => $chart,
        ];

        return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }

    public function generatejmlDokterPeriksaTable($data, $tahun)
    {
        // Ambil semua nama dokter unik dari seluruh bulan
        $dokterSet = [];
        foreach ($data as $bulan => $entries) {
            foreach ($entries as $entry) {
                $dokterSet[$entry['admin_nama']] = true;
            }
        }

        // Urutkan nama dokter
        $dokterList = array_keys($dokterSet);
        sort($dokterList);

        // Mulai bangun HTML tabel
        $html = '<table id="jumlahDokterTable" class="table table-bordered table-striped dataTable no-footer dtr-inline"
                aria-describedby="jumlahLabTable">';
        $html .= '<thead><tr>';
        $html .= '<th>Bulan-Tahun</th>';

        // Buat header dinamis dari nama dokter
        foreach ($dokterList as $dokter) {
            $html .= '<th>' . htmlspecialchars($dokter) . '</th>';
        }
        $html .= '</tr></thead><tbody>';

        // Bangun baris-baris data
        foreach ($data as $bulan => $entries) {
            $html .= '<tr>';
            $html .= '<td>' . $bulan . '-' . $tahun . '</td>';

            // Buat mapping nama dokter → jumlah untuk bulan ini
            $jumlahMap = [];
            foreach ($entries as $entry) {
                $jumlahMap[$entry['admin_nama']] = $entry['jumlah'];
            }

            // Cetak kolom jumlah berdasarkan urutan dokter
            foreach ($dokterList as $dokter) {
                $jumlah = $jumlahMap[$dokter] ?? 0;
                $html .= '<td>' . htmlspecialchars($jumlah) . '</td>';
            }

            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }

    public function generatejmlDokterPeriksaChart($data, $tahun)
    {
        // Inisialisasi label bulan dari 01 sampai 12
        $labels = [];
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $tahun . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $dokterData = [];

        // Loop setiap bulan dari 01 sampai 12
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $bulanStr = str_pad($bulan, 2, '0', STR_PAD_LEFT);
            $entries = $data[$bulanStr] ?? [];

            // Catat jumlah pasien tiap dokter
            $currentMonthData = [];

            foreach ($entries as $entry) {
                $nama = $entry['admin_nama'];
                $jumlah = (int) $entry['jumlah'];

                // Jika belum ada, inisialisasi array dengan nol untuk bulan-bulan sebelumnya
                if (!isset($dokterData[$nama])) {
                    $dokterData[$nama] = array_fill(0, $bulan - 1, 0);
                }

                // Masukkan jumlah pasien di bulan ini
                $dokterData[$nama][] = $jumlah;

                // Tandai dokter yang sudah dimasukkan data bulan ini
                $currentMonthData[$nama] = true;
            }

            // Tambahkan 0 untuk dokter yang tidak ada data di bulan ini
            foreach ($dokterData as $nama => &$dataArr) {
                if (!isset($currentMonthData[$nama])) {
                    $dataArr[] = 0;
                }
            }
            unset($dataArr); // Penting untuk menghindari pointer referensi aktif
        }

        // Format akhir untuk chart.js
        $datasets = [];
        $colors = [
            '#36A2EB', '#FF6384', '#4BC0C0', '#9966FF', '#FF9F40', '#8e44ad',
            '#e67e22', '#16a085', '#f1c40f', '#2c3e50', '#d35400', '#27ae60',
        ];

        $i = 0;
        foreach ($dokterData as $nama => $dataArr) {
            $color = $colors[$i % count($colors)];
            $datasets[] = [
                'label' => $nama,
                'data' => $dataArr,
                'borderColor' => $color,
                'backgroundColor' => $this->hexToRgba($color, 0),
                'tension' => 0.3,
                'fill' => true,
            ];
            $i++;
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

// Helper function untuk convert hex ke rgba
    private function hexToRgba($hex, $alpha = 1.0)
    {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) === 3) {
            $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        return "rgba($r, $g, $b, $alpha)";
    }

    public function reportPusatDataWaktuTunggu($tahun)
    {
        $kominfo = new KominfoModel();
        $result = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            // Format bulan dengan leading zero, misal: 01, 02, ..., 12
            $bulanFormatted = str_pad($bulan, 2, '0', STR_PAD_LEFT);

            // Buat tanggal awal dan akhir bulan
            $tanggal_awal = "$tahun-$bulanFormatted-01";
            $tanggal_akhir = date("Y-m-t", strtotime($tanggal_awal)); // tanggal akhir bulan

            // Siapkan parameter untuk request
            $params = [
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
                'no_rm' => '',
            ];

            // Ambil data dari model
            $data = $kominfo->pendaftaranRequest($params);

            // Proses data jika perlu
            $res = $this->waktuTungguProses($data, $bulanFormatted);

            // Simpan hasil ke dalam array result dengan struktur baru
            $result['html'][$bulanFormatted] = $res['html'];
            $result['total'][$bulanFormatted] = $res['total'];

        }

        return response()->json($result);
    }
}
