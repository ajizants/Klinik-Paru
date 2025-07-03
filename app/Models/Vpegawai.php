<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vpegawai extends Model
{
    use HasFactory;
    protected $table = 'V_pegawai';

    public function sisaCuti()
    {
        return $this->belongsTo(PegawaiModel::class, 'nip', 'nip');
    }

    public function cuti()
    {
        return $this->hasMany(CutiPegawai::class, 'nip', 'nip');
    }

    public function cutiTambahan()
    {
        return $this->hasMany(CutiTambahan::class, 'nip', 'nip');
    }

}
