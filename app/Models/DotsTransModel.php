<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DotsTransModel extends Model
{
    use HasFactory;

    protected $table = 't_kunjungan_dots';
    // protected $primaryKey = 'id';

    public function biodata()
    {
        return $this->hasOne(PasienModel::class, 'norm', 'norm');
    }
    public function pasien()
    {
        return $this->hasOne(DotsModel::class, 'norm', 'norm');
    }
    public function dokter()
    {
        return $this->hasOne(PegawaiModel::class, 'nip', 'dokter');
    }
    public function petugas()
    {
        return $this->hasOne(PegawaiModel::class, 'nip', 'petugas');
    }
    public function obat()
    {
        return $this->HasOne(DotsObatModel::class, 'id', 'terapi');
    }
}
