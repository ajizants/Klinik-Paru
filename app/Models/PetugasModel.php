<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PetugasModel extends Model
{

    protected $connection = 'mysql';

    protected $table = 't_petugas';


    public function pegawai()
    {
        return $this->hasOne(PegawaiModel::class, 'nip', 'p_dokter_poli');
    }
}
