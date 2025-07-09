<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PegawaiJenjangModel extends Model
{
    use HasFactory;
    protected $table      = 'peg_m_jenjang';
    protected $primaryKey = 'kd_jenjang';

}
