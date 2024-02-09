<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananModel extends Model
{
    use HasFactory;
    //layanan pemeriksaan yang tersedia di kkpm
    protected $connection = ('mysql');

    protected $table = ('kasir_m_layanan');
    // protected $primaryKey = ('idLayanan');

    protected $filable = [
        'kelas',
        'nmLayanan',
        'tarif',
        'status',
    ];

    public function kelas()
    {
        return $this->hasOne(LayananKelasModel::class, 'kelas', 'kelas');
    }
}
