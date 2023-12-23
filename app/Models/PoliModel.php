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
}
