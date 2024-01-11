<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class JabatanModel extends Model
{
    protected $table = 'peg_m_jabatan';
    protected $primaryKey = 'nip';

    public function biodata()
    {
        return $this->hasOne(BiodataModel::class, 'norm', 'norm');
    }
}
