<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PabrikanModel extends Model
{
    use HasFactory;

    protected $table = 'apt_m_pabrikan_obat';
    protected $primaryKey = 'pabrikan';


    protected $fillable = [
        'nmPabrikan',
    ];
}
