<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockModel extends Model
{
    use HasFactory;

    protected $connection = ('mysql');

    protected $table = ('apt_m_obat');
    protected $primaryKey = ('kdObat');

    public function transaksiBMHP()
    {
        return $this->hasMany(TransaksiBMHPModel::class, 'kdBmhp', 'kdBmhp');
    }

    protected $filable = [
        'nmObat',
        'hargaBeli',
        'hargaJual',
    ];
}
