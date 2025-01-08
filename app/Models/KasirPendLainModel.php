<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasirPendLainModel extends Model
{
    use HasFactory;

    protected $table = ('t_kasir_pendapatan_lain');

    protected $fillable = [
        'tanggal',
        'jumlah',
        'penyetor',
        'asal_pendapatan',
    ];

    public function pendapatanLainSimpan($params)
    {
        return $this->create($params);
    }
}
