<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasirSetoranModel extends Model
{
    use HasFactory;

    protected $table = ('t_kasir_setoran');

    protected $fillable = [
        'nomor',
        'tanggal',
        'jumlah',
        'penyetor',
        'asal_pendapatan',
    ];

    public function pendapatanLainSimpan($params)
    {
        return $this->create($params);
    }

    public function data($bulan, $tahun, $jaminan)
    {
        $doc = [];
        $tglAkhir = \Carbon\Carbon::create($tahun, $bulan, 1)->lastOfMonth()->locale('id')->isoFormat('DD MMMM YYYY');
        $blnTahun = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY');

        $totalPendapatanRajal = 0;
        $paramPendLain = $tahun . '-' . $bulan;

        $pendapatanLain = KasirSetoranModel::where('tanggal', 'like', '%' . $paramPendLain . '%')->get();
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
}
