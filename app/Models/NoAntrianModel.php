<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoAntrianModel extends Model
{
    use HasFactory;
    protected $connection = 'antrian';

    protected $table = 'tbnoantri';
    protected $primaryKey = 'Tanggal';

    protected $fillable = [
        'NoAntri',
        'NoRM',
        'Nama',
        'ALAMAT',
        'Tanggal',
        'Panggil',
        'Selesai',
        'LOKET',
        'LEWATI',
        'jenis',

    ];
}
