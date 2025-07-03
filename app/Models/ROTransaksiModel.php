<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ROTransaksiModel extends Model
{
    protected $table      = 't_rontgen';
    protected $primaryKey = 'notrans';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'norm', 'nama', 'alamat', 'jk', 'tgltrans', 'noreg', 'pasienRawat',
        'kdFoto', 'kdFilm', 'ma', 'kv', 's', 'jmlExpose', 'jmlFilmDipakai',
        'jmlFilmRusak', 'kdMesin', 'kdProyeksi', 'catatan', 'layanan', 'created_at', 'updated_at',
    ];

    public function film()
    {
        return $this->hasOne(ROJenisFilm::class, 'kdFilm', 'kdFilm');
    }
    public function foto()
    {
        return $this->hasOne(ROJenisFoto::class, 'kdFoto', 'kdFoto');
    }
    public function pemeriksaan()
    {
        return $this->hasOne(LayananModel::class, 'kdFoto', 'kdFoto');
    }
    public function proyeksi()
    {
        return $this->hasOne(RoProyeksiModel::class, 'kdProyeksi', 'kdProyeksi');
    }
    public function mesin()
    {
        return $this->hasOne(ROJenisMesin::class, 'kdMesin', 'kdMesin');
    }
    public function kv()
    {
        return $this->hasOne(ROJenisKondisi::class, 'kdKondisiRo', 'kv');
    }

    public function ma()
    {
        return $this->hasOne(ROJenisKondisi::class, 'kdKondisiRo', 'ma');
    }

    public function s()
    {
        return $this->hasOne(ROJenisKondisi::class, 'kdKondisiRo', 's');
    }

    public function kondisiOld()
    {
        return $this->hasOne(ROJenisKondisiOld::class, 'kdKondisiRo', 'kdKondisiRo');
    }

    public function hasil()
    {
        return $this->hasMany(ROTransaksiHasilModel::class, 'norm', 'norm');
    }
    public function pasien()
    {
        return $this->hasOne(PasienModel::class, 'norm', 'norm');
        // return $this->hasOne(KominfoModel::class, 'norm', 'norm');
    }
    public function radiografer()
    {
        return $this->hasOne(TransPetugasModel::class, 'notrans', 'notrans');
    }
    public function evaluator()
    {
        return $this->hasOne(TransPetugasModel::class, 'notrans', 'notrans');
    }
    public function dokter()
    {
        return $this->hasOne(TransPetugasModel::class, 'notrans', 'notrans');
    }
    public function kunjungan()
    {
        return $this->hasOne(KunjunganModel::class, 'notrans', 'notrans');
    }

    public function konsulRo()
    {
        return $this->belongsTo(KunjunganWaktuSelesai::class, 'notrans', 'notrans');
    }

    public function hasilBacaan()
    {
        return $this->hasOne(ROBacaan::class, 'notrans', 'notrans');
    }

    public function tungguRo($tgl)
    {
        $tgl = $tgl ?? date('Y-m-d');
        // $tgl = '2024-10-19';
        $dataRo = ROTransaksiModel::where('created_at', 'like', '%' . $tgl . '%')
            ->get();
        // return $dataRo;

        $tungguRo = []; // Inisialisasi array

        foreach ($dataRo as $d) {
            $jam_masuk = Carbon::parse($d->created_at)->format('H:i');
            $status    = "Belum";
            $hasil     = ROTransaksiHasilModel::where('norm', $d->norm)->where('tanggal', 'like', '%' . $tgl . '%')->first();

            if ($hasil) {
                $status = "Selesai";
            }

            $tungguRo[] = [
                'id'        => $d->id,
                'norm'      => $d->norm,
                'nama'      => $d->nama,
                'alamat'    => $d->alamat,
                'jam_masuk' => $jam_masuk,
                'estimasi'  => 15,
                'status'    => $status,
            ];
        }
        usort($tungguRo, function ($a, $b) {
            return ($a['status'] === 'Selesai' ? 0 : 1) <=> ($b['status'] === 'Selesai' ? 0 : 1);
        });

        return $tungguRo;
    }
}
