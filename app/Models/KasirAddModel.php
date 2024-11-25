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
}
