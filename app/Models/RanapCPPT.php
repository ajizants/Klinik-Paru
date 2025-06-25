<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RanapCPPT extends Model
{
    use HasFactory;

    protected $table = 'ranap_cppt';

    protected $fillable = [
        'norm',
        'notrans',
        'form_id',
        'td',
        'nadi',
        'suhu',
        'rr',
        'bb',
        'tb',
        'bbi',
        'lla',
        'imt',
        'status_gizi',
        'objektif',
        'subjektif',
        'assesment',
        'planing',
        'dx1',
        'ket_dx1',
        'dx2',
        'ket_dx2',
        'dx3',
        'ket_dx3',
        'dx4',
        'ket_dx4',
        'petugas',
        'created_at',
        'updated_at',
    ];

    public function diagnosa1()
    {
        return $this->belongsTo(DiagnosaIcdXModel::class, 'dx1', 'kdDx');
    }

    public function diagnosa2()
    {
        return $this->belongsTo(DiagnosaIcdXModel::class, 'dx2', 'kdDx');
    }

    public function diagnosa3()
    {
        return $this->belongsTo(DiagnosaIcdXModel::class, 'dx3', 'kdDx');
    }

    public function diagnosa4()
    {
        return $this->belongsTo(DiagnosaIcdXModel::class, 'dx4', 'kdDx');
    }

    public function nakes()
    {
        return $this->belongsTo(Vpegawai::class, 'petugas', 'nip');
    }
    public function pasien()
    {
        return $this->belongsTo(RanapPendaftaran::class, 'notrans', 'notrans');
    }

    public function order()
    {
        return $this->hasMany(RanapOrder::class, 'notrans', 'notrans');
    }
}
