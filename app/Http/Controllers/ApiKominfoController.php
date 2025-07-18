<?php
namespace App\Http\Controllers;

use App\Models\ApiKominfo;
use App\Models\DiagnosaMapModel;
use App\Models\IGDTransModel;
use App\Models\KasirTransModel;
use App\Models\KominfoModel;
use App\Models\LaboratoriumHasilModel;
use App\Models\ROTransaksiModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $data  = $model->data_pasien_kontrol($request->all());

        // Cek jika data kosong atau tidak valid
        if (! $data || count($data) == 0) {
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
        $model        = new KominfoModel();
        $res          = $model->getAkssLoket();
        $data         = $res['data'];
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
        $model  = new KominfoModel();
        $params = [
            'tanggal_awal'  => $request->input('tanggal_awal') ?? date('Y-m-d'),
            'tanggal_akhir' => $request->input('tanggal_akhir') ?? date('Y-m-d'),
        ];
        // dd($params);
        $data = $model->getDataSEP($params);
        $res  = $data['data'];
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
        $model  = new KominfoModel();
        $params = [
            'tanggal_awal'  => $request->input('tanggal_awal') ?? date('Y-m-d'),
            'tanggal_akhir' => $request->input('tanggal_akhir') ?? date('Y-m-d'),
        ];

        // Ambil data SEP
        $data = $model->getDataSEP($params);
        $res  = $data['data'];

        foreach ($res as &$item) {
            $nik     = $item['pasien_nik'];
            $noSurat = $item['no_surat_kontrol'];
            $hidden  = $noSurat == "" ? "hidden" : "";
            $norm    = $item['pasien_no_rm'];
            $tgl     = $item['tanggal'];

            $aksi = '
            <a href="' . url('api/sep/cetak/' . $item['no_sep']) . '" target="_blank" class="btn btn-sm btn-primary mt-2 col">SEP</a>
            <a href="' . url('api/SuratKontrol/cetak/' . $noSurat . '/' . $norm) . '" ' . $hidden . ' target="_blank" class="btn btn-sm btn-success mt-2 col">S.Kontrol</a>
            <a href="' . url('api/rujukan/cetak/' . $tgl . '/' . $norm) . '" target="_blank" class="btn btn-sm bg-lime mt-2 col">Rujukan Baru</a>
            <a href="' . url('api/prb/cetak/' . $tgl . '/' . $norm) . '" target="_blank" class="btn btn-sm btn-info mt-2 col">Surat PRB</a>
            <a href="' . url('api/billing/cetak/' . $item['no_sep']) . '" target="_blank" class="btn btn-sm btn-warning mt-2 col">Billing</a>
            ';
            // $aksi = '
            // <a href="' . url('api/sep/cetak/' . $item['no_sep']) . '" target="_blank" class="btn btn-sm btn-primary mt-2 col">SEP</a>
            // <a href="' . url('api/SuratKontrol/cetak/' . $noSurat) . '" ' . $hidden . ' target="_blank" class="btn btn-sm btn-success mt-2 col">S.Kontrol</a>
            // <a href="' . url('api/prb/cetak/' . $tgl . '/' . $norm) . '" target="_blank" class="btn btn-sm btn-info mt-2 col">PPK1</a>
            // <a href="' . url('api/billing/cetak/' . $item['no_sep']) . '" target="_blank" class="btn btn-sm btn-warning mt-2 col">Billing</a>
            // <a href="' . url('api/billing/sep/cetak/' . $item['no_sep']) . '"  target="_blank" class="btn btn-sm bg-lime mt-2 col">SEP & Billing</a>
            // <a href="' . url('api/billing/suratkontrol/cetak/' . $noSurat) . '" ' . $hidden . ' target="_blank" class="btn btn-sm bg-orange mt-2 col">S.Kontrol & Billing</a>
            // ';

            $sepTanggal       = $item['tanggal_sep'] ?? null;
            $sepTanggalTampil = $item['tanggal_sep_tampil'] ?? '-';
            $tanggalTampil    = $item['tanggal_tampil'] ?? '-';

            $item['detail_sep'] = '
                                        <p>No SEP: <br>' . ($item['no_sep'] ?? '-') . '</p>

                                        <p>Tanggal SEP: <br>' . $sepTanggalTampil . '</p>
                                    ';
            $item['detail_surat_kontrol'] = '
                                            <p>No Surat Kontrol: <br>' . ($item['no_surat_kontrol'] ?? '-') . '</p>

                                            <p>Rencana Kontrol: <br>' . ($item['tanggal_rencana_kontrol_tampil'] ?? '-') . '</p>
                                            ';

            $item['aksi'] = $aksi;
        }

        return response()->json($res);
    }

    public function getDetailSEP(Request $request)
    {
        $model  = new KominfoModel();
        $no_sep = $request->input('no_sep');
        // dd($params);
        $data = $model->getDetailSEP($no_sep);
        return response()->json($data);
    }
    public function cetakSEP(string $no_sep)
    {
        $model = new KominfoModel();
        // dd($params);
        $data      = $model->getDetailSEP($no_sep);
        $detailSEP = $data['data'];
        // return response()->json($detailSEP);
        if (empty($detailSEP)) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        // return response()->json($detailSEP);

        $noKartu   = $detailSEP['peserta']['noKartu'];
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($noKartu) . '&size=100x100';

        $qrCodeBase64 = base64_encode(file_get_contents($qrCodeUrl));
        //buat judul, yaitu 6 digit terakhir dari noSEP

        $judul = ltrim(substr($detailSEP['noSep'], -6), '0');

        // return view('Laporan.Pasien.sepPdf', compact('detailSEP', 'qrCodeBase64'));
        // Generate the PDF with the converted PNG QR code
        $pdf = PDF::loadView('Laporan.Pasien.sepPdf', compact('detailSEP', 'qrCodeBase64'));

        return $pdf->stream($judul . '.pdf'); // Generate the PDF with the converted PNG QR code

    }
    public function cetakSEPBilling(string $no_sep)
    {
        $model = new KominfoModel();
        // dd($params);
        $data         = $model->getDetailSEP($no_sep);
        $detailSEP    = $data['data'];
        $norm         = $detailSEP['peserta']['noMr'];
        $tglKunjungan = $detailSEP['tglSep'];

        $dataTagihan = $this->getDataTagihan($norm, $tglKunjungan);
        // dd($dataTagihan);

        $lab             = $dataTagihan['lab'];
        $totalLab        = $dataTagihan['totalLab'];
        $ro              = $dataTagihan['ro'];
        $totalRo         = $dataTagihan['totalRo'];
        $tindakan        = $dataTagihan['tindakan'];
        $totalTindakan   = $dataTagihan['totalTindakan'];
        $obat            = $dataTagihan['obat'];
        $totalObat       = $dataTagihan['totalObat'];
        $obatKronis      = $dataTagihan['obatKronis'];
        $totalObatKronis = $dataTagihan['totalObatKronis'];
        $bmhp            = $dataTagihan['bmhp'];
        $totalbmhp       = $dataTagihan['totalbmhp'];

        $noKartu   = $detailSEP['peserta']['noKartu'];
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($noKartu) . '&size=100x100';

        $qrCodeBase64 = base64_encode(file_get_contents($qrCodeUrl));
        //buat judul, yaitu 6 digit terakhir dari noSEP
        $judul = ltrim(substr($detailSEP['noSep'], -6), '0');

        return view('Laporan.Pasien.billingSEP',
            compact('detailSEP', 'qrCodeBase64', 'dataTagihan', 'lab',
                'totalLab', 'totalRo', 'ro', 'totalTindakan', 'tindakan',
                'totalObat', 'obat', 'totalObatKronis', 'totalbmhp'
            ));
        $pdf = PDF::loadView('Laporan.Pasien.billingSEP',
            compact('detailSEP', 'qrCodeBase64', 'dataTagihan', 'lab',
                'totalLab', 'totalRo', 'ro', 'totalTindakan', 'tindakan',
                'totalObat', 'obat', 'totalObatKronis', 'totalbmhp'
            ));

        return $pdf->stream($judul . '.pdf'); // Generate the PDF with the converted PNG QR code

    }
    public function cetakBilling(string $no_sep)
    {
        $model = new KominfoModel();
        // dd($params);
        $data         = $model->getDetailSEP($no_sep);
        $detailSEP    = $data['data'];
        $norm         = $detailSEP['peserta']['noMr'];
        $tglKunjungan = $detailSEP['tglSep'];

        $dataTagihan = $this->getDataTagihan($norm, $tglKunjungan);
        // return response()->json($dataTagihan);

        $lab             = $dataTagihan['lab'];
        $totalLab        = $dataTagihan['totalLab'];
        $ro              = $dataTagihan['ro'];
        $totalRo         = $dataTagihan['totalRo'];
        $tindakan        = $dataTagihan['tindakan'];
        $totalTindakan   = $dataTagihan['totalTindakan'];
        $obat            = $dataTagihan['obat'];
        $totalObat       = $dataTagihan['totalObat'];
        $obatKronis      = $dataTagihan['obatKronis'];
        $totalObatKronis = $dataTagihan['totalObatKronis'];
        $bmhp            = $dataTagihan['bmhp'];
        $totalbmhp       = $dataTagihan['totalbmhp'];

        $noKartu   = $detailSEP['peserta']['noKartu'];
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($noKartu) . '&size=100x100';

        return view('Laporan.Pasien.billing',
            compact('detailSEP', 'dataTagihan', 'lab',
                'totalLab', 'totalRo', 'ro', 'totalTindakan', 'tindakan',
                'totalObat', 'obat', 'totalObatKronis', 'totalbmhp'
            ));
        $pdf = PDF::loadView('Laporan.Pasien.billing',
            compact('detailSEP', 'dataTagihan', 'lab',
                'totalLab', 'totalRo', 'ro', 'totalTindakan', 'tindakan',
                'totalObat', 'obat', 'totalObatKronis', 'totalbmhp'
            ));

        return $pdf->stream($judul . '.pdf'); // Generate the PDF with the converted PNG QR code

    }
    public function cetakBillingSuratKontrol(string $no_SuratKontrol)
    {
        $model = new KominfoModel();
        // dd($params);
        // dd($params);
        $data               = $model->getDetailSuratKontrol($no_SuratKontrol);
        $detailSuratKontrol = $data['data'];
        // return response()->json($detailSuratKontrol);

        $norm         = $detailSuratKontrol['sep']['data_rujukan']['rujukan']['peserta']['mr']['noMR'];
        $tglKunjungan = $detailSuratKontrol['sep']['tglSep'];

        $dataTagihan = $this->getDataTagihan($norm, $tglKunjungan);
        // return response()->json($dataTagihan);

        $lab             = $dataTagihan['lab'];
        $totalLab        = $dataTagihan['totalLab'];
        $ro              = $dataTagihan['ro'];
        $totalRo         = $dataTagihan['totalRo'];
        $tindakan        = $dataTagihan['tindakan'];
        $totalTindakan   = $dataTagihan['totalTindakan'];
        $obat            = $dataTagihan['obat'];
        $totalObat       = $dataTagihan['totalObat'];
        $obatKronis      = $dataTagihan['obatKronis'];
        $totalObatKronis = $dataTagihan['totalObatKronis'];
        $bmhp            = $dataTagihan['bmhp'];
        $totalbmhp       = $dataTagihan['totalbmhp'];

        return view('Laporan.Pasien.billingSuratKontrol',
            compact('detailSuratKontrol', 'dataTagihan', 'lab',
                'totalLab', 'totalRo', 'ro', 'totalTindakan', 'tindakan',
                'totalObat', 'obat', 'totalObatKronis', 'totalbmhp'
            ));
        $pdf = PDF::loadView('Laporan.Pasien.billing',
            compact('detailSuratKontrol', 'dataTagihan', 'lab',
                'totalLab', 'totalRo', 'ro', 'totalTindakan', 'tindakan',
                'totalObat', 'obat', 'totalObatKronis', 'totalbmhp'
            ));

        return $pdf->stream($judul . '.pdf'); // Generate the PDF with the converted PNG QR code

    }

    public function resumePasien(Request $request)
    {
        // $validator = Validators::make($request->all(), [
        //     'no_rm'  => 'required',
        //     'tgl'    => 'required|date',
        //     'no_sep' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 400);
        // }

        ['no_rm' => $no_rm, 'tgl' => $tgl, 'no_sep' => $no_sep] = $request->only(['no_rm', 'tgl', 'no_sep']);
        $params                                                 = ['no_rm' => $no_rm, 'tanggal_awal' => $tgl, 'tanggal_akhir' => $tgl];

        try {
            $dataTagihan = $this->getDataTagihan($no_rm, $tgl);
            if ($dataTagihan['data'] === null) {
                return response()->json(['message' => 'Data tagihan tidak ditemukan, silahkan lakukan transaksi kasir terlebih dahulu'], 404);
            }
            $client      = new KominfoModel();
            $dataCPPT    = $client->cpptRequest($params);
            $kunjungan   = $client->pendaftaranRequest($params)[0]['rs_paru_pasien_lama_baru'] ?? '-';
            $dataCpptArr = $dataCPPT['response']['data'] ?? [];

            $resumePasien = new \stdClass();
            if (is_array($dataCpptArr) && count($dataCpptArr) > 0) {
                $resumePasien = (object) ($dataCpptArr[0]['id_cppt'] == null && isset($dataCpptArr[1])
                    ? $dataCpptArr[1]
                    : $dataCpptArr[0]);
            }

            $obats = collect($resumePasien->resep_obat ?? [])->map(fn($obat) => [
                'no_resep' => $obat['no_resep'],
                'aturan'   => "{$obat['signa_1']} X {$obat['signa_2']} {$obat['aturan_pakai']}",
                'nm_obat'  => $obat['resep_obat_detail'],
            ])->toArray();

            $dxs = collect($resumePasien->diagnosa ?? [])->map(function ($dx) {
                $dxMap = DiagnosaMapModel::where('kdDx', $dx['kode_diagnosa'])->first();
                return [
                    'kode_diagnosa' => $dx['kode_diagnosa'],
                    'nama_diagnosa' => $dx['nama_diagnosa'],
                    'nmDx'          => $dxMap->mapping ?? $dx['nama_diagnosa'],
                ];
            })->toArray();

            $alamat = implode(', ', array_filter([
                ucwords(strtolower($resumePasien->kelurahan_nama ?? '')) . ' RT ' . $resumePasien->pasien_rt . '/' . $resumePasien->pasien_rw,
                ucwords(strtolower($resumePasien->kecamatan_nama ?? '')),
                ucwords(strtolower($resumePasien->kabupaten_nama ?? '')),
                ucwords(strtolower($resumePasien->provinsi_nama ?? '')),
            ]));

            $dataRo = ROTransaksiModel::with('film', 'foto', 'proyeksi')
                ->where('norm', $no_rm)
                ->where('tgltrans', $tgl)
                ->first();

            $ro = $dataRo ? [
                'noReg'     => $dataRo->noreg,
                'tglRo'     => Carbon::parse($dataRo->tgltrans)->format('d-m-Y'),
                'jenisFoto' => $dataRo->foto->nmFoto ?? '-',
                'proyeksi'  => $dataRo->proyeksi->proyeksi ?? '-',
            ] : [];

            $dataLab = LaboratoriumHasilModel::with('pemeriksaan')
                ->where('norm', $no_rm)
                ->whereDate('created_at', Carbon::parse($tgl))
                ->get();

            $lab = $dataLab->map(function ($item) use ($dataLab) {
                return [
                    'idLab'       => $item->idLab,
                    'idLayanan'   => $item->idLayanan,
                    'tanggal'     => Carbon::parse($item->created_at)->format('d-m-Y'),
                    'hasil'       => $item->hasil,
                    'pemeriksaan' => str_replace(' (Stik)', '', $item->pemeriksaan->nmLayanan),
                    'satuan'      => $item->pemeriksaan->satuan,
                    'normal'      => $item->pemeriksaan->normal,
                    'totalItem'   => $dataLab->count(),
                ];
            })->toArray();

            $dataTindakan = IGDTransModel::with('tindakan', 'transbmhp.bmhp')
                ->where('norm', $no_rm)
                ->whereDate('created_at', Carbon::parse($tgl))
                ->get();

            $tindakan = $dataTindakan->map(function ($item) use ($dataTindakan) {
                $bmhp = collect($item->transbmhp)->map(fn($b) => [
                    'jumlah'  => $b->jml,
                    'nmBmhp'  => $b->bmhp->nmObat ?? '-',
                    'sediaan' => $b->sediaan,
                ])->toArray();

                return [
                    'id'        => $item->id,
                    'kdTind'    => $item->kdTind,
                    'tanggal'   => Carbon::parse($item->created_at)->format('d-m-Y'),
                    'tindakan'  => preg_replace('/\s?\(.*?\)/', '', $item->tindakan->nmTindakan ?? "Tidak ada Tindakan"),
                    'bmhp'      => $bmhp,
                    'totalItem' => $dataTindakan->count(),
                ];
            })->toArray();

            // Get Detail SEP + Tagihan
            $detailSEP = $client->getDetailSEP($no_sep)['data'] ?? [];

            $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?data='
            . urlencode($detailSEP['peserta']['noKartu'] ?? '') . '&size=100x100';

            // return [
            //     'resumePasien'    => $resumePasien,
            //     'alamat'          => $alamat,
            //     'kunjungan'       => $kunjungan,
            //     'detailSEP'       => $detailSEP,
            //     'dataTagihan'     => $dataTagihan,
            //     'obats'           => $obats,
            //     'dxs'             => $dxs,
            //     'ro'              => $dataTagihan['ro'],
            //     'lab'             => $dataTagihan['lab'],
            //     'tindakan'        => $dataTagihan['tindakan'],
            //     'totalLab'        => $dataTagihan['totalLab'],
            //     'totalRo'         => $dataTagihan['totalRo'],
            //     'totalTindakan'   => $dataTagihan['totalTindakan'],
            //     'totalObat'       => $dataTagihan['totalObat'],
            //     'obat'            => $dataTagihan['obat'],
            //     'totalObatKronis' => $dataTagihan['totalObatKronis'],
            //     'totalbmhp'       => $dataTagihan['totalbmhp'],
            // ];
            return view('Laporan.Pasien.resumeBilling', [
                'resumePasien'    => $resumePasien,
                'alamat'          => $alamat,
                'kunjungan'       => $kunjungan,
                'detailSEP'       => $detailSEP,
                'dataTagihan'     => $dataTagihan,
                'obats'           => $obats,
                'dxs'             => $dxs,
                'ro'              => $dataTagihan['ro'],
                'lab'             => $dataTagihan['lab'],
                'tindakan'        => $dataTagihan['tindakan'],
                'totalLab'        => $dataTagihan['totalLab'],
                'totalRo'         => $dataTagihan['totalRo'],
                'totalTindakan'   => $dataTagihan['totalTindakan'],
                'totalObat'       => $dataTagihan['totalObat'],
                'obat'            => $dataTagihan['obat'],
                'totalObatKronis' => $dataTagihan['totalObatKronis'],
                'totalbmhp'       => $dataTagihan['totalbmhp'],
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal memuat resume pasien: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function getDataTagihan($norm, $tglKunjungan)
    {

        $dataTagihan = KasirTransModel::with('item.layanan')
            ->where('norm', $norm)
            ->whereBetween('created_at', [
                $tglKunjungan . ' 00:00:00',
                $tglKunjungan . ' 23:59:59',
            ])->first();
        // return $dataTagihan;

        $res = [
            'data'            => $dataTagihan,
            'lab'             => null,
            'totalLab'        => 0,
            'ro'              => null,
            'totalRo'         => 0,
            'tindakan'        => null,
            'totalTindakan'   => 0,
            'obat'            => null,
            'totalObat'       => 0,
            'obatKronis'      => null,
            'totalObatKronis' => 0,
            'bmhp'            => null,
            'totalbmhp'       => 0,
        ];

        if ($dataTagihan) {
            $rincian = array_values($dataTagihan->toArray()['item']);

            [$res['lab'], $res['totalLab']] = $this->filterAndSum($rincian, function ($item) {
                return stripos($item['layanan']['kelas'], 9) !== false
                && ! in_array($item['layanan']['idLayanan'], [131, 214]);
            }, true);

            [$res['ro'], $res['totalRo']] = $this->filterAndSum($rincian, function ($item) {
                return isset($item['layanan']['kelas']) && (int) $item['layanan']['kelas'] === 8;
            }, true);

            [$res['tindakan'], $res['totalTindakan']] = $this->filterAndSum($rincian, function ($item) {
                return isset($item['layanan']['kelas']) && in_array((int) $item['layanan']['kelas'], [5, 6, 7], true);
            }, true);

            [$res['obat'], $res['totalObat']] = $this->filterAndSum($rincian, function ($item) {
                return $item['layanan']['idLayanan'] == 2;
            });

            [$res['obatKronis'], $res['totalObatKronis']] = $this->filterAndSum($rincian, function ($item) {
                return $item['layanan']['idLayanan'] == 228;
            });

            [$res['bmhp'], $res['totalbmhp']] = $this->filterAndSum($rincian, function ($item) {
                return $item['layanan']['idLayanan'] == 229;
            });

        }

        return $res;
    }

    private function filterAndSum(array $items, callable $filter, $returnItems = false)
    {
        $filtered = array_filter($items, $filter);
        $total    = array_sum(array_column($filtered, 'totalHarga'));

        return $returnItems ? [$filtered, $total] : [null, $total];
    }

    public function getDataSuratKontrol(Request $request)
    {
        $model  = new KominfoModel();
        $params = [
            'tanggal_awal'  => $request->input('tanggal_awal') ?? date('Y-m-d'),
            'tanggal_akhir' => $request->input('tanggal_akhir') ?? date('Y-m-d'),
        ];
        // dd($params);
        $data = $model->getDataSuratKontrol($params);
        $res  = $data['data'];
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
        $model           = new KominfoModel();
        $no_SuratKontrol = $request->input('no_SuratKontrol');
        // dd($params);
        $data = $model->getDetailSuratKontrol($no_SuratKontrol);
        return response()->json($data);
    }
    public function cetakSuratKontrol(string $no_SuratKontrol, $norm = null)
    {
        $model  = new KominfoModel();
        $data   = $model->getDetailSuratKontrol($no_SuratKontrol);
        $detail = $data['data'] ?? null;

        if (empty($detail)) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Ambil norm jika belum diberikan
        if ($norm === null) {
            $norm = $detail['sep']['data_rujukan']['rujukan']['peserta']['mr']['noMR'] ?? null;
            if ($norm === null) {
                return response()->json(['message' => 'Eror, silahkan tambahkan /{norm} di URL atau cetak lewat KKPM.local'], 404);
            }
        }

        $tgl          = $detail['sep']['tglSep'] ?? null;
        $cpptResponse = $model->cpptRequest([
            'no_rm'         => $norm,
            'tanggal_awal'  => $tgl,
            'tanggal_akhir' => $tgl,
        ]);
        $detailSuratKontrol = $detail;
        $cppt               = $cpptResponse['response']['data'][0] ?? null;

        return view('Laporan.Pasien.SuratKontrol', compact('detailSuratKontrol', 'cppt'));
    }

    public function suratRujukan($tgl, $norm)
    {
        $model = new KominfoModel();
        $cppt  = $model->cpptRequest([
            'no_rm'         => $norm,
            'tanggal_awal'  => $tgl,
            'tanggal_akhir' => $tgl,
        ]);
        if (empty($cppt)) {
            return response()->json(['message' => 'Data tidak ditemukan/salah norm'], 404);
        }
        $cppt = $cppt['response']['data'][0];
        // return $cppt;

        $statusPulang = $cppt['status_pasien_pulang'];
        // dd($statusPulang);
        $namaFaskes  = '-';
        $alasanRujuk = '-';

        if (strtolower(trim($statusPulang)) === 'prb') {
            return view('Laporan.Pasien.suratPRB', compact('cppt'));
        }
        if (strtolower(trim($statusPulang)) === 'selesai pengobatan') {
            return view('Laporan.Pasien.suratSelesaiPengobatan', compact('cppt'));
        }

        if (strtolower($statusPulang) === 'dirujuk') {
            $keterangan = $cppt['ket_status_pasien_pulang'];
            preg_match('/Nama Faskes\s*:\s*(.*?),\s*Alasan Dirujuk\s*:\s*(.*)/i', $keterangan, $matches);
            $namaFaskes  = $matches[1] ?? '-';
            $alasanRujuk = $matches[2] ?? '-';
        }

        if (strtolower(trim($alasanRujuk)) === 'prb') {
            return view('Laporan.Pasien.suratPRB', compact('cppt'));
        }

        // Lanjut ke view lain jika bukan PRB
        return view('Laporan.Pasien.suratRujukanBaru', compact('cppt'));
    }

    public function suratRujukanBaru($tgl, $norm)
    {
        $model = new KominfoModel();
        $cppt  = $model->cpptRequest([
            'no_rm'         => $norm,
            'tanggal_awal'  => $tgl,
            'tanggal_akhir' => $tgl,
        ]);
        $cppt = $cppt['response']['data'][0];
        // return response()->json($cppt);
        return view('Laporan.Pasien.suratRujukanBaru', compact('cppt'));
    }
    public function suratPRB($tgl, $norm)
    {
        $model = new KominfoModel();
        $cppt  = $model->cpptRequest([
            'no_rm'         => $norm,
            'tanggal_awal'  => $tgl,
            'tanggal_akhir' => $tgl,
        ]);
        $cppt = $cppt['response']['data'][0];
        // return response()->json($cppt);
        return view('Laporan.Pasien.suratPRB', compact('cppt'));
    }

    public function getJumlahPemeriksaanDokter($tahun, $bln)
    {
        $kominfo = new KominfoModel();
        $result  = [];

        for ($bulan = 1; $bulan <= $bln; $bulan++) {
            $bulanFormatted = str_pad($bulan, 2, '0', STR_PAD_LEFT);
            $tanggal_awal   = "$tahun-$bulanFormatted-01";
            $tanggal_akhir  = date("Y-m-t", strtotime($tanggal_awal));

            $params = [
                'tanggal_awal'  => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
                'no_rm'         => '',
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
                    "jumlah"     => 0,
                ], [
                    "ruang_nama" => "Ruang Poli (Dokter CPPT)",
                    "admin_nama" => "dr. Cempaka Nova Intani, Sp.P, FISR., MM.",
                    "jumlah"     => 0,
                ], [
                    "ruang_nama" => "Ruang Poli (Dokter CPPT)",
                    "admin_nama" => "dr. Filly Ulfa Kusumawardani",
                    "jumlah"     => 0,
                ], [
                    "ruang_nama" => "Ruang Poli (Dokter CPPT)",
                    "admin_nama" => "dr. Sigit Dwiyanto",
                    "jumlah"     => 0,
                ]];
            } else {
                // Ambil dan filter data yang sesuai
                $data = $response['response']['data'];
                $res  = array_filter($data, function ($item) {
                    return
                    ($item['ruang_nama'] ?? '') === 'Ruang Poli (Dokter CPPT)' &&
                    strtoupper($item['admin_nama'] ?? '') !== 'AJI SANTOSO';
                });

                // Jika setelah filter kosong, tetap tambahkan entri default
                if (empty($res)) {
                    $res = [[
                        "ruang_nama" => "Ruang Poli (Dokter CPPT)",
                        "admin_nama" => "dr. Agil Dananjaya, Sp.P",
                        "jumlah"     => 0,
                    ], [
                        "ruang_nama" => "Ruang Poli (Dokter CPPT)",
                        "admin_nama" => "dr. Cempaka Nova Intani, Sp.P, FISR., MM.",
                        "jumlah"     => 0,
                    ], [
                        "ruang_nama" => "Ruang Poli (Dokter CPPT)",
                        "admin_nama" => "dr. Filly Ulfa Kusumawardani",
                        "jumlah"     => 0,
                    ], [
                        "ruang_nama" => "Ruang Poli (Dokter CPPT)",
                        "admin_nama" => "dr. Sigit Dwiyanto",
                        "jumlah"     => 0,
                    ]];
                }
            }

                                                           // Simpan hasil
            $result[$bulanFormatted] = array_values($res); // reset key numerik
        }

        $html  = $this->generatejmlDokterPeriksaTable($result, $tahun);
        $chart = $this->generatejmlDokterPeriksaChart($result, $tahun);
        $res   = [
            'html'  => $html,
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
            $entries  = $data[$bulanStr] ?? [];

            // Catat jumlah pasien tiap dokter
            $currentMonthData = [];

            foreach ($entries as $entry) {
                $nama   = $entry['admin_nama'];
                $jumlah = (int) $entry['jumlah'];

                // Jika belum ada, inisialisasi array dengan nol untuk bulan-bulan sebelumnya
                if (! isset($dokterData[$nama])) {
                    $dokterData[$nama] = array_fill(0, $bulan - 1, 0);
                }

                // Masukkan jumlah pasien di bulan ini
                $dokterData[$nama][] = $jumlah;

                // Tandai dokter yang sudah dimasukkan data bulan ini
                $currentMonthData[$nama] = true;
            }

            // Tambahkan 0 untuk dokter yang tidak ada data di bulan ini
            foreach ($dokterData as $nama => &$dataArr) {
                if (! isset($currentMonthData[$nama])) {
                    $dataArr[] = 0;
                }
            }
            unset($dataArr); // Penting untuk menghindari pointer referensi aktif
        }

        // Format akhir untuk chart.js
        $datasets = [];
        $colors   = [
            '#36A2EB', '#FF6384', '#4BC0C0', '#9966FF', '#FF9F40', '#8e44ad',
            '#e67e22', '#16a085', '#f1c40f', '#2c3e50', '#d35400', '#27ae60',
        ];

        $i = 0;
        foreach ($dokterData as $nama => $dataArr) {
            $color      = $colors[$i % count($colors)];
            $datasets[] = [
                'label'           => $nama,
                'data'            => $dataArr,
                'borderColor'     => $color,
                'backgroundColor' => $this->hexToRgba($color, 0),
                'tension'         => 0.3,
                'fill'            => true,
            ];
            $i++;
        }

        return [
            'labels'   => $labels,
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
        $result  = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            // Format bulan dengan leading zero, misal: 01, 02, ..., 12
            $bulanFormatted = str_pad($bulan, 2, '0', STR_PAD_LEFT);

            // Buat tanggal awal dan akhir bulan
            $tanggal_awal  = "$tahun-$bulanFormatted-01";
            $tanggal_akhir = date("Y-m-t", strtotime($tanggal_awal)); // tanggal akhir bulan

            // Siapkan parameter untuk request
            $params = [
                'tanggal_awal'  => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
                'no_rm'         => '',
            ];

            // Ambil data dari model
            $data = $kominfo->pendaftaranRequest($params);

            // Proses data jika perlu
            $res = $this->waktuTungguProses($data, $bulanFormatted);

            // Simpan hasil ke dalam array result dengan struktur baru
            $result['html'][$bulanFormatted]  = $res['html'];
            $result['total'][$bulanFormatted] = $res['total'];

        }

        return response()->json($result);
    }

    public function cetakSM($norm, $tgl)
    {
        $title = 'Surat Rujukan';
        $model = new KominfoModel();
        $param = [
            'no_rm'         => $norm,
            'tanggal_awal'  => $tgl,
            'tanggal_akhir' => $tgl,
        ];
        $cppt = $model->cpptRequest($param)['response']['data'];

        return view('SuratMedis.suratMedis', compact('title', 'cppt'));
    }

    public function get_assesment_awal($norm, $tanggal)
    {
        $api         = new ApiKominfo();
        $pasien      = $api->get_pasien($norm);
        $kominfo     = new KominfoModel();
        $pendaftaran = $kominfo->pendaftaranRequest([
            'no_rm'         => $norm,
            'tanggal_awal'  => $tanggal,
            'tanggal_akhir' => $tanggal,
        ]);
        // return $pendaftaran;
        $log_id = $pendaftaran[0]['log_id'];
        // return response()->json($pasien);
        $id_pasien = $pasien[0]['id'];
        // return $id_pasien;
        $params = [
            'pasien_id' => $id_pasien,
            'log_id'    => $log_id,

        ];
        $assesment_awal_html = $api->get_assesment_awal($params);
        return $assesment_awal_html;
        // return response()->json($assesment_awal);
        return view('Laporan.Pasien.asesmentAwal', compact('assesment_awal_html'));
    }
    public function get_data_tindakan($pendaftatan_id)
    {
        $api  = new ApiKominfo();
        $data = $api->get_data_tindakan($pendaftatan_id);

        return $data;
        // return response()->json($assesment_awal);
        return view('Laporan.Pasien.asesmentAwal', compact('assesment_awal_html'));
    }
    public function get_master_obat(Request $request)
    {
        $namaObat = $request->input('namaObat') ?? '';
        $api      = new ApiKominfo();
        $data     = $api->get_master_obat($namaObat);

        return $data;

    }

    // public function jumlahPetugas(Request $request)
    // {
    //     $model  = new KominfoModel();
    //     $params = [
    //         'tgl_awal'  => '2025-06-01',
    //         'tgl_akhir' => '2025-06-10',
    //         'status'    => $request->input('status') ?? '',
    //     ];

    //     $dataPendaftaran = $model->getTungguLoketFilter($params)['data'];

    //     // Inisialisasi array untuk menyimpan hasil
    //     $pasienBaru = [];
    //     $pasienLama = [];

    //     // Proses penghitungan
    //     foreach ($dataPendaftaran as $item) {
    //         $adminName    = $item['admin_log_nama'] ?? 'Tidak Diketahui';
    //         $statusPasien = $item['pasien_lama_baru'] ?? 'Tidak Diketahui';

    //         if (strtoupper($statusPasien) === 'BARU') {
    //             if (! isset($pasienBaru[$adminName])) {
    //                 $pasienBaru[$adminName] = 0;
    //             }
    //             $pasienBaru[$adminName]++;
    //         } elseif (strtoupper($statusPasien) === 'LAMA') {
    //             if (! isset($pasienLama[$adminName])) {
    //                 $pasienLama[$adminName] = 0;
    //             }
    //             $pasienLama[$adminName]++;
    //         }
    //     }

    //     // Format output
    //     $result = [
    //         'pasien_baru' => array_map(function ($name, $count) {
    //             return ['nama_admin' => $name, 'jumlah' => $count];
    //         }, array_keys($pasienBaru), array_values($pasienBaru)),

    //         'pasien_lama' => array_map(function ($name, $count) {
    //             return ['nama_admin' => $name, 'jumlah' => $count];
    //         }, array_keys($pasienLama), array_values($pasienLama)),
    //     ];

    //     return response()->json($result);
    // }

    public function jumlahPetugas(Request $request)
    {
        $model = new KominfoModel();

        $tglAwal  = Carbon::parse($request->input('tgl_awal') ?? date('Y-m-d'));
        $tglAkhir = Carbon::parse($request->input('tgl_akhir') ?? date('Y-m-d'));
        $status   = $request->input('status') ?? '';

        $diffInDays = $tglAwal->diffInDays($tglAkhir);
        // dd($diffInDays);

        if ($diffInDays > 14) {
                                                        // Bagi jadi 2 rentang: 14 hari pertama, sisanya
            $tglTengah = $tglAwal->copy()->addDays(13); // 14 hari dari awal (13 hari setelah awal)

            // Request pertama
            $params1 = [
                'tgl_awal'  => $tglAwal->format('Y-m-d'),
                'tgl_akhir' => $tglTengah->format('Y-m-d'),
                'status'    => $status,
            ];
            $data1 = $model->getTungguLoketFilter($params1)['data'];

            // Request kedua
            $params2 = [
                'tgl_awal'  => $tglTengah->copy()->addDay()->format('Y-m-d'), // hari ke-15
                'tgl_akhir' => $tglAkhir->format('Y-m-d'),
                'status'    => $status,
            ];
            // dd($params2);
            $data2 = $model->getTungguLoketFilter($params2)['data'];

            // Gabungkan hasil
            $dataPendaftaran = array_merge($data1, $data2);
        } else {
            // Jika <= 14 hari, langsung ambil 1x
            $params = [
                'tgl_awal'  => $tglAwal->format('Y-m-d'),
                'tgl_akhir' => $tglAkhir->format('Y-m-d'),
                'status'    => $status,
            ];
            $dataPendaftaran = $model->getTungguLoketFilter($params)['data'];
        }

        // return count($dataPendaftaran);
        // return response()->json($dataPendaftaran);
        $dataPendaftaran = array_filter($dataPendaftaran, function ($item) {
            return $item['keterangan'] === 'SELESAI DIPANGGIL';
        });
        $dataPendaftaran = array_values($dataPendaftaran);

        // return response()->json($dataPendaftaran);
        // Inisialisasi array untuk menyimpan hasil per admin
        $adminData = [];

        // Proses penghitungan
        foreach ($dataPendaftaran as $item) {
            $adminName    = $item['admin_log_nama'];
            $statusPasien = strtoupper($item['pasien_lama_baru']);

            // Inisialisasi data admin jika belum ada
            if (! isset($adminData[$adminName])) {
                $adminData[$adminName] = [
                    'nama'       => $adminName,
                    'jumlahLama' => 0,
                    'jumlahBaru' => 0,
                ];
            }

            // Tambahkan ke jumlah yang sesuai
            if ($statusPasien === 'LAMA') {
                $adminData[$adminName]['jumlahLama']++;
            } elseif ($statusPasien === 'BARU') {
                $adminData[$adminName]['jumlahBaru']++;
            }
        }

        // Konversi associative array ke indexed array
        $result = array_values($adminData);

        return response()->json($result);
    }
}
