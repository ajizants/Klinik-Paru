<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunjunganWaktuSelesai extends Model
{
    use HasFactory;

    protected $table = ('t_waktu_rm_selesai');
    protected $primaryKey = 'notrans';

    protected $fillable = [
        'norm',
        'notrans',
        'no_sep',
        'waktu_selesai_rm',
        'waktu_selesai_igd',
        'created_at',
        'updated_at',
    ];
}
