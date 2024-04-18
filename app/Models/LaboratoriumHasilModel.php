<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoriumHasilModel extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "lab_hasil_pemeriksaan";

}
