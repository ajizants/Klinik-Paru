<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BiodataModel extends Model
{
    protected $table = 'peg_m_biodata';
    // protected $primaryKey = 'nip';

    public function jabatan()
    {
        return $this->hasOne(JabatanModel::class, 'kd_jab', 'kd_jab');
    }
}
