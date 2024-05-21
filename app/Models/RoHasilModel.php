<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoHasilModel extends Model
{
    protected $connection = 'rontgen';
    protected $table = 'foto_thorax';
    public $timestamps = false;
}
