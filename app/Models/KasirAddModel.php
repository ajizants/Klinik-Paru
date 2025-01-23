<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasirAddModel extends Model
{
    use HasFactory;

    protected $table      = 't_kasir_item';
    protected $primaryKey = 'id';

    protected $fillable = [
        'notrans',
        'norm',
        'idLayanan',
        'totalHarga',
        'qty',
        'jaminan',
        'created_at',
        'updated_at',
    ];

    public function layanan()
    {
        return $this->belongsTo(LayananModel::class, 'idLayanan', 'idLayanan');
    }

    public function transaksi()
    {
        return $this->belongsTo(KasirTransModel::class, 'notrans', 'notrans');
    }

    // public function pendapatanPerItem(array $params)
    // {
    //     $tglAwal = $params['tglAwal'] . ' 00:00:00';
    //     $tglAkhir = $params['tglAkhir'] . ' 23:59:59';

    //     $data = self::with('layanan')
    //         ->selectRaw('
    //             DATE(created_at) as tanggal,
    //             idLayanan,
    //             SUM(totalHarga) as jumlah,
    //             COUNT(*) as totalItem
    //         ')
    //         ->whereBetween('created_at', [$tglAwal, $tglAkhir])
    //         ->groupBy('tanggal', 'idLayanan')
    //         ->orderBy('tanggal', 'asc')
    //         ->get();

    //     $result = [];

    //     foreach ($data as $d) {
    //         $result[] = [
    //             'tanggal' => $d->tanggal,
    //             'idLayanan' => $d->idLayanan,
    //             'jumlah' => $d->jumlah,
    //             'totalItem' => $d->totalItem,
    //             'nmLayanan' => $d->layanan->nmLayanan,
    //             'tarif' => $d->layanan->tarif,
    //             'kelas' => $d->layanan->kelas,
    //         ];
    //     }

    //     return $result;
    // }
    public function pendapatanPerItem(array $params)
    {
        $tglAwal  = $params['tglAwal'] . ' 00:00:00';
        $tglAkhir = $params['tglAkhir'] . ' 23:59:59';

        $dataBPJS = self::with('layanan')
            ->selectRaw('
                DATE(created_at) as tanggal,
                idLayanan,
                SUM(totalHarga) as jumlah,
                COUNT(*) as totalItem
            ')
            ->whereBetween('created_at', [$tglAwal, $tglAkhir])
            ->where('jaminan', 'BPJS')
            ->groupBy('tanggal', 'idLayanan')
            ->orderBy('tanggal', 'asc')
            ->get();

        $dataUmum = self::with('layanan')
            ->selectRaw('
                DATE(created_at) as tanggal,
                idLayanan,
                SUM(totalHarga) as jumlah,
                COUNT(*) as totalItem
            ')
            ->whereBetween('created_at', [$tglAwal, $tglAkhir])
            ->where('jaminan', 'UMUM')
            ->groupBy('tanggal', 'idLayanan')
            ->orderBy('tanggal', 'asc')
            ->get();

        $bulanan = collect($this->pendapatanPerItemBulanan($params));
        // dd($bulanan['umum']);
        $bulananBpjs = $bulanan['bpjs'];
        $bulananUmum = $bulanan['umum'];

        return [
            'bpjs'        => $this->prosesPerItem($dataBPJS),
            'umum'        => $this->prosesPerItem($dataUmum),
            'bpjsBulanan' => $bulananBpjs,
            'umumBulanan' => $bulananUmum,
        ];

    }
    public function pendapatanPerItemBulanan(array $params)
    {
        $tglAwal  = $params['tglAwal'] . ' 00:00:00';
        $tglAkhir = $params['tglAkhir'] . ' 23:59:59';

        $dataBPJS = self::with('layanan')
            ->selectRaw('
            DATE_FORMAT(created_at, "%Y-%m") as bulan,
            idLayanan,
            SUM(totalHarga) as jumlah,
            COUNT(*) as totalItem
        ')
            ->whereBetween('created_at', [$tglAwal, $tglAkhir])
            ->where('jaminan', 'BPJS')
            ->groupBy('bulan', 'idLayanan')
            ->orderBy('bulan', 'asc')
            ->get();

        $dataUmum = self::with('layanan')
            ->selectRaw('
            DATE_FORMAT(created_at, "%Y-%m") as bulan,
            idLayanan,
            SUM(totalHarga) as jumlah,
            COUNT(*) as totalItem
        ')
            ->whereBetween('created_at', [$tglAwal, $tglAkhir])
            ->where('jaminan', 'UMUM')
            ->groupBy('bulan', 'idLayanan')
            ->orderBy('bulan', 'asc')
            ->get();

        return [
            'bpjs' => $this->prosesPerItem($dataBPJS),
            'umum' => $this->prosesPerItem($dataUmum),
        ];
    }

    private function prosesPerItem($data)
    {
        $result = [];

        foreach ($data as $d) {
            $result[] = [
                'tanggal'   => $d->tanggal,
                'idLayanan' => $d->idLayanan,
                'jumlah'    => $d->jumlah,
                'totalItem' => $d->totalItem,
                'nmLayanan' => $d->layanan->nmLayanan,
                'tarif'     => $d->layanan->tarif,
                'kelas'     => $d->layanan->kelas,
            ];
        }

        return $result;
    }

    public function pendapatanPerRuang(array $params)
    {
        $tglAwal  = $params['tglAwal'] . ' 00:00:00';
        $tglAkhir = $params['tglAkhir'] . ' 23:59:59';

        // Ambil data utama dengan relasi
        $dataBPJS = self::with('layanan.grup')
            ->selectRaw('
                DATE(created_at) as tanggal,
                idLayanan,
                SUM(totalHarga) as jumlah,
                COUNT(*) as totalItem
            ')
            ->whereBetween('created_at', [$tglAwal, $tglAkhir])
            ->where('jaminan', 'BPJS')
            ->groupBy('tanggal', 'idLayanan')
            ->orderBy('tanggal', 'asc')
            ->get();

        $dataUmum = self::with('layanan.grup')
            ->selectRaw('
                DATE(created_at) as tanggal,
                idLayanan,
                SUM(totalHarga) as jumlah,
                COUNT(*) as totalItem
            ')
            ->whereBetween('created_at', [$tglAwal, $tglAkhir])
            ->where('jaminan', 'UMUM')
            ->groupBy('tanggal', 'idLayanan')
            ->orderBy('tanggal', 'asc')
            ->get();

        $res = [
            'bpjs' => $this->prosesPerRuang($dataBPJS),
            'umum' => $this->prosesPerRuang($dataUmum),
        ];

        return $res;
    }

    private function prosesPerRuang($data)
    {
        $result = [];

        foreach ($data as $d) {
            $kelas = $d->layanan->grup->nmKelas;

            // Key pengelompokan berdasarkan tanggal dan kelas
            $key = $d->tanggal . '|' . $kelas;

            if (! isset($result[$key])) {
                $result[$key] = [
                    'tanggal'   => $d->tanggal,
                    'nmKelas'   => $kelas,
                    'idKelas'   => $d->layanan->kelas,
                    'jumlah'    => 0,
                    'totalItem' => 0,
                ];
            }

            // Akumulasi jumlah dan total item
            $result[$key]['jumlah'] += $d->jumlah;
            $result[$key]['totalItem'] += $d->totalItem;
        }
        return array_values($result);
    }

}
