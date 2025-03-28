<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiodataModel extends Model
{
    protected $table = 'peg_m_biodata';
    protected $primaryKey = 'nip';
    public $incrementing = false; // Karena 'nip' bukan auto-increment
    protected $keyType = 'string'; // Jika 'nip' adalah string

    protected $fillable = [ // ✅ Perbaikan typo dari "filallable" ke "fillable"
        'nip', 'nama', 'kd_jab', 'jeniskel', 'tempat_lahir', 'tgl_lahir', 'alamat',
        'kd_prov', 'kd_kab', 'kd_kec', 'kd_kel', 'kdAgama', 'status_kawin',
    ];

    public function jabatan()
    {
        return $this->hasOne(JabatanModel::class, 'kd_jab', 'kd_jab');
    }
}
