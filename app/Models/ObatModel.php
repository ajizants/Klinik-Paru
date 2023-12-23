<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObatModel extends Model
{
    use HasFactory;

    protected $connection = ('mysql');

    protected $table = ('apt_m_obat');
    protected $primaryKey = ('idObat');


    protected $filable = [
        'nmObat',
    ];
}
