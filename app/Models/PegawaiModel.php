<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PegawaiModel extends Model
{
    protected $table = 'peg_t_pegawai';
    // protected $primaryKey = 'nip';

    public function biodata()
    {
        return $this->hasOne(BiodataModel::class, 'nip', 'nip');
    }
    public function jabatan()
    {
        return $this->hasOne(JabatanModel::class, 'kd_jab', 'kd_jab');
    }
    public function karyawan()
    {
        return $this->with('biodata', 'jabatan')->get();
    }
}
