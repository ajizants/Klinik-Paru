<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ROTransaksiModel extends Model
{
    protected $table = 't_rontgen';
    protected $primaryKey = 'notrans';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'norm', 'nama', 'alamat', 'jk', 'tgltrans', 'noreg', 'pasienRawat',
        'kdFoto', 'kdFilm', 'ma', 'kv', 's', 'jmlExpose', 'jmlFilmDipakai',
        'jmlFilmRusak', 'kdMesin', 'kdProyeksi', 'catatan', 'layanan',
    ];

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

    public function kondisiOld()
    {
        return $this->hasOne(ROJenisKondisiOld::class, 'kdKondisiRo', 'kdKondisiRo');
    }

    public function hasil()
    {
        return $this->hasMany(ROTransaksiHasilModel::class, 'norm', 'norm');
    }
    public function pasien()
    {
        return $this->hasOne(PasienModel::class, 'norm', 'norm');
        // return $this->hasOne(KominfoModel::class, 'norm', 'norm');
    }
    public function radiografer()
    {
        return $this->hasOne(TransPetugasModel::class, 'notrans', 'notrans');
    }
    public function dokter()
    {
        return $this->hasOne(TransPetugasModel::class, 'norm', 'norm');
    }
    public function kunjungan()
    {
        return $this->hasOne(KunjunganModel::class, 'notrans', 'notrans');
    }
}
