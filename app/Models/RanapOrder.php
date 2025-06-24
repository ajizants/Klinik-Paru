<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RanapOrder extends Model
{
    use HasFactory;

    protected $table = 'ranap_order';

    protected $fillable = [
        'norm',
        'notrans', // ✅ perbaiki ini (bukan 'notrana')
        'petugas',
        'ruangan',
        'order',
        'form_id', // ✅ tambahkan jika belum ada
        'obat_id',
        'signa_1',
        'signa_2',
        'ket', // pastikan kolom ini benar
    ];

    public function detail()
    {
        return $this->hasMany(LayananModel::class, 'idLayanan', 'order');
    }
    public function detailObat()
    {
        return $this->hasMany(BMHPModel::class, 'id', 'obat_id');
    }
}
