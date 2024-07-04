<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoriumHasilModel extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "t_kunjungan_lab_hasil";

    public function pasien()
    {
        return $this->belongsTo(PasienModel::class, 'notrans', 'notrans');
    }
    public function pemeriksaan()
    {
        return $this->hasOne(LayananModel::class, 'idLayanan', 'idLayanan');
    }
    public function petugas()
    {
        return $this->belongsTo(PegawaiModel::class, 'petugas', 'nip');
    }

    public function dokter()
    {
        return $this->belongsTo(PegawaiModel::class, 'dokter', 'nip');
    }

}
