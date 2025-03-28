<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PegawaiModel extends Model
{
    protected $table = 'peg_t_pegawai';
    protected $primaryKey = 'nip'; // Set primary key menjadi 'nip'
    public $incrementing = false; // Karena 'nip' bukan auto-increment
    protected $keyType = 'string'; // Jika 'nip' adalah string

    protected $fillable = [
        'nip', 'kd_jab', 'kd_pend', 'kd_jurusan', 'gelar_d', 'gelar_b', 'stat_pns', 'tgl_masuk', 'sip', 'pangkat_gol',
    ];
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
