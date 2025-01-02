<?php

namespace App\Http\Controllers;

use App\Models\KasirPendLainModel;
use App\Models\KasirTransModel;
use Carbon\Carbon;

class KasirLapController extends Controller
{
    private function data($bulan, $tahun, $jaminan)
    {
        $doc = [];
        $tglAkhir = \Carbon\Carbon::create($tahun, $bulan, 1)->lastOfMonth()->locale('id')->isoFormat('DD MMMM YYYY');
        $blnTahun = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY');

        $model = new KasirTransModel();
        $data = $model->pendapatan($tahun);
        $totalPendapatanRajal = 0;
        $paramPendLain = $tahun . '-' . $bulan;

        $doc = array_filter($data[$jaminan], function ($item) use ($bulan) {
            return $item['bln_number'] == $bulan;
            $totalPendapatanRajal += $d['jumlah'];
        });
        //tanggal like $param PendLain

        $pendapatanLain = KasirPendLainModel::where('tanggal', 'like', '%' . $paramPendLain . '%')->get();
        // return $pendapatanLain;
        $docLain = [];
        $totalPendapatanLain = 0;
        if ($pendapatanLain->isEmpty()) {
            $docLain = [];
        } else {

            foreach ($pendapatanLain as $d) {
                $tanggal = \Carbon\Carbon::parse($d->tanggal); // Menggunakan Carbon
                $formattedDate = $tanggal->format('d-m-Y');
                $hari = $tanggal->locale('id')->isoFormat('dddd'); // Hari dalam bahasa Indonesia
                $bulan = $tanggal->locale('id')->isoFormat('MMMM');
                $blnNumber = $tanggal->format('m');
                $tglNomor = $tanggal->locale('id')->isoFormat('DD MMMM YYYY');
                $tgl = $tanggal->locale('id')->isoFormat('DD MMM YYYY');
                $model = new KasirTransModel();
                $terbilangPendapatan = $model->terbilang($d->jumlah); // Konversi terbilang

                // Format nomor
                $nomor = $tanggal->format('d') . '/SBS/01/' . $tanggal->format('Y');
                $nomor_sts = $tanggal->format('d') . '/KKPM/' . $tanggal->locale('id')->isoFormat('MMM') . '/' . $tanggal->format('Y');

                $totalPendapatanLain += $d->jumlah;

                // Tambahkan ke array hasil
                $docLain[] = [
                    'nomor' => $nomor,
                    'nomor_sts' => $nomor_sts,
                    'tanggal' => $formattedDate,
                    'tgl' => $tgl,
                    'hari' => $hari,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'bln_number' => $blnNumber,
                    'tgl_nomor' => $tglNomor,
                    'tgl_pendapatan' => $tglNomor,
                    'tgl_setor' => $tglNomor,
                    'pendapatan' => 'Rp ' . number_format($d->jumlah, 0, ',', '.') . ',00',
                    'jumlah' => $d->jumlah,
                    'terbilang' => ucfirst($terbilangPendapatan) . " rupiah.",
                    'kode_akun' => 102010041411,
                    'kode_rek' => "3.003.25581.5",
                    'asal_pendapatan' => $d->asal_pendapatan,
                    'bank' => "BPD",
                    'uraian' => 'Pendapatan Jasa Pelayanan Rawat Jalan 1',
                ];
            }
        }
        //gabungkan $doc dan $docLain dan urutkan berdasarkan tanggal ter awal
        $doc = array_merge($doc, $docLain);
        usort($doc, function ($a, $b) {
            return strtotime($a['tanggal']) - strtotime($b['tanggal']);
        });

        $totalPendapatan = $totalPendapatanRajal + $totalPendapatanLain;

        return compact('doc', 'totalPendapatan', 'totalPendapatanRajal', 'totalPendapatanLain', 'tglAkhir', 'blnTahun');
    }
    // public function rekapBulanan($tahun, $jaminan)
    // {
    //     $title = 'Rekap Bulanan';
    //     $doc = [];
    //     $bulanPendapatan = [];
    //     $totalPendapatan = 0;
    //     $totalSetoran = 0;
    //     $saldo = 0;
    //     $target = 6957500000;

