<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakanModel extends Model
{
    use HasFactory;
    protected $connection = 'mysql';

    protected $table = 'm_tindakan';
    protected $primaryKey = 'kdTindakan';

    public function transaksiBMHP()
    {
        return $this->hasMany(TransaksiBMHPModel::class, 'kdTindakan', 'kdTind');
    }

    protected $filable = [
        'nmTindakan',
        'harga',
    ];
}
