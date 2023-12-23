<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatModel extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    protected $table = 'v_det_poli';
    public function dx1()
    {
        return $this->hasOne(DiagnosaModel::class, 'kdDiag', 'diagnosa1');
    }
    public function dx2()
    {
        return $this->hasOne(DiagnosaModel::class, 'kdDiag', 'diagnosa2');
    }
}
