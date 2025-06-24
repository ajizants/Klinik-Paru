<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RanapOrder extends Model
{
    use HasFactory;

    protected $table    = 'ranap_order';
    protected $fillable = [
        'id',
        'norm',
        'notrana',
        'petugas',
        'ruangan',
        'order',
        'ket',
        'created_at',
        'updated_at',
    ];

    public function detail()
    {
        return $this->hasMany(LayananModel::class, 'idLayanan', 'order');
    }
}
