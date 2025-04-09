<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DotsModel extends Model
{
    use HasFactory;

    protected $table      = 'm_dots_pasien';
    protected $primaryKey = 'id';

    protected $fillable = [
        'norm',
        'nik',
        'nama',
        'alamat',
        'noHP',
        'tcm',
        'sample',
        'kdDx',
        'tglMulai',
        'bb',
        'obat',
        'hiv',
        'dm',
        'ket',
        'hasilBerobat',
        'petugas',
        'dokter',
    ];

    public function biodata()
    {
        // return $this->hasOne(PasienModel::class, 'norm', 'norm');
    }
    public function layanan()
    {
        return $this->hasOne(KunjunganModel::class, 'norm', 'norm');
    }
    public function diagnosa()
    {
        return $this->hasOne(DiagnosaModel::class, 'kdDiag', 'kdDx');
    }
    public function dokter()
    {
        return $this->hasOne(PegawaiModel::class, 'nip', 'dokter');
    }

    public function identitasDokter()
    {
        return $this->hasOne(PegawaiModel::class, 'nip', 'dokter');
    }
    public function pengobatan()
    {
        return $this->hasOne(DotsBlnModel::class, 'id', 'hasilBerobat');
    }

    public function pasienTB()
    {
        $Ptb      = $this->all();
        $pasienTB = [];

        foreach ($Ptb as $d) {
            $kdDiag = $d['kdDx'];

            $dx            = DiagnosaModel::where('kdDiag', $kdDiag)->first();
            $d['diagnosa'] = $dx['diagnosa'] ?? 'Unknown Diagnosis';

            if ($d['hasilBerobat'] === null) {
                $d['statusPengobatan'] = "Belum Ada Pengobatan";
            } else {
                $status                = DotsBlnModel::where('id', $d['hasilBerobat'])->first();
                $d['statusPengobatan'] = $status['nmBlnKe'] ?? 'Unknown Status';
            }
            $dataDokter      = PegawaiModel::with('biodata')->where('nip', $d->dokter)->first();
            $namaDokter      = $dataDokter->gelar_d . " " . $dataDokter->biodata->nama . " " . $dataDokter->gelar_b;
            $d['namaDokter'] = $namaDokter;

            $pasienTB[] = $d;
        }
        return $pasienTB;
    }

    public function getStatusPengobatan($status)
    {
        $statuses = [
            "1" => "Pengobatan Pertama",
            "2" => "Pengobatan Kedua",
            "3" => "Pengobatan Ketiga",
            "4" => "Pengobatan Keempat",
        ];
        return $statuses[$status] ?? "Tidak Diketahui";
    }

    // Masih di DotsModel.php
    public function scopeEnriched($query)
    {
        return $query->with(['diagnosa', 'pengobatan', 'identitasDokter.biodata']);
    }

}
