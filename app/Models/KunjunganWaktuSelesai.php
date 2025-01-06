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
        'waktu_selesai_farmasi',
        'konsul_ro',
        'created_at',
        'updated_at',
    ];

    public function layanan()
    {
        // Mengecek apakah no_sep bernilai null atau tidak
        if ($this->no_sep === null) {
            return 'UMUM'; // Jika no_sep null, maka layanan adalah UMUM
        } else {
            return 'BPJS'; // Jika no_sep tidak null, maka layanan adalah BPJS
        }
    }
}