    //     $model = new KasirTransModel();
    //     $data = $model->pendapatan($tahun);
    //     // return $data;
    //     if ($data === []) {
    //         // return "kosong";
    //         for ($i = 1; $i <= 12; $i++) {
    //             $bulanPendapatan[$i] = [
    //                 'bulan' => Carbon::createFromFormat('m', $i)->locale('id')->isoFormat('MMMM'),
    //                 'bln_number' => $i,
    //                 'penerimaan' => 0,
    //                 'setoran' => 0,
    //             ];
    //         }
    //         $doc[] = [
    //             'bulan' => 'Rp.0,00',
    //             'bln_number' => 'Rp.0,00',
    //             'penerimaan' => 'Rp.0,00',
    //             'setoran' => 'Rp.0,00',
    //             'saldo' => 'Rp.0,00',
    //         ];
    //         $totalSaldo = $totalPendapatan - $totalSetoran;
    //         $totalPendapatanFormatted = 'Rp ' . number_format($totalPendapatan, 0, ',', '.') . ',00';
    //         $totalSetoranFormatted = 'Rp ' . number_format($totalSetoran, 0, ',', '.') . ',00';
    //         $totalSaldoFormatted = 'Rp ' . number_format($totalSaldo, 0, ',', '.') . ',00';

    //         return view('Laporan.Kasir.rekapBulanan', compact(
    //             'doc',
    //             'totalPendapatanFormatted',
    //             'totalSetoranFormatted',
    //             'totalSaldoFormatted',
    //             'target',
    //             'title',
    //             'tahun'
    //         ));
    //     }
    //     $dataPendapatanLain = KasirPendLainModel::where('tanggal', 'like', '%' . $tahun . '%')->get();

    //     // Proses pendapatan lain
    //     foreach ($dataPendapatanLain as $d) {
    //         $date = Carbon::parse($d->tanggal);
    //         $bulan = $date->locale('id')->isoFormat('MMMM');
    //         $blnNumber = (int) $date->format('m'); // Nomor bulan sebagai integer

    //         if (!isset($bulanPendapatan[$blnNumber])) {
    //             $bulanPendapatan[$blnNumber] = [
    //                 'bulan' => $bulan,
    //                 'bln_number' => $blnNumber,
    //                 'penerimaan' => 0,
    //                 'setoran' => 0,
    //             ];
    //         }

    //         if ($d->asal_pendapatan === 'setoran') {
    //             $bulanPendapatan[$blnNumber]['setoran'] += $d->jumlah;
    //         } else {
    //             $bulanPendapatan[$blnNumber]['penerimaan'] += $d->jumlah;
    //         }
    //     }

    //     // Proses pendapatan rawat jalan (rajal)
    //     foreach ($data[$jaminan] as $d) {
    //         $date = Carbon::parse($d['tanggal']);
    //         $bulan = $date->locale('id')->isoFormat('MMMM');
    //         $blnNumber = (int) $date->format('m'); // Nomor bulan sebagai integer

    //         if (!isset($bulanPendapatan[$blnNumber])) {
    //             $bulanPendapatan[$blnNumber] = [
    //                 'bulan' => $bulan,
    //                 'bln_number' => $blnNumber,
    //                 'penerimaan' => 0,
    //                 'setoran' => 0,
    //             ];
    //         }

    //         $bulanPendapatan[$blnNumber]['penerimaan'] += $d['jumlah'];
    //     }

    //     // Urutkan berdasarkan nomor bulan
    //     ksort($bulanPendapatan);

    //     // Format data menjadi array yang sesuai untuk $doc
    //     foreach ($bulanPendapatan as $item) {
    //         $saldo += $item['penerimaan'] - $item['setoran'];
    //         $doc[] = [
    //             'bulan' => $item['bulan'],
    //             'bln_number' => $item['bln_number'],
    //             'penerimaan' => $item['penerimaan'],
    //             'setoran' => $item['setoran'],
    //             'saldo' => $saldo,
    //         ];
    //         $totalPendapatan += $item['penerimaan'];
    //         $totalSetoran += $item['setoran'];
    //     }

    //     // Konversi format ke dalam rupiah di akhir untuk menjaga akurasi
    //     foreach ($doc as &$d) {
    //         $d['penerimaan'] = 'Rp ' . number_format($d['penerimaan'], 0, ',', '.') . ',00';
    //         $d['setoran'] = 'Rp ' . number_format($d['setoran'], 0, ',', '.') . ',00';
    //         $d['saldo'] = 'Rp ' . number_format($d['saldo'], 0, ',', '.') . ',00';
    //     }

    //     $totalSaldo = $totalPendapatan - $totalSetoran;
    //     $totalPendapatanFormatted = 'Rp ' . number_format($totalPendapatan, 0, ',', '.') . ',00';
    //     $totalSetoranFormatted = 'Rp ' . number_format($totalSetoran, 0, ',', '.') . ',00';
    //     $totalSaldoFormatted = 'Rp ' . number_format($totalSaldo, 0, ',', '.') . ',00';

    //     return view('Laporan.Kasir.rekapBulanan', compact(
    //         'doc',
    //         'totalPendapatanFormatted',
    //         'totalSetoranFormatted',
    //         'totalSaldoFormatted',
    //         'target',
    //         'title',
    //         'tahun'
    //     ));
    // }

