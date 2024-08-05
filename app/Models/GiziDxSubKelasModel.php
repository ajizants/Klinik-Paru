<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiziDxSubKelasModel extends Model
{
    use HasFactory;
    protected $connection = ('mysql');
    protected $table = ('gizi_dx_kelas_sub');
    // protected $primarykey = 'id';
    protected $fillable = [
        'kode',
        'domain',
        'kelas',
        'sub_kelas',
    ];

    public function domain()
    {
        return $this->belongsTo(GiziDxDomainModel::class, 'domain', 'kode');
    }

    public function kelas()
    {
        return $this->belongsTo(GiziDxKelasModel::class, 'kelas', 'kode');
    }

}
