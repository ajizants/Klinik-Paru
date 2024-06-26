<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiBMHPModel extends Model
{
    use HasFactory;

    protected $table = 't_tind_bmhp';
    protected $primaryKey = 'id';

    public function tindakan()
    {
        return $this->belongsTo(IGDTransModel::class, 'idTind', 'id');
    }

    public function bmhp()
    {
        return $this->belongsTo(BMHPModel::class, 'kdBmhp', 'id');
    }

    protected $fillable = [
        'idTind',
        'kdBmhp',
        'jml',
        'biaya',
        'notrans',
    ];
}
