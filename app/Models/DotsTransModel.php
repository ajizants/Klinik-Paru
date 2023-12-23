<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DotsTransModel extends Model
{
    use HasFactory;

    protected $table = 't_kunjungan_dots';
    protected $primaryKey = 'id';


    protected $fillable = [
        'norm',
        'noHP',
        'notrans',
        'kdDx',
        'tglMulai',
        'petugas',
        'dokter',
    ];

    public function biodata()
    {
        return $this->hasOne(BiodataModel::class, 'norm', 'norm');
    }
    public function pasien()
    {
        return $this->hasOne(DotsModel::class, 'norm', 'norm');
    }
    public function dokter()
    {
        return $this->belongsTo(PegawaiModel::class, 'dokter', 'nip');
    }
}
