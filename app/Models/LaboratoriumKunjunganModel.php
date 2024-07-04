<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoriumKunjunganModel extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "t_kunjungan_lab";

    public function pemeriksaan()
    {
        return $this->hasMany(LaboratoriumHasilModel::class, 'notrans', 'notrans');
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
