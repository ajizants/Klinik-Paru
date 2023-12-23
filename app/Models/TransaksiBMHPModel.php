<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiBMHPModel extends Model
{
    use HasFactory;

    protected $table = 't_tind_bmhp';
    protected $primaryKey = 'id';

    public function tindakan()
    {
        return $this->belongsTo(TransaksiModel::class, 'idTind', 'id');
    }

    public function bmhp()
    {
        return $this->belongsTo(BMHPModel::class, 'kdBmhp', 'id');
    }

    protected $fillable = [
        'idTind',
        'kdBmhp',
        'jml',
        'biaya',
        'notrans',
    ];

    // protected static function booted()
    // {
    //     // Event saat transaksi disimpan
    //     static::saved(function ($transaksi) {
    //         // dd('Event Transaksi Disimpan', $transaksi->id, $transaksi->kdBmhp);
    //         self::updateStok($transaksi);
    //     });

    //     // Event saat transaksi dihapus
    //     static::deleted(function ($transaksi) {
    //         // dd('Event Transaksi Dihapus', $transaksi->id, $transaksi->kdBmhp);
    //         self::updateStok($transaksi);
    //     });
    // }

    // // Fungsi untuk mengupdate stok
    // protected static function updateStok($transaksi)
    // {
    //     $bmhp = $transaksi->bmhp;
    //     if ($bmhp) {
    //         if ($transaksi->jml < 0) {
    //             // Jika jenis transaksi adalah keluar, kurangi jumlah dari kolom 'keluar' di m_bmhp
    //             $bmhp->decrement('keluar', abs($transaksi->jml));
    //         }
    //         // Update kolom 'sisa' di m_bmhp
    //         $bmhp->update(['sisa' => $bmhp->stock_awal + $bmhp->masuk - $bmhp->keluar]);
    //     }
    // }
}
