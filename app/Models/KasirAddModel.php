<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasirAddModel extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $table = ('t_kasir_item');
    protected $primaryKey = 'id';

    protected $fillable = [
        'notrans',
        'norm',
        'idLayanan',
    ];

    public function layanan()
    {
        return $this->belongsTo(LayananModel::class, 'idLayanan', 'idLayanan');
    }
    public function transaksi()
    {
        return $this->belongsTo(KasirTransModel::class, 'notrans', 'notrans');
    }

    public function pendapatanPerItem(array $params)
    {
        $tglAwal = $params['tglAwal'] . ' 00:00:00';
        $tglAkhir = $params['tglAkhir'] . ' 23:59:59';

        // Ambil data dari model dengan relasi layanan
        $data = KasirAddModel::with('layanan') // Mengambil relasi 'layanan'
            ->selectRaw('
                        DATE(created_at) as tanggal,
                        idLayanan,
                        SUM(totalHarga) as jumlah,
                        COUNT(*) as totalItem
                    ')
            ->whereBetween('created_at', [$tglAwal, $tglAkhir]) // Menggunakan parameter date secara langsung
            ->groupBy('tanggal', 'idLayanan') // Kelompokkan berdasarkan tanggal dan idLayanan
            ->orderBy('tanggal', 'asc') // Urutkan berdasarkan tanggal
            ->get();

        // Inisialisasi array kosong untuk pendapatan
        $result = [];

        // Looping data pendapatan dan mengelompokkan berdasarkan tanggal
        foreach ($data as $d) {
            $result[] = [
                'tanggal' => $d->tanggal,
                'idLayanan' => $d->idLayanan,
                'jumlah' => $d->jumlah,
                'totalItem' => $d->totalItem,
                'nmLayanan' => $d->layanan->nmLayanan,
                'tarif' => $d->layanan->tarif,
                'kelas' => $d->layanan->kelas,
            ];
        }
        // dd($result);
        return $result;
    }
}
