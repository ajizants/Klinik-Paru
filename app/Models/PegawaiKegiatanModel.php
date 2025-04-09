<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PegawaiKegiatanModel extends Model
{
    use HasFactory;

    protected $table = 'peg_t_kegiatan';

    protected $fillable = [
        'nip', 'kegiatan', 'keterangan', 'tanggal', 'jumlah',
    ];

    public function user()
    {
        return $this->belongsTo(PegawaiModel::class, 'nip', 'nip');
    }

    public function rekap($request)
    {
        $tglAwal = $request['tanggal_awal'];
        $tglAkhir = $request['tanggal_akhir'];
        $nip = $request['nip'] ?? "";

        $query = PegawaiKegiatanModel::with('user')
            ->whereBetween('tanggal', [$tglAwal, $tglAkhir]);

        if ($nip) {
            $query->where('nip', $nip);
        }

        $data = $query->get();

        // Kelompokkan dan jumlahkan secara manual
        $rekap = [];

        foreach ($data as $item) {
            $nama = trim(($item->user->gelar_d ?? '') . ' ' . $item->user->biodata->nama . ' ' . ($item->user->gelar_b ?? ''));
            $key = $item->nip . '|' . $nama . '|' . $item->kegiatan . '|' . $item->keterangan;

            if (!isset($rekap[$key])) {
                $rekap[$key] = [
                    'nip' => $item->nip,
                    'nama' => $nama,
                    'kegiatan' => $item->kegiatan,
                    'keterangan' => $item->keterangan,
                    'total_jumlah' => 0,
                ];
            }

            $rekap[$key]['total_jumlah'] += $item->jumlah;
        }

        // Ubah ke collection atau array indexed ulang
        return array_values($rekap);
    }

}
