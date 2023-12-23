<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmhpAddStokModel extends Model
{
    use HasFactory;

    protected $table = 'tind_m_bmhp_instok';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kdBmhp',
        'masuk',
        'tglED',
    ];

    protected static function booted()
    {
        // Event saat transaksi disimpan
        static::saved(function ($transaksi) {
            self::updateStok($transaksi);
        });

        // Event saat transaksi dihapus
        static::deleted(function ($transaksi) {
            self::updateStok($transaksi);
        });
    }

    // Fungsi untuk mengupdate stok
    protected static function updateStok($transaksi)
    {
        $bmhp = BMHPModel::where('kdBmhp', $transaksi->kdBmhp)->first();
        if ($bmhp) {
            // Kurangi jumlah dari kolom 'masuk' di m_bmhp
            $bmhp->decrement('masuk', $transaksi->masuk);
            // Update kolom 'sisa' di m_bmhp
            $bmhp->update(['sisa' => $bmhp->stock_awal + $bmhp->masuk - $bmhp->keluar]);
        }
    }
}
