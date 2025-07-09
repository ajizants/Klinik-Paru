<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiPegawai extends Model
{
    use HasFactory;

    protected $table    = 'peg_t_cuti';
    protected $fillable = ['nip', 'tgl_mulai', 'tgl_selesai', 'persetujuan', 'keterangan', 'alasan'];

    public function pegawai()
    {
        return $this->belongsTo(Vpegawai::class, 'nip', 'nip');
    }
    // public function dataSisaCuti($nip = null)
    // {
    //     $query = Vpegawai::with('cuti', 'cutiTambahan')->whereNot('stat_pns', 'PENSIUNAN');
    //     if ($nip) {
    //         $query->where('nip', $nip);
    //     }

    //     // Ambil hari libur dari tabel
    //     $hariLibur = DB::table('hari_libur')->pluck('tanggal')->map(function ($tanggal) {
    //         return Carbon::parse($tanggal)->toDateString();
    //     })->toArray();

    //     $dataPegawai = $query->get();

    //     foreach ($dataPegawai as $key) {
    //         $jumlahCuti          = 0;
    //         $jumlahCutiDisetujui = 0;
    //         $jumlahCutiDitolak   = 0;
    //         $jumlahCutiTambahan  = 0;

    //         foreach ($key->cuti as $cuti) {
    //             // Hanya hitung cuti tahunan (misal ID jenis cuti = 1)
    //             if ($cuti->alasan !== 'Cuti Tahunan') {
    //                 continue;
    //             }

    //             $mulai   = Carbon::parse($cuti->tgl_mulai);
    //             $selesai = Carbon::parse($cuti->tgl_selesai);

    //             $hariCuti = 0;

    //             // Loop per hari, cek apakah hari itu bukan Minggu dan bukan hari libur
    //             for ($tanggal = $mulai->copy(); $tanggal->lte($selesai); $tanggal->addDay()) {
    //                 $isMinggu    = $tanggal->dayOfWeek == Carbon::SUNDAY;
    //                 $isHariLibur = in_array($tanggal->toDateString(), $hariLibur);

    //                 if (! $isMinggu && ! $isHariLibur) {
    //                     $hariCuti++;
    //                 }
    //             }

    //             $jumlahCuti += $hariCuti;

    //             if ($cuti->persetujuan == 1) {
    //                 $jumlahCutiDisetujui += $hariCuti;
    //             }
    //             if ($cuti->persetujuan == 2) {
    //                 $jumlahCutiDitolak += $hariCuti;
    //             }
    //         }

    //         foreach ($key->cutiTambahan as $cuti) {
    //             // Hanya hitung cuti tahunan (misal ID jenis cuti = 1)
    //             $jumlahCutiTambahan += $cuti->jumlah_tambahan;
    //         }

    //         $key['jumlahCutiDiambil']   = $jumlahCuti;
    //         $key['jumlahCutiDisetujui'] = $jumlahCutiDisetujui;
    //         $key['jumlahCutiDitolak']   = $jumlahCutiDitolak;
    //         $key['jumlahCutiTambahan']  = $jumlahCutiTambahan;
    //         $key['jumlahSisaCuti']      = ($key->jatah_cuti + $key->tambahan_cuti + $key->jumlahCutiTambahan) - $jumlahCutiDisetujui;
    //     }

    //     return $dataPegawai;
    // }
}
