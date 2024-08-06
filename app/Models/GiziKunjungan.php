<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiziKunjungan extends Model
{
    use HasFactory;
    protected $connection = ('mysql');
    protected $table = 't_kunjungan_gizi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'norm', 'notrans', 'dokter', 'ahli_gizi', 'bb', 'tb', 'imt', 'keluhan', 'parameter', 'dxMedis', 'dxGizi', 'etiologi', 'evaluasi',
    ];

    public function dxGizi()
    {
        return $this->belongsTo(GiziDxSubKelasModel::class, 'dxGizi', 'kode');
    }

    public function dxMedis()
    {
        return $this->belongsTo(DiagnosaModel::class, 'dxMedis', 'kdDiag');
    }
}
