<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vPegawaiModel extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    protected $table = 'v_pegawai';
}
