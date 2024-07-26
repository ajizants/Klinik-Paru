<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiziAsesmentModel extends Model
{
    use HasFactory;
    protected $connection = ('mysql');
    protected $table = ('gizi_asesment_awal');
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
