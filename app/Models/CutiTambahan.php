<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiTambahan extends Model
{
    use HasFactory;

    protected $table = 'peg_t_cuti_tambahan';

    protected $fillable = [
        'id',
        'nip',
        'jumlah_tambahan',
        'created_at',
        'updated_at',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Vpegawai::class, 'nip', 'nip');
    }
}
