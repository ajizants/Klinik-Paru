<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransPetugasModel extends Model
{
    protected $table      = 't_petugas'; // Specify your table name
    protected $primaryKey = 'notrans';
    protected $keyType    = 'string';
    public $incrementing  = false;
    public $timestamps    = false;

    protected $fillable = [
        'notrans', 'p_dokter_poli', 'p_rontgen', 'p_rontgen_evaluator',
    ];

    public function pegawai()
    {
        return $this->hasOne(Vpegawai::class, 'nip', 'p_dokter_poli');
    }

    public function transaksi()
    {
        return $this->belongsTo(ROTransaksiModel::class, 'notrans', 'notrans');
    }
    public function radiografer()
    {
        return $this->hasOne(BiodataModel::class, 'nip', 'p_rontgen');
    }
    public function evaluator()
    {
        return $this->hasOne(BiodataModel::class, 'nip', 'p_rontgen_evaluator');
    }
}
