<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoriumModel extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "lab_pendaftaran_pemeriksaan";

    public function kunjungan()
    {
        return $this->hasOne(KunjunganModel::class, 'notrans', 'notrans');

    }
    public function layanan()
    {
        return $this->belongsTo(LayananModel::class, 'idLayanan', 'idLayanan');
    }

    public function petugas()
    {
        return $this->belongsTo(PegawaiModel::class, 'petugas', 'nip');
    }

    public function dokter()
    {
        return $this->belongsTo(PegawaiModel::class, 'dokter', 'nip');
    }

    public static function destroyLab(string $id)
    {
        // Menemukan dan menghapus data berdasarkan ID
        return static::where('idLab', $id)->delete();
    }
}
