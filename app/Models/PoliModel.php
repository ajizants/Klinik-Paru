<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoliModel extends Model
{
    protected $table = 't_poli';

    public function dx1()
    {
        return $this->hasOne(DiagnosaModel::class, 'kdDiag', 'diagnosa1');
    }

    public function dx2()
    {
        return $this->hasOne(DiagnosaModel::class, 'kdDiag', 'diagnosa2');
    }
    public function dx3()
    {
        return $this->hasOne(DiagnosaModel::class, 'kdDiag', 'diagnosa3');
    }
    public function pasien()
    {
        return $this->hasOne(PasienModel::class, 'norm', 'norm');
    }
    public function kunjungan()
    {
        return $this->hasOne(KunjunganModel::class, 'notrans', 'notrans');
    }
}
