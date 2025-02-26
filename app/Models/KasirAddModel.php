<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
                SUM(qty) as totalItem
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
                SUM(qty) as totalItem
            ')
            ->whereBetween('created_at', [$tglAwal, $tglAkhir])
            ->where('jaminan', 'UMUM')
            ->groupBy('tanggal', 'idLayanan')
            ->orderBy('tanggal', 'asc')
            ->get();

        // return $dataUmum;
        $dataUmum = $this->prosesPerItem($dataUmum);
        $dataBPJS = $this->prosesPerItem($dataBPJS);

        // return $dataUmum;
        $dataUmumBulanan = $this->prosesPerItemBulanan($dataUmum);
        // return $dataUmumBulanan;
        $dataBPJSBulanan = $this->prosesPerItemBulanan($dataBPJS);
        // return $dataUmumBulanan;

        // $bulanan = collect($this->pendapatanPerItemBulanan($params));
        // // dd($bulanan['umum']);
        // $bulananBpjs = $bulanan['bpjs'];
        // $bulananUmum = $bulanan['umum'];

        return [
            'bpjs'        => $dataBPJS,
            'umum'        => $dataUmum,
            'bpjsBulanan' => $dataBPJSBulanan,
            'umumBulanan' => $dataUmumBulanan,
        ];

    }
    // public function pendapatanPerItemBulanan(array $params)
    // {
    //     $tglAwal  = $params['tglAwal'] . ' 00:00:00';
    //     $tglAkhir = $params['tglAkhir'] . ' 23:59:59';

    //     $dataBPJS = self::with('layanan')
    //         ->selectRaw('
    //         DATE_FORMAT(created_at, "%Y-%m") as bulan,
    //         idLayanan,
    //         SUM(totalHarga) as jumlah,
    //         COUNT(*) as totalItem
    //     ')
    //         ->whereBetween('created_at', [$tglAwal, $tglAkhir])
    //         ->where('jaminan', 'BPJS')
    //         ->groupBy('bulan', 'idLayanan')
    //         ->orderBy('bulan', 'asc')
    //         ->get();

    //     $dataUmum = self::with('layanan')
    //         ->selectRaw('
    //         DATE_FORMAT(created_at, "%Y-%m") as bulan,
    //         idLayanan,
    //         SUM(totalHarga) as jumlah,
    //         COUNT(*) as totalItem
    //     ')
    //         ->whereBetween('created_at', [$tglAwal, $tglAkhir])
    //         ->where('jaminan', 'UMUM')
    //         ->groupBy('bulan', 'idLayanan')
    //         ->orderBy('bulan', 'asc')
    //         ->get();

    //     return [
    //         'bpjs' => $this->prosesPerItem($dataBPJS),
    //         'umum' => $this->prosesPerItem($dataUmum),
    //     ];
    // }

    public function pendapatanPerItemBulanan(array $params)
    {
        $tglAwal  = $params['tglAwal'] . ' 00:00:00';
        $tglAkhir = $params['tglAkhir'] . ' 23:59:59';

        $dataBPJS = self::with('layanan')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as bulan'),
                'idLayanan',
                DB::raw('SUM(totalHarga) as jumlah'),
                DB::raw('SUM(qty) as totalItem')
            )
            ->whereBetween('created_at', [$tglAwal, $tglAkhir])
            ->where('jaminan', 'BPJS')
            ->groupBy('bulan', 'idLayanan')
            ->orderBy('bulan', 'asc')
            ->get();

        $dataUmum = self::with('layanan')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as bulan'),
                'idLayanan',
                DB::raw('SUM(totalHarga) as jumlah'),
                DB::raw('SUM(qty) as totalItem')
            )
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

    private function prosesPerItemBulanan($data)
    {
        // Kelompokkan data berdasarkan bulan dan idLayanan
        $groupedData = collect($data)->groupBy(function ($item) {
            return date('Y-m', strtotime($item['tanggal'])); // Ambil bulan dan tahun dari tanggal
        })->map(function ($monthGroup) {
            // Kelompokkan lagi berdasarkan idLayanan
            return $monthGroup->groupBy('idLayanan')->map(function ($itemGroup) {
                // Hitung total jumlah dan totalItem
                return [
                    'bulanTahun' => date('Y-m', strtotime($itemGroup->first()['tanggal'])), // Ambil bulan dan tahun
                    'idLayanan'  => $itemGroup->first()['idLayanan'],                       // Ambil idLayanan
                    'jumlah'     => $itemGroup->sum('jumlah'),                              // Jumlahkan total jumlah
                    'totalItem'  => $itemGroup->sum('totalItem'),                           // Jumlahkan total item
                    'nmLayanan'  => $itemGroup->first()['nmLayanan'],                       // Ambil nama layanan
                    'tarif'      => $itemGroup->first()['tarif'],                           // Ambil tarif
                    'kelas'      => $itemGroup->first()['kelas'],                           // Ambil kelas
                ];
            })->values(); // Konversi hasil ke array
        })->values(); // Konversi hasil ke array

        return $groupedData->flatten(1); // Ratakan hasil ke satu level
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
        // dd($params);

        // Ambil data utama dengan relasi
        $dataBPJS = self::with('layanan.ruang')
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
        // dd($dataBPJS);
        //     $query = self::with('layanan.ruang')
        //         ->selectRaw('
        //     DATE(created_at) as tanggal,
        //     idLayanan,
        //     SUM(totalHarga) as jumlah,
        //     COUNT(*) as totalItem
        // ')
        //         ->whereBetween('created_at', [$tglAwal, $tglAkhir])
        //         ->where('jaminan', 'BPJS')
        //         ->groupBy('tanggal', 'idLayanan')
        //         ->orderBy('tanggal', 'asc');

        //     // Dapatkan query SQL dan binding parameter
        //     $sql      = $query->toSql();
        //     $bindings = $query->getBindings();

        //     // Gabungkan query dengan binding parameter
        //     $fullQuery = vsprintf(str_replace('?', '%s', $sql), $bindings);

        //     dd($fullQuery);

        $dataUmum = self::with('layanan.ruang')
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
        // dd($dataUmum);

        $res = [
            'umum' => $this->prosesPerRuang($dataUmum),
            'bpjs' => $this->prosesPerRuang($dataBPJS),
        ];
        // dd($res);

        return $res;
    }

    private function prosesPerRuang($data)
    {
        // dd($data[0]->layanan->ruang);
        $result = [];

        foreach ($data as $d) {
            $kelas = $d->layanan->ruang->nmKelas;

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
