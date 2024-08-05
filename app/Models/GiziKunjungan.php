<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiziKunjungan extends Model
{
    use HasFactory;
    protected $connection = ('mysql');
    protected $table = 't_kunjungan_gizi';
    protected $primaryKey = 'id';
}
