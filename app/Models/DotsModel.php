<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DotsModel extends Model
{
    use HasFactory;

    protected $table = 'm_dots_pasien';
    protected $primaryKey = 'id';

    protected $fillable = [
        'norm',
        'nik',
        'nama',
        'alamat',
        'noHP',
        'tcm',
        'sample',
        'kdDx',
        'tglMulai',
        'bb',
        'obat',
        'hiv',
        'dm',
        'ket',
        'hasilBerobat',
        'petugas',
        'dokter',
    ];

    public function biodata()
    {
        // return $this->hasOne(PasienModel::class, 'norm', 'norm');
    }
    public function layanan()
    {
        return $this->hasOne(KunjunganModel::class, 'norm', 'norm');
    }
    public function diagnosa()
    {
        return $this->hasOne(DiagnosaModel::class, 'kdDiag', 'kdDx');
    }
    public function dokter()
    {
        return $this->hasOne(PegawaiModel::class, 'nip', 'dokter');
    }
    public function pengobatan()
    {
        return $this->hasOne(DotsBlnModel::class, 'notrans', 'hasilBerobat');
    }

}
