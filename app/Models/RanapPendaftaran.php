<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RanapPendaftaran extends Model
{
    use HasFactory;
    protected $table = 'ranap_pendaftaran';

    public function pasien()
    {
        return $this->belongsTo(PasienModel::class, 'norm', 'norm');
    }

    public function dpjp()
    {
        return $this->belongsTo(Vpegawai::class, 'dpjp', 'nip');
    }
}
