<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosaMapModel extends Model
{
    use HasFactory;

    protected $table      = 'm_diagnosa_map';
    protected $primaryKey = 'kdDx';   // Primary key menggunakan kdDx
    public $incrementing  = false;    // Non-incremental primary key
    protected $keyType    = 'string'; // Tipe primary key adalah string
    protected $fillable   = ['kdDx', 'diagnosa', 'mapping'];

    public function diagnosa($kdDx)
    {
        return $this->where('kdDx', $kdDx)->first();
    }

}
