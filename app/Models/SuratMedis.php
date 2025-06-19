<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMedis extends Model
{
    protected $table = 't_no_surat_medis';
    protected $primaryKey = 'id';
    protected $fillable = [
        'tanggal',
        'noSurat',
        'norm',
        'nama',
        'tglLahir',
        'umur',
        'alamat',
        'hasil',
        'keperluan',
        'dokter',
        'nik',
        'petugas',
        'td',
        'bb',
        'tb',
        'nadi',
        'pekerjaan',
        'catatan',
    ];

    public function adm()
    {
        return $this->belongsTo(PegawaiModel::class, 'petugas', 'nip');
    }

    public function dok()
    {
        return $this->belongsTo(PegawaiModel::class, 'dokter', 'nip');
    }
}
