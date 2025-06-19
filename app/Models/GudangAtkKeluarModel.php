<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangAtkKeluarModel extends Model
{
    use HasFactory;
    protected $table = 'gudang_atk_keluar';
    protected $fillable = [
        'id',
        'idBarang',
        'NamaBarang',
        'jumlah',
        'keterangan',
    ];
}
