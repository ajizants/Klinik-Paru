<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalModel extends Model
{
    use HasFactory;
    protected $table    = 'peg_t_jadwal';
    protected $fillable = ['id', 'nip', 'nama', 'jabatan', 'tanggal', 'shift'];

    public function getJadwal(array $request)
    {
        $tanggal = $request['tanggal'] ?? null;
        $jabatan = $request['jabatan'] ?? null;

        $data = JadwalModel::when($tanggal, function ($query, $tanggal) {
            return $query->whereMonth('tanggal', date('m', strtotime($tanggal)))
                ->whereYear('tanggal', date('Y', strtotime($tanggal)));
        })
            ->when($jabatan, function ($query, $jabatan) {
                return $query->where('jabatan', $jabatan);
            })
            ->get();
        return $data;
    }

}