    public function rekapBulanan($tahun, $jaminan)
    {
        $title = 'Rekap Bulanan';
        $doc = [];
        $bulanPendapatan = [];
        $totalPendapatan = 0;
        $totalSetoran = 0;
        $saldo = 0;
        $target = 6957500000;

        $model = new KasirTransModel();
        $data = $model->pendapatan($tahun);
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        for ($i = 1; $i <= 12; $i++) {
            $bulanPendapatan[$i] = [
                'bulan' => $namaBulan[$i],
                'bln_number' => $i,
                'penerimaan' => 0,
                'setoran' => 0,
            ];
        }

        // Jika tidak ada data, tetap kembalikan nilai default
        if (empty($data)) {
            foreach ($bulanPendapatan as $item) {
                $saldo += $item['penerimaan'] - $item['setoran'];
                $doc[] = [
                    'bulan' => $item['bulan'],
                    'bln_number' => $item['bln_number'],
                    'penerimaan' => 'Rp ' . number_format($item['penerimaan'], 0, ',', '.') . ',00',
                    'setoran' => 'Rp ' . number_format($item['setoran'], 0, ',', '.') . ',00',
                    'saldo' => 'Rp ' . number_format($saldo, 0, ',', '.') . ',00',
                ];
            }
            $totalSaldoFormatted = 'Rp 0,00';
            $totalPendapatanFormatted = 'Rp 0,00';
            $totalSetoranFormatted = 'Rp 0,00';

            return view('Laporan.Kasir.rekapBulanan', compact(
                'doc',
                'totalPendapatanFormatted',
                'totalSetoranFormatted',
                'totalSaldoFormatted',
                'target',
                'title',
                'tahun'
            ));
        }

        $dataPendapatanLain = KasirPendLainModel::where('tanggal', 'like', '%' . $tahun . '%')->get();

        // Proses pendapatan lain
        foreach ($dataPendapatanLain as $d) {
            $blnNumber = Carbon::parse($d->tanggal)->format('m');
            $blnNumber = (int) $blnNumber;

            if ($d->asal_pendapatan === 'setoran') {
                $bulanPendapatan[$blnNumber]['setoran'] += $d->jumlah;
            } else {
                $bulanPendapatan[$blnNumber]['penerimaan'] += $d->jumlah;
            }
        }

        // Proses pendapatan rawat jalan (rajal)
        foreach ($data[$jaminan] as $d) {
            $blnNumber = Carbon::parse($d['tanggal'])->format('m');
            $blnNumber = (int) $blnNumber;
            $bulanPendapatan[$blnNumber]['penerimaan'] += $d['jumlah'];
        }

        // Format data menjadi array yang sesuai untuk $doc
        foreach ($bulanPendapatan as $item) {
            $saldo += $item['penerimaan'] - $item['setoran'];
            $doc[] = [
                'bulan' => $item['bulan'],
                'bln_number' => $item['bln_number'],
                'penerimaan' => 'Rp ' . number_format($item['penerimaan'], 0, ',', '.') . ',00',
                'setoran' => 'Rp ' . number_format($item['setoran'], 0, ',', '.') . ',00',
                'saldo' => 'Rp ' . number_format($saldo, 0, ',', '.') . ',00',
            ];
            $totalPendapatan += $item['penerimaan'];
            $totalSetoran += $item['setoran'];
        }

        $totalSaldo = $totalPendapatan - $totalSetoran;
        $totalPendapatanFormatted = 'Rp ' . number_format($totalPendapatan, 0, ',', '.') . ',00';
        $totalSetoranFormatted = 'Rp ' . number_format($totalSetoran, 0, ',', '.') . ',00';
        $totalSaldoFormatted = 'Rp ' . number_format($totalSaldo, 0, ',', '.') . ',00';

        return view('Laporan.Kasir.rekapBulanan', compact(
            'doc',
            'totalPendapatanFormatted',
            'totalSetoranFormatted',
            'totalSaldoFormatted',
            'target',
            'title',
            'tahun'
        ));
    }

    public function stsBruto($bulan, $tahun, $jaminan)
    {
        $title = 'STS Bruto';

        $compact = $this->data($bulan, $tahun, $jaminan);

        return view('Laporan.Kasir.stsBruto')->with('title', $title)->with($compact);
    }
    public function stpbBruto($bulan, $tahun, $jaminan)
    {
        $title = 'STPB Bruto';
        $compact = $this->data($bulan, $tahun, $jaminan);

        return view('Laporan.Kasir.stpbBruto')->with('title', $title)->with($compact);
    }

}
