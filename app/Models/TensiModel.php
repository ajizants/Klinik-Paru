<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TensiModel extends Model
{
    protected $table = 't_tensi';

    public function pasien()
    {
        return $this->hasOne(PasienModel::class, 'norm', 'norm');
    }
    public function kunjungan()
    {
        return $this->hasOne(KunjunganModel::class, 'notrans', 'notrans');
    }
}
