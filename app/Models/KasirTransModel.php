<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasirTransModel extends Model
{
    use HasFactory;

    protected $table = ('t_kasir');
    // protected $primaryKey = 'notrans';

    protected $fillable = [
        'notrans',
        'norm',
        'nama',
        'umur',
        'jk',
        'alamat',
        'jaminan',
        'petugas',
        'tagihan',
        'bayar',
        'kembalian',
        'created_at',
        'updated_at',
    ];

    public function item()
    {
        return $this->hasMany(KasirAddModel::class, 'notrans', 'notrans');
    }

    public function pendapatan($tahun)
    {
        $dataUMUM = self::selectRaw('DATE(created_at) as tanggal, SUM(tagihan) as pendapatan')
            ->whereYear('created_at', $tahun) // Filter berdasarkan tahun
            ->where('jaminan', 'UMUM')
            ->groupBy('tanggal') // Kelompokkan berdasarkan tanggal
            ->orderBy('tanggal', 'asc') // Urutkan berdasarkan tanggal
            ->get();

        $dataBPJS = self::selectRaw('DATE(created_at) as tanggal, SUM(tagihan) as pendapatan')
            ->whereYear('created_at', $tahun) // Filter berdasarkan tahun
            ->where('jaminan', 'BPJS')
            ->groupBy('tanggal') // Kelompokkan berdasarkan tanggal
            ->orderBy('tanggal', 'asc') // Urutkan berdasarkan tanggal
            ->get();
        if ($dataUMUM->isEmpty() && $dataBPJS->isEmpty()) {
            return [];
        }
        $res = [
            'umum' => $this->getPendapatan($dataUMUM, $tahun),
            'bpjs' => $this->getPendapatan($dataBPJS, $tahun),
        ];

        return $res;
    }
    private function getPendapatan($data, $tahun)
    {

        $result = [];

        // Periksa apakah data ada
        if ($data->isEmpty()) {
            // return response()->json([
            //     'message' => 'Tidak ada data pendapatan untuk tahun ' . $tahun,
            //     'data' => [],
            // ], 200, [], JSON_PRETTY_PRINT);
            return [];
        }

        // Looping data pendapatan
        foreach ($data as $d) {
            $tanggal = \Carbon\Carbon::parse($d->tanggal); // Menggunakan Carbon
            $formattedDate = $tanggal->format('d-m-Y');
            $hari = $tanggal->locale('id')->isoFormat('dddd'); // Hari dalam bahasa Indonesia
            $bulan = $tanggal->locale('id')->isoFormat('MMMM');
            $blnNumber = $tanggal->format('m');
            $tglNomor = $tanggal->locale('id')->isoFormat('DD MMMM YYYY');
            $terbilangPendapatan = $this->terbilang($d->pendapatan); // Konversi terbilang
            $tgl = $tanggal->locale('id')->isoFormat('DD MMM YYYY');

            // Format nomor
            $nomor = $tanggal->format('d') . '/SBS/01/' . $tanggal->format('Y');
            $nomor_sts = $tanggal->format('d') . '/KKPM/' . $tanggal->locale('id')->isoFormat('MMM') . '/' . $tanggal->format('Y');
            $dataSetoran = KasirSetoranModel::where('noSbs', $nomor)->first();
            $btnColor = $dataSetoran !== null ? 'btn btn-info' : 'btn btn-danger';

            // Tambahkan ke array hasil
            $result[] = [
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
                'pendapatan' => 'Rp ' . number_format($d->pendapatan, 0, ',', '.') . ',00',
                'jumlah' => $d->pendapatan,
                'terbilang' => ucfirst($terbilangPendapatan) . " rupiah.",
                'kode_akun' => 102010041411,
                'kode_rek' => "3.003.25581.5",
                'asal_pendapatan' => "-",
                'bank' => "BPD",
                'uraian' => 'Pendapatan Jasa Pelayanan Rawat Jalan 1',
                'btnColor' => $btnColor,
            ];

        }
        return $result;
        // return response()->json($result, 200, [], JSON_PRETTY_PRINT);
    }

    public function terbilang($angka)
    {
        $angka = abs((int) $angka); // Pastikan angka dalam bentuk numerik
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";

        if ($angka < 12) {
            $temp = $huruf[$angka];
        } elseif ($angka < 20) {
            $temp = $huruf[$angka - 10] . " belas";
        } elseif ($angka < 100) {
            $temp = $this->terbilang((int) ($angka / 10)) . " puluh " . $this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            $temp = "seratus " . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $temp = $this->terbilang((int) ($angka / 100)) . " ratus " . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $temp = "seribu " . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $temp = $this->terbilang((int) ($angka / 1000)) . " ribu " . $this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $temp = $this->terbilang((int) ($angka / 1000000)) . " juta " . $this->terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $temp = $this->terbilang((int) ($angka / 1000000000)) . " milyar " . $this->terbilang(fmod($angka, 1000000000));
        } elseif ($angka < 1000000000000000) {
            $temp = $this->terbilang((int) ($angka / 1000000000000)) . " triliun " . $this->terbilang(fmod($angka, 1000000000000));
        }

        return trim($temp); // Pastikan hasil akhir tanpa spasi berlebih
    }
}
