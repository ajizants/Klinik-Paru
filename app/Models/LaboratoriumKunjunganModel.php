<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoriumKunjunganModel extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = "t_kunjungan_lab";

    protected $fillable = [
        'notrans', 'norm', 'nik', 'jk', 'no_sampel', 'umur', 'petugas', 'dokter', 'alamat', 'waktu_selesai', 'created_at', 'updated_at', 'nama', 'layanan', 'ket',
    ];

    public function pemeriksaan()
    {
        return $this->hasMany(LaboratoriumHasilModel::class, 'notrans', 'notrans');
    }

    public function petugas()
    {
        return $this->belongsTo(PegawaiModel::class, 'petugas', 'nip');
    }

    public function dokter()
    {
        return $this->belongsTo(PegawaiModel::class, 'dokter', 'nip');
    }

    // public function hasil($tgl, $norm)
    // {
    //     try {
    //         $data = self::with('pemeriksaan.pemeriksaan')
    //             ->whereDate('created_at', 'like', '%' . $tgl . '%')->where('norm', 'like', '%' . $norm . '%')->first();

    //         foreach ($data as $item) {
    //             $pemeriksaan = $item->pemeriksaan;
    //             $nonNullHasilCount = 0;

    //             foreach ($pemeriksaan as $periksa) {
    //                 if (!is_null($periksa->hasil)) {
    //                     $nonNullHasilCount++;
    //                 }
    //             }

    //             $item->jmlh = $pemeriksaan->count();

    //             if ($nonNullHasilCount == 0) {
    //                 $item->status = 'Belum';
    //             } else if ($nonNullHasilCount < $item->jmlh) {
    //                 $item->status = 'Belum Lengkap';
    //             } else {
    //                 $item->status = 'Lengkap';
    //             }

    //             $doctorNipMap = [
    //                 '198311142011012002' => 'dr. Cempaka Nova Intani, Sp.P, FISR., MM.',
    //                 '9' => 'dr. AGIL DANANJAYA, Sp.P',
    //                 '198907252019022004' => 'dr. FILLY ULFA KUSUMAWARDANI',
    //                 '198903142022031005' => 'dr. SIGIT DWIYANTO',
    //             ];
    //             $item->nama_dokter = $doctorNipMap[$item['dokter']] ?? 'Unknown';
    //         }

    //         // $lab = $data->toArray(); // Convert the collection to an array
    //         return $data;

    //     } catch (\Exception $e) {
    //         $res = [
    //             'message' => $e->getMessage(),
    //             'code' => 400,
    //         ];
    //         return response()->json($res, 400, [], JSON_PRETTY_PRINT);
    //     }
    // }

}
