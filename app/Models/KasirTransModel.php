<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasirTransModel extends Model
{
    use HasFactory;

    protected $table = ('t_kasir');
    // protected $primaryKey = 'notrans';

    protected $fillable = [
        'notrans',
        'norm',
        'tagihan',
        'bayar',
        'kembalian',
        'created_at',
        'updated_at',
    ];

    public function item()
    {
        return $this->hasMany(KasirAddModel::class, 'notrans', 'notrans');
    }
}
