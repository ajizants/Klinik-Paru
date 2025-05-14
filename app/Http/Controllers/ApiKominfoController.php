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

            $aksi = '<a href="' . url('api/sep/cetak/' . $item['no_sep']) . '" target="_blank" class="btn btn-sm btn-primary mt-2">SEP</a>
            <a href="' . url('api/sep/billing/cetak/' . $item['no_sep']) . '" target="_blank" class="btn btn-sm btn-warning mt-2">SEP & Billing</a> ';

            // Cek apakah ada surat kontrol untuk NIK tersebut
            if (isset($suratKontrolMap[$nik])) {
                $noSurat = $suratKontrolMap[$nik]['no_surat_kontrol']; // Atau 'no_surat_kontrol' kalau kamu pakai itu
                $aksi .= '<a href="' . url('api/SuratKontrol/cetak/' . $noSurat) . '" target="_blank" class="btn btn-sm btn-success mt-2">S.Kontrol</a>';
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
        // return response()->json($detailSEP);

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

}
