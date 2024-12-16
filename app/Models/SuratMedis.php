<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMedis extends Model
{
    protected $table = 't_no_surat_medis';
    protected $primaryKey = 'id';

    public function adm()
    {
        return $this->belongsTo(PegawaiModel::class, 'petugas', 'nip');
    }

    public function dok()
    {
        return $this->belongsTo(PegawaiModel::class, 'dokter', 'nip');
    }
}
