<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DotsTransModel extends Model
{
    use HasFactory;

    protected $table = 't_kunjungan_dots';
    // protected $primaryKey = 'id';

    public function biodata()
    {
        return $this->hasOne(PasienModel::class, 'norm', 'norm');
    }
    public function pasien()
    {
        return $this->hasOne(DotsModel::class, 'norm', 'norm');
    }
    public function dokter()
    {
        return $this->hasOne(PegawaiModel::class, 'nip', 'dokter');
    }
    public function dok()
    {
        return $this->hasOne(PegawaiModel::class, 'nip', 'dokter');
    }
    public function petugas()
    {
        return $this->hasOne(PegawaiModel::class, 'nip', 'petugas');
    }
    public function pet()
    {
        return $this->hasOne(PegawaiModel::class, 'nip', 'petugas');
    }
    public function obat()
    {
        return $this->HasOne(DotsObatModel::class, 'id', 'terapi');
    }

    public function ts()
    {
        return $this->HasMany(DotsTransModel::class, 'norm', 'norm');
    }
    public function bln()
    {
        return $this->hasOne(DotsBlnModel::class, 'id', 'blnKe');
    }

    public function poinPetugas($mulaiTgl, $selesaiTgl, $petugas = null)
    {
        $mulaiTgl = date('Y-m-d 00:00:00', strtotime($mulaiTgl));
        $selesaiTgl = date('Y-m-d 23:59:59', strtotime($selesaiTgl));

        if ($petugas) {
            $kunjungan = DotsTransModel::whereBetween('created_at', [$mulaiTgl, $selesaiTgl])->where('petugas', $petugas)->get();
            $pasienBaru = DotsModel::whereBetween('created_at', [$mulaiTgl, $selesaiTgl])->where('petugas', $petugas)->get();
        } else {
            // Ambil data kunjungan
            $kunjungan = DotsTransModel::whereBetween('created_at', [$mulaiTgl, $selesaiTgl])->get();
            // Ambil data pasien baru
            $pasienBaru = DotsModel::whereBetween('created_at', [$mulaiTgl, $selesaiTgl])->get();
        }

        // Data kunjungan
        $dataLama = [];
        foreach ($kunjungan as $d) {
            $pegawai = PegawaiModel::with('biodata')->where('nip', $d->petugas)->first();
            $dataLama[] = [
                "id" => $d->id,
                "norm" => $d->norm,
                "notrans" => $d->notrans,
                'nip' => $d->petugas,
                'nama' => $pegawai->biodata->nama ?? 'Tidak Diketahui',
            ];
        }

        // Data pasien baru
        $dataBaru = [];
        foreach ($pasienBaru as $d) {
            $pegawai = PegawaiModel::with('biodata')->where('nip', $d->petugas)->first();
            $dataBaru[] = [
                "id" => $d->id,
                "norm" => $d->norm,
                "notrans" => $d->notrans,
                'nip' => $d->petugas,
                'nama' => $pegawai->biodata->nama ?? 'Tidak Diketahui',
            ];
        }

        // Hitung jumlah poin lama
        $poinLama = collect($dataLama)->groupBy('nip')->map(function ($group, $nip) {
            return [
                'nip' => $nip,
                'nama' => $group->first()['nama'],
                'jumlahLama' => $group->count(),
            ];
        });

        // Hitung jumlah poin baru
        $poinBaru = collect($dataBaru)->groupBy('nip')->map(function ($group, $nip) {
            return [
                'nip' => $nip,
                'nama' => $group->first()['nama'],
                'jumlahBaru' => $group->count(),
            ];
        });

        // Gabungkan data berdasarkan NIP
        $poinGabungan = $poinLama->map(function ($lama, $nip) use ($poinBaru) {
            $baru = $poinBaru->get($nip, ['jumlahBaru' => 0]);

            return [
                'nip' => $nip,
                'nama' => $lama['nama'],
                'jumlahLama' => $lama['jumlahLama'],
                'jumlahBaru' => $baru['jumlahBaru'] ?? 0,
            ];
        })->values();

        // Tambahkan data dari poinBaru yang tidak ada di poinLama
        $poinTambahan = $poinBaru->filter(function ($item) use ($poinLama) {
            return !$poinLama->has($item['nip']);
        })->map(function ($item) {
            return [
                'nip' => $item['nip'],
                'nama' => $item['nama'],
                'jumlahLama' => 0,
                'jumlahBaru' => $item['jumlahBaru'],
            ];
        });

        // Gabungkan semua poin
        $poin = $poinGabungan->merge($poinTambahan)->values();

        if ($petugas && $poin->isEmpty()) {
            return [
                'nip' => $petugas,
                // 'nama' => $item['nama'],
                'jumlahLama' => 0,
                'jumlahBaru' => 0,
            ];
        }

        if ($petugas !== null) {
            $poin = $poin[0];
        }

        return $poin;
    }
}
