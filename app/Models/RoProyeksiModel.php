<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoProyeksiModel extends Model
{
    protected $table = 'm_rontgen_proyeksi';
    protected $primaryKey = 'kdProyeksi';
    public $timestamps = false;
}
