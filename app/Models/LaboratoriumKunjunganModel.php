<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoriumKunjunganModel extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "t_kunjungan_lab";

    protected $fillable = [
        'notrans', 'norm', 'nik', 'jk', 'no_sampel', 'umur', 'petugas', 'dokter', 'alamat', 'waktu_selesai', 'created_at', 'updated_at', 'nama', 'layanan', 'ket',
    ];

    public function pemeriksaan()
    {
        return $this->hasMany(LaboratoriumHasilModel::class, 'notrans', 'notrans');
    }

    public function tb04()
    {
        return $this->hasMany(LaboratoriumHasilModel::class, 'notrans', 'notrans')
            ->whereIn('idLayanan', [130, 131, 214]);
    }

    public function petugas()
    {
        return $this->belongsTo(PegawaiModel::class, 'petugas', 'nip');
    }

    public function dokter()
    {
        return $this->belongsTo(PegawaiModel::class, 'dokter', 'nip');
    }

}
