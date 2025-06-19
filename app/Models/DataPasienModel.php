<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPasienModel extends Model
{
    use HasFactory;

    protected $table = 'm_pasien';
    protected $primaryKey = 'inorm';
    public $incrementing = true; // penting untuk auto-increment
    protected $keyType = 'int'; // karena biasanya id auto increment berupa integer
    public $timestamps = false;

    protected $fillable = [
        'norm',
        'rmlama',
        'tgldaftar',
        'jamdaftar',
        'kkelompok',
        'noasuransi',
        'noktp',
        'nama',
        'alamat',
        'kprovinsi',
        'kkabupaten',
        'kkecamatan',
        'kkelurahan',
        'rtrw',
        'jeniskel',
        'tmptlahir',
        'tgllahir',
        'kdAgama',
        'kdPendidikan',
        'nohp',
        'statKawin',
        'pekerjaan',
        'pjwb',
        'ibuKandung',
        'jctkkartu',
        'goldarah',
        'kunj',
    ];
}
