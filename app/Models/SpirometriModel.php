<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpirometriModel extends Model
{
    use HasFactory;

    protected $table    = 't_kunjungan_tindakan_sepiro';
    protected $fillable = [
        'norm',
        'notrans',
        'pred_fvc',
        'value_fvc',
        'pred_fvc_2',
        'pred_fev1',
        'value_fev1',
        'pred_fev1_2',
        'pred_fev1_fvc',
        'value_fev1_fvc',
        'pred_fev1_fvc_2',
        'petugas',
        'dokter',
        'created_at', 'updated_at',
    ];

    public function pasien()
    {
        return $this->belongsTo(PasienModel::class, 'norm', 'norm');
    }

    public function biodataPetugas()
    {
        return $this->belongsTo(Vpegawai::class, 'petugas', 'nip');
    }

    public function biodataDokter()
    {
        return $this->belongsTo(Vpegawai::class, 'dokter', 'nip');
    }
}
