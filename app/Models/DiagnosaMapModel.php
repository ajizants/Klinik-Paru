<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosaMapModel extends Model
{
    use HasFactory;

    protected $table = 'm_diagnosa_map';

    public function diagnosa($kd_dx)
    {
        return $this->where('kd_dx', $kd_dx)->first();
    }

}
