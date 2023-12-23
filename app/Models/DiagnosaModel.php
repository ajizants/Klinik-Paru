<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosaModel extends Model
{
    use HasFactory;

    protected $table = 'm_diagnosa';

    protected $fillable = [
        'kdDiag',
        'diagnosa',
    ];
}
