<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmasiModel extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    protected $table = 't_kunjungan_farmasi';
    protected $primaryKey = 'idAptk';

    public function obat()
    {
        return $this->belongsTo(GudangFarmasiInStokModel::class, 'product_id', 'id');
    }

    public function bmhp()
    {
        return $this->hasMany(TransaksiModel::class, 'notrans', 'notrans');
    }
    public function petugasPegawai()
    {
        return $this->belongsTo(PegawaiModel::class, 'petugas', 'nip');
    }

    public function dokterPegawai()
    {
        return $this->belongsTo(PegawaiModel::class, 'dokter', 'nip');
    }


    protected $fillable = [
        'norm',
        'notrans',
        'product_id',
        'jumlah',
        'total',
        'petugas',
        'dokter'
    ];
}
