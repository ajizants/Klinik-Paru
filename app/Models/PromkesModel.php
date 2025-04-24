<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromkesModel extends Model
{
    use HasFactory;
    protected $table = 't_promkes_luar';

    protected $fillable = [
        'id',
        'tanggal',
        'tempat',
        'keterangan',
        'keterangan2',
    ];
}
