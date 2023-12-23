<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DotsBlnModel extends Model
{
    use HasFactory;

    protected $table = 'm_dots_kemajuan';
    protected $primaryKey = 'id';


    protected $fillable = [
        'nmBlnKe',
        'nilai',
    ];
}
