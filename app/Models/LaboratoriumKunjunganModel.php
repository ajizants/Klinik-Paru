<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoriumKunjunganModel extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table   = "t_kunjungan_lab";

    protected $fillable = [
        'notrans', 'norm', 'nik', 'jk', 'no_sampel', 'umur', 'petugas', 'dokter', 'alamat', 'waktu_selesai', 'created_at', 'updated_at', 'nama', 'layanan', 'ket',
    ];

    public function pemeriksaan()
    {
        return $this->hasMany(LaboratoriumHasilModel::class, 'notrans', 'notrans');
    }

    public function tb04()
    {
        return $this->hasMany(LaboratoriumHasilModel::class, 'notrans', 'notrans')
            ->whereIn('idLayanan', [130, 131, 214]);
    }

    public function petugas()
    {
        return $this->belongsTo(PegawaiModel::class, 'petugas', 'nip');
    }

    public function dokter()
    {
        return $this->belongsTo(PegawaiModel::class, 'dokter', 'nip');
    }

    public function pasien()
    {
        return $this->belongsTo(PasienModel::class, 'norm', 'norm');
    }

    public function tungguLab($tgl)
    {
        $tgl = $tgl ?? date('Y-m-d');
        // $tgl = '2024-10-19'; // Bisa digunakan untuk tanggal tertentu
        $dataLab = LaboratoriumKunjunganModel::with('pemeriksaan.pemeriksaan')
            ->where('created_at', 'like', '%' . $tgl . '%')
            ->get();

        $tungguLab = []; // Inisialisasi array

        foreach ($dataLab as $d) {
            $estimasi          = 10; // Nilai default estimasi
            $pemeriksaan       = $d->pemeriksaan;
            $nonNullHasilCount = 0;
            $params            = ['BTA 1', 'BTA 2', 'Ureum darah', 'Creatinin darah', 'Asam Urat', 'SGOT', 'SGPT', 'Dlukosa darah', 'Trigliserid'];

            foreach ($pemeriksaan as $periksa) {
                // Mengecek apakah hasil pemeriksaan tidak null
                if (! is_null($periksa->hasil)) {
                    $nonNullHasilCount++;
                }

                // Menambahkan nama pemeriksaan dari relasi nmLayanan
                $periksa->nmPemeriksaan = $periksa->pemeriksaan->nmLayanan;
                $estimasiLayanan        = $periksa->pemeriksaan->estimasi;

                // Mengecek apakah nmPemeriksaan ada dalam array params
                if (in_array($periksa->nmPemeriksaan, $params)) {
                    $estimasi = 60; // Mengubah estimasi menjadi 60 jika nmPemeriksaan ditemukan dalam params
                }
            }

            $jmlh = $pemeriksaan->count();

            // Menentukan status
            if ($nonNullHasilCount == 0) {
                $status = 'Belum';
            } else if ($nonNullHasilCount < $jmlh) {
                $status = 'Belum';
            } else {
                $status = 'Selesai';
            }

            $jam_masuk = Carbon::parse($d->created_at)->format('H:i');

            // Menambahkan data tungguLab
            $tungguLab[] = [
                'id'        => $d->id,
                'norm'      => $d->norm,
                'nama'      => $d->nama,
                'alamat'    => $d->alamat,
                'jam_masuk' => $jam_masuk,
                'estimasi'  => $estimasi,
                'status'    => $status,
            ];
        }

        // Mengurutkan berdasarkan status
        usort($tungguLab, function ($a, $b) {
            return ($a['status'] === 'Belum' ? -1 : 1) <=> ($b['status'] === 'Belum' ? -1 : 1);
        });

        return $tungguLab;
    }

}
