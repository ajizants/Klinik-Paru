<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoriumHasilModel extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "t_kunjungan_lab_hasil";

    protected $fillable = [
        'idLab', 'notrans', 'norm', 'idLayanan', 'hasil', 'ket', 'jumlah',
        'total', 'petugas', 'dokter', 'nik', 'nama', 'created_at', 'updated_at',
        'no_reg_lab', 'no_iden_sediaan', 'tgl_hasil', 'alasan_periksa', 'namaFaskes,',
    ];

    public function biodataPasien()
    {
        return $this->belongsTo(PasienModel::class, 'norm', 'norm');
    }

    public function pasien()
    {
        return $this->belongsTo(LaboratoriumKunjunganModel::class, 'notrans', 'notrans');
    }
    public function pemeriksaan()
    {
        return $this->hasOne(LayananModel::class, 'idLayanan', 'idLayanan');
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

    public static function desroyAll(string $notrans)
    {
        return static::where('notrans', $notrans)->delete();
    }

}
