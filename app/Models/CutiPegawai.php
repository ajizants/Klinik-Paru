<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiPegawai extends Model
{
    use HasFactory;

    protected $table    = 'peg_t_cuti';
    protected $fillable = ['nip', 'tgl_mulai', 'tgl_selesai', 'persetujuan', 'keterangan', 'alasan'];

    public function pegawai()
    {
        return $this->belongsTo(Vpegawai::class, 'nip', 'nip');
    }
}
