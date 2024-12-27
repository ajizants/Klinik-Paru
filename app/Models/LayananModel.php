<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananModel extends Model
{
    use HasFactory;
    protected $table = 'kasir_m_layanan';
    protected $primaryKey = 'idLayanan';
    protected $fillable = [
        'nmLayanan', 'tarif', 'kelas', 'status', 'kdTind', 'kdFoto', 'satuan', 'normal',
    ];

    public function grup()
    {
        return $this->belongsTo(LayananKelasModel::class, 'kelas', 'kelas');
    }
}
