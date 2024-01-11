<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DotsModel extends Model
{
    use HasFactory;

    protected $table = 'm_dots';
    protected $primaryKey = 'id';


    protected $fillable = [
        'norm',
        'noHP',
        'notrans',
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
        return $this->hasOne(PasienModel::class, 'norm', 'norm');
    }
    public function diagnosa()
    {
        return $this->hasOne(DiagnosaModel::class, 'kdDiag', 'kdDX');
    }
    public function dokter()
    {
        return $this->hasOne(PegawaiModel::class, 'nip', 'dokter');
    }
}
