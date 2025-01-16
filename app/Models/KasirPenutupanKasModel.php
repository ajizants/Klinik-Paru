<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasirPenutupanKasModel extends Model
{
    use HasFactory;

    protected $table = ('t_kasir_penutupanKas');

    protected $fillable = [
        'tanggal_sekarang',
        'tanggal_lalu',
        'petugas',
        'total_penerimaan',
        'total_pengeluaran',
        'saldo_bku',
        'saldo_kas',
        'selisih_saldo',
        'kertas100k',
        'kertas50k',
        'kertas20k',
        'kertas10k',
        'kertas5k',
        'kertas2k',
        'kertas1k',
        'logam1k',
        'logam500',
        'logam200',
        'logam100',
    ];

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
            ->get();

        return $data;
    }
    function penerimaan($tahun, $bulan)
    {
        $paramPendLain = $tahun . '-' . $bulan;
        $data = $data = $this->where('tanggal', 'like', '%' . $paramPendLain . '%')
            ->where('pendapatan', '!=', 0)
            ->whereNotIn('asal_pendapatan', ['Tunai', 'Saldo Bank'])
            ->get();

        return $data;
    }
    function pengeluaran($tahun, $bulan)
    {
        $paramPendLain = $tahun . '-' . $bulan;
        $data = $data = $this->where('tanggal', 'like', '%' . $paramPendLain . '%')
            ->where('setoran', '!=', 0)
            ->whereNotIn('asal_pendapatan', ['Tunai', 'Saldo Bank'])
            ->get();

        return $data;
    }

    function pendapatanSebelumnya($tahun, $bulan)
    {
        // Jika bulan Januari, langsung return 0
        if ($bulan == 1) {
            return 0;
        }

        // Format awal tahun
        $awal = $tahun . '-01-01';

        // Hitung bulan sebelumnya
        $akhir = $tahun . '-' . str_pad($bulan - 1, 2, '0', STR_PAD_LEFT) . '-31';
        // Pastikan tanggal akhir valid untuk bulan sebelumnya
        $akhir = date('Y-m-t', strtotime($akhir));

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
        if ($bulan == 1) {
            return 0;
        }

        // Format awal tahun
        $awal = $tahun . '-01-01';

        // Hitung bulan sebelumnya
        $akhir = $tahun . '-' . str_pad($bulan - 1, 2, '0', STR_PAD_LEFT) . '-31';
        // Pastikan tanggal akhir valid untuk bulan sebelumnya
        $akhir = date('Y-m-t', strtotime($akhir));

        // Ambil data dari awal hingga akhir
        $data = $this->whereBetween('tanggal', [$awal, $akhir])
            ->whereNotIn('asal_pendapatan', ['Tunai', 'Saldo Bank'])
            ->sum('setoran');
        // dd($data);
        // Return hasil
        return $data;
    }
    public function dataTahunan($tahun)
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
        $doc = $this->where('tanggal', 'like', $tahun . '-%')
            ->whereNotIn('asal_pendapatan', ['Tunai', 'Saldo Bank'])
            ->get();

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
