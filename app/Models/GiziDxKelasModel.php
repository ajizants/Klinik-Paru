<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiziDxKelasModel extends Model
{
    use HasFactory;
    protected $connection = ('mysql');
    protected $table = ('gizi_dx_kelas');
}
