<?php
namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasirSetoranModel extends Model
{
    use HasFactory;

    protected $table = ('t_kasir_setoran');

    protected $fillable = [
        'nomor',
        'noSbs',
        'tanggal',
        'pendapatan',
        'setoran',
        'penyetor',
        'asal_pendapatan',
    ];

    public function pendapatanLainSimpan($params)
    {
        return $this->create($params);
    }

    public function findSetoran($nomor)
    {
        return $this->where('noSbs', $nomor)->get();
    }

    public function getData($noSBS)
    {
        return $this->where('noSbs', $noSBS)->get();
    }

    // public function data($bulan, $tahun, $jaminan)
    // {
    //     $doc = [];
    //     $tglAkhir = \Carbon\Carbon::create($tahun, $bulan, 1)->lastOfMonth()->locale('id')->isoFormat('DD MMMM YYYY');
    //     $blnTahun = \Carbon\Carbon::create($tahun, $bulan, 1)->locale('id')->isoFormat('MMMM YYYY');

    //     $totalPendapatanRajal = 0;
    //     $paramPendLain = $tahun . '-' . $bulan;

    //     $pendapatanLain = KasirSetoranModel::where('tanggal', 'like', '%' . $paramPendLain . '%')->get();
    //     // return $pendapatanLain;
    //     $docLain = [];
    //     $totalPendapatanLain = 0;
    //     if ($pendapatanLain->isEmpty()) {
    //         $docLain = [];
    //     } else {

    //         foreach ($pendapatanLain as $d) {
    //             $tanggal = \Carbon\Carbon::parse($d->tanggal); // Menggunakan Carbon
    //             $formattedDate = $tanggal->format('d-m-Y');
    //             $hari = $tanggal->locale('id')->isoFormat('dddd'); // Hari dalam bahasa Indonesia
    //             $bulan = $tanggal->locale('id')->isoFormat('MMMM');
    //             $blnNumber = $tanggal->format('m');
    //             $tglNomor = $tanggal->locale('id')->isoFormat('DD MMMM YYYY');
    //             $tgl = $tanggal->locale('id')->isoFormat('DD MMM YYYY');
    //             $model = new KasirTransModel();
    //             $terbilangPendapatan = $model->terbilang($d->jumlah); // Konversi terbilang

    //             // Format nomor
    //             $nomor = $tanggal->format('d') . '/SBS/01/' . $tanggal->format('Y');
    //             $nomor_sts = $tanggal->format('d') . '/KKPM/' . $tanggal->locale('id')->isoFormat('MMM') . '/' . $tanggal->format('Y');

    //             $totalPendapatanLain += $d->jumlah;

    //             // Tambahkan ke array hasil
    //             $docLain[] = [
    //                 'nomor' => $nomor,
    //                 'nomor_sts' => $nomor_sts,
    //                 'tanggal' => $formattedDate,
    //                 'tgl' => $tgl,
    //                 'hari' => $hari,
    //                 'bulan' => $bulan,
    //                 'tahun' => $tahun,
    //                 'bln_number' => $blnNumber,
    //                 'tgl_nomor' => $tglNomor,
    //                 'tgl_pendapatan' => $tglNomor,
    //                 'tgl_setor' => $tglNomor,
    //                 'pendapatan' => 'Rp ' . number_format($d->jumlah, 0, ',', '.') . ',00',
    //                 'jumlah' => $d->jumlah,
    //                 'terbilang' => ucfirst($terbilangPendapatan) . " rupiah.",
    //                 'kode_akun' => 102010041411,
    //                 'kode_rek' => "3.003.25581.5",
    //                 'asal_pendapatan' => $d->asal_pendapatan,
    //                 'bank' => "BPD",
    //                 'uraian' => 'Pendapatan Jasa Pelayanan Rawat Jalan 1',
    //             ];
    //         }
    //     }
    //     //gabungkan $doc dan $docLain dan urutkan berdasarkan tanggal ter awal
    //     $doc = array_merge($doc, $docLain);
    //     usort($doc, function ($a, $b) {
    //         return strtotime($a['tanggal']) - strtotime($b['tanggal']);
    //     });

    //     $totalPendapatan = $totalPendapatanRajal + $totalPendapatanLain;

    //     return compact('doc', 'totalPendapatan', 'totalPendapatanRajal', 'totalPendapatanLain', 'tglAkhir', 'blnTahun');
    // }
    public function tunai($tahun, $bulan)
    {
        $data = $this->where('tanggal', 'like', '%' . $tahun . '-' . $bulan . '%')
            ->where('asal_pendapatan', 'Tunai')
            ->first();
        $tunai = $data->pendapatan;
        return $tunai;
    }

    public function saldoBank($tahun, $bulan)
    {
        $data = $this->where('tanggal', 'like', '%' . $tahun . '-' . $bulan . '%')
            ->where('asal_pendapatan', 'Saldo Bank')
            ->first();
        $saldoBank = $data->pendapatan;
        return $saldoBank;
    }

    public function data($tahun, $bulan)
    {
        $paramPendLain = $tahun . '-' . $bulan;
        $data = $this->where('tanggal', 'like', '%' . $paramPendLain . '%')
            ->whereNotIn('asal_pendapatan', ['Tunai', 'Saldo Bank'])
            ->orderBy('tanggal', 'asc')
            ->get();
        return $data;
    }
    function penerimaan($tahun, $bulan)
    {
        $paramPendLain = $tahun . '-' . $bulan;
        $data = $this->where('tanggal', 'like', '%' . $paramPendLain . '%')
            ->where('pendapatan', '!=', 0)
            ->whereNotIn('asal_pendapatan', ['Tunai', 'Saldo Bank'])
            ->orderBy('tanggal', 'asc')
            ->get();

        return $data;
    }
    function pengeluaran($tahun, $bulan)
    {
        $paramPendLain = $tahun . '-' . $bulan;
        $data = $this->where('tanggal', 'like', '%' . $paramPendLain . '%')
            ->where('setoran', '!=', 0)
            ->whereNotIn('asal_pendapatan', ['Tunai', 'Saldo Bank'])
            ->orderBy('tanggal', 'asc')
            ->get();
        // dd(count($data));
        return $data;
    }

    function pendapatanSebelumnya($tahun, $bulan)
    {
        // Jika bulan Januari, langsung return 0
        if ((int) $bulan === 1) {
            return 0;
        }

        // Tanggal awal: 1 Januari tahun itu
        $awal = "$tahun-01-01";

        // Tanggal akhir: akhir bulan sebelumnya
        $tanggal = new DateTime("$tahun-" . str_pad($bulan, 2, '0', STR_PAD_LEFT) . "-01");
        $tanggal->modify('-1 month');
        $akhir = $tanggal->format('Y-m-t');

        // dd([$awal, $akhir]);

        // Ambil data dari awal hingga akhir
        $data = $this->whereBetween('tanggal', [$awal, $akhir])
            ->whereNotIn('asal_pendapatan', ['Tunai', 'Saldo Bank'])
            ->sum('pendapatan');
        // dd($data);
        // Return hasil
        return $data;
    }
    function pengeluaranSebelumnya($tahun, $bulan)
    {
        // Jika bulan Januari, langsung return 0
        if ((int) $bulan === 1) {
            return 0;
        }

        // Tanggal awal: 1 Januari tahun itu
        $awal = "$tahun-01-01";

        // Tanggal akhir: akhir bulan sebelumnya
        $tanggal = new DateTime("$tahun-" . str_pad($bulan, 2, '0', STR_PAD_LEFT) . "-01");
        $tanggal->modify('-1 month');
        $akhir = $tanggal->format('Y-m-t');

        // dd([$awal, $akhir]);

        // Ambil data dari awal hingga akhir
        $data = $this->whereBetween('tanggal', [$awal, $akhir])
            ->whereNotIn('asal_pendapatan', ['Tunai', 'Saldo Bank'])
            ->sum('setoran');
        // dd($data);
        // Return hasil
        return $data;
    }
    public function dataTahunan($params)
    {
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

        // Inisialisasi data per bulan
        $dataBulanan = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataBulanan[] = [
                'bulan' => $namaBulan[$i],
                'bln_number' => $i,
                'penerimaan' => 0,
                'setoran' => 0,
                'sisa' => 0,
                'penerimaanRp' => 'Rp 0,00',
                'setoranRp' => 'Rp 0,00',
                'sisaRp' => 'Rp 0,00',
            ];
        }

        // Variabel total
        $totalPendapatan = 0;
        $totalSetoran = 0;

        // Mengambil data berdasarkan tahun
        $tglAkhir = $params['tglAkhir'];
        $tahun = $params['tahun'];
        // dd($bulanTahun);
        $doc = $this->where('tanggal', "<=", $tglAkhir)
            ->whereYear('tanggal', $tahun)
            ->whereNotIn('asal_pendapatan', ['Tunai', 'Saldo Bank'])
            ->get();
        // dd($doc);

        // Mengolah data dokumen
        foreach ($doc as $d) {
            $bulan = (int) date('n', strtotime($d->tanggal)) - 1; // Indeks array mulai dari 0
            $dataBulanan[$bulan]['penerimaan'] += $d->pendapatan;
            $dataBulanan[$bulan]['setoran'] += $d->setoran;
            $dataBulanan[$bulan]['sisa'] = $dataBulanan[$bulan]['penerimaan'] - $dataBulanan[$bulan]['setoran'];
            $dataBulanan[$bulan]['penerimaanRp'] = 'Rp ' . number_format($dataBulanan[$bulan]['penerimaan'], 0, ',', '.') . ',00';
            $dataBulanan[$bulan]['setoranRp'] = 'Rp ' . number_format($dataBulanan[$bulan]['setoran'], 0, ',', '.') . ',00';
            $dataBulanan[$bulan]['sisaRp'] = 'Rp ' . number_format($dataBulanan[$bulan]['sisa'], 0, ',', '.') . ',00';

            // Menambahkan ke total
            $totalPendapatan += $d->pendapatan;
            $totalSetoran += $d->setoran;
        }

        // Hitung saldo total
        $totalSaldo = $totalPendapatan - $totalSetoran;
        $totalPendapatanRp = 'Rp ' . number_format($totalPendapatan, 0, ',', '.') . ',00';
        $totalSetoranRp = 'Rp ' . number_format($totalSetoran, 0, ',', '.') . ',00';
        $totalSaldoRp = 'Rp ' . number_format($totalSaldo, 0, ',', '.') . ',00';

        return [
            'dataBulanan' => $dataBulanan,
            'totalPendapatan' => $totalPendapatan,
            'totalSetoran' => $totalSetoran,
            'totalSaldo' => $totalSaldo,
            'totalPendapatanRp' => $totalPendapatanRp,
            'totalSetoranRp' => $totalSetoranRp,
            'totalSaldoRp' => $totalSaldoRp,
        ];
    }

}
