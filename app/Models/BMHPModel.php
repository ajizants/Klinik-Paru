<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BMHPModel extends Model
{
    use HasFactory;

    protected $connection = ('mysql');

    protected $table = ('tind_m_obat_stok_igd');
    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'idObat',
        'nmObat',
        'jenis',
        'pabrikan',
        'sediaan',
        'sumber',
        'supplier',
        'tglPembelian',
        'ed',
        'hargaBeli',
        'hargaJual',
        'stokBaru',
        'masuk',
        'keluar',
        'sisa',
    ];

    public function supplier()
    {
        return $this->belongsTo(SupplierModel::class, 'supplier', 'id');
    }
    public function pabrikan()
    {
        return $this->belongsTo(PabrikanModel::class, 'pabrikan', 'pabrikan');
    }
}
