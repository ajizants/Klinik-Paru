<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiziModel extends Model
{
    use HasFactory;
    protected $connection = ('mysql');
    protected $table = ('gizi_pasien');
    protected $primaryKey = 'id';

    // protected $fillable = [
    //     'id',
    //     'norm',
    //     'nama',
    //     'alamat',
    //     'tgl_lahir',
    //     'sumber',
    //     'supplier',];
}
