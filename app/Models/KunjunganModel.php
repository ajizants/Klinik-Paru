<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunjunganModel extends Model
{
    use HasFactory;
    protected $table = 't_kunjungan';
    public $timestamps = false;
    public function poli()
    {
        return $this->hasOne(PoliModel::class, 'notrans', 'notrans');
    }
    public function tensi()
    {
        return $this->hasOne(TensiModel::class, 'notrans', 'notrans');
    }
    public function tujuan()
    {
        return $this->hasOne(TujuanModel::class, 'kd_tujuan', 'ktujuan');
    }

    public function biodata()
    {
        return $this->hasOne(PasienModel::class, 'norm', 'norm');
    }

    public function kelompok()
    {
        return $this->hasOne(KelompokModel::class, 'kkelompok', 'kkelompok');
    }

    public function petugas()
    {
        return $this->hasOne(PetugasModel::class, 'notrans', 'notrans');
    }

    public function tindakan()
    {
        return $this->hasMany(IGDTransModel::class, 'notrans', 'notrans');
    }
    public function lab()
    {
        return $this->hasMany(LaboratoriumModel::class, 'notrans', 'notrans');
    }
    public function farmasi()
    {
        return $this->hasMany(FarmasiModel::class, 'notrans', 'notrans');
    }
    public function dots()
    {
        return $this->hasOne(DotsTransModel::class, 'notrans', 'notrans');
    }
    public function ptb()
    {
        return $this->hasOne(DotsModel::class, 'norm', 'norm');
    }
    public function riwayatFarmasi()
    {
        return $this->hasMany(FarmasiModel::class, 'notrans', 'notrans');
    }
    public function riwayatTindakan()
    {
        return $this->hasMany(IGDTransModel::class, 'notrans', 'notrans');
    }
    public function riwayatLab()
    {
        return $this->hasMany(LaboratoriumModel::class, 'notrans', 'notrans');
    }

}
