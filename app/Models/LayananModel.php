<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananModel extends Model
{
    use HasFactory;
    protected $table      = 'kasir_m_layanan';
    protected $primaryKey = 'idLayanan';
    protected $fillable   = [
        'nmLayanan', 'tarif', 'kelas', 'status', 'kdTind', 'kdFoto', 'satuan', 'normal',
    ];

    public function grup()
    {
        return $this->belongsTo(LayananKelasModel::class, 'kelas', 'kelas');
    }
    public function ruang()
    {
        return $this->belongsTo(LayananKelasModel::class, 'kelas', 'kelas');
    }
    public function layanans($kelas)
    {
        $data = LayananModel::where('status', "1")
            ->whereIn('kelas', $kelas)
        // ->whereIn('kelas', 'like', '%' . $kelas . '%')
            ->get();

        $layanan = [];

        foreach ($data as $d) {
            $layanan[] = [
                'idLayanan' => $d->idLayanan,
                'kdTind'    => $d->kdTind,
                'kdFoto'    => $d->kdFoto,
                'kelas'     => $d->kelas,
                'nmLayanan' => $d->nmLayanan,
                'tarif'     => $d->tarif,
                'status'    => $d->status,
            ];
        }
        // dd($layanan);
        $layanan = array_map(function ($item) {
            return (object) $item;
        }, $layanan);

        return $layanan;
    }
}
