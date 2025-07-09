<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vpegawai extends Model
{
    use HasFactory;
    protected $table = 'V_pegawai';

    public function sisaCuti()
    {
        return $this->belongsTo(PegawaiModel::class, 'nip', 'nip');
    }

    public function cuti()
    {
        return $this->hasMany(CutiPegawai::class, 'nip', 'nip');
    }

    public function cutiTambahan()
    {
        return $this->hasMany(CutiTambahan::class, 'nip', 'nip');
    }

    public function pimpinan($bln = null, $tahun)
    {
        $bln             = $bln == null ? date('m') : $bln;
        $blnTahun        = Carbon::create($tahun, $bln);
        $blnTahunCompare = Carbon::create(2025, 5);

        if ($blnTahun->lessThan($blnTahunCompare)) {
            $pimpinan = [
                'gelar'    => 'Plt. Kepala',
                'kepala'    => 'dr. RENDI RETISSU',
                'nipKepala' => '198810162019021002',
            ];
        } else {
            $pimpinan = [
                'gelar'    => 'Plt. Kepala',
                'kepala'    => 'dr. ANWAR HUDIONO, M.P.H.',
                'nipKepala' => '198212242010011022',
            ];
        }

        return $pimpinan;
    }

}
