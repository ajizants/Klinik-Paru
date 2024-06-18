<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ROTransaksiModel extends Model
{
    protected $table = 't_rontgen';
    protected $primaryKey = 'notrans';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function film()
    {
        return $this->hasOne(ROJenisFilm::class, 'kdFilm', 'kdFilm');
    }
    public function foto()
    {
        return $this->hasOne(ROJenisFoto::class, 'kdFoto', 'kdFoto');
    }
    public function proyeksi()
    {
        return $this->hasOne(RoProyeksiModel::class, 'kdProyeksi', 'kdProyeksi');
    }
    public function mesin()
    {
        return $this->hasOne(ROJenisMesin::class, 'kdMesin', 'kdMesin');
    }
    public function kv()
    {
        return $this->hasOne(ROJenisKondisi::class, 'kdKondisiRo', 'kv');
    }
    public function ma()
    {
        return $this->hasOne(ROJenisKondisi::class, 'kdKondisiRo', 'ma');
    }
    public function s()
    {
        return $this->hasOne(ROJenisKondisi::class, 'kdKondisiRo', 's');
    }
    public function hasil()
    {
        return $this->hasMany(ROTransaksiHasilModel::class, 'norm', 'norm');
    }
    public function pasien()
    {
        return $this->hasOne(PasienModel::class, 'norm', 'norm');
    }
    public function radiografer()
    {
        return $this->hasOne(TransPetugasModel::class, 'norm', 'norm');
    }
    public function dokter()
    {
        return $this->hasOne(TransPetugasModel::class, 'norm', 'norm');
    }
}
