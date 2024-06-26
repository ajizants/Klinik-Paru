<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IGDTransModel extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    protected $table = 't_kunjungan_tindakan';
    protected $primaryKey = 'id';

    public function tindakan()
    {
        return $this->belongsTo(TindakanModel::class, 'kdTind', 'kdTindakan');
    }
    public function transbmhp()
    {
        return $this->hasMany(TransaksiBMHPModel::class, 'idTind');
    }

    public function petugas()
    {
        return $this->belongsTo(PegawaiModel::class, 'petugas', 'nip');
    }

    public function dokter()
    {
        return $this->belongsTo(PegawaiModel::class, 'dokter', 'nip');
    }

    protected $fillable = [
        'norm',
        'notrans',
        'kdtind',
        'petugas',
        'dokter',
        'created_at',
        // 'updated_at'
    ];
}
